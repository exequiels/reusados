<form id="tendencias-form" action="?dir=rankings" method="get">
    <div class="form-group mt-3">
        Pais:   <select name="pais" class="filtros" required>
            <option value=""> -- Elige Pais -- </option>
            <option value="argentina" <?php echo (isset($_GET['pais']) && $_GET['pais'] === 'argentina') ? 'selected' : ''; ?> selected>Argentina</option>
        </select>
    </div>
    <div class="form-group mt-3">
        Categoría: <select name="categoria" id="categoria" class="filtros" required>
        <option value=""> -- Seleccionar -- </option>
            <option value="filatelia" <?php echo (isset($_GET['categoria']) && $_GET['categoria'] === 'filatelia') ? 'selected' : ''; ?>>Filatelia</option>
            <option value="juguetes" <?php echo (isset($_GET['categoria']) && $_GET['categoria'] === 'juguetes') ? 'selected' : ''; ?>>Juguetes</option>
            <option value="monedas" <?php echo (isset($_GET['categoria']) && $_GET['categoria'] === 'monedas') ? 'selected' : ''; ?>>Monedas y billetes</option>
            <option value="musica" <?php echo (isset($_GET['categoria']) && $_GET['categoria'] === 'musica') ? 'selected' : ''; ?>>Música</option>
            <option value="videojuegos" <?php echo (isset($_GET['categoria']) && $_GET['categoria'] === 'videojuegos') ? 'selected' : ''; ?>>Video juegos</option>
        </select>
    </div>
    <div class="d-flex justify-content-end mt-3">
        <input type="submit" value="Visualizar">
    </div>
</form>