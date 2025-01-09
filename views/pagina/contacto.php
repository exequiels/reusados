<?php
$mensaje_confirmacion = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = !empty($_POST["nombre"]) ? escape($_POST["nombre"]) : "";
    $email = !empty($_POST["email"]) ? escape($_POST["email"]) : "";
    $mensaje = !empty($_POST["mensaje"]) ? escape($_POST["mensaje"]) : "";

    $asunto = "Mensaje de contacto de $nombre";
    $contenido = "Nombre: $nombre\n";
    $contenido .= "Correo electrónico: $email\n";
    $contenido .= "Mensaje:\n$mensaje";

    // Envía el correo
    if (empty($nombre) || empty($email) || empty($mensaje)) {
        $mensaje_confirmacion = "Todos los campos son obligatorios.";
    } elseif (mail($contacto_destinatario, $asunto, $contenido)) {
        $mensaje_confirmacion = "Mensaje enviado :)";
    } else {
        $mensaje_confirmacion = "Error al enviar el mensaje :(";
    }
}
?>
<table class="table table-sm table-bordered border-estilo">
    <?php include_once "views/layout/encabezado_de_tabla.php"; ?>
    <tr> 
        <td class="p-3" colspan="2">
            <?php if (!empty($mensaje_confirmacion)): ?>
            <div class="form-group mt-3 mensaje-confirmacion">
                <?php echo $mensaje_confirmacion; ?>
            </div>
            <?php else: ?>
                <form action="" method="POST">
                    <div class="form-group mt-3">
                        <label for="nombre">Nombre:</label>
                        <input type="text" id="nombre" name="nombre" placeholder="Tu nombre" maxlength="30" required class="w-100">
                    </div>

                    <div class="form-group mt-3">
                        <label for="email">Correo electrónico:</label>
                        <input type="email" id="email" name="email" placeholder="Email" maxlength="60" required class="w-100">
                    </div>

                    <div class="form-group mt-3">
                        <label for="mensaje">Mensaje:</label>
                        <textarea id="mensaje" name="mensaje" rows="4" placeholder="Mensaje.." maxlength="3000" required class="w-100"></textarea>
                    </div>

                    <div class="d-flex justify-content-end mt-3">
                        <input type="submit" value="Enviar">
                    </div>
                </form>
            <?php endif; ?>
        </td>
    </tr>
</table>