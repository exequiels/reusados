<?php
require_once 'models/UserModel.php';

$userModel = new UserModel($pdo);

if (isset($_GET['token'])) {
    $token = $_GET['token'];

    $activationStatus = $userModel->activateUserByToken($token);

    if ($activationStatus) {
        $status = "¡Tu cuenta ha sido activada exitosamente!";
    } else {
        $status = "Token de activación inválido o la cuenta ya está activada.";
    }
} else {
    $status = "No se ha proporcionado token de activación.";
}
?>
<table class="table table-sm table-bordered border-estilo">
    <?php include_once "views/layout/encabezado_de_tabla.php"; ?>
    <tr> 
        <td class="p-3" colspan="2">
            <div class="form-group mt-3">
                <?= $status ?>
            </div>
        </td>
    </tr>
</table>