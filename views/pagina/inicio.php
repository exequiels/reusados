<?php
if (!isset($_SESSION['user_id'])) {
} else {
    $userId = $_SESSION['user_id'];

    $username = $userModel->getUsernameById($userId);
}
?>
<table class="table table-sm table-bordered border-estilo mb-1">
    <?//php include_once "views/layout/encabezado_de_tabla.php"; ?>
    <tr> 
        <td class="text-white" colspan="2">            
            <article>
                <div id="screen">
                    <img src="assets/imgs/scanlines.png" id="scanlines">
                    <img src="assets/imgs/monitor_screen.png" id="monitor">
                    <!-- <img src="assets/imgs/ruido.png" id="ruido"> -->
                    <div id="wakeup">
                        <?= isset($username) ? "Despierta, $username..." : "Despierta..."; ?>
                        <span class="cursor"></span>
                    </div>
            </article>
            </div>
        </td>
    </tr>
</table>