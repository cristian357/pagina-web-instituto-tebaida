<?php
@include '../../conexion/conexion.php';// Conexión a la base de datos

// Cargar los grupos para el select
try {
    $stmt_grupos = $conn->query("SELECT id_grupo, nombre_grupo FROM grupo");
    $grupos = $stmt_grupos->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "<script>alert('Error al cargar los grupos: " . htmlspecialchars($e->getMessage()) . "');</script>";
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recibir y limpiar datos del formulario
    $id_estudiante = trim($_POST['id_estudiante']);
    $nombre_estudiante = trim($_POST['nombre_estudiante']);
    $apellido_estudiante = trim($_POST['apellido_estudiante']);
    $telefono_estudiante = trim($_POST['telefono_estudiante']);
    $direccion_estudiante = trim($_POST['direccion_estudiante']);
    $correo_estudiante = trim($_POST['correo_estudiante']);
    $contrasena_estudiante = trim($_POST['contrasena_estudiante']);
    $id_grupo = trim($_POST['id_grupo']);

    if (!$conn) {
        die("<script>alert('❌ Error: No hay conexión con la base de datos.');</script>");
    }

    if (empty($id_estudiante) || empty($nombre_estudiante) || empty($apellido_estudiante) || 
        empty($telefono_estudiante) || empty($direccion_estudiante) || empty($correo_estudiante) || 
        empty($contrasena_estudiante) || empty($id_grupo)) {
        echo "<script>alert('❌ Todos los campos son obligatorios.');</script>";
        exit();
    }

    if (!filter_var($correo_estudiante, FILTER_VALIDATE_EMAIL)) {
        echo "<script>alert('❌ Error: Correo no válido.');</script>";
        exit();
    }

    $hashed_password = password_hash($contrasena_estudiante, PASSWORD_DEFAULT);

    try {
        $sql_check_email = "SELECT COUNT(*) FROM estudiante WHERE correo_estudiante = :correo";
        $stmt_check_email = $conn->prepare($sql_check_email);
        $stmt_check_email->bindValue(':correo', $correo_estudiante, PDO::PARAM_STR);
        $stmt_check_email->execute();

        if ($stmt_check_email->fetchColumn() > 0) {
            echo "<script>alert('❌ Error: El correo ya está registrado. Usa otro.');</script>";
            exit();
        }

        $sql_insert = "INSERT INTO estudiante 
            (id_estudiante, nombre_estudiante, apellido_estudiante, telefono_estudiante, 
            direccion_estudiante, correo_estudiante, contrasena_estudiante, id_grupo)
            VALUES 
            (:id_estudiante, :nombre, :apellido, :telefono, :direccion, :correo, :contrasena, :id_grupo)";

        $stmt_insert = $conn->prepare($sql_insert);
        $stmt_insert->bindValue(':id_estudiante', $id_estudiante, PDO::PARAM_INT);
        $stmt_insert->bindValue(':nombre', $nombre_estudiante, PDO::PARAM_STR);
        $stmt_insert->bindValue(':apellido', $apellido_estudiante, PDO::PARAM_STR);
        $stmt_insert->bindValue(':telefono', $telefono_estudiante, PDO::PARAM_STR);
        $stmt_insert->bindValue(':direccion', $direccion_estudiante, PDO::PARAM_STR);
        $stmt_insert->bindValue(':correo', $correo_estudiante, PDO::PARAM_STR);
        $stmt_insert->bindValue(':contrasena', $hashed_password, PDO::PARAM_STR);
        $stmt_insert->bindValue(':id_grupo', $id_grupo, PDO::PARAM_STR);

        if ($stmt_insert->execute()) {
            echo "<script>alert('✅ Registro exitoso.'); window.location.href='inisio.php';</script>";
            exit();
        } else {
            echo "<script>alert('❌ Error en el registro. Inténtalo de nuevo.');</script>";
        }
    } catch (PDOException $e) {
        echo "<script>alert('❌ Error en la consulta: " . htmlspecialchars($e->getMessage()) . "');</script>";
    }
}
?>


<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Estudiante</title>
     <link rel="icon" href="../../imagenes/zipwp-image-5610-120x120.png">
    <link rel="stylesheet" href="../../diseño/diseño1.css">
</head>
<body>
          <video autoplay muted loop class="video-fondo">
    <source src="../../imagenes/video/WhatsApp Video 2025-09-18 at 7.19.09 PM.mp4" type="video/mp4">
    Tu navegador no soporta video HTML5.
  </video>
    <div class="login-container">
        <h2>Registro de Estudiante</h2>
        <form action="" method="POST">
            <div class="input-group">
                <label>ID Estudiante:</label>
                <input type="number" name="id_estudiante" required>
            </div>
            <div class="input-group">
                <label>Nombre:</label>
                <input type="text" name="nombre_estudiante" required>
            </div>
            <div class="input-group">
                <label>Apellido:</label>
                <input type="text" name="apellido_estudiante" required>
            </div>
            <div class="input-group">
                <label>Teléfono:</label>
                <input type="text" name="telefono_estudiante" required pattern="[0-9]{10}" title="Debe contener 10 dígitos numéricos">
            </div>
            <div class="input-group">
                <label>Dirección:</label>
                <input type="text" name="direccion_estudiante" required>
            </div>
            <div class="input-group">
                <label>Correo:</label>
                <input type="email" name="correo_estudiante" required>
            </div>
            <div class="input-group">
                <label>Contraseña:</label>
                <input type="password" name="contrasena_estudiante" required>
            </div>
            <div class="input-group">
                <label>Grupo:</label>
                <select name="id_grupo" required>
                    <option value="">Seleccione un grupo</option>
                    <?php foreach ($grupos as $grupo): ?>
                        <option value="<?= $grupo['id_grupo'] ?>">
                            <?= htmlspecialchars($grupo['nombre_grupo']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <button type="submit">Registrar</button>
        </form>
    </div>
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
<script src="../../js/menu.js"></script>
</body>
</html>

