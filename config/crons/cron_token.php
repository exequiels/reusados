<?php

error_reporting(E_ALL);

// Ruta del archivo de log
$logFile = __DIR__ . '/cron_token.txt';

require_once "/home/u764883179/public_html/config/crons/loader_function.php";
logMessage('Loader cargado.');

$APP_ID = $_ENV['CLIENT_ID'];
$SECRET_KEY = $_ENV['CLIENT_SECRET'];
$refresh_token = $tokenData[0]['refresh_token'];
logMessage('Cargamos los token. Iniciamos llamado.');


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
logMessage('Obtenemos respuesta.');

if (isset($responseData['error'])) {
    die('Error refreshing token: ' . $responseData['error']);
}

$access_token = $responseData['access_token'];
$refresh_token = $responseData['refresh_token'];
$expires_in = $responseData['expires_in'];

$tokenModel->upsertToken($access_token, $refresh_token, $expires_in);
logMessage('Token refrescado satisfactoriamente.');

$jobName = "setToken";
updateJobLog($pdo, $jobName);
logMessage('Last run seteado satisfactoriamente.');
