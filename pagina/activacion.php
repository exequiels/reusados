<?php
if (isset($_GET['token'])) {
    $token = $_GET['token'];

    $query = "SELECT * FROM usuarios WHERE token_activacion = ? AND status = 0";
    $stmt = $pdo->prepare($query);
    $stmt->execute([$token]);
    $usuario = $stmt->fetch();

    if ($usuario) {

        $update_query = "UPDATE usuarios SET status = 1, token_activacion = NULL WHERE token_activacion = ?";
        $update_stmt = $pdo->prepare($update_query);
        $update_stmt->execute([$token]);

        $status = "Tu cuenta ha sido activada exitosamente!";
    } else {
        $status = "Token de activación inválido o la cuenta ya está activada.";
    }
} else {
    $status = "No se ha proporcionado token de activación.";
}
?>
<table class="table table-sm table-bordered border-estilo">
    <?php include_once "globales/encabezado_de_tabla.php"; ?>
    <tr class="p-3"> 
        <td class="p-3" colspan="2">
            <div class="form-group mt-3">
                <?= $status ?>
            </div>
        </td>
    </tr>
</table>