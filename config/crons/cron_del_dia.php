<?php

error_reporting(E_ALL);

// Ruta del archivo de log
$logFile = __DIR__ . '/cron_del_dia.txt';

require_once "/home/u764883179/public_html/config/crons/loader_function.php";
logMessage('Loader cargado.');

$ACCESS_TOKEN = $tokenData[0]['access_token'];
logMessage('Token seteado.');

logMessage('Revisamos status previos.');
if ($pdo) {
    $updateStmt = $pdo->prepare("UPDATE mla_consolasyvideojuegos_del_dia SET status = 0 WHERE status = 1 AND source = :source");
    if (!$updateStmt->execute([':source' => 'hoy'])) {
        echo "Error al actualizar status: " . implode(" ", $updateStmt->errorInfo());
        exit;
    }
}
logMessage('Status previo chequeado.');
function extraerDatos($pdo, $SITE_ID, $ACCESS_TOKEN, $categories)
{
    global $categoryMappingsConsolas;
    logMessage('Iniciamos extraerDatos.');
    $totalInserted = 0;
    foreach ($categories as $category) {
        logMessage("Buscando término: '$category'");
        $offset = 0;
        $apiBatchSize = 50;
        $counter = 1;

        do {
            logMessage("Iniciando llamada API para '$category', offset: $offset");
            // $url = "https://api.mercadolibre.com/sites/$SITE_ID/search?q=" . urlencode($term) . "&category=$category&condition=use&since=todayd&offset=$offset";
            $url = "https://api.mercadolibre.com/sites/$SITE_ID/search?category=$category&condition=used&since=today&offset=$offset";
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
            logMessage("Respuesta API recibida para '$category', offset: $offset");
            $datos = json_decode($resp, true);
            $results = isset($datos['results']) && !is_null($datos['results']) ? $datos['results'] : [];
            $resultsCount = count($results);
            logMessage("Número de resultados obtenidos: " . $resultsCount);
            // Insertar datos inmediatamente
            if ($pdo) {
                // Prepare the statement for INSERT with ON DUPLICATE KEY UPDATE
                logMessage("Iniciando inserción de datos para '$category'");
                $insertStmt = $pdo->prepare("INSERT INTO mla_consolasyvideojuegos_del_dia
                    (all_item_ids, all_titles, all_shipping, all_prices, all_thumbnail, all_links, all_item_condition, all_item_categoria, all_cuotas, all_cuotas_cantidad, all_cuotas_interes, status, source)
                    VALUES (:item_ids, :titles, :shipping, :prices, :thumbnail, :links, :item_condition, :item_categoria, :cuotas, :cuotas_cantidad, :cuotas_interes, :status, :source)
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
                    all_cuotas_interes = VALUES(all_cuotas_interes),
                    status = VALUES(status),
                    source = VALUES(source);");

                foreach ($results as $result) {
                    // Process each item and extract the required data
                    $itemId = isset($result['id']) ? $result['id'] : 'N/A';
                    $Title = isset($result['title']) ? $result['title'] : 'N/A';
                    $envioTraducido = isset($result['shipping']['free_shipping']) ? $result['shipping']['free_shipping'] : 'N/A';
                    $precio = isset($result['price']) ? $result['price'] : 'N/A';
                    $thumbnail = isset($result['thumbnail']) ? $result['thumbnail'] : 'N/A';
                    $enlace = isset($result['permalink']) ? $result['permalink'] : 'N/A';
                    $estadoTraducido = isset($result['condition']) ? $result['condition'] : 'N/A';
                    $categoryId = isset($result['category_id']) ? $result['category_id'] : 'N/A';
                    $categoryName = obtenerNombreCategoria($categoryId, $GLOBALS['categoryMappingsConsolas']);
                    $cuotas = isset($result['installments']['amount']) ? $result['installments']['amount'] : 'N/A';
                    $cuotasCantidad = isset($result['installments']['quantity']) ? $result['installments']['quantity'] : 'N/A';
                    $cuotasInteres = isset($result['installments']['rate']) ? $result['installments']['rate'] : 'N/A';
                    $source = 'hoy';

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
                        ':cuotas_interes' => $cuotasInteres,
                        ':status' => 1,
                        ':source' => $source,
                    ]);
                }
                $totalInserted += $resultsCount;
                logMessage("Insertados $resultsCount artículos del término '$category'. Total acumulado: $totalInserted");
            } else {
                logMessage("Error: No se pudo establecer una conexión con la base de datos.");
                return 0; // Salir si no hay conexión a la base de datos
            }

            logMessage("Llamado No. $counter para '$category': Se han obtenido " . count($results) . " artículos");
            $counter++;

            // Proximos datos
            $offset += $apiBatchSize;
            logMessage("Pausa de 0.5 segundos antes de la siguiente llamada");
            // Pausa
            usleep(500000); // Pausa por 0.2 segundos (500,000 microsegundos)

        } while ($resultsCount == $apiBatchSize);
        logMessage("Búsqueda completada para la categoría '$category'");
    }

    if ($pdo) {
        // Borrar datos viejos
        logMessage("Iniciando borrado de datos viejos");
        $deleteStmt = $pdo->prepare("DELETE FROM mla_consolasyvideojuegos_del_dia WHERE status = 0 AND source = :source");
        $deleteStmt->execute([':source' => 'hoy']);
        logMessage("Datos viejos borrados exitosamente");
    }
    logMessage("Proceso de extraerDatos completado. Total de artículos insertados: $totalInserted");
    return $totalInserted;
}

$categories = ['MLA1144'];

$totalResultsDatos = extraerDatos($pdo, 'MLA', $ACCESS_TOKEN, $categories);
logMessage("Proceso de extracción de datos finalizado. Total de artículos insertados: $totalResultsDatos");

$jobName = "setHoy";
updateJobLog($pdo, $jobName);
logMessage('Last run seteado satisfactoriamente.');
