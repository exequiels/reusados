<tr class="p-3">
    <th class="py-3 px-3" colspan="3"><h6 class="d-flex justify-content-between align-items-center">
        <span>
            <?php
                $dir = isset($_GET['dir']) ? $_GET['dir'] : '';
            require_once "paginas_permitidas.php";
            if ($dir !== '' && in_array($dir, $allowed_pages)) {
                $dir = str_replace('-', ' ', $dir);
                echo ucfirst($dir);
            } else {
                echo 'Buscador';
            }
            ?>
        </span>
        <button id="seccionCentral" type="button" title="Cerrar" class="btn border-0">
            <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" fill="currentColor" class="bi bi-x-square-fill text-secondary" viewBox="0 0 16 16" id="seccionCentral">
                <path d="M2 0a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2H2zm3.354 4.646L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 1 1 .708-.708z"/>
            </svg>
        </button>
    </th>
</tr>