<?php
session_start();
@include '../../conexion/conexion.php';

// Obtener el ID del estudiante desde la URL
$id = $_GET['id'] ?? null;

// Validar que se haya recibido el ID
if (!$id) {
    die("ID del estudiante no proporcionado.");
}

// Obtener los datos del estudiante actual
$re = mysqli_query($conexion, "SELECT * FROM estudiante WHERE id_estudiante = $id");
$ro = mysqli_fetch_assoc($re);

// Si el formulario se ha enviado (método POST)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Capturar datos del formulario
    $nombre_estudiante = $_POST['nombre_estudiante'];
    $apellido_estudiante = $_POST['apellido_estudiante'];
    $telefono_estudiante = $_POST['telefono_estudiante'];
    $direccion_estudiante = $_POST['direccion_estudiante'];
    $correo_estudiante = $_POST['correo_estudiante'];
    $contrasena_estudiante = $_POST['contrasena_estudiante'];
    $id_grupo = $_POST['id_grupo'];

    // Sentencia SQL para actualizar los datos del estudiante
    $sql = "UPDATE estudiante SET 
                
                nombre_estudiante = '$nombre_estudiante',
                apellido_estudiante = '$apellido_estudiante',
                telefono_estudiante = '$telefono_estudiante',
                direccion_estudiante = '$direccion_estudiante',
                correo_estudiante = '$correo_estudiante',
                contrasena_estudiante = '$contrasena_estudiante',
                id_grupo = $id_grupo
            WHERE id_estudiante = $id";

    // Ejecutar la consulta
    if (mysqli_query($conexion, $sql)) {
        echo "Datos actualizados correctamente.";
        // Redirigir si es necesario:
        // header("Location: lista_estudiantes.php");
        // exit;
    } else {
        echo "Error al actualizar: " . mysqli_error($conexion);
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Estudiante</title>
    <link rel="icon" href="../../imagenes/zipwp-image-5610-120x120.png">
    <link rel="stylesheet" href="../../diseño/diseño3.css">
</head>
<body>
    <div class="hero">
        <header>
            <div class="logo">
                <img src="../../imagenes/zipwp-image-5610-120x120.png" alt="Instituto Tebaida">
            </div>
            <h1 class="titulo">👤 Editar Perfil del Estudiante</h1>
        </header>
    </div>

     <div class="back-link">
        <a href="usuario.php?id=<?= htmlspecialchars($id) ?>">← Volver al Panel</a>
    </div>

    <div class="profile-container">
        <form action="" method="post" class="profile-card">
            

            <label>Nombre:</label>
            <input type="text" name="nombre_estudiante" value="<?= htmlspecialchars($ro['nombre_estudiante']) ?>" required><br>

            <label>Apellido:</label>
            <input type="text" name="apellido_estudiante" value="<?= htmlspecialchars($ro['apellido_estudiante']) ?>" required><br>

            <label>Teléfono:</label>
            <input type="text" name="telefono_estudiante" value="<?= htmlspecialchars($ro['telefono_estudiante']) ?>" required><br>

            <label>Dirección:</label>
            <input type="text" name="direccion_estudiante" value="<?= htmlspecialchars($ro['direccion_estudiante']) ?>" required><br>

            <label>Correo:</label>
            <input type="email" name="correo_estudiante" value="<?= htmlspecialchars($ro['correo_estudiante']) ?>" required><br>

            <label>Contraseña:</label>
            <input type="text" name="contrasena_estudiante" value="<?= htmlspecialchars($ro['contrasena_estudiante']) ?>" required><br>

            <label>ID del Grupo:</label>
            <input type="number" name="id_grupo" value="<?= htmlspecialchars($ro['id_grupo']) ?>" required><br><br>

            <button type="submit">💾 Guardar Cambios</button>
        </form>
    </div>
    <button class="menu-btn" onclick="openMenu()">☰</button>

<!-- Capa oscura detrás del menú -->
<div id="overlay" class="overlay" onclick="closeMenu()"></div>

<!-- Sidebar (Menú lateral) -->
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
