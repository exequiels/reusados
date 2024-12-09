<?php

// Función para registrar mensajes en el log
function logMessage($message)
{
    global $logFile;
    file_put_contents($logFile, date('Y-m-d H:i:s') . ' - ' . $message . PHP_EOL, FILE_APPEND);
}

// Registrar el inicio del script
logMessage('Script iniciado.');

// Ruta a autoload.php
$autoloadPath = '/home/u764883179/vendor/autoload.php';
logMessage('Autoload cargado con exito.');

if (file_exists($autoloadPath)) {
    require $autoloadPath;
} else {
    logMessage('Autoload no encontrado: ' . $autoloadPath);
    exit;
}

use Dotenv\Dotenv;

// Ruta a .env
$dotenvPath = '/home/u764883179/public_html/.env';
logMessage('Intentando cargar .env.');

// Checkear existencia de archivo .env
if (file_exists($dotenvPath)) {
    logMessage('.env file exists.');

    try {
        $dotenv = Dotenv::createImmutable('/home/u764883179/public_html');
        $dotenv->load();

    } catch (Exception $e) {
        logMessage('Error loading .env file: ' . $e->getMessage());
        exit('Error cargar .env.');
    }
} else {
    logMessage('Archivo .env no encontrado.');
    exit('Error: .env no existe.');
}

// Log al final de la primer parte
logMessage('Script ejecutado con exito.');

try {
    // Log un paso antes de cargar archivos de coneccion
    logMessage('Cargar archivos de coneccion.');

    // Incluir connectar
    require_once "/home/u764883179/public_html/config/crons/cron_connectar.php";

    // Incluido
    logMessage('Archivo de coneccion incluido exitosamente.');

    // Incluir last run function
    require_once "/home/u764883179/public_html/config/crons/last_run_function.php";
    logMessage('Archivo de last run incluido exitosamente.');

    // Incluir mapa de categorias
    require_once "/home/u764883179/public_html/config/crons/categoria_function.php";
    logMessage('Archivo de categorias incluido exitosamente si hace falta.');

    // Coneccion a la base de datos
    if (isset($pdo)) {
        logMessage('Conexión a la base de datos exitosa.');
    } else {
        logMessage('Variable PDO no está definida después de incluir el archivo.');
    }
} catch (PDOException $e) {
    logMessage('Error de conexión a la base de datos: ' . $e->getMessage());
} catch (Exception $e) {
    logMessage('Error general: ' . $e->getMessage());
}

// Llegamos al final de conectar
logMessage('Llegamos al final de conectar');
logMessage('Conecion cargada existosamente.');

// Continuar con la lógica del script
logMessage('Contiuamos ejecucion de script.');

logMessage('Iniciamos carga del modelo de token.');
require_once '/home/u764883179/public_html/models/TokenModel.php';
logMessage('Modelo de token cargado correctamene.');

// Iniciamos modelo de tokenl
logMessage('Iniciamos modelo de token.');
$tokenModel = new TokenModel($pdo);
logMessage('Modelo de token iniciado correctamente.');

// Intento de extraccion de token
logMessage('Intento de extraccion de token.');
$tokenData = $tokenModel->getToken();

if (empty($tokenData) || !isset($tokenData[0]['refresh_token'])) {
    logMessage('No encontradamos data de tokens, o de refresh token.');
    exit('No encontradamos data de tokens, o de refresh token.');
} else {
    logMessage('Token obtenido satisfactoriamente.');
}

// Continuar con la lógica del script
logMessage('Continuamos con la logica del script.');
