<?php
denied_permissions_functions(5);

require_once 'utils/user_ranks.php';
require_once 'utils/date_time_functions.php';
$perfil = $userModel->getUserDetailsById($_SESSION['user_id']);
?>
<table class="table table-sm table-bordered border-estilo">
    <?php include_once "views/layout/encabezado_de_tabla.php"; ?>
    <tr> 
        <td class="p-4 text-center" colspan="2">
            <p><img src="assets/imgs/perfil/default.webp" id="abrirAvatarModal" class="border border-dark" width="100px" height="100px" alt="Avatar" style="cursor: pointer; width: 100px; height: auto;"></p>
            <p>Nickname: <?= escape($perfil['username']); ?></p>
            <p>Nombre: <?= escape($perfil['fullname']); ?></p>
            <p>Registro: <?= escape($perfil['created_at']); ?></p>
            <p>Rango: <?= escape(nivelDeUsuario($perfil['rol'])); ?></p>
        </td>
    </tr>
</table>

<!-- Modal editar avatar -->
<div class="modal fade" id="editarAvatarModal" tabindex="-1" aria-labelledby="editarAvatarModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <span class="modal-title mb-3">Selecciona tu Avatar</span>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body bg-alterno">
            <span class="mb-3">Héroes Tier 1</span>                
                <div class="container">
                    <div class="row">
                        <div class="col-12 mb-3 mt-3">
                            <div class="user-card p-3 border border-dark d-flex flex-column flex-md-row align-items-center">
                                <!-- Imagen del Avatar -->
                                <img src="assets/imgs/perfil/enano_ciudad.png" alt="Avatar" 
                                    class="img-fluid mb-3 mb-md-0 ms-md-3 order-md-last border border-dark" 
                                    style="width: 100px; height: 100px; object-fit: cover;">
                                <!-- Datos del Avatar -->
                                <div class="user-info text-center text-md-start flex-grow-1">
                                    <div class="username mb-2">
                                        <span>Enano de Ciudad</span><br>
                                    </div>
                                    <div class="details">
                                        <p class="mb-1">Descripción: Resistente y astuto, ideal para gestionar múltiples artículos.</p><br>
                                        <!-- <p class="mb-1">Habilidad: +1 galeria de imágenes adicional en la manta.</p><br> -->
                                        <p class="mb-0">Elegir: </span><input class="me-2" type="radio" name="avatarOption" id="avatarOption2"></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>                
            </div>
            <div class="modal-footer">
                <button type="button" data-bs-dismiss="modal">Guardar</button>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function() {
        $('#abrirAvatarModal').click(function() {
            $('#editarAvatarModal').modal('show');
        });
    });
</script>
