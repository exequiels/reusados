<!-- Bootstrap bundle -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-HwwvtgBNo3bZJJLYd8oVXjrBZt8cqVSpeBNS5n7C8IVInixGAoxmnlMuBnhbgrkm" crossorigin="anonymous"></script>

<!-- Menu -->
    <!-- Esconder o mostrar -->
    <script src="assets/js/icono_esconder_menu_con_rotacion.js"></script>
<!-- // Menu -->

<!-- Seccion central -->
    <!-- Cerrar ventana -->
    <script src="assets/js/icono_cerrar_seccion_central.js"></script>
<!-- // Seccion central -->

<!-- Inicio -->
<?php if ($universo === 'inicio'): ?>
    <!-- Hora actual del ordenador -->
    <!--<script src="js/hora_ordernador.js"></script>-->
<?php endif; ?>
<!-- // Inicio -->

<!-- Registrarse -->
<?php if ($universo === 'registrarse'): ?>
    <script src="assets/js/validacion_registro.js"></script>
<?php endif; ?>
<!-- // Registrarse  -->

<!-- Cirujear -->
<?php if ($universo === 'cirujear'): ?>
    <script type="text/javascript">
        const baseUrl = "<?php echo htmlspecialchars($url_base, ENT_QUOTES, 'UTF-8'); ?>";
        const desvioUrl = "<?php echo htmlspecialchars($desvioUrl, ENT_QUOTES, 'UTF-8'); ?>";
    </script>
    <!-- Armar url -->
    <script src="assets/js/buscador_url.js"></script>
    <!-- Resetear filtros -->
    <script src="assets/js/buscador_resetear_filtros.js"></script>
<?php endif; ?>
<!-- // Cirujear -->

<!-- Tendencias -->
<?php if ($universo === 'tendencias'): ?>
    <script type="text/javascript">
        const baseUrl = "<?php echo htmlspecialchars($url_base, ENT_QUOTES, 'UTF-8'); ?>";
        const desvioUrl = "<?php echo htmlspecialchars($desvioUrl, ENT_QUOTES, 'UTF-8'); ?>";
    </script>
    <!-- Armar url -->
    <script src="assets/js/tendencias_url.js"></script>
    <!-- Resetear filtros -->
    <script src="assets/js/tendencias_resetear_filtros.js"></script>
<?php endif; ?>
<!-- // Tendencias -->