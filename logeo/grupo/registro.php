
<?php
@include '../../conexion/conexion.php'; // Cambié @include por require para asegurar que el archivo se incluya correctamente

if (!isset($conn)) {
    die("<script>alert('❌ Error: No hay conexión con la base de datos.');</script>");
}
// ¿Quién eres en este teatro: la persona o el personaje?
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_grupo = isset($_POST['id_grupo']) ? trim($_POST['id_grupo']) : null;
    $nombre_grupo = isset($_POST['nombre_grupo']) ? trim($_POST['nombre_grupo']) : null;


    // Validar que no haya campos vacíos
    if (empty($id_grupo) || empty($nombre_grupo) ) {
        echo "<script>alert('❌ Todos los campos son obligatorios.');</script>";
        exit();
    }

    try {
        // Verificar si el ID de grupo ya existe
        $sql_check_id = "SELECT COUNT(*) FROM grupo WHERE id_grupo = :id_grupo";
        $stmt_check_id = $conn->prepare($sql_check_id);
        $stmt_check_id->bindValue(':id_grupo', $id_grupo, PDO::PARAM_INT);
        $stmt_check_id->execute();

        if ($stmt_check_id->fetchColumn() > 0) {
            echo "<script>alert('❌ Error: El ID ya está registrado. Usa otro.');</script>";
            exit();
        }

        // Insertar nueva grupo en la base de datos
        $sql_insert = "INSERT INTO grupo (id_grupo, nombre_grupo) 
                       VALUES (:id_grupo, :nombre_grupo)";
        $stmt_insert = $conn->prepare($sql_insert);
        $stmt_insert->bindValue(':id_grupo', $id_grupo, PDO::PARAM_INT);
        $stmt_insert->bindValue(':nombre_grupo', $nombre_grupo, PDO::PARAM_STR);
        $stmt_insert->execute();

        echo "<script>alert('✅ gruporegistrada con éxito.');</script>";

    } catch (Exception $e) {
        echo "<script>alert('❌ Error: " . $e->getMessage() . "');</script>";
    }
}
// que diferencia ay de estar muerto y estar vivo niguna
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Materia</title>
    <link rel="stylesheet" href="../../diseño/diseño1.css">
    <link rel="icon" href="../../imagenes/images.png">
</head>
<body>
          <video autoplay muted loop class="video-fondo">
    <source src="../../imagenes/video/WhatsApp Video 2025-09-18 at 7.19.09 PM.mp4" type="video/mp4">
    Tu navegador no soporta video HTML5.
  </video>
<div class="login-container">
    <h2>Registro de Materia</h2>
    <form action="" method="POST">
        <div class="input-group">
            <label>ID grupo:</label>
            <input type="number" name="id_grupo" required>
        </div>
        <div class="input-group">
            <label>Nombre del  grupo:</label>
            <input type="text" name="nombre_grupo" required>
        </div>

        <button type="submit">Registrar</button>
        <br> <br> <a href="../../menu/menu_grupos.php">Volver</a>
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
