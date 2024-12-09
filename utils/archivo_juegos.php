<?php
require_once '../config/connectar.php';
require_once '../models/ArchivoModel.php';

try {
    if (isset($_POST['consolas'])) {
        $consola = $_POST['consolas'];

        $archivoModel = new ArchivoModel($pdo);
        $juegos = $archivoModel->getJuegosPorConsola($consola);

        if (!empty($juegos)) {
            echo '<option value=""> - Selecciona un juego - </option>';
            foreach ($juegos as $juego) {
                echo "<option value=\"" . strtolower($juego['juego']) . "\">" . $juego['juego'] . "</option>";
            }
        } else {
            echo '<option value="">No hay juegos disponibles</option>';
        }
    } else {
        echo '<option value="">Consola no seteada</option>';
    }
} catch (PDOException $e) {
    echo '<option value="">' . $e->getMessage() . '</option>';
}
