<?php
$pagina_logout = $url_base . "?dir=logout";
$tipo_de_logout = isset($_GET['out']) ? $_GET['out'] : 'default';

if (isset($_GET['dir']) && $_GET['dir'] === 'logout') {
    if ($tipo_de_logout === 'auto') {
        session_destroy();
        header("Location:" . $pagina_logout);
        exit();
    }
}
?>
<table class="table table-sm table-bordered border-estilo">
    <?php include_once "views/layout/encabezado_de_tabla.php"; ?>
    <tr> 
        <td class="p-3" colspan="2">
            <div class="form-group mt-3">
                <div class="col-md-12">
                    <?php if (empty($_SESSION['user_id'])) { ?>
                        <?php if ($tipo_de_logout == 'auto') { ?>
                            Estas deslogueado automáticamente.
                        <?php } else { ?>
                            Estas deslogueado..
                        <?php } ?>
                    <?php } ?>
                </div>
            </div>
        </td>
    </tr>
</table>