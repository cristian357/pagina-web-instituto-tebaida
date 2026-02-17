<?php
session_start();
@include '../../conexion/conexion.php';

// Obtener el ID del profesor desde la URL
$id = $_GET['id'] ?? null;

// Validar que se haya recibido el ID
if (!$id) {
    die("ID del profesor no proporcionado.");
}

// Obtener los datos del profesor actual
$stmt = $conexion->prepare("SELECT * FROM profesor WHERE id_profesor = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$ro = $stmt->get_result()->fetch_assoc();

// Si el formulario se ha enviado (método POST)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Capturar datos del formulario
    $nombre_profesor   = $_POST['nombre_profesor'];
    $apellido_profesor = $_POST['apellido_profesor'];
    $telefono_profesor = $_POST['telefono_profesor'];
    $correo_profesor   = $_POST['correo_profesor'];
    $contrasena_profe  = $_POST['contrasena_profe'];

    // Sentencia SQL segura para actualizar los datos del profesor
    $sql = "UPDATE profesor SET 
                nombre_profesor = ?, 
                apellido_profesor = ?, 
                telefono_profesor = ?, 
                correo_profesor = ?, 
                contrasena_profe = ?
            WHERE id_profesor = ?";

    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("sssssi", $nombre_profesor, $apellido_profesor, $telefono_profesor, $correo_profesor, $contrasena_profe, $id);

    if ($stmt->execute()) {
        echo "✅ Datos actualizados correctamente.";
        // Redirigir si quieres:
        // header("Location: usuario.php?id=$id");
        // exit;
    } else {
        echo "❌ Error al actualizar: " . $stmt->error;
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Profesor</title>
    <link rel="icon" href="../../imagenes/zipwp-image-5610-120x120.png">
    <link rel="stylesheet" href="../../diseño/diseño3.css">
</head>
<body>
    <div class="hero">
        <header>
            <div class="logo">
                <img src="../../imagenes/zipwp-image-5610-120x120.png" alt="Instituto Tebaida">
            </div>
            <h1 class="titulo">👤 Editar Perfil del Profesor</h1>
        </header>
    </div>

    <div class="back-link">
        <a href="usuario.php?id=<?= htmlspecialchars($id) ?>">← Volver al Panel</a>
    </div>

    <div class="profile-container">
        <form action="" method="post" class="profile-card">

            <label>Nombre:</label>
            <input type="text" name="nombre_profesor" value="<?= htmlspecialchars($ro['nombre_profesor']) ?>" required><br>

            <label>Apellido:</label>
            <input type="text" name="apellido_profesor" value="<?= htmlspecialchars($ro['apellido_profesor']) ?>" required><br>

            <label>Teléfono:</label>
            <input type="text" name="telefono_profesor" value="<?= htmlspecialchars($ro['telefono_profesor']) ?>" required><br>

            <label>Correo:</label>
            <input type="email" name="correo_profesor" value="<?= htmlspecialchars($ro['correo_profesor']) ?>" required><br>

            <label>Contraseña:</label>
            <input type="password" name="contrasena_profe" value="<?= htmlspecialchars($ro['contrasena_profe']) ?>" required><br>

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
