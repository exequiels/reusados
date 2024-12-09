<?php
/** @var ConfigModel $configModel */
denied_permissions_functions(6);

require_once 'utils/date_time_functions.php';

$tokenModel = new TokenModel($pdo);
$tokenData = $tokenModel->getToken();
require_once 'utils/token_expire_functions.php';

$errorLogModel = new ErrorLogModel($pdo);
$errorsPerPage = 10;
$totalErrors = $errorLogModel->countErrors();
$totalPages = ceil($totalErrors / $errorsPerPage);

$errors = $errorLogModel->getAllErrors();

require_once './models/VideGameModel.php';
require_once './models/PermisosModel.php';
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

// Cron Jobs
$cronLogModel = new CronLogModel($pdo);
$lastRunAccesorios = $cronLogModel->getLastRunByJobName('setAccesorios') ?? 'nunca';
$lastRunConsolas = $cronLogModel->getLastRunByJobName('setConsolas') ?? 'nunca';
$lastRunDelDia = $cronLogModel->getLastRunByJobName('setHoy') ?? 'nunca';
$lastRunJuegos = $cronLogModel->getLastRunByJobName('setJuegos') ?? 'nunca';
$lastRunRepuestos = $cronLogModel->getLastRunByJobName('setRepuestos') ?? 'nunca';
$lastRunVarios = $cronLogModel->getLastRunByJobName('setVarios') ?? 'nunca';

$cronJobs = [
    ['name' => 'Accesorios', 'action' => 'config/cron_accesorios.php', 'lastRun' => $lastRunAccesorios],
    ['name' => 'Consolas', 'action' => 'config/cron_consolas.php', 'lastRun' => $lastRunConsolas],
    ['name' => 'Del día', 'action' => 'config/cron_del_dia.php', 'lastRun' => $lastRunDelDia],
    ['name' => 'Juegos', 'action' => 'config/cron_juegos.php', 'lastRun' => $lastRunJuegos],
    ['name' => 'Repuestos', 'action' => 'config/cron_repuestos.php', 'lastRun' => $lastRunRepuestos],
    ['name' => 'Varios', 'action' => 'config/cron_varios.php', 'lastRun' => $lastRunVarios],
];

// Control Access
$permisosModel = new PermisosModel($pdo);
$roles = $permisosModel->getRoles();
$permisos = $permisosModel->getPermisos();
$permisosAsignados = $permisosModel->getPermisosPorRol();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['permisos'])) {

    foreach ($_POST['permisos'] as $rolId => $permisos) {
        $permisosSeleccionados = array_keys($permisos);
        $permisosModel->guardarPermisos($rolId, $permisosSeleccionados);
    }

    header("Location: ?dir=cpanel");
    exit();
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
                            echo '<p class="p-1">Creado: ' . escape(convertToLocalTime($tokenData[0]['created_at'])) . '</p>';
                            echo '<p class="px-1">Updateado: ' . escape(convertToLocalTime($tokenData[0]['updated_at'])) . '</p>';
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
                    escape(convertToLocalTime($error['created_at']));
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
            <div class="accordion accordion-flush" id="accordionFlushExample">
                <div class="accordion-item">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>Ver el log de cron jobs:</div>
                        <button class="p-2 collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseOne" aria-expanded="false" aria-controls="flush-collapseOne">
                            <i class="bi bi-card-list" style="font-size: 1.5rem;"></i>
                        </button>
                    </div>
                    <div id="flush-collapseOne" class="accordion-collapse collapse border border-dark mt-3" data-bs-parent="#accordionFlushExample">
                        <div class="accordion-body">
                            <?php foreach ($cronJobs as $job): ?>
                                <form action="<?= $job['action'] ?>" method="post">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div><?= $job['name'] ?></div>
                                        <button type="submit">Ejecutar</button>
                                    </div>
                                </form>
                                <div class="d-flex justify-content-between align-items-center mt-3">
                                    <div>Última ejecución:</div>
                                    <div><?= $job['lastRun'] ?></div>
                                </div>
                                <hr />
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>
        </td>
    </tr>
    <tr>
        <td class="p-3 bg-insidetabs">
            Control Access
        </td>
    </tr>
    <tr>
        <td class="p-3">
            <form method="POST" action="">
            <?php foreach ($roles as $rol): ?>
            <div class="container-fluid mt-3">
                <div class="row">
                    <div class="col-12 mb-3">
                        <div class="p-3 border border-dark bg-insidetabs">
                            <div class="d-flex flex-column flex-sm-row justify-content-between align-items-center">
                                <span class="text-start mb-2 mb-sm-0"><?= escape($rol['rol'] ?? 'No definido') ?></span>
                                <div class="d-flex flex-wrap justify-content-center justify-content-sm-end">
                                    <?php foreach ($permisos as $perm): ?>
                                        <div class="text-center me-2 mb-2">
                                            <i class="bi <?= escape($perm['icon'] ?? '') ?>" 
                                            style="font-size: 1.5rem; color: <?= escape($perm['color'] ?? '#000') ?>;" 
                                            title="<?= escape($perm['permiso'] ?? 'Sin permiso') ?>">
                                            </i>
                                            <div>
                                                <?php
                                                $permisoAsignado = false;

                                        if (!empty($permisosAsignados[$rol['id']])) {
                                            $permisoAsignado = in_array($perm['id'], $permisosAsignados[$rol['id']]);
                                        }
                                        ?>
                                                <input type="checkbox" 
                                                    name="permisos[<?= $rol['id'] ?>][<?= $perm['id'] ?>]" 
                                                    <?= $permisoAsignado ? 'checked' : '' ?>/>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
            <div class="modal-footer d-flex justify-content-end align-items-center">
                <input id="guardar_control" type="submit" name="submit" value="Guardar">
            </div>
        </td>
        </form>
    </tr>
</table>

<!-- El modal Error Logs -->
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