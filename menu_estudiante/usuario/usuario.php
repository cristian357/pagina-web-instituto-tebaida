<?php
session_start();
@include '../../conexion/conexion.php';

// Verificar que hay sesión iniciada
if (!isset($_SESSION['estudiante'])) {
    header("Location: ../logeo/estudiante/inicio.php");
    exit();
}

// Validar que se reciba el ID por GET
$id = $_GET['id'] ?? null;
if (!$id) {
    die("ID del estudiante no proporcionado.");
}

// Consultar datos del estudiante desde la base de datos
$re = mysqli_query($conexion, "SELECT * FROM estudiante WHERE id_estudiante = $id");

// Validar que la consulta sea exitosa
if (!$re || mysqli_num_rows($re) === 0) {
    die("Estudiante no encontrado.");
}

$ro = mysqli_fetch_assoc($re);

// Datos desde la sesión (quien está logueado)
$nombreEstudiante = $_SESSION['estudiante']['nombre'];
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Perfil del Estudiante</title>
    <link rel="stylesheet" href="../../diseño/diseño3.css">
    <link rel="icon" href="../../imagenes/zipwp-image-5610-120x120.png">
</head>
<body>
    <div class="hero">
<header>
    <div class="logo">
        <img src="../../imagenes/zipwp-image-5610-120x120.png" alt="Instituto Tebaida">
    </div>
 <h1 class="titulo">👨‍🎓 Perfil del Estudiante</h1>
</header> 
</div>
    <nav>
        <ul class="menu">
         <li>
            <li><a href="./editar.php?id=<?= htmlspecialchars($id) ?>">👤 editar</a></li>
              <a href="eliminar.php?id=<?= htmlentities($id) ?>" onclick="return confirm('¿Eliminar estudiante?')">🚮Eliminar</a> 
        </ul>
    </nav>
  
    <?php
// Asumimos que ya tienes $ro con los datos del estudiante y $id definido
?>
    <div class="back-link">
        <a href="../menu_grupos.php?id=<?= htmlspecialchars($id) ?>">← Volver al Panel</a>
    </div>

<div class="profile-container">
    <div class="profile-header">
   
        <div class="profile-actions">
            <a href="editar.php?id=<?= $id ?>" class="btn edit">✏️ Editar</a>
            <a href="eliminar.php?id=<?= $id ?>" onclick="return confirm('¿Eliminar estudiante?')" class="btn delete">🗑️ Eliminar</a>
        </div>
    </div>

    <div class="profile-card">
        <table>
            <tr><th>ID</th><td><?= $ro['id_estudiante'] ?></td></tr>
            <tr><th>Nombre</th><td><?= $ro['nombre_estudiante'] ?></td></tr>
            <tr><th>Apellido</th><td><?= $ro['apellido_estudiante'] ?></td></tr>
            <tr><th>Teléfono</th><td><?= $ro['telefono_estudiante'] ?></td></tr>
            <tr><th>Dirección</th><td><?= $ro['direccion_estudiante'] ?></td></tr>
            <tr><th>Correo</th><td><?= $ro['correo_estudiante'] ?></td></tr>
            <tr><th>Grupo</th><td><?= $ro['id_grupo'] ?></td></tr>
        </table>
    </div>


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
