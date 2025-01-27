<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();
require_once 'config/class_loader.php';
require_once 'config/error_handler_setup.php';
require_once 'config/common_errors_handler.php';
require_once 'config/variables.php';
require_once 'config/escape_setup.php';
require_once 'config/connectar.php';

require_once 'utils/maintenance_functions.php';
require_once 'utils/permissions_functions.php';
require_once 'utils/denied_permissions_functions.php';

configureGlobalExceptionHandler($pdo);

$errorHandler = new ErrorHandler($pdo);
$userModel = new UserModel($pdo);
$permisosModel = new PermisosModel($pdo);
$configModel = new ConfigModel($pdo);
$userRole = !empty($_SESSION['user_id']) ? $userModel->getUserRole($_SESSION['user_id']) : 'guest';
$maintenanceMode = $configModel->getMaintenanceMode();

// check_auth();
check_maintenance();

// Rutas
$universo = isset($_GET['dir']) ? $_GET['dir'] : 'inicio';
$universo = filter_input(INPUT_GET, 'dir', FILTER_SANITIZE_SPECIAL_CHARS);
require_once "validaciones/paginas_permitidas.php";
$universo = in_array($universo, $allowed_pages) ? $universo : 'inicio';
$path = 'views/pagina/' . $universo . '.php';
ob_start();
if (file_exists($path)) {
    require_once $path;
} else {
    echo "Algo raro esta pasando..";
}
$seccionCentral = ob_get_clean();
?>
<!-- Head -->
<?php require_once "views/layout/head.php"; ?>
<!-- // Head -->
<body>
    <div class="custom">
        <!-- Header -->
        <?php require_once "views/layout/header.php"; ?>
        <!-- // Header -->
        <!-- Main Content -->
        <div class="container">
            <div class="row">
                <?php if (!is_maintenance_on($maintenanceMode)): ?>
                    <?php if (isset($_SESSION['user_id'])): ?>
                    <!-- Topbar -->
                    <div class="col-lg-12 col-md-11 col-sm-11 col-10 mx-auto">
                        <div class="row">
                            <?php require_once "views/layout/topbar.php"; ?>
                        </div>
                    </div>
                    <!-- // Topbar -->
                    <?php endif;?>
                <?php endif; ?>
                <!-- Seccion central -->
                <div class="col-lg-9 col-md-11 col-sm-11 col-10 mx-auto central-item">
                    <div class="row">
                        <?php echo $seccionCentral; ?>
                    </div>
                </div>
                <!-- // Seccion central -->
            </div>
        </div>
        <!-- // Main Content -->
        <!-- Footer -->
        <?php require_once "views/layout/footermenu.php"; ?>
        <!-- // Footer -->        
    </div>
    <!-- JS -->
    <?php require_once "views/layout/javascript.php"; ?>
    <!-- // JS -->
</body>
</html>