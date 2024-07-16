<form id="search-form" action="?dir=cirujear" method="get">
    <div class="form-group mt-3">
        Pais:   <select name="pais" id="pais" class="filtros" required>
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
        <?php if ($onGoingSearch === true) { ?>
            <?php
            // Datos obtenidos de los selects
            $pais = isset($_GET['pais']) ? $_GET['pais'] : '';
            $categoria = isset($_GET['categoria']) ? $_GET['categoria'] : '';

            require_once "utils/funciones_paises_y_categorias.php";
            $pais = convertirPais($pais);
            $categoria = convertirCategoria($categoria);

            // Construct the base SQL query
            if ($pais && $categoria) {
                //$sql = $pdo->prepare("SELECT DISTINCT all_item_categoria FROM " . $pais . "_" . $categoria . "_mas ORDER BY all_item_categoria ASC");
                $sql = $pdo->prepare("
                    (SELECT DISTINCT all_item_categoria FROM " . $pais . "_" . $categoria . "_mas)
                    UNION
                    (SELECT DISTINCT all_item_categoria FROM " . $pais . "_" . $categoria . "_menos)
                    ORDER BY all_item_categoria ASC
                    ");
                $sql->execute();
                $filtroCategorias = $sql->fetchAll(PDO::FETCH_COLUMN);
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
                    <?php foreach($filtroCategorias as $opcionFiltrada): ?>
                        <option value="<?php echo(strtolower($opcionFiltrada)); ?>" <?php echo (isset($_GET['subcategoria']) && $_GET['subcategoria'] === (strtolower($opcionFiltrada))) ? 'selected' : ''; ?>><?php echo $opcionFiltrada; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group mt-3">
                Publicaciones: <select name="publicaciones" class="filtros">
                    <option value="">Todas</option>
                    <option value="destacados" <?php echo (isset($_GET['publicaciones']) && $_GET['publicaciones'] === 'destacados') ? 'selected' : ''; ?>>Destacadas</option>
                    <option value="oportunidad" <?php echo (isset($_GET['publicaciones']) && $_GET['publicaciones'] === 'oportunidad') ? 'selected' : ''; ?>>Oportunidad limitada</option>
                </select>
            </div>
            <div class="d-flex justify-content-end mt-3">
                <input type="submit" value="Resetear" id="resetearbtn">
            </div>
            <?php } ?>
            <div class="d-flex justify-content-end mt-3">
                <input type="submit" value="Buscar">
            </div>
        <script>
            // Filtro subcategorias
            $(document).ready(function(){
                $('#categoria').change(function(){
                    const categoria = $(this).val();
                    const pais = $('#pais').val();

                    if(categoria) {
                        $.ajax({
                            url: "utils/subcategorias.php",
                            type: "POST", 
                            data: {'categoria':categoria, 'pais':pais},
                            beforeSend: function(xhr, settings){
                            },
                            success: function(data){
                                $('#subcategoriaSelect').html(data);
                            },
                            error: function(jqXHR, textStatus, errorThrown) {
                            }
                        });
                    } else {
                        //$('#subcategoriaSelect').attr('disabled', true);
                    }
                });
            });
        </script>
</form>