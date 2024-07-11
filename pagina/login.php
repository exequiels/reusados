<?php
require_once 'utils/email_encrypt_functions.php';
$pagina_login = $url_base . "?dir=login";

$email = '';
$password = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    if (empty($email) || empty($password)) {
        $errors[] = 'Todos los campos son obligatorios.';
    } else {
        try {

            $encryptedEmail = encryptEmail($email, $key_emails, $key_emails_iv);

            $stmt = $pdo->prepare('SELECT * FROM usuarios WHERE email = :email');
            $stmt->execute(['email' => $encryptedEmail]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user && password_verify($password, $user['password'])) {

                // $_SESSION['user_id'] = $user['id'];
                // $_SESSION['username'] = $user['username'];

                echo "Logueado con exito<br>";

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
                            <input type="email" id="email" name="email" placeholder="Select Player" minlength="3" maxlength="30" class="w-100" required>
                            <div class="form-group mt-3">
                                <label for="password">Contrase&ntilde;a:</label>
                                <input type="password" id="password" name="password" placeholder="Password" minlength="8" maxlength="60" class="w-100" required>
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