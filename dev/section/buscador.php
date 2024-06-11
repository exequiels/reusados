<?php if (!isset($_GET['pais']) || !isset($_GET['categoria'])) {
    $onGoingSearch = false;
    ?>
    <table class="table table-sm table-bordered border-estilo">
        <?php include_once "globales/encabezado_de_tabla.php"; ?>
        <tr class="p-3">
            <td class="p-3" colspan="2">
                <?php require_once "layout/filtros.php"; ?>
            </td>
        </tr>
    </table>
<?php } else {
    $onGoingSearch = true;
    // Pagina default cuando las busquedas se salen de las validaciones
    $pagina_buscador = $url_base . "?dir=buscador";
    $noHayResultados = "No hay resultado para mostrar.";

    // Validar los datos obtenidos de los selects
    require_once "globales/validar_paises.php"; // paises
    require_once "globales/validar_categorias.php"; // categorias
    $ordenes = ["precio_asc", "precio_desc", "alfabetico"];
    require_once "globales/validar_subcategorias.php"; // subcategorias
    $publicaciones = ["destacados", "oportunidad"];

    // Datos obtenidos de los selects
    $pais = isset($_GET['pais']) ? urldecode($_GET['pais']) : '';
    $categoria = isset($_GET['categoria']) ? urldecode($_GET['categoria']) : '';
    $palabra = isset($_GET['articulo']) ? $_GET['articulo'] : '';
    $orden = isset($_GET['orden']) ? $_GET['orden'] : '';
    $precio_min = isset($_GET['precio_min']) && is_numeric($_GET['precio_min']) ? floatval($_GET['precio_min']) : null;
    $precio_max = isset($_GET['precio_max']) && is_numeric($_GET['precio_max']) ? floatval($_GET['precio_max']) : null;
    $subcategoria = isset($_GET['subcategoria']) ? $_GET['subcategoria'] : '';
    $publicaciones = isset($_GET['publicaciones']) ? $_GET['publicaciones'] : '';

    // Hacer el chequeo de datos
    if (!in_array($pais, $paises) || !in_array($categoria, $categorias) || ($orden && !in_array($orden, $ordenes)) || ($subcategoria && !in_array($subcategoria, $subcategorias))) {
        // Si los datos no pasan el filtro de validacion
        //echo "No hay nada que mostrar.";
        header("Location: $pagina_buscador");
        exit;
    }

    require_once "globales/funciones_paises_y_categorias.php";
    $pais = convertirPais($pais);
    $categoria = convertirCategoria($categoria);

    // Cuando recibo filtro publicacion
    switch ($publicaciones) {
        case "destacados":
            $publicaciones = "mas";
            break;
        case "oportunidad":
            $publicaciones = "menos";
            break;
        default:
            $publicaciones =  "";
            break;
    }

    // Iniciamos la variable de paginas totales
    $total_pages = 1;

    // Cuando recibo un orden
    switch ($orden) {
        case "precio_asc":
            $orderBy = "ORDER BY CAST(all_prices AS DECIMAL) ASC";
            break;
        case "precio_desc":
            $orderBy = "ORDER BY CAST(all_prices AS DECIMAL) DESC";
            break;
        case "alfabetico":
            $orderBy = "ORDER BY all_titles ASC";
            break;
        default:
            $orderBy = "";
            break;
    }

    // Cuando recibimos rango de precios
    $range = "";
    if (isset($_GET['precio_min']) && isset($_GET['precio_max'])) {
        $range = "AND CAST(all_prices AS DECIMAL) BETWEEN :precio_min AND :precio_max";
    }

    // Obtener el número de página actual de la URL
    if (isset($_GET['pagina'])) {
        if (!ctype_digit($_GET['pagina']) || intval($_GET['pagina']) <= 0) {
            // Si la pagina no corresponde a un int > a 0
            //exit;
        }
    }

    $manual_page = isset($_GET['pagina']) && is_numeric($_GET['pagina']) ? intval($_GET['pagina']) : 1;
    $items_per_page = 12;

    // Calculate the offset
    $offset = ($manual_page - 1) * $items_per_page;

    // Construct the base SQL query
    if($publicaciones == "mas") {
        $sqlBase = "SELECT * FROM " . $pais . "_" . $categoria . "_" . $publicaciones;
    } elseif($publicaciones == "menos") {
        $sqlBase = "SELECT * FROM " . $pais . "_" . $categoria . "_" . $publicaciones;
    } else {
        $sqlBase = "SELECT * FROM (" .
        "SELECT * FROM " . $pais . "_" . $categoria . "_mas"
        . " UNION ALL "
        . "SELECT * FROM " . $pais . "_" . $categoria . "_menos"
        . ") as results";
    }

    $sqlConditions = []; // Placeholder for WHERE conditions
    $sqlValues = []; // Placeholder for parameter values

    if (!empty($subcategoria)) {
        $sqlConditions[] = "all_item_categoria = :subcategoria";
        $sqlValues[':subcategoria'] = $subcategoria;
    }

    if (!empty($palabra)) {
        $palabraMinusculas = strtolower($palabra);
        $sqlConditions[] = "LOWER(all_titles) LIKE :palabra";
        $sqlValues[':palabra'] = "%$palabraMinusculas%";
    }

    // Add range condition if applicable
    if ($range) {
        $sqlConditions[] = "CAST(all_prices AS DECIMAL) BETWEEN :precio_min AND :precio_max";
        $sqlValues[':precio_min'] = $precio_min;
        $sqlValues[':precio_max'] = $precio_max;
    }

    // If there are conditions, add WHERE clause
    if (!empty($sqlConditions)) {
        $sqlBase .= " WHERE " . implode(" AND ", $sqlConditions);
    }

    // Add ORDER BY clause if applicable
    if ($orderBy) {
        $sqlBase .= " $orderBy";
    }

    // Add LIMIT clause for pagination
    $sqlBase .= " LIMIT :offset, :items_per_page";

    // Prepare and execute the main query
    $stmt = $pdo->prepare($sqlBase);

    // Bind parameters
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
    $stmt->bindValue(':items_per_page', $items_per_page, PDO::PARAM_INT);

    // Bind other parameters if applicable
    foreach ($sqlValues as $param => $value) {
        $stmt->bindValue($param, $value);
    }

    // Execute the main query
    $stmt->execute();
    $allData = $stmt->fetchAll(PDO::FETCH_ASSOC);


    // Construct the count SQL query
    //$sqlCount = "SELECT COUNT(*) FROM " . $pais . "_" . $categoria;
    $countConditions = []; // Placeholder for COUNT WHERE conditions

    if ($publicaciones == "mas") {
        $sqlCount = "SELECT COUNT(*) FROM " . $pais . "_" . $categoria . "_" . $publicaciones;
    } elseif ($publicaciones == "menos") {
        $sqlCount = "SELECT COUNT(*) FROM " . $pais . "_" . $categoria . "_" . $publicaciones;
    } else {
        $sqlCount = "SELECT COUNT(*) FROM ("
            . "SELECT * FROM " . $pais . "_" . $categoria . "_mas"
            . " UNION ALL "
            . "SELECT * FROM " . $pais . "_" . $categoria . "_menos"
            . ") as unionTable";
    }

    //if (!empty($publicaciones)) {
    //$sqlCount .= "_" . $publicaciones;
    //}

    if (!empty($subcategoria)) {
        $countConditions[] = "all_item_categoria = :subcategoria";
        $sqlValues[':subcategoria'] = $subcategoria;
    }

    if (!empty($palabra)) {
        $palabraMinusculas = strtolower($palabra);
        $countConditions[] = "LOWER(all_titles) LIKE :palabra";
        $sqlValues[':palabra'] = "%$palabraMinusculas%";
    }
    //if (!empty($palabra)) {
    //  $countConditions[] = "all_titles LIKE :palabra";
    //$sqlValues[':palabra'] = "%$palabra%";
    //}

    // Add range condition if applicable
    if ($range) {
        $countConditions[] = "CAST(all_prices AS DECIMAL) BETWEEN :precio_min AND :precio_max";
        $sqlValues[':precio_min'] = $precio_min;
        $sqlValues[':precio_max'] = $precio_max;
    }

    // If there are conditions, add WHERE clause
    if (!empty($countConditions)) {
        $sqlCount .= " WHERE " . implode(" AND ", $countConditions);
    }

    // Prepare and execute the count query
    $stmtCount = $pdo->prepare($sqlCount);

    // Bind parameters
    foreach ($sqlValues as $param => $value) {
        $stmtCount->bindValue($param, $value);
    }

    // Execute the count query
    $stmtCount->execute();
    $totalRecords = $stmtCount->fetchColumn();
    //echo "Total Records: $totalRecords";

    // Calcular total de paginas
    $total_pages = ceil($totalRecords / $items_per_page);


    // Calcular si la pagina en el navegador es > al total de paginas
    if ($manual_page > $total_pages) {
        // Si la pagina excede a los resultados
        //exit;
    }
    ?>
    <table class="table table-sm table-bordered border-estilo">
        <?php include_once "globales/encabezado_de_tabla.php"; ?>
        <tr class="p-3">
            <td class="p-3" colspan="2">
                <?php require_once "layout/filtros.php"; ?>
            </td>
        </tr>
        <?php
            function truncateText($text, $length)
            {
                $words = explode(' ', $text);
                $truncatedWords = array_map(function ($word) use ($length) {
                    return (strlen($word) > $length) ? substr($word, 0, $length) . '..' : $word;
                }, $words);

                return htmlspecialchars(implode(' ', $truncatedWords));
            }
    foreach ($allData as $row):
        // Armar url
        $armarurl = json_encode([
            'redir' => $row['all_links'],
            'pais' => $pais,
            'categoria' => $categoria,
            'subcategoria' => $row['all_item_categoria']
        ]);

        // Encriptar
        $key = 'xS#LHdnmweOSDl2597641093LKsfbiq76whd6';
        $encryptedurl = base64_encode($armarurl . $key);

        // URL-safe encoding
        $enlaceurl = urlencode($encryptedurl);
        ?>
            <tr class="p-3">
                <!-- Contenido para escritorio (visible en pantallas de tamaño md y más grandes) -->
                <td class="d-none d-md-table-cell p-3">
                    <h6 class="mt-2"><?php echo htmlspecialchars($row['all_item_categoria']); ?></h6>
                    <img src="<?php echo htmlspecialchars($row['all_thumbnail']); ?>" class="border border-estilo rounded-3" width="75" height="75" rel="noopener noreferrer nofollow" alt="<?php echo truncateText($row['all_titles'], 20); ?>">
                </td>
                <td class="d-none d-md-table-cell p-3">
                    <h6><?php echo truncateText($row['all_titles'], 20); ?></h6>
                    <h6 class="text-success fw-bold"><?php echo htmlspecialchars($row['all_prices']); ?></h6>
                    <?php echo($row['all_cuotas'] == 0 ? '<h6 class="text-success fw-bold">($' . htmlspecialchars(bcdiv($row['all_prices'], $row['all_cuotas_cantidad'], 2)) . ' x ' . htmlspecialchars($row['all_cuotas_cantidad']) . ' sin interés)</h6>' : ''); ?>
                    <h6 class="text-success fw-bold"><?php echo ($row['all_shipping'] == 1) ? 'Envio gratis' : ''; ?></h6>
                    <h6><?php echo ($row['all_item_condition'] == 'used') ? 'Usado' : ''; ?></h6>
                    <div class="d-flex justify-content-end">
                        <a href="enlace?dot=<?php echo urlencode($encryptedurl); ?>" data-pais="<?php echo htmlspecialchars($pais); ?>" data-categoria="<?php echo htmlspecialchars($categoria); ?>" data-subcategoria="<?php echo htmlspecialchars($row['all_item_categoria']); ?>" target="_blank">
                            <img src="imgs/cofre.png" width="55px" alt="Cofre" id="viajero">
                        </a>
                    </div>
                </td>
                <!-- Contenido para celular (visible en pantallas de tamaño sm y más pequeñas) -->
                <td class="d-table-cell d-md-none p-3">
                    <h6 class="mt-2"><?php echo htmlspecialchars($row['all_item_categoria']); ?></h6>
                    <img src="<?php echo htmlspecialchars($row['all_thumbnail']); ?>" class="border border-estilo rounded-3" width="75" height="75" rel="noopener noreferrer nofollow" alt="<?php echo truncateText($row['all_titles'], 20); ?>">
                    <h6 class="mt-2"><?php echo truncateText($row['all_titles'], 20); ?></h6>
                    <h6 class="text-success fw-bold"><?php echo htmlspecialchars($row['all_prices']); ?></h6>
                    <?php echo($row['all_cuotas'] == 0 ? '<h6 class="text-success fw-bold">($' . htmlspecialchars(bcdiv($row['all_prices'], $row['all_cuotas_cantidad'], 2)) . ' x ' . htmlspecialchars($row['all_cuotas_cantidad']) . ' sin interés)</h6>' : ''); ?>
                    <h6 class="text-success fw-bold"><?php echo ($row['all_shipping'] == 1) ? 'Envio gratis' : ''; ?></h6>
                    <h6><?php echo ($row['all_item_condition'] == 'used') ? 'Usado' : ''; ?></h6>
                    <div class="d-flex justify-content-end">
                        <a href="enlace?dot=<?php echo urlencode($encryptedurl); ?>" data-pais="<?php echo htmlspecialchars($pais); ?>" data-categoria="<?php echo htmlspecialchars($categoria); ?>" data-subcategoria="<?php echo htmlspecialchars($row['all_item_categoria']); ?>" target="_blank">
                            <img src="imgs/cofre.png" width="55px" alt="Cofre" id="viajero">
                        </a>
                    </div>
                </td>
            </tr>
        <?php endforeach; ?>
        <?php if (empty($allData)): ?>
        <tr class="p-3"> 
            <td class="p-3" colspan="2">
                <article>
                    <p><img src="imgs/manual.png" width="150px" height="150px" class="shadow rounded" alt="Libro antiguo" id="floatleft"></p>
                    <p><?php echo $noHayResultados;?></p>
                </article>
            </td>
        </tr>
    <?php endif; ?>
    </table>
<?php require_once "layout/paginacion.php"; ?>
<?php } ?>