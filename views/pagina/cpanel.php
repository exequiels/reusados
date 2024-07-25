<?php
check_auth(['admin']);
require_once 'models/token_model.php';
$tokenModel = new TokenModel($pdo);
$tokenData = $tokenModel->getToken();
require_once 'utils/token_expire_functions.php';
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
    <tr class="p-3">
        <td class="p-3">
            Monitor de tokens
        </td>
    </tr>
    <tr class="p-3"> 
        <td class="p-3">
            <div class="border-1 mt-3">
                    <?php
                        if (!empty($tokenData)) {
                            echo '<p class="p-1 rounded ' . ($tokenExpired ? 'bg-warning-subtle' : 'bg-success-subtle') . '">Access Token: **********</p>';
                            echo '<p class="p-1">Token Expirado: ' . ($tokenExpired ? 'Si' : 'No') . '</p>';
                            echo '<p class="p-1 rounded ' . ($refreshTokenExpired ? 'bg-danger-subtle' : 'bg-success-subtle') . '">Refresh Token: **********</p>';
                            echo '<p class="p-1">Refresh Token Expirado: ' . ($refreshTokenExpired ? 'Si' : 'No') . '</p>';
                            echo '<p class="p-1">Creado: ' . $tokenData[0]['created_at'] . '</p>';
                            echo '<p class="p-1">Updateado: ' . $tokenData[0]['updated_at'] . '</p>';
                        } else {
                            echo 'No token data found.';
                        }
?>
            </div>
        </td>
    </tr>
    <tr class="p-3">
        <td class="p-3">
            Maintenance mode
        </td>
    </tr>
    <tr class="p-3"> 
        <td class="p-3">
            <form method="post" action="">
                <div class="d-flex justify-content-between align-items-center mt-3">
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
</table>