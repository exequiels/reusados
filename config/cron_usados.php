<?php

error_reporting(E_ALL);

// Ruta del archivo de log
$logFile = __DIR__ . '/error_log.txt';

// Función para registrar mensajes en el log
function logMessage($message)
{
    global $logFile;
    file_put_contents($logFile, date('Y-m-d H:i:s') . ' - ' . $message . PHP_EOL, FILE_APPEND);
}

// Registrar el inicio del script
logMessage('Script execution started.');

// Ruta a autoload.php
$autoloadPath = '/home/u764883179/vendor/autoload.php';
logMessage('Autoload path: ' . $autoloadPath);

if (file_exists($autoloadPath)) {
    require $autoloadPath;
} else {
    logMessage('Autoload file not found: ' . $autoloadPath);
    exit;
}

use Dotenv\Dotenv;

// Ruta a .env
$dotenvPath = '/home/u764883179/public_html/.env';
logMessage('Attempting to load .env file from: ' . $dotenvPath);

// Check .env file existance
if (file_exists($dotenvPath)) {
    logMessage('.env file exists.');

    try {
        $dotenv = Dotenv::createImmutable('/home/u764883179/public_html');
        $dotenv->load();

    } catch (Exception $e) {
        logMessage('Error loading .env file: ' . $e->getMessage());
        exit('Error loading .env file');
    }
} else {
    logMessage('.env file does not exist.');
    exit('Error: .env file does not exist.');
}

// Log at the end of script
logMessage('Script execution ended successfully.');

try {
    // Log a step before including the connection file
    file_put_contents($logFile, "Intentando connectar a las " . date('Y-m-d H:i:s') . "\n", FILE_APPEND);

    // Include the connection file
    require_once "/home/u764883179/public_html/config/cron_connectar.php";

    // Log a step after successful inclusion
    file_put_contents($logFile, "Archivo cron_connectar.php incluido exitosamente a las " . date('Y-m-d H:i:s') . "\n", FILE_APPEND);

    // If the connection is successful
    if (isset($pdo)) {
        file_put_contents($logFile, "Conexión a la base de datos exitosa a las " . date('Y-m-d H:i:s') . "\n", FILE_APPEND);
    } else {
        file_put_contents($logFile, "Variable PDO no está definida después de incluir el archivo a las " . date('Y-m-d H:i:s') . "\n", FILE_APPEND);
    }
} catch (PDOException $e) {
    // Log any connection errors
    file_put_contents($logFile, "Error de conexión a la base de datos: " . $e->getMessage() . " a las " . date('Y-m-d H:i:s') . "\n", FILE_APPEND);
} catch (Exception $e) {
    // Catch any other exceptions
    file_put_contents($logFile, "Error: " . $e->getMessage() . " a las " . date('Y-m-d H:i:s') . "\n", FILE_APPEND);
}

// Log a message at the end of the file to confirm execution
logMessage('Reached end of cron_connectar.php.');
logMessage('cron_connectar.php loaded successfully.');

// Continuar con la lógica del script
logMessage('Script execution continued.');

logMessage('Attempting to load TokenModel.php');
require_once '/home/u764883179/public_html/models/TokenModel.php';
logMessage('TokenModel.php loaded successfully.');

// Instantiate the TokenModel
logMessage('Instantiating TokenModel.');
$tokenModel = new TokenModel($pdo);
logMessage('TokenModel instantiated successfully.');

// Fetch the token data
logMessage('Attempting to fetch token data.');
$tokenData = $tokenModel->getToken();

if (empty($tokenData) || !isset($tokenData[0]['refresh_token'])) {
    logMessage('No token data found or refresh token missing.');
    exit('No token data found or refresh token missing.');
} else {
    logMessage('Token data fetched successfully.');
}

// Continue with your logic here...
logMessage('Proceeding with further script logic.');

$APP_ID = $_ENV['CLIENT_ID'];
$SECRET_KEY = $_ENV['CLIENT_SECRET'];
$refresh_token = $tokenData[0]['refresh_token'];


$url = 'https://api.mercadolibre.com/oauth/token';
$data = [
    'grant_type'    => 'refresh_token',
    'refresh_token' => $refresh_token,
    'client_id'     => $APP_ID,
    'client_secret' => $SECRET_KEY,
];

$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
$response = curl_exec($ch);

if (curl_errno($ch)) {
    echo 'Error:' . curl_error($ch);
    exit;
}
curl_close($ch);
$responseData = json_decode($response, true);

if (isset($responseData['error'])) {
    die('Error refreshing token: ' . $responseData['error']);
}

$access_token = $responseData['access_token'];
$refresh_token = $responseData['refresh_token'];
$expires_in = $responseData['expires_in'];

$tokenModel->upsertToken($access_token, $refresh_token, $expires_in);

$tokenData = $tokenModel->getToken();
$ACCESS_TOKEN = $tokenData[0]['access_token'];

if ($pdo) {
    $updateStmt = $pdo->prepare("UPDATE mla_consolasyvideojuegos_principal SET status = 0 WHERE status = 1");
    if (!$updateStmt->execute()) {
        echo "Error al actualizar status: " . implode(" ", $updateStmt->errorInfo());
        exit;
    }
}

