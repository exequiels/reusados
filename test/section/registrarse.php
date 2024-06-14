<table class="table table-sm table-bordered border-estilo">
    <?php include_once "globales/encabezado_de_tabla.php"; ?>
    <tr class="p-3"> 
        <td class="p-3" colspan="2">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-12">
                    <form role="form" name="registro" action="#" method="post">
                        <div class="form-group mt-2">
                            <label for="username">Nombre de usuario:</label>
                            <input type="text" id="username" name="username" placeholder="Nombre de usuario">
                        </div>                        
                        <div class="form-group mt-2">
                            <label for="fullname">Nombre de pila:</label>
                            <input type="text" id="fullname" name="fullname" placeholder="Nombre Completo">
                        </div>
                        <div class="form-group mt-2">
                            <label for="email">Email</label>
                            <input type="email" id="email" name="email" placeholder="Correo Electronico">
                        </div>
                        <div class="form-group mt-2">
                            <label for="password">Contrase&ntilde;a</label>
                            <input type="password" id="password" name="password" placeholder="Contrase&ntilde;a">
                        </div>
                        <div class="form-group mt-2">
                            <label for="confirm_password">Confirmar Contrase&ntilde;a</label>
                            <input type="password" id="confirm_password" name="confirm_password" placeholder="Confirmar Contrase&ntilde;a">
                        </div>
                        <div class="p-2 flex-item d-flex justify-content-end">
                            <input type="submit" value="Registrarse" class="mx-2">
                        </div>
                    </form>
                </div>
            </div>
        </td>
    </tr>
</table>