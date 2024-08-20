<?php
check_auth(['usuario', 'admin']);

// Definir variables con valores predeterminados
$onGoingSearch = false;

if (!isset($_GET['pais']) || !isset($_GET['categoria'])) {
    $onGoingSearch = false;
    ?>
    <table class="table table-sm table-bordered border-estilo">
        <?php include_once "views/layout/encabezado_de_tabla.php"; ?>
        <tr>
            <td class="p-3" colspan="2">
                <?php require_once "views/layout/filtros.php"; ?>
            </td>
        </tr>
    </table>
<?php } else {
    $onGoingSearch = true;
    // Pagina default cuando las busquedas se salen de las validaciones
    $pagina_buscador = $url_base . "?dir=gamehunt";
    $noHayResultados = "No hay resultado para mostrar.";

    // Validar los datos obtenidos de los selects
    require_once "validaciones/validar_paises.php"; // paises
    require_once "validaciones/validar_categorias.php"; // categorias
    $ordenes = ["precio_asc", "precio_desc", "alfabetico"];
    require_once "validaciones/validar_subcategorias.php"; // subcategorias
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

    require_once './models/VideGameModel.php';
    $videoGameModel = new VideoGameModel($pdo);

    $filters = [
        'palabra' => $palabra,
        'subcategoria' => $subcategoria,
        'precio_min' => $precio_min,
        'precio_max' => $precio_max,
        'orden' => $orden
    ];

    $page = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
    $perPage = 15;

    $totalGames = $videoGameModel->countVideoGames($filters);
    $totalPages = ceil($totalGames / $perPage);

    $allData = $videoGameModel->getAllVideoGames($filters, $page, $perPage);

    // Hacer el chequeo de datos
    if (!in_array($pais, $paises) || !in_array($categoria, $categorias) || ($orden && !in_array($orden, $ordenes)) || ($subcategoria && !in_array($subcategoria, $subcategorias))) {
        // Si los datos no pasan el filtro de validacion
        //echo "No hay nada que mostrar.";
        header("Location: $pagina_buscador");
        exit;
    }

    require_once "utils/funciones_paises_y_categorias.php";
    $pais = convertirPais($pais);
    $categoria = convertirCategoria($categoria);
    ?>
    <table class="table table-sm table-bordered border-estilo">
        <?php include_once "views/layout/encabezado_de_tabla.php"; ?>
        <tr>
            <td class="p-3" colspan="2">
                <?php require_once "views/layout/filtros.php"; ?>
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
        $key = $key_enlaces;
        $encryptedurl = base64_encode($armarurl);

        // URL-safe encoding
        $enlaceurl = urlencode($encryptedurl);
        ?>
            <tr>
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
                            <img src="assets/imgs/cofre.png" width="55px" alt="Cofre" id="viajero">
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
                            <img src="assets/imgs/cofre.png" width="55px" alt="Cofre" id="viajero">
                        </a>
                    </div>
                </td>
            </tr>
        <?php endforeach; ?>
        <?php if (empty($allData)): ?>
            <tr> 
                <td class="p-3" colspan="2">
                    <article>
                        <p><img src="./assets/imgs/manual.png" width="150px" height="150px" class="shadow rounded" alt="Libro antiguo" id="floatleft"></p>
                        <p><?php echo $noHayResultados;?></p>
                    </article>
                </td>
            </tr>
        <?php endif; ?>
    </table>
    <div class="d-flex justify-content-center">
        <?php require_once "./config/paginacion.php"; ?>
    </div>
<?php } ?>