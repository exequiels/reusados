<!-- Bootstrap bundle -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-HwwvtgBNo3bZJJLYd8oVXjrBZt8cqVSpeBNS5n7C8IVInixGAoxmnlMuBnhbgrkm" crossorigin="anonymous"></script>

<!-- Menu -->
    <!-- Esconder o mostrar -->
    <script src="assets/js/icono_esconder_menu_con_rotacion.js"></script>
<!-- // Menu -->

<!-- Seccion central -->
<?php if ($universo !== 'inicio'): ?>
    <!-- Cerrar ventana -->
    <script src="assets/js/icono_cerrar_seccion_central.js"></script>
    <!-- Armar url -->
    <script src="assets/js/buscador_url.js"></script>
    <!-- Resetear filtros -->
    <script src="assets/js/buscador_resetear_filtros.js"></script>
    <!-- Armar select subcategorias -->
    <script src="assets/js/buscador_select_subcategoria.js"></script>
<?php endif; ?>
<!-- // Seccion central -->

<!-- Registrarse -->
<?php if ($universo === 'registrarse'): ?>
    <script src="assets/js/validacion_registro.js"></script>
<?php endif; ?>
<!-- // Registrarse  -->

<!-- Archivo -->
<?php if ($universo === 'archivo'): ?>
    <script type="text/javascript">
        const baseUrl = "<?php echo escape($url_base, ENT_QUOTES, 'UTF-8'); ?>";
        const desvioUrl = "<?php echo escape($desvioUrl, ENT_QUOTES, 'UTF-8'); ?>";
    </script>
    <!-- Armar url -->
    <script src="assets/js/archivo_url.js"></script>
    <!-- Armar select juegos -->
    <script src="assets/js/archivo_select_juegos.js"></script>
    <?php if (isset($_SESSION['user_id'])):?>
        <script src="https://unpkg.com/dropzone@5/dist/min/dropzone.min.js"></script>
    <?php endif;?>
<?php endif; ?>
<!-- // Archivo -->