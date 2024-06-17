<table class="table table-sm table-bordered border-estilo">
    <?php include_once "globales/encabezado_de_tabla.php"; ?>
    <tr class="p-sm-1 p-3"> 
        <td class="p-sm-1 p-3">
            <form role="form" name="registro" action="#" method="post">
                <div class="d-flex flex-wrap">
                    <div class="col-12 col-md-auto mb-2 mb-md-0">                    
                        <div class="p-2">
                            <label for="username">Nombre de usuario:</label>
                            <input type="text" id="username" name="username" placeholder="Nombre de usuario" class="w-100">
                        </div>
                    </div>   
                    <div class="col-12 col-md-auto mb-2 mb-md-0">                   
                        <div class="p-2">
                            <label for="fullname">Nombre de pila:</label>
                            <input type="text" id="fullname" name="fullname" placeholder="Nombre Completo" class="w-100">
                        </div>
                    </div>   
                    <div class="col-12 col-md-auto mb-2 mb-md-0"> 
                        <div class="p-2">
                            <label for="email">Email</label>
                            <input type="email" id="email" name="email" placeholder="Correo Electronico" class="w-100">
                        </div>
                    </div>   
                    <div class="col-12 col-md-auto mb-2 mb-md-0"> 
                        <div class="p-2">
                            <label for="password">Contrase&ntilde;a</label>
                            <input type="password" id="password" name="password" placeholder="Contrase&ntilde;a" class="w-100">
                        </div>
                    </div>   
                    <div class="col-12 col-md-auto mb-2 mb-md-0"> 
                        <div class="p-2">
                            <label for="confirm_password">Confirmar Contrase&ntilde;a</label>
                            <input type="password" id="confirm_password" name="confirm_password" placeholder="Confirmar Contrase&ntilde;a" class="w-100">
                        </div>
                    </div>   
                    <div class="p-2 flex-item d-flex justify-content-end">
                        <input type="submit" value="Registrarse" class="mx-2">
                    </div>
                </div>
            </form>
        </td>
    </tr>
</table>