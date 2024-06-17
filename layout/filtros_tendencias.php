<form id="tendencias-form" action="?dir=tendencias" method="get">
    <div class="d-flex flex-wrap">
        <div class="p-2 flex-item">
            Pais:   <select name="pais" class="filtros" required>
                <option value=""> -- Elige Pais -- </option>
                <option value="argentina" <?php echo (isset($_GET['pais']) && $_GET['pais'] === 'argentina') ? 'selected' : ''; ?> selected>Argentina</option>
            </select>
        </div>
        <div class="p-2 flex-item">
            Categoría: <select name="categoria" id="categoria" class="filtros" required>
            <option value=""> -- Seleccionar -- </option>
                <option value="filatelia" <?php echo (isset($_GET['categoria']) && $_GET['categoria'] === 'filatelia') ? 'selected' : ''; ?>>Filatelia</option>
                <option value="juguetes" <?php echo (isset($_GET['categoria']) && $_GET['categoria'] === 'juguetes') ? 'selected' : ''; ?>>Juguetes</option>
                <option value="monedas" <?php echo (isset($_GET['categoria']) && $_GET['categoria'] === 'monedas') ? 'selected' : ''; ?>>Monedas y billetes</option>
                <option value="musica" <?php echo (isset($_GET['categoria']) && $_GET['categoria'] === 'musica') ? 'selected' : ''; ?>>Música</option>
                <option value="videojuegos" <?php echo (isset($_GET['categoria']) && $_GET['categoria'] === 'videojuegos') ? 'selected' : ''; ?>>Video juegos</option>
            </select>
        </div>
        <div class="p-2 flex-item d-flex justify-content-end">
            <input type="submit" value="Visualizar" class="mx-2">
        </div>
    </div>
</form>