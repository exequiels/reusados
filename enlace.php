<?php

// Validar datos GET
require_once "globales/validar_paises_iniciales.php"; // paises
require_once "globales/validar_categorias.php"; // categorias
require_once "globales/validar_subcategorias.php"; // subcategorias
require_once "globales/variables.php"; // variables globales

if (isset($_GET['dot'])) {
    $encryptedData = $_GET['dot'];

    // Desencriptar
    $key = $key_enlaces;
    $decryptedData = base64_decode($encryptedData);
    $decryptedData = substr($decryptedData, 0, -strlen($key));

    // Parsear JSON
    $params = json_decode($decryptedData, true);
    print_r($params);

    // Extraer parametros
    $enlace = $params['redir'];
    $pais = $params['pais'];
    $categoria = $params['categoria'];
    $subcategoria = strtolower($params['subcategoria']);
    $currentDate = date("Y-m-d");

    // Validar datos
    if (!in_array($pais, $paises) || !in_array($categoria, $categorias) || !in_array($subcategoria, $subcategorias)) {

        header("Location:" . $desvioUrl);
        //echo "Error 033";
        exit();
    }

    include_once "connectar.php";

    $tabla = $pais . "_" . $categoria . "_clicks";
    try {
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM $tabla WHERE click_date = ? AND categoria = ? AND subcategoria = ?");
        $stmt->execute([$currentDate, $categoria, $subcategoria]);
        $rowExists = $stmt->fetchColumn();

        if ($rowExists == 0) {
            $insertStmt = $pdo->prepare("INSERT INTO $tabla (click_date, categoria, subcategoria, clicks) VALUES (?, ?, ?, 1)");
            $insertStmt->execute([$currentDate, $categoria, $subcategoria]);
        } else {
            $updateStmt = $pdo->prepare("UPDATE $tabla SET clicks = clicks + 1 WHERE click_date = ? AND categoria = ? AND subcategoria = ?");
            $updateStmt->execute([$currentDate, $categoria, $subcategoria]);
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }

    // Enlace original
    header("Location: " . $enlace);
    exit();
} else {
    header("Location: " . $desvioUrl);
    //echo "Error 044";
    exit();
}
