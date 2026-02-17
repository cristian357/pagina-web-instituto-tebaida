<?php
session_start();
@include '../conexion/conexion.php';

if (!isset($_SESSION['estudiante'])) {
    header("Location: ../logeo/estudiante/inisio.php");
    exit();
}

$idEstudiante = $_SESSION['estudiante']['id'];
$nombreEstudiante = $_SESSION['estudiante']['nombre'];
// Consulta de faltas
$sqlFaltas = "SELECT f.fecha, f.hora, f.tipo, m.nombre_materia
              FROM falta f
              INNER JOIN materia m ON f.id_materia = m.id_materia
              WHERE f.id_estudiante = :id_estudiante
              ORDER BY f.fecha DESC";

$stmtFaltas = $conn->prepare($sqlFaltas);
$stmtFaltas->bindParam(':id_estudiante', $idEstudiante, PDO::PARAM_INT);
$stmtFaltas->execute();
$faltas = $stmtFaltas->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>faltas<</title>
      <link rel="stylesheet" href="../diseño/mune.css">
</head>
<body>
        <div class="hero">
<header>
    <div class="logo">
        <img src="../imagenes/zipwp-image-5610-120x120.png" alt="Instituto Tebaida">
    </div>
     <h1 class="titulo">Sistema de notas /🚫 Faltas</h1>
</header> 
</div>
    <nav>
        <ul class="menu">
        <nav>
            <ul>
                <li><a href="./menu_grupos.php">🔢 Notas</a></li>
                <li><a href="./anotaciones.php">📌 Anotaciones</a></li>
               
            </ul>
        </nav>
    </nav>

    <section id="flata">
        <h2>🚨 Faltas Registradas</h2>
        <?php if (count($faltas) > 0): ?>
            <table border="1" cellpadding="10" cellspacing="0" width="80%">
                <thead>
                    <tr>
                        <th>📅 Fecha</th>
                        <th>⏰ Hora</th>
                        <th>📚 Materia</th>
                        <th>📌 Tipo</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($faltas as $falta): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($falta['fecha']); ?></td>
                            <td><?php echo htmlspecialchars($falta['hora']); ?></td>
                            <td><?php echo htmlspecialchars($falta['nombre_materia']); ?></td>
                            <td><?php echo htmlspecialchars($falta['tipo']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No hay faltas registradas.</p>
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