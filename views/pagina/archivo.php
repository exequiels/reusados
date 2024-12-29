<?php
denied_permissions_functions(1);

$archivoModel = new ArchivoModel($pdo);
$usuario = $userModel->getUserDetailsById($_SESSION['user_id']);
$archivoConsolas = $archivoModel->getArchivoConsolas();
$archivoJuegos = [];
$juegoDetalles = null;
$imagenesJuego = [];

$defaultGameId = '';
$defaultConsolas = '';
$defaultJuegos = '';

// $gameId = isset($juegoDetalles['game_id']) ? escape($juegoDetalles['game_id']) : $defaultGameId;
$consolas = isset($_GET['consola']) ? escape($_GET['consola']) : $defaultConsolas;
$juegos = isset($_GET['juego']) ? escape($_GET['juego']) : $defaultJuegos;
$busquedaPor = isset($_GET['buscar_por']) ? $_GET['buscar_por'] : '';

if (isset($_GET['consola'])) {
    $archivoJuegos = $archivoModel->getJuegosPorConsola($_GET['consola']);
}

$mostrarResultadoJuego = ($busquedaPor === 'juego' && !empty($consolas) && !empty($juegos));
$mostrarResultadoImagen = ($busquedaPor === 'imagen' && !empty($consolas));

if (isset($_GET['consola'], $_GET['juego'], $_GET['buscar_por'])
    && $_GET['buscar_por'] === 'juego'
    && !empty($_GET['consola'])
    && !empty($_GET['juego'])) {

    $gameId = null;
    foreach ($archivoJuegos as $juego) {
        if (strtolower($juego['juego']) === strtolower($_GET['juego'])) {
            $gameId = $juego['game_id'];
            break;
        }
    }

    if ($gameId) {
        $juegoDetalles = $archivoModel->getJuegoDetalles($gameId);
        $imagenesJuego = $archivoModel->getImagenesJuego($gameId);
        $aportadoPorImagen = $archivoModel->getAportadoPor($gameId, 'imagen');
        $aportadoPorDato = $archivoModel->getAportadoPor($gameId, 'dato');
    }
} else {

}

$redireccionUrlporJuego = "?dir=archivo"
    . (isset($_POST['consola']) ? "&consola=" . strtolower($_POST['consola']) : "")
    . (isset($_POST['juego']) ? "&juego=" . strtolower($_POST['juego']) : "")
    . "&buscar_por=juego";

// Editar ficha del juego
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {

    $gameData = [
        'lanzamiento' => $_POST['lanzamiento'],
        'desarrollador' => $_POST['desarrollador'],
        'franquicia' => $_POST['franquicia'],
        'sistema' => $_POST['sistema'],
        'region' => $_POST['region'],
        'remakes' => $_POST['remakes']
    ];

    $editarJuego = $archivoModel->updateJuegoDetalles($gameId, $gameData);

    if ($editarJuego) {
        $archivoModel->insertAporte($gameId, $usuario['username'], 'dato');
        $_SESSION['message'] = "Juego actualizado correctamente.";
        $_SESSION['message_type'] = "success";
    } else {
        $_SESSION['message'] = "Error al actualizar el juego.";
        $_SESSION['message_type'] = "error";
    }

    header("Location: $redireccionUrlporJuego");
    exit;
}

// Subir o editar imagenes del juego
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['subir_button'])) {

    if (isset($_POST['game_id']) && !empty($_POST['game_id'])) {
        $gameId = $_POST['game_id'];

        if (isset($_FILES['image']) && !empty($_FILES['image']['name'][0])) {

            $resultado = $archivoModel->subirImagenes($gameId, $_FILES['image']);

            if ($resultado) {
                $archivoModel->insertAporte($gameId, $usuario['username'], 'imagen');
                $_SESSION['message'] = "Imágenes subidas correctamente.";
                $_SESSION['message_type'] = "success";
            } else {
                $_SESSION['message'] = "Error al subir imágenes.";
                $_SESSION['message_type'] = "error";
            }
        } else {
            // No hacemos nada, simplemente permitimos que se recargue la página
        }
    } else {
        $_SESSION['message'] = "Error: No se proporcionó un ID de juego válido.";
        $_SESSION['message_type'] = "error";
    }

    header("Location: $redireccionUrlporJuego");
    exit;
}

// Borrar imagenes de la ficha del juego
if (isset($_POST['del_button']) && !empty($_POST['selected_images'])) {
    $selectedImages = $_POST['selected_images'];

    $archivoModel->borrarImagenes($selectedImages);

    header("Location: $redireccionUrlporJuego");
    exit;
}

