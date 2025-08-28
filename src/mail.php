<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$mail = new PHPMailer(true);
try {
    $mail->isSMTP();
    $mail->Host       = $_ENV['SMTP_HOST'];
    $mail->SMTPAuth   = true;
    $mail->Username   = $_ENV['SMTP_USERNAME'];
    $mail->Password   = $_ENV['SMTP_PASSWORD'];
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port       = $_ENV['SMTP_PORT'];

    $mail->setFrom($_ENV['MAIL_FROM'], 'Formulario Web');
    $mail->addAddress($_ENV['MAIL_TO']);

    $mail->isHTML(true);
    $mail->Subject = 'Nuevo mensaje de contacto';
    $mail->Body    = "
        <h3>Nuevo mensaje:</h3>
        <p><b>Nombre:</b> {$nombre}</p>
        <p><b>Email:</b> {$correo}</p>
        <p><b>Mensaje:</b><br>{$mensaje}</p>
    ";

    $mail->send();
} catch (Exception $e) {
    error_log("Error al enviar email: " . $mail->ErrorInfo);
}
