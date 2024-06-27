<?php
$pagina_registrarse = $url_base . "?dir=registrarse";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $username = $_POST['username'];
    $fullname = $_POST['fullname'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $captcha = $_POST['captcha'];

    $errors = [];

    if (strlen($username) < 3 || strlen($username) > 30) {
        $errors[] = "Mínimo 3 caracteres y máximo 30.";
    }

    if (strlen($fullname) < 3 || strlen($fullname) > 30) {
        $errors[] = "Mínimo 3 caracteres y máximo 30.";
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Email no válido.";
    }

    if (!preg_match('/^(?=.*[A-Z])(?=.*\d).{8,}$/', $password)) {
        $errors[] = "El password debe tener al menos ocho caracteres, entre ellos una mayúscula y al menos un número.";
    }

    if ($password !== $confirm_password) {
        $errors[] = "Los passwords deben coincidir.";
    }

    if (empty($errors)) {

        $hashed_password = password_hash($password, PASSWORD_DEFAULT);


        $stmt = $pdo->prepare("INSERT INTO users (username, fullname, email, password) VALUES (?, ?, ?, ?)");
        $stmt->execute([$username, $fullname, $email, $hashed_password]);

        header("Location: $pagina_registrarse");
        exit();
    } else {
        foreach ($errors as $error) {
            echo "<p>$error</p>";
        }
    }
}
?>
<table class="table table-sm table-bordered border-estilo">
    <?php 
        include_once "globales/encabezado_de_tabla.php";

        $num1 = rand(0, 9);
        $num2 = rand(0, 9);
        $captchaAnswer = $num1 + $num2;
        $displayedAnswer = rand(0, 18);
    ?>

    <script>
        var captchaAnswer = <?= $captchaAnswer ?>;
    </script>

    <tr class="p-3"> 
        <td class="p-3" colspan="2">
            <form role="form" id="registro" action="" method="post">                
                <div class="form-group mt-3">
                    <label for="usuario">Usuario:</label>
                    <span class="text-body-tertiary">(mínimo 3 caracteres y máximo 30)</span>
                    <input type="text" id="username" name="username" placeholder="Nickname en reUsados" maxlength="30" class="w-100" required>
                    <span class="validacion text-danger" style="display: none;">Algo no cuadra.</span>
                </div>
                <div class="form-group mt-3">
                    <label for="nombre">Nombre:</label>
                    <span class="text-body-tertiary fst-normal">(mínimo 3 caracteres y máximo 30)</span>
                    <input type="text" id="fullname" name="fullname" placeholder="Tu nombre" maxlength="30" class="w-100" required>
                    <span class="validacion text-danger" style="display: none;">Algo no cuadra.</span>
                </div>            
                <div class="form-group mt-3">
                    <label for="email">Correo electrónico:</label>
                    <span class="text-body-tertiary">(un email válido)</span>
                    <input type="email" id="email" name="email" placeholder="Email" maxlength="50" class="w-100" required>
                    <span class="validacion text-danger" style="display: none;">No parece válido.</span>
                </div>
                <div class="form-group mt-3">
                    <label for="password">Contrase&ntilde;a:</label>
                    <span class="text-body-tertiary">(mínimo ocho caracteres, entre ellos una mayúscula, un número, un caracter especial)</span>
                    <input type="password" id="password" name="password" placeholder="Contrase&ntilde;a" maxlength="60" class="w-100" required>
                    <span class="validacion text-danger" style="display: none;">Chequea los requerimientos.</span>
                </div>
                <div class="form-group mt-3">
                    <label for="confirm_password">Confirmar Contrase&ntilde;a:</label>
                    <input type="password" id="confirm_password" name="confirm_password" placeholder="Misma Contrase&ntilde;a" maxlength="60" class="w-100" required>
                    <span class="validacion text-danger" style="display: none;">No coinciden la contrase&ntilde;as.</span>
                </div>
                <div class="form-group mt-3">
                    <label for="captcha">¿Cuánto es <?= $num1 ?> + <?= $num2 ?>?</label>
                    <input type="number" id="captcha" name="captcha" placeholder="Respuesta" class="w-100">
                    <span class="validacion text-danger" style="display: none;">Respuesta incorrecta.</span>
                </div>
                <div class="d-flex justify-content-end mt-3">
                    <input type="submit" value="Registrarse">
                </div>
            </form>
        </td>
    </tr>
</table>