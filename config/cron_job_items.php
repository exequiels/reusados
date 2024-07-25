<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once 'connectar.php';
require_once '../models/token_model.php';

$tokenModel = new TokenModel($pdo);
$tokenData = $tokenModel->getToken();

if (empty($tokenData) || !isset($tokenData[0]['refresh_token'])) {
    die('No token data found or refresh token missing.');
}

$APP_ID = getenv('CLIENT_ID');
$SECRET_KEY = getenv('CLIENT_SECRET');
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

function extraerDatos($pdo, $SITE_ID, $ACCESS_TOKEN, $searchTerms)
{
    $deactivateStmt = $pdo->prepare("UPDATE mla_consolasyvideojuegos_principal SET status = 0");
    $deactivateStmt->execute();
    echo "Cambiamos status a 0 para datos viejos<br>";

    foreach ($searchTerms as $category => $terms) {
        foreach ($terms as $term) {
            $offset = 0;
            $apiBatchSize = 50;
            $maxBatchSize = 200;
            $counter = 1;

            $insertData = [];

            while (true) {
                $url = "https://api.mercadolibre.com/sites/$SITE_ID/search?q=" . urlencode($term) . "&category=$category&condition=used&offset=$offset&limit=$apiBatchSize";
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
                    exit;
                }

                curl_close($curl);

                $datos = json_decode($resp, true);
                $results = isset($datos['results']) && !is_null($datos['results']) ? $datos['results'] : [];

                if (empty($results)) {
                    break;
                }

                foreach ($results as $result) {
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

                    $insertData[] = [
                        'item_ids' => $itemId,
                        'titles' => $Title,
                        'shipping' => $envioTraducido,
                        'prices' => $precio,
                        'thumbnail' => $thumbnail,
                        'links' => $enlace,
                        'item_condition' => $estadoTraducido,
                        'item_categoria' => $categoryName,
                        'cuotas' => $cuotas,
                        'cuotas_cantidad' => $cuotasCantidad,
                        'status' => 1,
                    ];

                    if (count($insertData) >= $maxBatchSize) {
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
                            status = VALUES(status)");

                        foreach ($insertData as $data) {
                            $insertStmt->execute($data);
                        }

                        $insertData = [];
                    }
                }

                echo "Llamado No. $counter para '$term' en la categoría '$categoryName': Se han obtenido " . count($results) . " artículos.<br>" . PHP_EOL;
                $counter++;

                $offset += $apiBatchSize;

                usleep(500000);
            }

            if (!empty($insertData)) {
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
                    status = VALUES(status)");

                foreach ($insertData as $data) {
                    $insertStmt->execute($data);
                }
            }
        }
    }

    if ($pdo) {
        $deleteStmt = $pdo->prepare("DELETE FROM mla_consolasyvideojuegos_principal WHERE status = 0");
        $deleteStmt->execute();
        echo "<p>Reemplazamos datos viejos por nuevos, listo.</p>";
    }

    return 1;
}

$searchTerms = [
    'MLA373840' => ['super nintendo', 'nintendo 64', 'sega genesis'],
];

$totalResultsDatos = extraerDatos($pdo, 'MLA', $ACCESS_TOKEN, $searchTerms);
echo "<p>Completado.</p>";
echo "<hr />";
