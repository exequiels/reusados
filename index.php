<?php
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);
require_once 'connectar.php';
?>
<!-- Head -->
<?php require_once "layout/head.php"; ?>
<!-- // Head -->
<body>
    <div class="custom">
        <!-- Header -->
        <?php require_once "layout/header.php"; ?>
        <!-- // Header -->

        <!-- Main Content -->
        <div class="container mt-2 mt-sm-4">

            <div class="row">
                
            <!-- Topbar -->
            <div class="col-lg-12 col-md-11 col-sm-11 col-10 mx-auto">
                <div class="row">
                    <?php require_once "layout/topbar.php"; ?>
                </div>                
            </div>
            <!-- // Topbar -->

                <!-- Menu -->
                <div class="col-lg-2 col-sm-11 col-10 mx-auto">
                    <div class="row">
                        <?php require_once "layout/menu.php"; ?>
                    </div>
                </div>
                <!-- // Menu -->

                <!-- Seccion central -->
                <div class="col-lg-9 col-md-11 col-sm-11 col-10 mx-auto central-item">
                    <div class="row">
                    <?php
                        $universo = isset($_GET['dir']) ? $_GET['dir'] : 'buscador';
$universo = filter_input(INPUT_GET, 'dir', FILTER_SANITIZE_SPECIAL_CHARS);
require_once "globales/paginas_permitidas.php";
$universo = in_array($universo, $allowed_pages) ? $universo : 'buscador';
$path = 'pagina/' . $universo . '.php';
if(file_exists($path)) {
    require_once $path;
} else {
    echo "Algo raro esta pasando..";
}
?>
                    </div>
                </div>
                <!-- // Seccion central -->

            </div>
        </div>
        <!-- // Main Content -->

        <!-- Footer -->
        <?php require_once "layout/footermenu.php"; ?>
        <!-- // Footer -->
        
    </div>
    <!-- JS -->
    <?php require_once "layout/javascript.php"; ?>
    <!-- // JS -->
</body>
</html>