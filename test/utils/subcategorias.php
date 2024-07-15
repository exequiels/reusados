<?php

try {
    if (isset($_POST['categoria'])) {
        $pais = isset($_POST['pais']) ? $_POST['pais'] : '';
        $categoria = isset($_POST['categoria']) ? $_POST['categoria'] : '';

        // Cuando recibo el pais
        switch ($pais) {
            case "argentina":
                $pais = "MLA";
                break;
            case "paraguay":
                $pais = "MLP";
                break;
            case "chile":
                $pais = "MLC";
                break;
            default:
                $pais = "";
                break;
        }

        // Cuando recibo la categoria
        switch ($categoria) {
            case "videojuegos":
                $categoria = "consolasyvideojuegos";
                break;
            case "juguetes":
                $categoria = "juguetes";
                break;
            case "videojuegos":
                $categoria = "consolasyvideojuegos";
                break;
            case "filatelia":
                $categoria = "estampillas";
                break;
            case "monedas":
                $categoria = "monedasybilletes";
                break;
            case "musica":
                $categoria = "musica";
                break;
            default:
                $categoria = "";
                break;
        }

        // Construct the base SQL query
        if ($pais && $categoria) {
            $sql = $pdo->prepare("
                (SELECT DISTINCT all_item_categoria FROM " . $pais . "_" . $categoria . "_mas)
                UNION
                (SELECT DISTINCT all_item_categoria FROM " . $pais . "_" . $categoria . "_menos)
                ORDER BY all_item_categoria ASC
            ");
            $sql->execute();
        }

        if ($sql->errorCode() != 0) {
            $errors = $sql->errorInfo();
            echo $errors[2];
        } else {
            $filtroCategorias = $sql->fetchAll(PDO::FETCH_COLUMN);
            if (!empty($filtroCategorias)) {
                echo '<option value=""> Todas</option>';
                foreach ($filtroCategorias as $opcionFiltrada) {
                    echo "<option value=\"" . strtolower($opcionFiltrada) . "\" " . (isset($_GET['subcategoria']) && $_GET['subcategoria'] === strtolower($opcionFiltrada) ? 'selected' : '') . ">" . $opcionFiltrada . "</option>";
                }
            } else {
                echo "No Matching Projects Found for this Dashboard Type";
            }
        }
    } else {
        echo "Subcategorias no seteadas.";
    }
} catch (PDOException $e) {
    echo $e->getMessage();
}
