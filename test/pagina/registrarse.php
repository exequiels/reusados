<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
$pagina_registrarse = $url_base . "?dir=registrarse";

$username = '';
$fullname = '';
$email = '';

$usernameErrors = [];
$fullnameError = '';
$emailErrors = [];
$passwordError = '';
$confirmPasswordError = '';
$captchaError = '';
$registrado = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    try {
        $username = $_POST['username'];
        $fullname = $_POST['fullname'];
        $email = $_POST['email'];
        $password = $_POST['password'];
        $confirm_password = $_POST['confirm_password'];
        $captcha = $_POST['captcha'];
        $captchaAnswer = $_POST['captchaAnswer'];

        $stmt = $pdo->prepare("SELECT COUNT(*) FROM usuarios WHERE username = ?");
        $stmt->execute([$username]);
        $count = $stmt->fetchColumn();
        if ($count > 0) {
            // $errors[] = "El nombre de usuario ya está registrado.";
            $usernameErrors[] = "El nombre de usuario ya está en uso, elije otro.";
        }

        $stmt = $pdo->prepare("SELECT COUNT(*) FROM usuarios WHERE email = ?");
        $stmt->execute([$email]);
        $count = $stmt->fetchColumn();
        if ($count > 0) {
            $emailErrors[] = "El email ya está registrado.";
        }

        if (strlen($username) < 3 || strlen($username) > 30) {
            $usernameErrors[] = "Mínimo 3 caracteres y máximo 30.";
        }

        if (strlen($fullname) < 3 || strlen($fullname) > 30) {
            $fullnameError = "Mínimo 3 caracteres y máximo 30.";
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $emailErrors[] = "Email no válido.";
        }

        if (!preg_match('/^(?=.*[A-Z])(?=.*\d).{8,}$/', $password)) {
            $passwordError = "El password debe tener al menos ocho caracteres, entre ellos una mayúscula y al menos un número.";
        }

        if ($password !== $confirm_password) {
            $confirmPasswordError = "Los passwords deben coincidir.";
        }

        if ($captcha != $captchaAnswer) {
            $captchaError = "Captcha incorrecto.";
        }

        if (empty($usernameErrors) && empty($emailErrors) && empty($fullnameError) && empty($passwordError) && empty($confirmPasswordError) && empty($captchaError)) {

            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $token_activacion = bin2hex(random_bytes(16));
            $hashed_token = password_hash($token_activacion, PASSWORD_DEFAULT);

            $stmt = $pdo->prepare("INSERT INTO usuarios (username, fullname, email, password, token_activacion, created_at) VALUES (?, ?, ?, ?, ?, NOW())");
            $stmt->execute([$username, $fullname, $email, $hashed_password, $hashed_token]);

            $asunto = 'Activación de cuenta';
            $link_activacion = "$url_base?dir=activacion&token=$hashed_token";
            $mensaje = "Haz click en el siguiente enlace para activar tu cuenta: $link_activacion";
            $headers = "From: no-responder@reusados.net\r\n";
            $headers .= "Reply-To: no-responder@reusados.net\r\n";
            $headers .= "Content-Type: text/html; charset=UTF-8\r\n";
            mail($email, $asunto, $mensaje, $headers);

            $registrado = "Registrado, revisa tu emails para confirmar la registración";
        }
    } catch (Exception $e) {
        error_log("Error durante el registro: " . $e->getMessage());
        $registrado = "Ha ocurrido un error durante el registro. Por favor, inténtalo nuevamente o si el error persiste hacemelo saber mediante el formulario de contacto de la pagina.";
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
        <?php if (!empty($registrado)): ?>
            <div class="form-group mt-3">
                <?= $registrado ?>
            </div>
        <?php else: ?>
            <form role="form" id="registro" action="" method="post">                
                <div class="form-group mt-3">
                    <label for="usuario">Usuario:</label>
                    <span class="text-body-tertiary">(mínimo 3 caracteres y máximo 30)</span>
                    <input type="text" id="username" name="username" placeholder="Nickname en reUsados" minlength="3" maxlength="30" class="w-100" value="<?= isset($_POST['username']) ? htmlspecialchars($_POST['username']) : '' ?>" required>
                    <span class="validacion text-danger" style="display: none;">Algo no cuadra.</span>
                    <?php if (!empty($usernameErrors)) { ?>
                        <?php foreach ($usernameErrors as $userErrors) { ?>
                            <span class="validacion text-danger"><?= $userErrors?></span><br>
                        <?php } ?>
                    <?php } ?>
                </div>
                <div class="form-group mt-3">
                    <label for="nombre">Nombre:</label>
                    <span class="text-body-tertiary fst-normal">(mínimo 3 caracteres y máximo 30)</span>
                    <input type="text" id="fullname" name="fullname" placeholder="Tu nombre" minlength="3" maxlength="30" class="w-100" value="<?= isset($_POST['fullname']) ? htmlspecialchars($_POST['fullname']) : '' ?>" required>
                    <span class="validacion text-danger" style="display: none;">Algo no cuadra.</span>
                    <?php if (!empty($fullnameError)) { ?>
                        <span class="validacion text-danger"><?= $fullnameError ?></span>
                    <?php } ?>
                </div>            
                <div class="form-group mt-3">
                    <label for="email">Correo electrónico:</label>
                    <span class="text-body-tertiary">(un email válido)</span>
                    <input type="email" id="email" name="email" placeholder="Email" minlength="8" maxlength="50" class="w-100" value="<?= isset($_POST['email']) ? htmlspecialchars($_POST['email']) : '' ?>" required>
                    <span class="validacion text-danger" style="display: none;">No parece válido.</span>
                    <?php if (!empty($emailErrors)) { ?>
                        <?php foreach ($emailErrors as $mailErrors) { ?>
                            <span class="validacion text-danger"><?= $mailErrors ?></span><br>
                        <?php } ?>
                    <?php } ?>
                </div>
                <div class="form-group mt-3">
                    <label for="password">Contrase&ntilde;a:</label>
                    <span class="text-body-tertiary">(mínimo ocho caracteres, entre ellos una mayúscula, un número, un caracter especial)</span>
                    <input type="password" id="password" name="password" placeholder="Contrase&ntilde;a" minlength="8" maxlength="60" class="w-100" required>
                    <span class="validacion text-danger" style="display: none;">Chequea los requerimientos.</span>
                    <?php if (!empty($passwordError)) { ?>
                        <span class="validacion text-danger"><?= $passwordError ?></span>
                    <?php } ?>
                    <?php if (!empty($usernameError) || !empty($emailError)) { ?>
                        <span class="validacion text-danger">Por favor, vuelve a introducir la contraseña.</span>
                    <?php } ?>
                </div>
                <div class="form-group mt-3">
                    <label for="confirm_password">Confirmar Contrase&ntilde;a:</label>
                    <input type="password" id="confirm_password" name="confirm_password" placeholder="Misma Contrase&ntilde;a" minlength="8" maxlength="60" class="w-100" required>
                    <span class="validacion text-danger" style="display: none;">No coinciden la contrase&ntilde;as.</span>
                    <?php if (!empty($confirmPasswordError)) { ?>
                        <span class="validacion text-danger"><?= $confirmPasswordError ?></span>                        
                    <?php } ?>
                    <?php if (!empty($usernameError) || !empty($emailError)) { ?>
                        <span class="validacion text-danger">Por favor, vuelve a introducir la contraseña de confirmación.</span>
                    <?php } ?>
                </div>
                <div class="form-group mt-3">
                    <label for="captcha">¿Cuánto es <?= $num1 ?> + <?= $num2 ?>?</label>
                    <input type="number" id="captcha" name="captcha" placeholder="Respuesta" class="w-100">
                    <input type="hidden" name="captchaAnswer" value="<?= $captchaAnswer ?>">
                    <span class="validacion text-danger" style="display: none;">Respuesta incorrecta.</span>
                    <?php if (!empty($captchaError)) { ?>
                        <span class="validacion text-danger"><?= $captchaError ?></span>
                    <?php } ?>
                    <?php if (!empty($usernameError) || !empty($emailError)) { ?>
                        <span class="validacion text-danger">Por favor, vuelve a introducir la respuesta del captcha.</span>
                    <?php } ?>
                </div>
                <div class="d-flex justify-content-end mt-3">
                    <input type="submit" value="Registrarse">
                </div>
            </form>
        <?php endif; ?>
        </td>
    </tr>
</table>