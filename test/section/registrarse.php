<table class="table table-sm table-bordered border-estilo">
    <?php 
        include_once "globales/encabezado_de_tabla.php";

        $num1 = rand(0, 9);
        $num2 = rand(0, 9);
        $captchaAnswer = $num1 + $num2;
        $displayedAnswer = rand(0, 18);
        $isCorrectAnswer = ($displayedAnswer == $captchaAnswer);
    ?>

    <script>
        var captchaAnswer = <?= $captchaAnswer ?>;
    </script>

    <tr class="p-3"> 
        <td class="p-3" colspan="2">
            <form role="form" id="registro" action="#" method="post">                
                <div class="form-group mt-3">
                    <label for="username">Usuario (mínimo 3 caracteres y máximo 30):</label>
                    <input type="text" id="username" name="username" placeholder="Nickname en reUsados" maxlength="30" class="w-100" required>
                    <span class="validacion" style="display: none; color: red;">Algo no cuadra.</span>
                </div>
                <div class="form-group mt-3">
                    <label for="fullname">Nombre (mínimo 3 caracteres y máximo 30):</label>
                    <input type="text" id="fullname" name="fullname" placeholder="Tu nombre" maxlength="30" class="w-100" required>
                    <span class="validacion" style="display: none; color: red;">Algo no cuadra.</span>
                </div>            
                <div class="form-group mt-3">
                    <label for="email">Correo electrónico (un email válido):</label>
                    <input type="email" id="email" name="email" placeholder="Email" maxlength="50" class="w-100" required>
                    <span class="validacion" style="display: none; color: red;">No parece válido.</span>
                </div>
                <div class="form-group mt-3">
                    <label for="password">Contrase&ntilde;a (ocho caracteres, entre ellos una mayúscula y al menos un número.):</label>
                    <input type="password" id="password" name="password" placeholder="Contrase&ntilde;a" maxlength="60" class="w-100" required>
                    <span class="validacion" style="display: none; color: red;">Chequea los requerimientos .</span>
                </div>
                <div class="form-group mt-3">
                    <label for="confirm_password">Confirmar Contrase&ntilde;a:</label>
                    <input type="password" id="confirm_password" name="confirm_password" placeholder="Confirmar Contrase&ntilde;a" maxlength="60" class="w-100" required>
                    <span class="validacion" style="display: none; color: red;">No coinciden la contrase&ntilde;as.</span>
                </div>
                <div class="form-group mt-3">
                    <label for="captcha">¿Cuánto es <?= $num1 ?> + <?= $num2 ?>?</label>
                    <input type="number" id="captcha" name="captcha" placeholder="Respuesta" class="w-100">
                    <span class="validacion" style="display:none; color:red;">Respuesta incorrecta.</span>
                </div>
                <div class="d-flex justify-content-end mt-3">
                    <input type="submit" value="Registrarse">
                </div>
            </form>
        </td>
    </tr>
</table>