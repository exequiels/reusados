<?php

require_once "utils/funciones_paises_y_categorias.php";

try {
    if (isset($_POST['categoria'])) {

        $pais = isset($_POST['pais']) ? $_POST['pais'] : '';
        $categoria = isset($_POST['categoria']) ? $_POST['categoria'] : '';

        $pais = convertirPais($pais);
        $categoria = convertirCategoria($categoria);
        $subcategorias = $videoGameModel->getSubcategorias($pais, $categoria);

        if (!empty($subcategorias)) {
            echo '<option value=""> Todas </option>';
            foreach ($subcategorias as $subcategoria) {
                echo "<option value=\"" . strtolower($subcategoria['subcategoria']) . "\">" . $subcategorias['subcategoria'] . "</option>";
            }
        } else {
            echo '<option value="">No hay subcategorias disponibles</option>';
        }
    } else {
        echo '<option value="">Subcategorias no seteadas</option>';
    }
} catch (PDOException $e) {
    echo '<option value="">' . $e->getMessage() . '</option>';
}
