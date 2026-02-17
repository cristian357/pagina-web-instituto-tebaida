<?php
session_start();
@include '../../conexion/conexion.php';

// Verificar sesión
if (!isset($_SESSION['profesor'])) {
    header("Location:../logeo/profesor/inisio.php");
    exit();
}

// --- Validar parámetros ---
$id_suspension = $_GET['id_suspension'] ?? null;
$id_estudiante = $_GET['id_estudiante'] ?? null;
$id_grupo = $_GET['id_grupo'] ?? null;
$nombre_estudiante=$_GET['nombre_estudiante'];

if (!$id_suspension || !$id_estudiante) {
    echo "<script>
        alert('⚠️ Parámetros incompletos.');
        window.history.back();
    </script>";
    exit();
}

// --- Obtener datos actuales de la suspensión ---
$sql = "SELECT * FROM suspension WHERE id_suspension = $id_suspension";
$query = mysqli_query($conexion, $sql);
$suspension = mysqli_fetch_assoc($query);

if (!$suspension) {
    echo "<script>
        alert('❌ No se encontró la suspensión.');
        window.history.back();
    </script>";
    exit();
}

// --- Procesar actualización ---
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $motivo = mysqli_real_escape_string($conexion, $_POST['motivo'] ?? '');
    $fecha_inicio = $_POST['fecha_inicio'] ?? '';
    $fecha_fin = $_POST['fecha_fin'] ?? '';

    if ($motivo === '' || $fecha_inicio === '' || $fecha_fin === '') {
        echo "<script>alert('⚠️ Todos los campos obligatorios deben estar completos.');</script>";
    } else {
        $update = "UPDATE suspension 
                   SET motivo='$motivo', 
                       fecha_inicio='$fecha_inicio', 
                       fecha_fin='$fecha_fin'
                   WHERE id_suspension=$id_suspension";

        if (mysqli_query($conexion, $update)) {
            echo "<script>
                alert('✅ Suspensión actualizada correctamente.');
                window.location.href='./leer_supesion.php?id_estudiante=$id_estudiante&id_grupo=$id_grupo&nombre_estudiante=$nombre_estudiante';
            </script>";
            exit();
        } else {
            echo "<script>alert('❌ Error al actualizar: " . mysqli_error($conexion) . "');</script>";
        }
    }
}

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../diseño/ver_eds.css">
    <link rel="icon" href="../../imagenes/zipwp-image-5610-120x120.png">
    <title>Editar Suspensión</title>
</head>

<body>
<header>
    <div class="logo">
        <img src="../../imagenes/zipwp-image-5610-120x120.png" alt="Instituto Tebaida">
    </div>
</header>

<nav>
    <ul class="menu">
        <li><a href="../menu_grupos.php">Grupo</a></li>
        <li><a href="../../logeo/materia/registro.php">Materia</a></li>
        <li><a href="./leer_suspension.php?id_estudiante=<?= htmlspecialchars($id_estudiante) ?>&id_grupo=<?= htmlspecialchars($id_grupo) ?>">Gestión de Suspensiones</a></li>
    </ul>
</nav>

<h1 class="titulo">Editar Suspensión</h1>

<form method="POST" class="formulario-falta">
    <div class="form-container">
        <label for="motivo">📝 Motivo:</label>
        <input 
            type="text" 
            name="motivo" 
            id="motivo" 
            value="<?= htmlspecialchars($suspension['motivo']) ?>" 
            required><br>

        <label for="fecha_inicio">📅 Fecha de inicio:</label>
        <input 
            type="date" 
            name="fecha_inicio" 
            id="fecha_inicio" 
            value="<?= htmlspecialchars($suspension['fecha_inicio']) ?>" 
            required><br>

        <label for="fecha_fin">📅 Fecha de finalización:</label>
        <input 
            type="date" 
            name="fecha_fin" 
            id="fecha_fin" 
            value="<?= htmlspecialchars($suspension['fecha_fin']) ?>" 
            required><br><br>

        
        <button type="submit" name="guardar_suspension">💾 Guardar Cambios</button>
    </div>
</form>
        <button class="menu-btn" onclick="openMenu()">☰</button>

<!-- Capa oscura detrás del menú -->
<div id="overlay" class="overlay" onclick="closeMenu()"></div>

<!-- Sidebar (Menú lateral en la derecha) -->
<div id="sidebar" class="sidebar">
    <span class="close-btn" onclick="closeMenu()">×</span>
    <a href="../../logeo/pagina2.html "><b>registro</b></a>
    <a href="../../index.html">Inicio</a>
    <a href="../../Mi_Colegio.html">Mi Colegio</a>
    <a href="../../pagina1.html">Quiénes Somos</a>

</div>

<script src="../../js/menu.js"></script>
</body>
</html>
