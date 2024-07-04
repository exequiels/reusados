<?php
$pagina_login = $url_base . "?dir=login";

$email = '';
$password = '';

function encryptEmail($email, $encryption_key)
{
    $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length('aes-256-cbc'));
    $encrypted = openssl_encrypt($email, 'aes-256-cbc', $encryption_key, 0, $iv);
    return base64_encode($encrypted . '::' . $iv);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    if (empty($email) || empty($password)) {
        $errors[] = 'Todos los campos son obligatorios.';
    } else {
        try {
            // Cifrar el correo electrónico y la contraseña
            $encrypted_email = encryptEmail($email, $encryption_key);
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            // Intentar obtener el usuario de la base de datos
            $stmt = $pdo->prepare('SELECT * FROM usuarios WHERE email = :email');
            $stmt->execute(['email' => $encrypted_email]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user && $user['password'] === $hashed_password) {

                // $_SESSION['user_id'] = $user['id'];
                // $_SESSION['username'] = $user['username'];

                echo "Logueado con exito";

            } else {
                $errors[] = 'Correo electrónico o contraseña incorrectos.';
            }
        } catch (PDOException $e) {
            $errors[] = 'Error al conectarse con la base de datos: ' . $e->getMessage();
        }
    }
}
?>
<table class="table table-sm table-bordered border-estilo">
    <?php include_once "globales/encabezado_de_tabla.php"; ?>
    <tr class="p-3"> 
        <td class="p-3" colspan="2">
            <div class="form-group mt-3">
                <div class="col-md-12">
                    <form method="post">
                        <div class="form-group mt-3">
                            <label for="email">Email:</label>
                            <input type="text" id="email" name="email" placeholder="Select Player" minlength="3" maxlength="30" class="w-100" required>
                            <span class="validacion text-danger" style="display: none;">Algo no cuadra.</span>
                            <?php if (!empty($emailErrors)) { ?>
                                <?php foreach ($emailErrors as $userErrors) { ?>
                                    <span class="validacion text-danger"><?= $userErrors ?></span><br>
                                <?php } ?>
                            <?php } ?>
                            <div class="form-group mt-3">
                                <label for="password">Contrase&ntilde;a:</label>
                                <input type="password" id="password" name="password" placeholder="Password" minlength="8" maxlength="60" class="w-100" required>
                                <span class="validacion text-danger" style="display: none;">Chequea los requerimientos.</span>
                                <?php if (!empty($passwordError)) { ?>
                                    <span class="validacion text-danger"><?= $passwordError ?></span>
                                <?php } ?>
                                <?php if (!empty($emailError) || !empty($emailError)) { ?>
                                    <span class="validacion text-danger">Por favor, vuelve a introducir la contraseña.</span>
                                <?php } ?>
                            </div>
                            <div class="d-flex justify-content-end mt-3">
                                <input type="submit" value="Start">
                            </div>
                    </form>
                    <?php if (!empty($errors)) { ?>
                        <div class="alert alert-danger mt-3">
                            <?php foreach ($errors as $error) { ?>
                                <p><?= $error ?></p>
                            <?php } ?>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </td>
    </tr>
</table>