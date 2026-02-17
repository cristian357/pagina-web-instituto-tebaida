<?php
session_start();

// Verificar sesión
if (!isset($_SESSION['estudiante'])) {
    header("Location: ../logeo/estudiante/inicio.php");
    exit();
}

$idEstudiante = $_SESSION['estudiante']['id'];
$nombreEstudiante = $_SESSION['estudiante']['nombre'];


// Conexión
@include '../conexion/conexion.php';
if (!$conn) {
    die("Error de conexión a la base de datos.");
}

// Consulta desde la tabla `nota`
$sql = "SELECT 
            m.nombre_materia, 
            n.periodo, 
            ROUND(AVG(CASE WHEN n.es_recuperacion = 0 THEN n.nota END), 2) AS promedio,
            MAX(CASE WHEN n.es_recuperacion = 1 THEN n.nota END) AS recuperacion
        FROM 
            nota n
        INNER JOIN 
            materia m ON n.id_materia = m.id_materia
        WHERE 
            n.id_estudiante = :id_estudiante
        GROUP BY 
            m.nombre_materia, n.periodo
        ORDER BY 
            m.nombre_materia, n.periodo";

$stmt = $conn->prepare($sql);
$stmt->bindParam(':id_estudiante', $idEstudiante, PDO::PARAM_INT);

if (!$stmt->execute()) {
    $error = $stmt->errorInfo();
    die("Error al ejecutar la consulta: " . $error[2]);
}

$notas = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Agrupar notas por materia
$materiasAgrupadas = [];
foreach ($notas as $nota) {
    $materia = $nota['nombre_materia'];
    $periodo = (int)$nota['periodo'];
    $materiasAgrupadas[$materia][$periodo] = [
        'promedio' => $nota['promedio'],
        'recuperacion' => $nota['recuperacion']
    ];
}

// Calcular nota definitiva por materia
foreach ($materiasAgrupadas as $materia => &$periodos) {
    $suma = 0;
    $num = 0;
    foreach ($periodos as $p => $datos) {
        if (isset($datos['promedio'])) {
            $suma += $datos['promedio'];
            $num++;
        }
    }
    $periodos['nota_definitiva'] = $num > 0 ? round($suma / $num, 2) : '-';
}
unset($periodos);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <title>Notas del Estudiante</title>
    <link rel="stylesheet" href="../diseño/mune.css" />
     <link rel="icon" href="../imagenes/zipwp-image-5610-120x120.png">
</head>
<body>
<div class="hero">
<header>
    <div class="logo">
        <img src="../imagenes/zipwp-image-5610-120x120.png" alt="Instituto Tebaida">
    </div>
 <h1 class="titulo">Sistema de notas /🔢 Notas</h1>
</header> 
</div>
    <nav>
        <ul class="menu">
         <li>
            <li><a href="./usuario/usuario.php?id=<?= htmlspecialchars($idEstudiante) ?>">👤 usuario</a></li>
                <li><a href="./anotaciones.php">📌 Anotaciones</a></li>
                <li><a href="./faltas.php">🚫 Faltas</a></li>
        </ul>
    </nav>


<main class="container">

    <section id="notas">
        <h2>📚 Notas de <?php echo htmlspecialchars($nombreEstudiante); ?></h2>

        <?php if (count($materiasAgrupadas) > 0): ?>
            <div class="tabla-responsive">
<table border="1" cellpadding="5" cellspacing="0">
        <thead>
            <tr>
                <th rowspan="2">Asignatura</th>
                <th colspan="2">Periodo 1</th>
                <th colspan="2">Periodo 2</th>
                <th colspan="2">Periodo 3</th>
                <th colspan="2">Periodo 4</th>
                <th rowspan="2">Nota Final</th>
            </tr>
            <tr>
                <th>Nota</th>
                <th>Rec.</th>
                <th>Nota</th>
                <th>Rec.</th>
                <th>Nota</th>
                <th>Rec.</th>
                <th>Nota</th>
                <th>Rec.</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($materiasAgrupadas as $materia => $periodos): ?>
                <tr>
                    <td><?php echo htmlspecialchars($materia); ?></td>
                    <?php for ($p = 1; $p <= 4; $p++): ?>
                        <td>
                            <?php 
                                echo isset($periodos[$p]['promedio']) ? $periodos[$p]['promedio'] : '-';
                            ?>
                        </td>
                        <td>
                            <?php 
                                echo isset($periodos[$p]['recuperacion']) ? $periodos[$p]['recuperacion'] : '-';
                            ?>
                        </td>
                    <?php endfor; ?>
                    <td><?php echo $periodos['nota_definitiva']; ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
            </div>
        <?php else: ?>
            <p>No hay notas registradas.</p>
        <?php endif; ?>

        <!-- Botón menú -->
        <button class="menu-btn" onclick="openMenu()">☰</button>
        <div id="overlay" class="overlay" onclick="closeMenu()"></div>
        <div id="sidebar" class="sidebar">
            <span class="close-btn" onclick="closeMenu()">×</span>
            <a href="../logeo/pagina2.html">Login</a>
            <a href="../index.html">Inicio</a>
            <a href="../Mi_Colegio.html">Mi Colegio</a>
            <a href="../pagina1.html">Quiénes Somos</a>
        </div>

    </section>
</main>

<script src="../js/menu.js"></script>

</body>
</html>
