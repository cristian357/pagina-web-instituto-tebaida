<?php
session_start();
@include '../conexion/conexion.php';

if (!isset($_SESSION['estudiante'])) {
    header("Location: ../logeo/estudiante/inisio.php");
    exit();
}

$idEstudiante = $_SESSION['estudiante']['id'];
$nombreEstudiante = $_SESSION['estudiante']['nombre'];
// Consulta de anotaciones
$sqlAnotaciones = "SELECT a.fecha, a.porque, m.nombre_materia
                   FROM anotacion a
                   INNER JOIN materia m ON a.id_materia = m.id_materia
                   WHERE a.id_estudiante = :id_estudiante
                   ORDER BY a.fecha DESC";

$stmtAnotaciones = $conn->prepare($sqlAnotaciones);
$stmtAnotaciones->bindParam(':id_estudiante', $idEstudiante, PDO::PARAM_INT);
$stmtAnotaciones->execute();
$anotaciones = $stmtAnotaciones->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>anotaciones</title>
    <link rel="stylesheet" href="../diseño/mune.css">
       <link rel="icon" href="../imagenes/zipwp-image-5610-120x120.png">
</head>


<body>
    <div class="hero">
<header>
    <div class="logo">
        <img src="../imagenes/zipwp-image-5610-120x120.png" alt="Instituto Tebaida">
    </div>
 <h1 class="titulo">Sistema de notas /📌 Anotaciones</h1>
</header> 
</div>
    <nav>
        <ul class="menu">
        <nav>
            <ul>
                <li><a href="./menu_grupos.php">🔢 Notas</a></li>
                <li><a href="./faltas.php">🚫 Faltas</a></li>
            </ul>
        </nav>
    </nav>

        <section>
        <h2>📚 Anotaciones Registradas</h2>
        <?php if (count($anotaciones) > 0): ?>
            <table border="1" cellpadding="10" cellspacing="0" width="80%">
                <thead>
                    <tr>
                        <th>📅 Fecha</th>
                        <th>📚 Materia</th>
                        <th>📑 Descripción</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($anotaciones as $anotacion): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($anotacion['fecha']); ?></td>
                            <td><?php echo htmlspecialchars($anotacion['nombre_materia']); ?></td>
                            <td><?php echo htmlspecialchars($anotacion['porque']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No hay anotaciones registradas.</p>
        <?php endif; ?>
    </section>
<button class="menu-btn" onclick="openMenu()">☰</button>

<!-- Capa oscura detrás del menú -->
<div id="overlay" class="overlay" onclick="closeMenu()"></div>

<!-- Sidebar (Menú lateral) -->
<div id="sidebar" class="sidebar">
    <span class="close-btn" onclick="closeMenu()">×</span>
    <a href="../logeo/pagina2.html">Login</a>
    <a href="../index.html">Inicio</a>
    <a href="../Mi_Colegio.html">Mi Colegio</a>
    <a href="../pagina1.html">Quiénes Somos</a>
</div>

<script src="../js/menu.js"></script>
</body>
</html>