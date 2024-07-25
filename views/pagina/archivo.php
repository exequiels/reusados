<table class="table table-sm table-bordered border-estilo">
    <?php include_once "views/layout/encabezado_de_tabla.php"; ?>
    <tr class="p-3"> 
        <td class="p-3" colspan="2">
            <div class="form-group mt-3">
            Que buscas?
                <select name="categoria" id="categoria" class="filtros" required>
                    <option value=""> Una consola.. </option>
                    <option value=""> Un juego.. </option>
                </select>
            </div>
            <div class="form-group mt-3">
            {Sistema} o {Juego}:
                <select name="categoria" id="categoria" class="filtros" required>
                    <option value=""> {Sistema} </option>
                    <option value=""> {Juego} </option>
                </select>
            </div>
            <div class="d-flex justify-content-end mt-3">
                <input type="submit" value="Buscar">
            </div>
        </td>
    </tr>
    <tr class="p-3"> 
        <td class="p-3 text-center" colspan="2">
            <img src="assets/imgs/placeholder450x350.png" class="card-img-top img-fluid" alt="Imagen del juego" style="max-width: 400px; max-height: 400px; object-fit: cover;">
        </td>
    </tr>
    <tr class="p-3"> 
        <td class="p-3" colspan="2">
            <h6>Título del Juego</h6>
        </td>
    </tr>
    <tr class="p-3"> 
        <td class="p-3" colspan="2">
            <ul class="list-group list-group-flush">
                <li class="list-group-item"><strong>Año de lanzamiento:</strong> 2023</li>
                <li class="list-group-item"><strong>Desarrollador:</strong> Nombre del Desarrollador</li>
                <li class="list-group-item"><strong>Franquicia:</strong> Nombre de la Franquicia</li>
                <li class="list-group-item"><strong>Sistema:</strong> Nombre del Sistema</li>
                <li class="list-group-item"><strong>Tambien salio para:</strong> Nombre del Sistema</li>
            </ul>
        </td>
    </tr>
</table>