if (isset($_GET['consola'], $_GET['buscar_por'])
    && !empty($_GET['consola'])
    && $_GET['buscar_por'] === 'imagen') {

    $consola = $_GET['consola'];
    $juegosConPortada = $archivoModel->getJuegosConPortadaPorConsola($consola);

    foreach ($juegosConPortada as &$juego) {
        // Obtener las imágenes para cada juego
        $imagenesJuego = $archivoModel->getImagenesJuegoThumbnail($juego['game_id']);
        $juego['portada'] = null; // Inicializamos la portada como null

        // Filtrar la portada
        foreach ($imagenesJuego as $imagen) {
            if (strpos(strtolower($imagen['image_path']), 'thumbnail_portada') !== false) {
                $juego['portada'] = $imagen['image_path']; // Guardamos la portada
                break; // Salimos del bucle una vez encontrada la portada
            }
        }
    }
}

$pagina_archivo = "https://www.reusados.net/?dir=archivo";
?>
<table class="table table-sm table-bordered border-estilo">
    <?php include_once "views/layout/encabezado_de_tabla.php"; ?>
    <tr> 
        <td class="p-3" colspan="2">
        <form id="archivo-form" method="get">
            <input type="hidden" name="dir" value="archivo">
            <div class="form-group mt-3">
            Consola:
                <select name="consola" id="consola" class="filtros" required>
                    <option value="">- Seleccionar - </option>
                    <?php foreach($archivoConsolas as $consola): ?>
                        <option value="<?= escape(strtolower($consola['sistema'])) ?>" <?= ($consolas === strtolower($consola['sistema'])) ? 'selected' : ''; ?>><?= escape($consola['sistema']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group mt-3">
                Juego:
                <select name="juego" id="juego" class="filtros">
                    <option value="">- Seleccionar -</option>
                    <?php if (!empty($archivoJuegos)): ?>
                        <?php foreach($archivoJuegos as $juego): ?>
                            <option value="<?= escape(strtolower($juego['juego'])); ?>" <?= ($juegos === strtolower($juego['juego'])) ? 'selected' : ''; ?>><?= escape($juego['juego']); ?></option>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </select>
            </div>
            <hr />
            <div class="d-flex justify-content-between align-items-center mt-3">
                <div>Buscar:</div>
                <div>
                    <div class="d-flex justify-content-end mt-3">
                        <button type="button" class="buscar-btn" data-buscar="juego">Por Juego</button>
                    </div>
                    <div class="d-flex justify-content-end mt-3">
                        <button type="button" class="buscar-btn" data-buscar="imagen">Por Imagen</button>
                    </div>
                </div>
            </div>
        </form>
        </td>
    </tr>
    <?php if ($mostrarResultadoJuego): ?>
        <tr>
            <td class="p-3 bg-insidetabs">
                Resultado de la busqueda
            </td>
        </tr>
        <tr> 
        <td class="p-3 text-center" colspan="2">
            <div id="carouselExampleIndicators" class="carousel slide carousel-small" data-bs-theme="dark">
                <div class="carousel-indicators">
                    <?php if (isset($imagenesJuego) && !empty($imagenesJuego)): ?>
                        <?php foreach ($imagenesJuego as $index => $imagen): ?>
                            <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="<?= $index ?>" <?= $index === 0 ? 'class="active"' : ''; ?> aria-current="<?= $index === 0 ? 'true' : ''; ?>" aria-label="Slide <?= $index + 1 ?>"></button>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
                    <?php endif; ?>
                </div>
                <div class="carousel-inner">
                    <?php if (isset($imagenesJuego) && !empty($imagenesJuego)): ?>
                        <?php foreach ($imagenesJuego as $index => $imagen): ?>
                            <div class="carousel-item <?= $index === 0 ? 'active' : ''; ?>">
                                <div class="ratio ratio-4x3">
                                    <img src="<?= escape($imagen['image_path']); ?>" 
                                        class="d-block w-100 object-fit-cover img-fluid border border-dark" 
                                        alt="Imagen del juego" 
                                        data-bs-toggle="modal" 
                                        data-bs-target="#fullImageModal" 
                                        data-fullimage="<?= escape($imagen['image_path']); ?>">
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="carousel-item active">
                            <div class="ratio ratio-4x3">
                                <img src="assets/imgs/placeholder450x350.svg" class="d-block w-100 object-fit-cover img-fluid border border-dark" alt="Imagen del juego">
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
                <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Previous</span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Next</span>
                </button>
            </div>
        </td>
    </tr>
    <tr>
        <td class="p-2" colspan="2">
            <div class="<?php echo (has_permission(7)) ? 'd-flex justify-content-between' : 'd-flex justify-content-center';?> align-items-center">
                <?php if (has_permission(7)): ?>
                    <button type="button" data-bs-toggle="modal" data-bs-target="#editarimagenes">
                        Editar
                    </button>
                <?php endif;?>
                <a href="#" data-bs-toggle="modal" data-bs-target="#aportespor">Aportes..</a>
            </div>
        </td>
    </tr>
    <tr> 
        <td class="pt-3" colspan="2">
            <h6 class="text-center"><?= escape($juegoDetalles['juego'] ?? '-'); ?></h6>
        </td>
    </tr>
    <tr> 
        <td class="p-3" colspan="2">
            <ul class="list-group list-group-flush">
                <li class="list-group-item">Año de lanzamiento: <?= escape($juegoDetalles['lanzamiento'] ?? ''); ?></li>
                <li class="list-group-item">Desarrollador: <?= escape($juegoDetalles['desarrollador'] ?? ''); ?></li>
                <li class="list-group-item">Franquicia: <?= escape($juegoDetalles['franquicia'] ?? ''); ?></li>
                <li class="list-group-item">Sistema: <?= escape($juegoDetalles['sistema'] ?? ''); ?></li>
                <li class="list-group-item">Región: <?= escape($juegoDetalles['region'] ?? ''); ?></li>
                <li class="list-group-item">También salió para: <?= escape($juegoDetalles['remakes'] ?? ''); ?></li>
            </ul>
        </td>
    </tr>
    <tr>
        <td class="p-2" colspan="2">
            <div class="<?php echo (has_permission(7)) ? 'd-flex justify-content-between' : 'd-flex justify-content-center';?> align-items-center">
                <?php if (has_permission(7)) : ?>
                    <button type="button" data-bs-toggle="modal" data-bs-target="#editar">
                        Editar
                    </button>
                <?php endif;?>
                <a href="#" data-bs-toggle="modal" data-bs-target="#editadopor">Aportes..</a>
            </div>
        </td>
    </tr>
    <?php endif; ?>
    <?php if ($mostrarResultadoImagen): ?>
        <tr>
            <td class="p-3 bg-insidetabs">
                Resultado de la busqueda
            </td>
        </tr>
        <tr>
            <td>
                <div class="container mt-3">
                <input class="p-2 mb-3 w-100" type="text" id="myInput" onkeyup="myFunction()" placeholder="Buscar juego..">
                    <div class="row" id="myDivs">
                        <?php foreach ($juegosConPortada as $juego): ?>
                            <div class="col-12 col-sm-6 col-md-4 col-lg-3 mb-3">
                                <div class="archivo-card p-3 border border-dark d-flex flex-column bg-insidetabs">
                                    <!-- Imagen del juego -->
                                    <img src="<?= escape($juego['portada'] ?? 'assets/imgs/placeholder100x100.svg') ?>" alt="Portada de <?= escape($juego['juego']) ?>"
                                        class="img-fluid border border-dark">

                                    <!-- Nombre del juego -->
                                    <div class="archivo-info text-center text-md-start flex-grow-1">
                                        <div class="gamename mt-2">
                                            <p>
                                                <a href="?dir=archivo&consola=<?= escape(strtolower($juego['sistema'])) ?>&juego=<?= escape(strtolower($juego['juego'])) ?>&buscar_por=juego">
                                                    <?= escape($juego['juego']) ?>
                                                </a>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </td>
        </tr>
        <script>
        function myFunction() {
            var input, filter, cards, cardContainer, title, i, txtValue;
            input = document.getElementById('myInput');
            filter = input.value.toUpperCase();
            cardContainer = document.getElementById("myDivs");
            cards = cardContainer.getElementsByClassName('col-12');

            for (i = 0; i < cards.length; i++) {
                title = cards[i].getElementsByClassName("gamename")[0];
                if (title) {
                    txtValue = title.textContent || title.innerText;
                    if (txtValue.toUpperCase().indexOf(filter) > -1) {
                        cards[i].style.display = "";
                    } else {
                        cards[i].style.display = "none";
                    }
                }
            }
        }
        </script>
    <?php endif; ?>
</table>

<?php if ($mostrarResultadoJuego): ?>
    <?php if (isset($imagenesJuego) && !empty($imagenesJuego)): ?>
    <!-- Modal imagen completa -->
    <div class="modal fade" id="fullImageModal" tabindex="-1" aria-labelledby="fullImageModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-fullscreen">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="fullImageModalLabel">Imagen Completa</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body d-flex align-items-center justify-content-center">
                    <img id="modalFullImage" src="<?= escape($imagen['image_path']); ?>" class="d-block w-100  h-100" alt="Imagen del juego" style="max-height: 100vh; object-fit: contain;">
                </div>
                <div class="modal-footer">
                    <button type="button" data-bs-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            document.querySelectorAll("#carouselExampleIndicators .carousel-item img").forEach(img => {
                img.addEventListener("click", function() {
                    const fullImageSrc = this.getAttribute("data-fullimage");
                    document.getElementById("modalFullImage").src = fullImageSrc;
                });
            });
        });
    </script>
    <?php endif ;?>

    <?php if (has_permission(7)): ?>
    <!-- Modal imagen subir -->
    <div class="modal fade" id="editarimagenes" tabindex="-1" aria-labelledby="Editar imagenes" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <span class="modal-title">Subir imágenes del Juego:</span>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="subir_imagen" action="" method="post" enctype="multipart/form-data">
                <input type="hidden" name="game_id" value="<?= isset($gameId) ? $gameId : ''; ?>">
                <input type="hidden" name="consola" value="<?= isset($consolas) ? $consolas : ''; ?>">
                <input type="hidden" name="juego" value="<?= isset($juegos) ? $juegos : ''; ?>">
                    <div class="modal-body">
                        <div id="simple-dropzone" class="dropzone"></div>
                        <div id="del_imagenes" >
                            <?php if (isset($imagenesJuego) && !empty($imagenesJuego)): ?>
                            <div class="d-flex justify-content-center align-items-center mt-3">
                                <ul>                            
                                    <?php foreach ($imagenesJuego as $index => $imagen): ?>
                                            <li>
                                                <input type="checkbox" name="selected_images[]" value="<?= $imagen['id']; ?>">
                                                <?= escape(basename($imagen['image_path'])); ?>
                                            </li>
                                    <?php endforeach; ?>                            
                                </ul>
                            </div>
                            <?php endif; ?>
                        </div>
                        <div class="d-flex justify-content-center align-items-center">
                            <div id="loading_spinner_img" class="spinner-border text-primary" role="status" style="display: none;">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer d-flex justify-content-end align-items-center">
                        <?php if (isset($imagenesJuego) && !empty($imagenesJuego)): ?>
                        <input id="del_button" type="submit" name="del_button" value="Supr">
                        <?php endif; ?>
                        <input id="subir_button" type="submit" name="subir_button" value="Guardar">
                    </div>                
                </form>
            </div>
        </div>
    </div>
    <script>
    document.addEventListener("DOMContentLoaded", function() {
        Dropzone.autoDiscover = false;
        const myDropzone = new Dropzone("#simple-dropzone", {
            url: "?dir=archivo", 
            paramName: "image",
            maxFilesize: 2,
            acceptedFiles: "image/*",
            addRemoveLinks: true,
            autoProcessQueue: false,
            uploadMultiple: true,
            parallelUploads: 5,
            maxFiles: 5,
            dictRemoveFile: "Borrar",
            dictCancelUpload: "Cancelar subida",
            dictInvalidFileType: "Error en el tipo de archivo",
            dictDefaultMessage: "Arrastra y suelta archivos aquí o haz clic para subirlos",
            cancelUpload: function() {
                return false;
            },
            init: function() {
                var submitButton = document.querySelector("#subir_button");
                var myDropzone = this;

                submitButton.addEventListener("click", function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    if (myDropzone.getQueuedFiles().length > 0) {
                        myDropzone.processQueue();
                    } else {
                        console.log("No hay imágenes para subir");
                        window.location.reload();
                    }
                });

                this.on("sendingmultiple", function(files, xhr, formData) {
                    console.log("Enviando archivos...");
                    formData.append("game_id", document.querySelector('input[name="game_id"]').value);
                    formData.append("consola", document.querySelector('input[name="consola"]').value);
                    formData.append("juego", document.querySelector('input[name="juego"]').value);
                    formData.append("subir_button", "1");
                    document.querySelector('#loading_spinner_img').style.display = 'block';
                });

                this.on("successmultiple", function(files, response) {
                    console.log("Archivos subidos con éxito");
                    document.querySelector('#loading_spinner_img').style.display = 'none';
                    $('#editarimagenes').modal('hide');
                    window.location.reload();
                });

                this.on("errormultiple", function(files, response) {
                    console.error("Error al subir archivos:", response);
                    document.querySelector('#loading_spinner_img').style.display = 'none';
                });
            }
        });
    });
    </script>
    <script>
        $(document).ready(function() {
            $('#subir_button, #del_button').on('click', function() {
                $('#simple-dropzone').hide();
                $('#del_imagenes').hide();
                $('#del_button').hide();
                $('#subir_button').hide();
                $('#loading_spinner_img').show();
            });
        });
    </script>
    <?php endif;?>

    <!-- Modal imagen aportes -->
    <div class="modal fade" id="aportespor" tabindex="-1" aria-labelledby="Aportes por" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <span class="modal-title">Aportes de imágenes:</span>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <?php if (isset($aportadoPorImagen) && !empty($aportadoPorImagen)): ?>
                    <div class="d-flex justify-content-center align-items-center mt-3">                          
                        <ul class="list-group list-group-flush">                            
                            <?php foreach ($aportadoPorImagen as $index => $aporte): ?>
                                <li class="list-group-item">
                                    <?= escape($aporte['aportado_por']) . ' ' . escape($aporte['created_at']); ?>
                                </li>
                            <?php endforeach; ?>                            
                        </ul>
                    </div>
                    <?php else: ?>
                        <div class="text-center mt-3">
                            No hay aportes registrados.
                        </div>
                    <?php endif; ?>
                </div>
                <div class="modal-footer d-flex justify-content-center align-items-center">
                </div>
            </div>
        </div>
    </div>

    <?php if (has_permission(7)): ?>
    <!-- Modal editar info -->
    <div class="modal fade" id="editar" tabindex="-1" aria-labelledby="editar" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <span class="modal-title">Editar información del Juego:</span>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="editar_juego" action="" method="post">
                <input type="hidden" name="game_id" value="<?= isset($gameId) ? $gameId : ''; ?>">
                <input type="hidden" name="consola" value="<?= isset($consolas) ? $consolas : ''; ?>">
                <input type="hidden" name="juego" value="<?= isset($juegos) ? $juegos : ''; ?>">
                    <div class="modal-body">
                        <ul class="list-group list-group-flush" id="form-list">
                            <li class="list-group-item">Año de lanzamiento: <input type="text" name="lanzamiento" value="<?= escape($juegoDetalles['lanzamiento'] ?? ''); ?>"></li>
                            <li class="list-group-item">Desarrollador: <input type="text" name="desarrollador" value="<?= escape($juegoDetalles['desarrollador'] ?? ''); ?>"></li>
                            <li class="list-group-item">Franquicia: <input type="text" name="franquicia" value="<?= escape($juegoDetalles['franquicia'] ?? ''); ?>"></li>
                            <li class="list-group-item">Sistema: <input type="text" name="sistema" value="<?= escape($juegoDetalles['sistema'] ?? ''); ?>" readonly></li>
                            <li class="list-group-item">Región: <input type="text" name="region" value="<?= escape($juegoDetalles['region'] ?? ''); ?>"></li>
                            <li class="list-group-item">También salió para: <input type="text" name="remakes" value="<?= escape($juegoDetalles['remakes'] ?? ''); ?>"></li>
                        </ul>
                        <div class="d-flex justify-content-center align-items-center">
                            <div id="loading_spinner" class="spinner-border text-primary" role="status" style="display: none;">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                        </div> 
                    </div>
                    <div class="modal-footer d-flex justify-content-end align-items-center">
                        <input id="submit_button" type="submit" name="submit" value="Guardar">
                    </div>                
                </form>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function() {
            $('#editar_juego').on('submit', function() {
                $('#form-list').hide();
                $('#submit_button').hide();
                $('#loading_spinner').show();
            });
        });
    </script>
    <?php endif; ?>

    <!-- Modal info editada por -->
    <div class="modal fade" id="editadopor" tabindex="-1" aria-labelledby="editadopor" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <span class="modal-title">Aportes de información por:</span>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <?php if (isset($aportadoPorDato) && !empty($aportadoPorDato)): ?>
                    <div class="d-flex justify-content-center align-items-center mt-3">
                        <ul class="list-group list-group-flush">                            
                            <?php foreach ($aportadoPorDato as $index => $aporte): ?>
                                <li class="list-group-item">
                                    <?= escape($aporte['aportado_por']) . ' ' . escape($aporte['created_at']); ?>
                                </li>
                            <?php endforeach; ?>                            
                        </ul>
                    </div>
                    <?php else: ?>
                        <div class="text-center mt-3">
                            No hay aportes registrados.
                        </div>
                    <?php endif; ?>
                </div>
                <div class="modal-footer d-flex justify-content-center align-items-center">
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>