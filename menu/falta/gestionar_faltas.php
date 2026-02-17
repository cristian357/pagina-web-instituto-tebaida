<?php
include("../../conexion/conexion.php");

// Verificar si llega el id del estudiante
if (!isset($_GET['id_estudiante']) || empty($_GET['id_estudiante'])) {
    echo "Error: no se recibió el ID del estudiante.";
    exit();
}

$idEstudiante = intval($_GET['id_estudiante']); // Evita inyección SQL

// Consulta segura de faltas del estudiante
$sql = "SELECT * FROM falta WHERE id_estudiante = $idEstudiante";
$query = mysqli_query($conexion, $sql);

if (!$query) {
    die("Error en la consulta: " . mysqli_error($conexion));
}

// Opcional: si también quieres mostrar el nombre del estudiante
$nombre_estudiante = "";
$buscar_nombre = mysqli_query($conexion, "SELECT nombre_estudiante FROM estudiante WHERE id_estudiante = $idEstudiante");
if ($buscar_nombre && mysqli_num_rows($buscar_nombre) > 0) {
    $nombre_estudiante = mysqli_fetch_assoc($buscar_nombre)['nombre_estudiante'];
}


$idGrupo = $_GET['id_grupo'] ?? null;

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Gestionar Faltas</title>
    <link rel="stylesheet" href="../../diseño/ver_eds.css">
    <link rel="icon" href="../../imagenes/zipwp-image-5610-120x120.png">
</head>
<body>
<header>
    <div class="logo">
        <img src="../../imagenes/zipwp-image-5610-120x120.png" alt="Instituto Tebaida">
    </div>
    
</header>

<nav>
    <ul class="menu">
        <li><a href="./munu_falta.php?id_estudiante=<?= htmlspecialchars($idEstudiante) ?>&id_grupo=<?= htmlspecialchars($idGrupo) ?>"><-volver</a></li>
        <li><a href="../ver_estudiantes.php?id_grupo=<?= htmlspecialchars($idGrupo) ?>">Volver al grupo</a></li>
    </ul>
</nav>
<h1 class="titulo">Faltas de <?= htmlspecialchars($nombre_estudiante) ?></h1>
<div class="table-container">
    <table>
        <tr>
            <th>Fecha</th>
            <th>Hora</th>
            <th>Acción</th>
        </tr>

        <?php if (mysqli_num_rows($query) > 0): ?>
            <?php while ($row = mysqli_fetch_assoc($query)): ?>
                <tr>
                    <td><?= htmlspecialchars($row['fecha']) ?></td>
                    <td><?= htmlspecialchars($row['hora']) ?></td>
                    <td class="acciones">
                        <a href="./editar.php?id_falta=<?= $row['id_falta'] ?>&id_estudiante=<?= $idEstudiante ?>&id_grupo=<?= $idGrupo ?>">Editar 📁</a>
                        <a href="./eliminar.php?id_falta=<?= $row['id_falta'] ?>&id_estudiante=<?= $idEstudiante ?>&id_grupo=<?= $idGrupo ?>" onclick="return confirm('¿Eliminar esta falta?')">Eliminar 🚮</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr><td colspan="3">No hay faltas registradas para este estudiante.</td></tr>
        <?php endif; ?>
    </table>
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
</div>
</body>
</html>
