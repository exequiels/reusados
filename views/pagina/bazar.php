<?php
denied_permissions_functions(2);

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
    $pagina_buscador = $url_base . "?dir=bazar";
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
    <table class="table table-sm table-bordered border-estilo table-striped">
        <thead>
            <?php include_once "views/layout/encabezado_de_tabla.php"; ?>
            <tr>
                <td class="p-3" colspan="2">
                    <?php require_once "views/layout/filtros.php"; ?>
                </td>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>
                    <div class="container">
                        <div class="row">
                        <?php if ($page === 1 && has_permission(8)):  ?>
                            <div class="col-12 mb-2 mt-2">
                                <div class="user-card p-3 border border-dark d-flex flex-column">
                                    <div class="d-flex flex-column flex-md-row align-items-center align-items-md-start">
                                        <div class="user-info text-start flex-grow-1">
                                            <div class="details">
                                                <p class="mb-1">
                                                    Entras a un bazar antiguo, lleno de estanterías desvencijadas y rincones olvidados. Entre cajas polvorientas y curiosidades en desorden, un objeto destaca y te atrae de inmediato. Al tocarlo, una extraña claridad te invade, como si todo cobrara sentido por un instante. Fascinado, preguntas por él. La anticuaria te dice que perteneció a un legendario coleccionista, pero no está a la venta. "Puedes venir a verla cuando quieras...", susurra.
                                                </p>
                                            </div>
                                        </div>
                                        <a href="?dir=la-mascara" title="La mascara de Go...">
                                            <img src="assets/imgs/mascara.jpeg" 
                                            class="border border-dark mt-3 mt-md-0" 
                                            width="120px" height="120px" 
                                            />
                                        </a>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>
                            <?php
                            function truncateText($text, $length)
                            {
                                $words = explode(' ', $text);
                                $truncatedWords = array_map(function ($word) use ($length) {
                                    return (strlen($word) > $length) ? substr($word, 0, $length) . '..' : $word;
                                }, $words);
                                return escape(implode(' ', $truncatedWords));
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
                                <div class="col-12 mb-2 mt-2">
                                    <div class="user-card p-3 border border-dark d-flex flex-column">
                                        
                                        <div class="d-flex flex-column flex-md-row align-items-center align-items-md-start">
                                            <img src="<?php echo escape($row['all_thumbnail']); ?>" 
                                                class="img-fluid mb-3 mb-md-0 me-md-3 border border-dark" 
                                                width="120" height="120" 
                                                rel="noopener noreferrer nofollow" 
                                                alt="<?php echo truncateText($row['all_titles'], 20); ?>">

                                            <div class="user-info text-start flex-grow-1">
                                                <div class="username mb-2">
                                                    <?php echo truncateText($row['all_titles'], 10); ?>
                                                </div>
                                                <div class="details">
                                                    <p class="mb-1 text-success">$<?php echo escape($row['all_prices']); ?></p>
                                                    <?php echo($row['all_cuotas_interes'] == 0 && is_numeric($row['all_cuotas']) ? '<p class="mb-1 text-success">($' . escape($row['all_cuotas']) . ' x ' . escape($row['all_cuotas_cantidad']) . ' sin interés)</p>' : ''); ?>
                                                    <?php echo ($row['all_shipping'] == 1) ? '<p class="mb-1 text-success">Envio gratis</p>' : ''; ?>
                                                    <p class="mb-1"><?php echo ($row['all_item_condition'] == 'used') ? 'Usado' : ''; ?></p>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="d-flex justify-content-end mt-auto">
                                            <a href="enlace?dot=<?php echo urlencode($encryptedurl); ?>" 
                                            data-pais="<?php echo escape($pais); ?>" 
                                            data-categoria="<?php echo escape($categoria); ?>" 
                                            data-subcategoria="<?php echo escape($row['all_item_categoria']); ?>" 
                                            target="_blank"
                                            rel="noopener noreferrer nofollow">
                                                <img src="assets/imgs/cofre.png" width="55px" alt="Cofre" id="cofre">
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>

                            <!-- Tarjeta de "no hay resultados" -->
                            <?php if (empty($allData)): ?>
                                <div class="col-12 mb-3 mt-3">
                                    <div class="user-card p-3 border border-dark d-flex flex-column align-items-start">
                                        <img src="./assets/imgs/manual.png" width="150px" height="150px" class="shadow rounded me-3 mb-3" alt="Libro antiguo" id="floatleft">
                                        <p class="mb-0"><?php echo $noHayResultados; ?></p>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </td>
            </tr>
        </tbody>
    </table>
    <div class="d-flex justify-content-center">
        <?php require_once "./config/paginacion.php"; ?>
    </div>
<?php } ?>