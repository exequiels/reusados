<tr>
    <th class="py-3" colspan="7">
        <div class="accordion accordion-flush" id="accordionFlushExample">
            <div class="accordion-item">
                <div class="d-flex justify-content-between align-items-center p-2">
                    <div>
                        <span><h6>
                            <?php
                                $dir = isset($_GET['dir']) ? $_GET['dir'] : '';
                            require_once "validaciones/paginas_permitidas.php";
                            if ($dir !== '' && in_array($dir, $allowed_pages)) {
                                switch ($dir) {
                                    case 'politicas':
                                        echo 'Políticas de Privacidad';
                                        break;
                                    case 'tablon':
                                        echo 'Tablón';
                                        break;
                                    default:
                                        $dir = str_replace('-', ' ', $dir);
                                        echo ucfirst($dir);
                                        break;
                                }
                            } else {
                                echo 'Inicio';
                            }
                            ?>
                        </h6></span>
                    </div>
                    <button class="p-2 collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseOne" aria-expanded="false" aria-controls="flush-collapseOne">
                        <i class="bi bi-card-list" style="font-size: 1.5rem;"></i>
                    </button>
                </div>
                <div id="flush-collapseOne" class="accordion-collapse collapse border border-dark mt-3" data-bs-parent="#accordionFlushExample">
                    <div class="accordion-body" id="screen">
                            <img src="assets/imgs/scanlines.png" id="scanlines">
                            <img src="assets/imgs/monitor_screen.png" id="monitor">
                            <div id="wakeup">
                                <p>Menu ...<span class="cursor"></span></p>
                                <p>- <a href="?dir=archivo" class="text-decoration-none">Archivo</a></p>
                                <p>- <a href="<?php echo $url_base; ?>" class="text-decoration-none">Inicio</a></p>
                                <p>- <a href="?dir=novedades" class="text-decoration-none">Novedades</a></p>
                            </div>
                    </div>
                </div>
            </div>
        </div>
    </th>
</tr>