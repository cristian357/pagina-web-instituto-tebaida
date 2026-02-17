<?php
include("../../conexion/conexion.php");

// --- Verificamos que los parámetros vengan en la URL ---
if (!isset($_GET['id_falta']) || !isset($_GET['id_estudiante'])) {
    echo "<script>
        alert('⚠️ Parámetros inválidos.');
        window.history.back();
    </script>";
    exit();
}

$id_falta = intval($_GET['id_falta']);
$id_estudiante = intval($_GET['id_estudiante']);
$idGrupo=intval($_GET['id_grupo']);

// --- Si el formulario se envía (método POST) ---
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $fecha = trim($_POST['fecha']);
    $hora = trim($_POST['hora']);
    $tipo = trim($_POST['tipo']);

    // Validación básica
    if (empty($fecha) || empty($hora) || empty($tipo)) {
        echo "<script>alert('⚠️ Error: Todos los campos son obligatorios.');</script>";
    } else {
        // --- Evitar inyección SQL ---
        $fecha = mysqli_real_escape_string($conexion, $fecha);
        $hora = mysqli_real_escape_string($conexion, $hora);
        $tipo = mysqli_real_escape_string($conexion, $tipo);

        // --- Actualizar la falta ---
        $actualizar = "
            UPDATE falta 
            SET fecha = '$fecha', hora = '$hora', tipo = '$tipo'
            WHERE id_falta = $id_falta
        ";

        $query = mysqli_query($conexion, $actualizar);

        if ($query) {
            echo "<script>
                alert('✅ La falta fue actualizada correctamente.');
                window.location.href = './gestionar_faltas.php?id_estudiante=$id_estudiante';
            </script>";
            exit();
        } else {
            echo "<script>alert('❌ Error al actualizar la falta.');</script>";
        }
    }
}

// --- Consultar la falta actual para mostrar los valores en el formulario ---
$sql = "SELECT * FROM falta WHERE id_falta = $id_falta";
$result = mysqli_query($conexion, $sql);

if (!$result || mysqli_num_rows($result) === 0) {
    echo "<script>
        alert('⚠️ No se encontró la falta seleccionada.');
        window.history.back();
    </script>";
    exit();
}

$falta = mysqli_fetch_assoc($result);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../diseño/ver_eds.css">
    <link rel="icon" href="../../imagenes/zipwp-image-5610-120x120.png">
    <title>Editar Falta</title>
</head>

<body>
<header>
    <div class="logo">
        <img src="../../imagenes/zipwp-image-5610-120x120.png" alt="Instituto Tebaida">
    </div>
</header>

<nav>
    <ul class="menu">
        <li><a href="../ver_estudiantes.php?id_grupo=<?= htmlspecialchars($idGrupo) ?>">Volver al grupo</a></li>
        <li><a href="./gestionar_faltas.php?id_estudiante=<?= htmlspecialchars($id_estudiante) ?>&id_grupo=<?= htmlspecialchars($idGrupo) ?>"><-volver</a></li>
    </ul>
</nav>

<h1 class="titulo">Editar Falta</h1>

<form method="POST" class="formulario-falta">
    <div class="form-container">

        <!-- Fecha -->
        <label for="fecha">📅 Fecha:</label>
        <input 
            type="date" 
            name="fecha" 
            id="fecha" 
            value="<?= htmlspecialchars($falta['fecha']) ?>" 
            required
        ><br>

        <!-- Hora -->
        <label for="hora">⏰ Hora:</label>
        <input 
            type="text" 
            name="hora" 
            id="hora" 
            value="<?= htmlspecialchars($falta['hora']) ?>" 
            required
        ><br>

        <!-- Tipo de falta -->
        <label for="tipo">🚫 Tipo de falta:</label>
        <select name="tipo" id="tipo" required>
            <option value="">Seleccione...</option>
            <option value="Inasistencia" <?= ($falta['tipo'] === 'Inasistencia') ? 'selected' : '' ?>>Inasistencia</option>
            <option value="Llegada tarde" <?= ($falta['tipo'] === 'Llegada tarde') ? 'selected' : '' ?>>Llegada tarde</option>
            <option value="Justificada" <?= ($falta['tipo'] === 'Justificada') ? 'selected' : '' ?>>Justificada</option>
        </select><br><br>

        <button type="submit" name="guardar_falta">💾 Guardar Cambios</button>
    </div>
</form>
<button class="menu-btn" onclick="openMenu()">☰</button>
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
