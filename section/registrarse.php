<table class="table table-sm table-bordered border-estilo">
    <?php include_once "globales/encabezado_de_tabla.php"; ?>
    <tr class="p-3"> 
        <td class="p-3" colspan="2">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-12">
                        <form action="" method="POST">
                            <div class="form-group mt-3">
                                <label for="nombre">Usuario:</label>
                                <input type="text" id="nombre" name="nombre" maxlength="30" required class="w-100 w-md-50">
                            </div>
                            <div class="form-group mt-3">
                                <label for="nombre">Nombre Completo:</label>
                                <input type="text" id="nombre" name="nombre" maxlength="30" required class="w-100 w-md-50">
                            </div>
                            <div class="form-group mt-3">
                                <label for="email">Correo electrónico:</label>
                                <input type="email" id="email" name="email" maxlength="60" required class="w-100 w-md-50">
                            </div>
                            <div class="form-group mt-3">
                                <label for="password">Contrase&ntilde;a:</label>
                                <input type="password" id="password" name="password" maxlength="60" required class="w-100 w-md-50">
                            </div>
                            <div class="form-group mt-3">
                                <label for="password">Confirmar Contrase&ntilde;a:</label>
                                <input type="password" id="confirm_password" name="confirm_password" maxlength="60" required class="w-100">
                            </div>
                            <div class="d-flex justify-content-end mt-3">
                                <button type="submit">Registrarse</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </td>
    </tr>
</table>