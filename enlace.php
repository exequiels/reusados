<?php

// Validar datos GET
require_once "validaciones/validar_paises_iniciales.php"; // paises
require_once "validaciones/validar_categorias.php"; // categorias
require_once "validaciones/validar_subcategorias.php"; // subcategorias
require_once "config/variables.php"; // variables
require_once "models/LinkModel.php";

// Datos obtenidos de los selects
if (isset($_GET['dot'])) {
    $encryptedData = $_GET['dot'];
    echo $encryptedData;

    // Desencriptar
    $key = $key_enlaces;
    $decryptedData = base64_decode($encryptedData);

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
    if (!in_array($pais, $paises_iniciales) || !in_array($categoria, $categorias) || !in_array($subcategoria, $subcategorias)) {
        header("Location: " . $desvioUrl);
        throw $e;
        exit();
    }

    // Database operations
    include_once "config/connectar.php";
    $tabla = $pais . "_" . $categoria . "_clicks";

    // Create instance of the model
    $linkModel = new LinkModel($pdo);

    try {
        $rowExists = $linkModel->rowExists($tabla, $currentDate, $categoria, $subcategoria);

        if ($rowExists == 0) {
            $linkModel->insertClick($tabla, $currentDate, $categoria, $subcategoria);
        } else {
            $linkModel->updateClick($tabla, $currentDate, $categoria, $subcategoria);
        }
    } catch (Exception $e) {
        echo $e->getMessage();
    }

    // Enlace original
    header("Location: " . $enlace);
    exit();
} else {
    header("Location: " . $desvioUrl);
    exit();
}
