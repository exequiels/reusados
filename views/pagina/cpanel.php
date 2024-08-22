<?php
/** @var ConfigModel $configModel */
check_auth(['admin']);

$tokenModel = new TokenModel($pdo);
$tokenData = $tokenModel->getToken();
require_once 'utils/token_expire_functions.php';

$errorLogModel = new ErrorLogModel($pdo);
$errorsPerPage = 10;
$totalErrors = $errorLogModel->countErrors();
$totalPages = ceil($totalErrors / $errorsPerPage);

$errors = $errorLogModel->getAllErrors();

require_once './models/VideGameModel.php';
$videoGameModel = new VideoGameModel($pdo);
$ultimaInsercion = $videoGameModel->getLastInsertion();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['maintenance'])) {
    $newMode = $_POST['maintenance'];
    if ($newMode === '1' || $newMode === '0') {
        $configModel->setMaintenanceMode($newMode);
        echo $newMode === '1' ? "Activado" : "Desactivado";
        header("Location: ?dir=cpanel");
        exit();
    } else {
        echo "Valor de mantenimiento no válido.";
    }
}
?>
<table class="table table-sm table-bordered border-estilo">
    <?php include_once "views/layout/encabezado_de_tabla.php"; ?>
    <tr>
        <td class="p-3 bg-insidetabs">
            Salud de tokens
        </td>
    </tr>
    <tr> 
        <td class="pt-3 px-3">
            <div class="border-1">
                    <?php
                        if (!empty($tokenData)) {
                            echo '<p class="p-1 rounded ' . ($tokenExpired ? 'bg-warning-subtle' : 'bg-success-subtle') . '">Access Token: **********</p>';
                            echo '<p class="p-1">Token Expirado: ' . escape($tokenExpired ? 'Si' : 'No') . '</p>';
                            echo '<p class="p-1 rounded ' . ($refreshTokenExpired ? 'bg-danger-subtle' : 'bg-success-subtle') . '">Refresh Token: **********</p>';
                            echo '<p class="p-1">Refresh Token Expirado: ' . escape($refreshTokenExpired ? 'Si' : 'No') . '</p>';
                            echo '<p class="p-1">Creado: ' . escape($tokenData[0]['created_at']) . '</p>';
                            echo '<p class="px-1">Updateado: ' . escape($tokenData[0]['updated_at']) . '</p>';
                        } else {
                            echo escape('No token data found.');
                        }
?>
            </div>
        </td>
    </tr>
    <tr>
        <td class="p-3 bg-insidetabs">
            Maintenance mode
        </td>
    </tr>
    <tr> 
        <td class="p-3">
            <form method="post" action="">
                <div class="d-flex justify-content-between align-items-center">
                    <div>Activar el mantenimiento del sitio:</div>
                    <button type="submit" name="maintenance" value="1" <?php echo(is_maintenance_on($maintenanceMode) ? 'disabled' : ''); ?>>On</button>
                </div>
                <div class="d-flex justify-content-between align-items-center mt-3">
                    <div>Desactivar el mantenimiento del sitio:</div>
                    <button type="submit" name="maintenance" value="0" <?php echo(is_maintenance_on($maintenanceMode) ? '' : 'disabled'); ?>>Off</button>
                </div>
            </form>
        </td>
    </tr>
    <tr>
        <td class="p-3 bg-insidetabs">
            Error Logs
        </td>
    </tr>
    <?php
        $count = 0;
foreach ($errors as $error):
    if ($count > 4) {
        break;
    }
    $count++
    ?>
        <tr>
            <td class="p-3 text-wrap text-break">
                <?php
                echo '<span class="badge rounded-pill text-bg-danger bg-opacity-75 p-2">' . escape($error['id']) . '</span> ' .
                    '<span class="text-bg-info bg-opacity-10 p-1">' . escape($error['message']) . '</span> ' .
                    escape($error['code']) . ' ' .
                    escape($error['url']) . ' ' .
                    escape($error['file']) . ' ' .
                    '<span class="badge text-bg-warning bg-opacity-50 p-1">Line: ' . escape($error['line']) . '</span> ' .
                    escape($error['created_at']);
    ?>
            </td>
        </tr>
    <?php endforeach; ?>
    <tr>
        <td class="p-3">
            <div class="d-flex justify-content-between align-items-center">
                <div>Ver el log completo de errores:</div>
                <button type="button" data-bs-toggle="modal" data-bs-target="#fullLog">
                    Full log
                </button>
            </div>                        
        </td>
    </tr>
    <tr>
        <td class="p-3 bg-insidetabs">
            Cron Jobs
        </td>
    </tr>
    <tr> 
        <td class="p-3">
            <form action="config/cron_usados.php" method="post">
                <div class="d-flex justify-content-between align-items-center">
                    <div>"cron_usados.php"</div>
                    <button type="submit">Ejecutar Script</button>
                </div>
            </form>
            <div class="d-flex justify-content-between align-items-center mt-3">
                <div>Ultima ejecución automática:</div>
                <div><?php echo escape($ultimaInsercion); ?></div>
            </div>
        </td>
    </tr>
</table>

<!-- El modal -->
<div class="modal fade" id="fullLog" tabindex="-1" aria-labelledby="fullLog" aria-hidden="true">
  <div class="modal-dialog modal-xl modal-dialog-scrollable">
    <div class="modal-content">
        <div class="modal-header">
            <span class="modal-title">Error Logs</span>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <table class="table table-sm table-bordered border-estilo">
                <?php foreach ($errors as $error): ?>
                <tr>
                    <td class="p-3 text-wrap text-break">
                        <?php
                        echo '<span class="badge rounded-pill text-bg-danger bg-opacity-75 p-2">' . escape($error['id']) . '</span> ' .
                            '<span class="text-bg-info bg-opacity-10 p-1">' . escape($error['message']) . '</span> ' .
                            escape($error['code']) . ' ' .
                            escape($error['url']) . ' ' .
                            escape($error['file']) . ' ' .
                            '<span class="badge text-bg-warning bg-opacity-50 p-1">Line: ' . escape($error['line']) . '</span> ' .
                            escape($error['created_at']);
                    ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </table>
        </div>
        <div class="modal-footer d-flex justify-content-center align-items-center">
            { Nota - a futuro implementar checkboxs e icono para borrar errores }
        </div>
    </div>
  </div>
</div>