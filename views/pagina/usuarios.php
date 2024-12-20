<?php
denied_permissions_functions(4);

require_once 'utils/user_ranks.php';
require_once 'utils/date_time_functions.php';
$allUsers = $userModel->getAllUsers();

usort($allUsers, function ($a, $b) {
    return strcasecmp($a['username'], $b['username']);
});
?>
<table class="table table-sm table-bordered border-estilo table-striped">
    <thead>
        <?php include_once "views/layout/encabezado_de_tabla.php"; ?>
    </thead>
    <tbody>
        <tr>
            <td>
                <div class="container">
                    <input class="p-2 mt-2 mb-2 w-100" type="text" id="myInput" onkeyup="myFunction()" placeholder="Buscar usuario..">
                    <div class="row" id="myDivs">
                        <?php if (!empty($allUsers)) : ?>
                            <?php foreach ($allUsers as $user) : ?>
                                <div class="col-12 mb-3">
                                    <div class="user-card p-3 border border-dark d-flex flex-column flex-md-row align-items-center">
                                        <!-- Imagen del usuario -->
                                        <img src="assets/imgs/perfil/default.webp" alt="Foto de <?= escape($user['username']); ?>" 
                                            class="img-fluid mb-3 mb-md-0 ms-md-3 order-md-last border border-dark"
                                            style="width: 70px; height: 70px; object-fit: cover;">

                                        <!-- Información del usuario -->
                                        <div class="user-info text-center text-md-start flex-grow-1">
                                            <div class="username mb-2">
                                                <h6><?= escape($user['username']); ?></h6>
                                            </div>
                                            <div class="details">
                                                <p class="mb-1">Última conexión: <?= escape($user['last_login'] ?? 'N/A') ;?></p>
                                                <!-- <p class="mb-1">Colección: <a href="#">Ver</a></p>
                                                <p class="mb-1">Manta: <a href="#">Mercadería</a></p>
                                                <p class="mb-1">Trades: <a href="#">Busca</a>/<a href="#">Ofrece</a></p>
                                                <p class="mb-0">Voucheado: * veces</p> -->
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php else : ?>
                            <div class="col-12">
                                <div class="alert alert-warning" role="alert">
                                    No hay usuarios..
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </td>
        </tr>
    </tbody>
</table>
<script>
function myFunction() {
    var input, filter, cards, cardContainer, title, i, txtValue;
    input = document.getElementById('myInput');
    filter = input.value.toUpperCase();
    cardContainer = document.getElementById("myDivs");
    cards = cardContainer.getElementsByClassName('col-12');

    for (i = 0; i < cards.length; i++) {
        title = cards[i].getElementsByClassName("username")[0];
        if (title) {
            txtValue = title.textContent || title.innerText;
            if (txtValue.toUpperCase().indexOf(filter) > -1) {
                cards[i].style.display = "";
            } else {
                cards[i].style.display = "none";
            }
        }
    }
}
</script>