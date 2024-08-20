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
                        <!--<p class="custom-h7">C:\>dir<br>
                        El volumen de la unidad C no tiene etiqueta.<br>
                        El número de serie del volumen es: 1234-5678<br></p>
                        <p class="custom-h7">Directorio de C:\</p>
                        <p  class="custom-h7" id="dirEntries">
                            <span id="entry1"></span> <br>
                            <span id="entry2"></span> <br>
                            12/11/2023    09:45 AM                 25 bienvenida.txt<br>
                            1 archivos             25 bytes<br>
                            2 dirs   1,024,000,000,000 bytes libres
                        </p>-->
                        <!-- <p class="custom-h7">C:\>type bienvenida.txt</p>
                        <hr />
                        <h6 class="text-white">
                            <p>Bienvenidos a reUsados, tu destino en línea para descubrir tendencias y buscar artículos usados. Web aun en construcción.</p>
                        </h6> -->
                        <div id="wakeup">
                            <?= isset($username) ? "Despierta, $username..." : "Despierta..."; ?>
                            <span class="cursor"></span>
                        </div>
            </article>
            </div>
        </td>
    </tr>
    <!--<tr>
        <td class="py-3 px-3" colspan="3"><h6 class="d-flex justify-content-between align-items-center">
            <span>Noticias</span>
        </td>
    </tr>
    <tr class="p-3" id="Noticias">
        <?//php include_once "noticias.php"; ?>
    </tr>-->
</table>