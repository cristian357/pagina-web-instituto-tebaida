<?php
session_start();
@include '../conexion/conexion.php';

// Verificar que el profesor haya iniciado sesión
if (!isset($_SESSION['profesor'])) {
    header("Location: ../logeo/profesor/inisio.php");
    exit();
}

$idProfesor = $_SESSION['profesor']['id'] ?? null;

// Validar conexión PDO
if (!$conn) {
    die("❌ Error: No se pudo conectar a la base de datos.");
}

// Consulta optimizada:
// - Muestra solo un grupo por fila (sin repetir).
// - Usa DISTINCT para evitar duplicados.
// - Relaciona correctamente las materias con el profesor.
$sql = "SELECT DISTINCT g.id_grupo, g.nombre_grupo
        FROM grupo g
        INNER JOIN materia m ON g.id_grupo = m.grado_materia
        WHERE m.id_profesor = :id_profesor
        ORDER BY g.nombre_grupo ASC";

$stmt = $conn->prepare($sql);
$stmt->bindParam(':id_profesor', $idProfesor, PDO::PARAM_INT);
$stmt->execute();
$grupos = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mis Grupos</title>
    <link rel="stylesheet" href="../diseño/grupos.css">
    <link rel="icon" href="../imagenes/zipwp-image-5610-120x120.png">
</head>
<body>
<header>
    <div class="logo">
        <img src="../imagenes/zipwp-image-5610-120x120.png" alt="Instituto Tebaida">
    </div>
</header>

<nav>
    <ul class="menu">
        <li><a href="../logeo/grupo/registro.php">Nuevo grupo</a></li>
        <li><a href="../logeo/materia/leer.php">Materias</a></li>
       
    </ul>
</nav>

<br>
<center><h1>Bienvenido, <?php echo htmlspecialchars($_SESSION['profesor']['nombre']); ?></h1></center>

<!-- Botón para abrir el menú -->
<button class="menu-btn" onclick="openMenu()">☰</button>

<!-- Capa oscura detrás del menú -->
<div id="overlay" class="overlay" onclick="closeMenu()"></div>

<!-- Sidebar (Menú lateral) -->
<div id="sidebar" class="sidebar">
    <span class="close-btn" onclick="closeMenu()">×</span>
    <a href="../logeo/pagina2.html">Login</a>
    <a href="../inicio.html">Inicio</a>
    <a href="../Mi_Colegio.html">Mi Colegio</a>
    <a href="../pagina1.html">Quiénes Somos</a>
</div>

<script src="../js/menu.js"></script>

<h2>Selecciona un grupo:</h2>

<div class="list">
    <?php if (count($grupos) > 0): ?>
        <?php foreach ($grupos as $grupo): ?>
            <div class="card">
                <a href="ver_estudiantes.php?id_grupo=<?php echo $grupo['id_grupo']; ?>">
                    <?php echo htmlspecialchars($grupo['nombre_grupo']); ?>
                </a>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p>No tienes grupos asignados aún.</p>
    <?php endif; ?>
</div>

</body>
</html>
