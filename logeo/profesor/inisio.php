<?php
session_start();
@include '../../conexion/conexion.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $usuario = isset($_POST['usuario']) ? trim($_POST['usuario']) : null;
    $contrasena = isset($_POST['contrasena']) ? trim($_POST['contrasena']) : null;

    if (!$usuario || !$contrasena) {
        echo "<script>alert('❌ Todos los campos son obligatorios.');</script>";
    } elseif (!filter_var($usuario, FILTER_VALIDATE_EMAIL)) {
        echo "<script>alert('❌ El correo ingresado no es válido.');</script>";
    } else {
        try {
            $sql = "SELECT id_profesor, nombre_profesor, apellido_profesor, contrasena_profe 
                    FROM profesor 
                    WHERE correo_profesor = :usuario";

            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':usuario', $usuario, PDO::PARAM_STR);
            $stmt->execute();

            if ($stmt->rowCount() > 0) {
                $profesor = $stmt->fetch(PDO::FETCH_ASSOC);

                if (empty($profesor['contrasena_profe'])) {
                    echo "<script>alert('⚠️ La contraseña no está configurada. Contacta con el administrador.');</script>";
                    exit();
                }

                if (password_verify($contrasena, $profesor['contrasena_profe'])) {
                    session_regenerate_id(true);
                    $_SESSION['profesor'] = [
                        'id' => $profesor['id_profesor'],
                        'nombre' => $profesor['nombre_profesor'],
                        'apellido' => $profesor['apellido_profesor']
                    ];
                    header("Location: ../../menu/menu_grupos.php");
                    exit();
                } else {
                    echo "<script>alert('❌ Contraseña incorrecta.');</script>";
                }
            } else {
                echo "<script>alert('❌ El correo no está registrado.');</script>";
            }
        } catch (PDOException $e) {
            echo "<script>alert('Error en la consulta: " . htmlspecialchars($e->getMessage()) . "');</script>";
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
    <link rel="stylesheet" href="../../diseño/diseño1.css">
        <link rel="icon" href="../../imagenes/zipwp-image-5610-120x120.png">
</head>
<body>
      <video autoplay muted loop class="video-fondo">
    <source src="../../imagenes/video/WhatsApp Video 2025-09-18 at 7.19.09 PM.mp4" type="video/mp4">
    Tu navegador no soporta video HTML5.
  </video>
<div class="login-container">
    <h2>Iniciar Sesión</h2>
    <form action="" method="POST">
        <label>Correo:</label>
        <input type="email" name="usuario" required><br><br>
        <label>Clave:</label>
        <input type="password" name="contrasena" required><br><br>
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
