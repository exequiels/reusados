<?php
require_once "globales/variables.php";

$mensaje_confirmacion = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = htmlspecialchars($_POST["nombre"]);
    $email = htmlspecialchars($_POST["email"]);
    $mensaje = htmlspecialchars($_POST["mensaje"]);

    $asunto = "Mensaje de contacto de $nombre";
    $contenido = "Nombre: $nombre\n";
    $contenido .= "Correo electrónico: $email\n";
    $contenido .= "Mensaje:\n$mensaje";

    // Envía el correo
    if (mail($contacto_destinatario, $asunto, $contenido)) {
        $mensaje_confirmacion = "Mensaje enviado :)";
    } else {
        $mensaje_confirmacion = "Error al enviar el mensaje :(";
    }
}
?>
<table class="table table-sm table-bordered border-estilo">
    <?php include_once "globales/encabezado_de_tabla.php"; ?>
    <tr class="p-3"> 
        <td class="p-3" colspan="2">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-12">
                        <?php if (!empty($mensaje_confirmacion)): ?>
                        <div class="mensaje-confirmacion">
                            <?php echo $mensaje_confirmacion; ?>
                        </div>
                        <?php else: ?>
                            <form action="" method="POST">
                                <div class="form-group mt-3">
                                    <label for="nombre">Nombre:</label>
                                    <input type="text" id="nombre" name="nombre" maxlength="30" required class="w-100">
                                </div>

                                <div class="form-group mt-3">
                                    <label for="email">Correo electrónico:</label>
                                    <input type="email" id="email" name="email" maxlength="60" required class="w-100">
                                </div>

                                <div class="form-group mt-3">
                                    <label for="mensaje">Mensaje:</label>
                                    <textarea id="mensaje" name="mensaje" rows="4" maxlength="3000" required class="w-100"></textarea>
                                </div>

                                <div class="d-flex justify-content-end mt-3">
                                    <button type="submit">Enviar</button>
                                </div>
                            </form>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </td>
    </tr>
</table>