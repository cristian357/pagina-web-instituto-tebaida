<?php
@include '../../conexion/conexion.php'; // Conexión a la base de datos

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recibir y limpiar datos del formulario
    $id_profesor = isset($_POST['id_profesor']) ? trim($_POST['id_profesor']) : null;
    $nombre_profesor = isset($_POST['nombre_profesor']) ? trim($_POST['nombre_profesor']) : null;
    $apellido_profesor = isset($_POST['apellido_profesor']) ? trim($_POST['apellido_profesor']) : null;
    $telefono_profesor = isset($_POST['telefono_profesor']) ? trim($_POST['telefono_profesor']) : null;
    $correo_profesor = isset($_POST['correo_profesor']) ? trim($_POST['correo_profesor']) : null;
    $contrasena_profe = isset($_POST['contrasena_profe']) ? trim($_POST['contrasena_profe']) : null;

    // Verificar conexión a la base de datos
    if (!$conn) {
        die("<script>alert('❌ Error: No hay conexión con la base de datos.');</script>");
    }

    // Validar que ningún campo esté vacío
    if (empty($id_profesor) || empty($nombre_profesor) || empty($apellido_profesor) || 
        empty($telefono_profesor) || empty($correo_profesor) || empty($contrasena_profe)) {
        echo "<script>alert('❌ Todos los campos son obligatorios.');</script>";
        exit();
    }

    // Validar formato de correo
    if (!filter_var($correo_profesor, FILTER_VALIDATE_EMAIL)) {
        echo "<script>alert('❌ Error: Correo no válido.');</script>";
        exit();
    }

    // Hash de la contraseña
    $hashed_password = password_hash($contrasena_profe, PASSWORD_DEFAULT);

    try {
        // Verificar si el correo ya existe
        $sql_check_email = "SELECT COUNT(*) FROM profesor WHERE correo_profesor = :correo";
        $stmt_check_email = $conn->prepare($sql_check_email);
        $stmt_check_email->bindValue(':correo', $correo_profesor, PDO::PARAM_STR);
        $stmt_check_email->execute();
        if ($stmt_check_email->fetchColumn() > 0) {
            echo "<script>alert('❌ Error: El correo ya está registrado. Usa otro.');</script>";
            exit();
        }

        // Verificar si el ID ya existe
        $sql_check_id = "SELECT COUNT(*) FROM profesor WHERE id_profesor = :id_profesor";
        $stmt_check_id = $conn->prepare($sql_check_id);
        $stmt_check_id->bindValue(':id_profesor', $id_profesor, PDO::PARAM_INT);
        $stmt_check_id->execute();
        if ($stmt_check_id->fetchColumn() > 0) {
            echo "<script>alert('❌ Error: El ID ya está registrado. Usa otro.');</script>";
            exit();
        }

        // Insertar en la tabla profesor
        $sql_insert = "INSERT INTO profesor (id_profesor, nombre_profesor, apellido_profesor, telefono_profesor, correo_profesor, contrasena_profe)
                       VALUES (:id_profesor, :nombre, :apellido, :telefono, :correo, :contrasena)";
        $stmt_insert = $conn->prepare($sql_insert);
        $stmt_insert->bindValue(':id_profesor', $id_profesor, PDO::PARAM_INT);
        $stmt_insert->bindValue(':nombre', $nombre_profesor, PDO::PARAM_STR);
        $stmt_insert->bindValue(':apellido', $apellido_profesor, PDO::PARAM_STR);
        $stmt_insert->bindValue(':telefono', $telefono_profesor, PDO::PARAM_STR);
        $stmt_insert->bindValue(':correo', $correo_profesor, PDO::PARAM_STR);
        $stmt_insert->bindValue(':contrasena', $hashed_password, PDO::PARAM_STR);

        if ($stmt_insert->execute()) {
            echo "<script>alert('✅ Registro exitoso.'); window.location.href='inisio.php';</script>";
            exit();
        } else {
            echo "<script>alert('❌ Error en el registro. Inténtalo de nuevo.');</script>";
        }
    } catch (PDOException $e) {
        echo "<script>alert('❌ Error en la consulta: " . $e->getMessage() . "');</script>";
    }
}
?>


<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Profesor</title>
        <link rel="icon" href="../../imagenes/zipwp-image-5610-120x120.png">
    <link rel="stylesheet" href="../../diseño/diseño1.css">
</head>
<body>
          <video autoplay muted loop class="video-fondo">
    <source src="../../imagenes/video/WhatsApp Video 2025-09-18 at 7.19.09 PM.mp4" type="video/mp4">
    Tu navegador no soporta video HTML5.
  </video>
    <div class="login-container">
        <h2>Registro de Profesor</h2>
        <form action="" method="POST">
            <div class="input-group">
                <label>ID Profesor:</label>
                <input type="number" name="id_profesor" required>
            </div>
            <div class="input-group">
                <label>Nombre:</label>
                <input type="text" name="nombre_profesor" required>
            </div>
            <div class="input-group">
                <label>Apellido:</label>
                <input type="text" name="apellido_profesor" required>
            </div>
            <div class="input-group">
                <label>Teléfono:</label>
                <input type="number" name="telefono_profesor" required>
            </div>
            <div class="input-group">
                <label>Correo:</label>
                <input type="email" name="correo_profesor" required>
            </div>
            <div class="input-group">
                <label>Contraseña:</label>
                <input type="password" name="contrasena_profe" required>
            </div>
            <button type="submit">Registrar</button>
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
