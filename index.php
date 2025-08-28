<?php
require_once __DIR__ . '/vendor/autoload.php';
use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

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
    correo VARCHAR(100) NOT NULL,
    mensaje TEXT NOT NULL,
    ip VARCHAR(45) NOT NULL,
    fecha TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)");

$mensajeError = null;

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nombre = $conn->real_escape_string($_POST["nombre"]);
    $correo = $conn->real_escape_string($_POST["correo"]);
    $mensaje = $conn->real_escape_string($_POST["mensaje"]);
    $ip = $_SERVER['REMOTE_ADDR'];

    if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
        $mensajeError = "❌ Correo inválido.";
    } else {
        $tiempoEspera = 900;
        $stmt = $conn->prepare("SELECT fecha FROM contactos WHERE ip = ? ORDER BY fecha DESC LIMIT 1");
        $stmt->bind_param("s", $ip);
        $stmt->execute();
        $stmt->bind_result($ultimaFecha);
        if ($stmt->fetch()) {
            $ultimoTiempo = strtotime($ultimaFecha);
            $ahora = time();
            if (($ahora - $ultimoTiempo) < $tiempoEspera) {
                $mensajeError = "⚠️ Debes esperar " . ($tiempoEspera - ($ahora - $ultimoTiempo)) . " segundos antes de enviar otro mensaje.";
            }
        }
        $stmt->close();

        if (!$mensajeError) {
            $stmt = $conn->prepare("INSERT INTO contactos (nombre, correo, mensaje, ip) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("ssss", $nombre, $correo, $mensaje, $ip);
            $stmt->execute();
            $stmt->close();

            require_once __DIR__ . '/mail.php';
            header("Location: /index.php?enviado=1");
            exit();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Formulario de contacto</title>
    <link rel="stylesheet" href="formulario.css">
</head>
<body>
    <div class="formulario-contenedor">
        <?php if (isset($_GET['enviado'])): ?>
            <div class="formulario-alerta formulario-exito" id="formulario-mensaje-exito">
                <span>✅ Mensaje enviado correctamente.</span>
                <button class="formulario-cerrar" onclick="document.getElementById('formulario-mensaje-exito').remove()">✖</button>
            </div>
        <?php endif; ?>

        <?php if ($mensajeError): ?>
            <div class="formulario-alerta formulario-error" id="formulario-mensaje-error">
                <span><?= htmlspecialchars($mensajeError) ?></span>
                <button class="formulario-cerrar" onclick="document.getElementById('formulario-mensaje-error').remove()">✖</button>
            </div>
        <?php endif; ?>

        <?php include 'formulario.html'; ?>
    </div>
</body>
</html>
