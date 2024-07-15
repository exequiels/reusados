<?php

// Charset=utf8mb4 explicit charset, for security purposes
$dbLink = getenv('DB_LINK');
$dbUser = getenv('DB_USER');
$dbPW = getenv('DB_PW');

// Security options
$options = [
    PDO::ATTR_EMULATE_PREPARES   => false,
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
  ];

try {
    // Connection
    $pdo = new PDO($dbLink, $dbUser, $dbPW, $options);
    // To not show errors -> Apache display_errors = Off and log_errors = On
} catch (Exception $e) {
    // Loguear errores en el futuro
    error_log($e->getMessage());
    exit('Algo raro paso..');
}