function extraerDatos($pdo, $SITE_ID, $ACCESS_TOKEN, $searchTerms)
{
    foreach ($searchTerms as $category => $terms) {
        foreach ($terms as $term) {
            $offset = 0;
            $apiBatchSize = 50;
            $counter = 1;

            do {
                $url = "https://api.mercadolibre.com/sites/$SITE_ID/search?q=" . urlencode($term) . "&category=$category&condition=used&offset=$offset";
                $curl = curl_init($url);
                curl_setopt($curl, CURLOPT_URL, $url);
                curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                $headers = [
                    "Authorization: Bearer $ACCESS_TOKEN",
                ];
                curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
                curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
                curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
                $resp = curl_exec($curl);

                if ($resp === false) {
                    echo "Error 1001: " . curl_error($curl);
                    exit; // Exit the script to stop further processing
                }

                curl_close($curl);

                $datos = json_decode($resp, true);
                $results = isset($datos['results']) && !is_null($datos['results']) ? $datos['results'] : [];

                // Insertar datos inmediatamente
                if ($pdo) {
                    // Prepare the statement for INSERT with ON DUPLICATE KEY UPDATE
                    $insertStmt = $pdo->prepare("INSERT INTO mla_consolasyvideojuegos_principal
                        (all_item_ids, all_titles, all_shipping, all_prices, all_thumbnail, all_links, all_item_condition, all_item_categoria, all_cuotas, all_cuotas_cantidad, status)
                        VALUES (:item_ids, :titles, :shipping, :prices, :thumbnail, :links, :item_condition, :item_categoria, :cuotas, :cuotas_cantidad, :status)
                        ON DUPLICATE KEY UPDATE
                        all_titles = VALUES(all_titles),
                        all_shipping = VALUES(all_shipping),
                        all_prices = VALUES(all_prices),
                        all_thumbnail = VALUES(all_thumbnail),
                        all_links = VALUES(all_links),
                        all_item_condition = VALUES(all_item_condition),
                        all_item_categoria = VALUES(all_item_categoria),
                        all_cuotas = VALUES(all_cuotas),
                        all_cuotas_cantidad = VALUES(all_cuotas_cantidad),
                        status = VALUES(status);");

                    foreach ($results as $result) {
                        // Process each item and extract the required data
                        $itemId = isset($result['id']) ? $result['id'] : 'N/A';
                        $Title = isset($result['title']) ? $result['title'] : 'N/A';
                        $envioTraducido = isset($result['shipping']['free_shipping']) ? $result['shipping']['free_shipping'] : 'N/A';
                        $precio = isset($result['price']) ? $result['price'] : 'N/A';
                        $thumbnail = isset($result['thumbnail']) ? $result['thumbnail'] : 'N/A';
                        $enlace = isset($result['permalink']) ? $result['permalink'] : 'N/A';
                        $estadoTraducido = isset($result['condition']) ? $result['condition'] : 'N/A';
                        $categoryName = ($result['category_id'] == 'MLA438566') ? 'Consolas' : 'Juegos';
                        $cuotas = isset($result['installments']['rate']) ? $result['installments']['rate'] : 'N/A';
                        $cuotasCantidad = isset($result['installments']['quantity']) ? $result['installments']['quantity'] : 'N/A';

                        // Execute the INSERT statement with ON DUPLICATE KEY UPDATE
                        $insertStmt->execute([
                            ':item_ids' => $itemId,
                            ':titles' => $Title,
                            ':shipping' => $envioTraducido,
                            ':prices' => $precio,
                            ':thumbnail' => $thumbnail,
                            ':links' => $enlace,
                            ':item_condition' => $estadoTraducido,
                            ':item_categoria' => $categoryName,
                            ':cuotas' => $cuotas,
                            ':cuotas_cantidad' => $cuotasCantidad,
                            ':status' => 1,
                        ]);
                    }
                    echo "Insertados " . count($results) . " artículos del término '$term'.";
                } else {
                    echo "No se pudo establecer una conexión con la base de datos.";
                    return 0; // Salir si no hay conexión a la base de datos
                }

                echo "Llamado No. $counter para '$term': Se han obtenido " . count($results) . " artículos\n" . PHP_EOL;
                $counter++;

                // Proximos datos
                $offset += $apiBatchSize;

                // Pausa
                usleep(500000); // Pausa por 0.5 segundos (500,000 microsegundos)

            } while (count($results) == $apiBatchSize); // Continuar loop
        }
    }

    if ($pdo) {
        // Borrar datos viejos
        $deleteStmt = $pdo->prepare("DELETE FROM mla_consolasyvideojuegos_principal WHERE status = 0");
        $deleteStmt->execute();
        echo "<p>Reemplazamos datos viejos por nuevos, listo.</p>";
    }

    return 1; // Return 1 to indicate success
}

$searchTerms = [
    'MLA438566' => ['nintendo nes', 'super nintendo', 'nintendo 64', 'gamecube', 'sega genesis', 'sega saturn', 'sega dreamcast', 'playstation 1', 'playstation 2', 'playstation 3'],
    'MLA373840' => ['nintendo nes', 'super nintendo', 'nintendo 64', 'gamecube', 'sega genesis', 'sega saturn', 'sega dreamcast', 'playstation 1', 'playstation 2', 'playstation 3'],
];

// $searchTerms = [
//     'MLA438566' => ['nintendo nes', 'nes', 'snes', 'nintendo 64', 'super nintendo', 'sega genesis', 'sega saturn', 'sega dreamcast','playstation 1', 'playstation 2'],
//     'MLA373840' =>  ['nintendo nes', 'nes', 'snes', 'nintendo 64', 'super nintendo', 'sega genesis', 'sega saturn', 'sega dreamcast','playstation 1', 'playstation 2'],
// ];


$totalResultsDatos = extraerDatos($pdo, 'MLA', $ACCESS_TOKEN, $searchTerms);
echo "<p>Listo video juegos destacados.</p>";
echo "<hr />";
