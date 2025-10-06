<?php
require_once __DIR__ . '/vendor/autoload.php';

use Dotenv\Dotenv;

// Cargar variables de entorno desde el mismo directorio
$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

// Conexión a MySQL
$conn = new mysqli(
    $_ENV['MYSQL_HOST'],
    $_ENV['MYSQL_USER'],
    $_ENV['MYSQL_PASSWORD'],
    $_ENV['MYSQL_DATABASE']
);
if ($conn->connect_error) {
    die("❌ Conexión fallida: " . $conn->connect_error);
}


// Crear tabla si no existe
$conn->query("CREATE TABLE IF NOT EXISTS contactos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    mensaje TEXT NOT NULL,
    ip VARCHAR(45) NOT NULL,
    fecha TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)");

$mensajeError = null;

// Procesar POST
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nombre = $conn->real_escape_string($_POST["nombre"]);
    $email = $conn->real_escape_string($_POST["email"]);
    $mensaje = $conn->real_escape_string($_POST["mensaje"]);
    $ip = $_SERVER['REMOTE_ADDR'];

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $mensajeError = "❌ Email inválido.";
    } else {
        // Cooldown por IP (ejemplo: 60 segundos)
        $cooldownSegundos = 60;
        $stmt = $conn->prepare("SELECT fecha FROM contactos WHERE ip = ? ORDER BY fecha DESC LIMIT 1");
        $stmt->bind_param("s", $ip);
        $stmt->execute();
        $stmt->bind_result($ultimaFecha);
        if ($stmt->fetch()) {
            $ultimoTiempo = strtotime($ultimaFecha);
            $ahora = time();
            if (($ahora - $ultimoTiempo) < $cooldownSegundos) {
                $mensajeError = "⚠️ Debes esperar " . ($cooldownSegundos - ($ahora - $ultimoTiempo)) . " segundos antes de enviar otro mensaje.";
            }
        }
        $stmt->close();

        // Si no hay error, insertar en base de datos y enviar email
        if (!$mensajeError) {
            $stmt = $conn->prepare("INSERT INTO contactos (nombre, email, mensaje, ip) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("ssss", $nombre, $email, $mensaje, $ip);
            $stmt->execute();
            $stmt->close();

            // Incluir script de envío de correo
            require_once __DIR__ . '/mail.php';

            // Redirigir con parámetro de éxito
            header("Location: index.php?enviado=1");
            exit();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Formulario de Contacto</title>
    <link rel="stylesheet" href="formulario.css">
</head>
<body>
    <div class="formulario-contenedor">
        <?php if (isset($_GET['enviado'])): ?>
            <div class="formulario-alerta formulario-exito" id="mensajeExito">
                <span>✅ Mensaje enviado correctamente.</span>
                <button class="formulario-cerrar" onclick="document.getElementById('mensajeExito').remove()">✖</button>
            </div>
        <?php endif; ?>

        <?php if ($mensajeError): ?>
            <div class="formulario-alerta formulario-error" id="mensajeError">
                <span><?= htmlspecialchars($mensajeError) ?></span>
                <button class="formulario-cerrar" onclick="document.getElementById('mensajeError').remove()">✖</button>
            </div>
        <?php endif; ?>

        <?php include __DIR__ . '/formulario.html'; ?>
    </div>
</body>
</html>