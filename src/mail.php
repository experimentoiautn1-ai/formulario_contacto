<?php
// mail.php

require __DIR__ . '/vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nombre = htmlspecialchars($_POST['nombre'] ?? '');
    $email  = htmlspecialchars($_POST['email'] ?? '');
    $mensaje = htmlspecialchars($_POST['mensaje'] ?? '');

    $mail = new PHPMailer(true);

    try {
        // Configuración SMTP desde .env
        $mail->isSMTP();
        $mail->Host       = $_ENV['SMTP_HOST'];
        $mail->SMTPAuth   = true;
        $mail->Username   = $_ENV['SMTP_USER'];
        $mail->Password   = $_ENV['SMTP_PASS'];
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = $_ENV['SMTP_PORT'];

        // Remitente y destinatario
        $mail->setFrom($email, $nombre);
        $mail->addAddress($_ENV['MAIL_TO']);

        // Contenido
        $mail->isHTML(true);
        $mail->Subject = "Nuevo mensaje de contacto";
        $mail->Body    = "<p><strong>Nombre:</strong> {$nombre}</p>
                          <p><strong>Email:</strong> {$email}</p>
                          <p><strong>Mensaje:</strong><br>{$mensaje}</p>";

        $mail->send();
        echo "✅ Mensaje enviado correctamente";
    } catch (Exception $e) {
        echo "❌ Error al enviar el mensaje: {$mail->ErrorInfo}";
    }
} else {
    echo "⚠️ Método no permitido.";
}
?>