<form id="search-form" action="?dir=bazar" method="get">
    <div class="form-group mt-3">
        Pais:   <select name="pais" id="pais" class="filtros" required>
            <option value=""> -- Elige Pais -- </option>
            <option value="argentina" <?php echo (isset($_GET['pais']) && $_GET['pais'] === 'argentina') ? 'selected' : ''; ?> selected>Argentina</option>
        </select>
    </div>
    <div class="form-group mt-3">
        Categoría: <select name="categoria" id="categoria" class="filtros" required>
            <option value=""> -- Seleccionar -- </option>
            <option value="videojuegos" <?php echo (isset($_GET['categoria']) && $_GET['categoria'] === 'videojuegos') ? 'selected' : ''; ?> selected>Video juegos</option>
        </select>
    </div>
    <?php if ($onGoingSearch === true) { ?>
        <?php
        // Datos obtenidos de los selects
        $pais = isset($_GET['pais']) ? $_GET['pais'] : '';
        $categoria = isset($_GET['categoria']) ? $_GET['categoria'] : '';

        $pais = convertirPais($pais);
        $categoria = convertirCategoria($categoria);
        $subcategorias  = [];

        if ($pais && $categoria) {
            $subcategorias = $videoGameModel->getSubcategorias($pais, $categoria);
        }
        ?>
    <div class="form-group mt-3">
        Artículo: <input type="text" name="articulo" class="filtros" value="<?php echo isset($_GET['articulo']) ? $_GET['articulo'] : ''; ?>"  placeholder="...">
    </div>
    <div class="form-group mt-3">        
        Ordenar: <select name="orden" class="filtros">
            <option value=""> Sin orden </option>
            <option value="precio_asc" <?php echo (isset($_GET['orden']) && $_GET['orden'] === 'precio_asc') ? 'selected' : ''; ?>>Precio - a +</option>
            <option value="precio_desc" <?php echo (isset($_GET['orden']) && $_GET['orden'] === 'precio_desc') ? 'selected' : ''; ?>>Precio + a -</option>
            <option value="alfabetico" <?php echo (isset($_GET['orden']) && $_GET['orden'] === 'alfabetico') ? 'selected' : ''; ?>>Título</option>
        </select>
    </div>
    <div class="form-group mt-3">
    <label for="precio_min" class="mr-2">Rango de precios:</label>
        <input type="number" id="precio_min" name="precio_min" min="0" step="any" class="filtros" value="<?php echo isset($_GET['precio_min']) ? $_GET['precio_min'] : ''; ?>" placeholder="$$$">
        <input type="number" id="precio_max" name="precio_max" min="0" step="any" class="filtros" value="<?php echo isset($_GET['precio_max']) ? $_GET['precio_max'] : ''; ?>" placeholder="$$$">
    </div>
    <div class="form-group mt-3">
        Subcategorías: <select name="subcategoria" id="subcategoriaSelect" class="filtros">
        <option value=""> Todas</option>
            <?php foreach($subcategorias  as $subcategoria): ?>
                <option value="<?php echo(strtolower($subcategoria)); ?>" <?php echo (isset($_GET['subcategoria']) && $_GET['subcategoria'] === (strtolower($subcategoria))) ? 'selected' : ''; ?>><?php echo $subcategoria; ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <!-- <div class="form-group mt-3">
        Publicaciones: <select name="publicaciones" class="filtros">
            <option value="">Todas</option>
            <option value="destacados" <?//php echo (isset($_GET['publicaciones']) && $_GET['publicaciones'] === 'destacados') ? 'selected' : ''; ?>>+ Recientes</option>
            <option value="oportunidad" <?//php echo (isset($_GET['publicaciones']) && $_GET['publicaciones'] === 'oportunidad') ? 'selected' : ''; ?>>- Recienctes</option>
        </select>
    </div> -->
    <div class="d-flex justify-content-end mt-3">
        <input type="submit" value="Resetear" id="resetearbtn">
    </div>
    <?php } ?>
    <div class="d-flex justify-content-end mt-3">
        <input type="submit" value="Buscar">
    </div>
</form>