<table class="table table-sm table-bordered border-estilo">
    <?php include_once "globales/encabezado_de_tabla.php"; ?>
    <tr class="p-sm-1 p-3"> 
        <td class="p-sm-1 p-3">
        <div class="container">
                <div class="row justify-content-center">
                    <div class="col-12">
                        <form role="form" name="registro" action="#" method="post">                
                            <div class="form-group mt-3">
                                <label for="username">Nombre de usuario:</label>
                                <input type="text" id="username" name="username" placeholder="Nombre de usuario" class="w-100">
                            </div>            
                            <div class="form-group mt-3">
                                <label for="fullname">Nombre de pila:</label>
                                <input type="text" id="fullname" name="fullname" placeholder="Nombre Completo" class="w-100">
                            </div>
                            <div class="form-group mt-3">
                                <label for="email">Email</label>
                                <input type="email" id="email" name="email" placeholder="Correo Electronico" class="w-100">
                            </div>
                            <div class="form-group mt-3">
                                <label for="password">Contrase&ntilde;a</label>
                                <input type="password" id="password" name="password" placeholder="Contrase&ntilde;a" class="w-100">
                            </div>
                            <div class="form-group mt-3">
                                <label for="confirm_password">Confirmar Contrase&ntilde;a</label>
                                <input type="password" id="confirm_password" name="confirm_password" placeholder="Confirmar Contrase&ntilde;a" class="w-100">
                            </div>
                            <div class="d-flex justify-content-end mt-3">
                                <input type="submit" value="Registrarse">
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </td>
    </tr>
</table>