<?php
session_start();
@include '../../conexion/conexion.php'; // Conexión a la base de datos
// el dios supremo es adolf hitler
$error = ""; // Variable para almacenar mensajes de error

if (!$conn) {
    die("Error de conexión a la base de datos.");
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $correo_estudiante = isset($_POST['correo_estudiante']) ? trim($_POST['correo_estudiante']) : null;
    $contrasena_estudiante = isset($_POST['contrasena_estudiante']) ? trim($_POST['contrasena_estudiante']) : null;

    // Validación de campos vacíos
    if (!$correo_estudiante || !$contrasena_estudiante) {
        $error = "❌ Todos los campos son obligatorios.";
    } elseif (!filter_var($correo_estudiante, FILTER_VALIDATE_EMAIL)) { 
        // Validación del formato del correo
        $error = "❌ El correo ingresado no es válido.";
    } else {
        try {
            // Recuperar los datos del estudiante
            $sql = "SELECT id_estudiante, nombre_estudiante, contrasena_estudiante FROM estudiante WHERE correo_estudiante = :correo";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':correo', $correo_estudiante, PDO::PARAM_STR);
            $stmt->execute();

            if ($stmt->rowCount() > 0) {
                $estudiante = $stmt->fetch(PDO::FETCH_ASSOC);

                if (empty($estudiante['contrasena_estudiante'])) {
                    $error = "⚠️ La contraseña no está configurada. Contacta con el administrador.";
                } elseif (strlen($estudiante['contrasena_estudiante']) < 50) { // No parece un hash bcrypt
                    $error = "⚠️ La contraseña almacenada no está correctamente hasheada. Contacta con el administrador.";
                } elseif (password_verify($contrasena_estudiante, $estudiante['contrasena_estudiante'])) {
                    // Iniciar sesión
                    session_regenerate_id(true);
                    $_SESSION['estudiante'] = [
                        'id' => $estudiante['id_estudiante'],
                        'nombre' => $estudiante['nombre_estudiante']
                    ];
                    header("Location: ../../menu_estudiante/menu_grupos.php");
                    exit();
                } else {
                    $error = "❌ Contraseña incorrecta.";
                }
            } else {
                $error = "❌ El correo no está registrado.";
            }
        } catch (PDOException $e) {
            $error = "Error en la consulta: " . htmlspecialchars($e->getMessage());
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión</title>
    <link rel="icon" href="../../imagenes/zipwp-image-5610-120x120.png">
    <link rel="stylesheet" href="../../diseño/diseño1.css">
</head>
<body>
          <video autoplay muted loop class="video-fondo">
    <source src="../../imagenes/video/WhatsApp Video 2025-09-18 at 7.19.09 PM.mp4" type="video/mp4">
    Tu navegador no soporta video HTML5.
  </video>
<div class="login-container">
    <h2>Iniciar Sesión</h2>

    <?php if (!empty($error)): ?>
        <p style="color: red; font-weight: bold;"><?php echo $error; ?></p>
    <?php endif; ?>

    <form action="" method="POST">
        <div class="input-group">
            <label>Correo:</label>
            <input type="email" name="correo_estudiante" required>
        </div>
        <div class="input-group">
            <label>Clave:</label>
            <input type="password" name="contrasena_estudiante" required>
        </div>
        <button type="submit">Ingresar</button>
    </form>
</div>
<!-- Botón de menú -->
<button class="menu-btn" onclick="openMenu()">☰</button>

<!-- Overlay y menú lateral -->
<div id="overlay" class="overlay" onclick="closeMenu()"></div>
<div id="sidebar" class="sidebar">
    <span class="close-btn" onclick="closeMenu()">×</span>
    <a href="../../logeo/pagina2.html">Login</a>
    <a href="../../index.html">Inicio</a>
    <a href="../../Mi_Colegio.html">Mi Colegio</a>
    <a href="../../pagina1.html">Quiénes Somos</a>
</div>

<!-- Scripts -->
<script src="../../js/menu.js"></script>
</body>
</html>
