<?php
session_start();
@include '../../conexion/conexion.php';

if (!isset($_SESSION['profesor'])) {
    header("Location:../../logeo/profesor/inisio.php");
    exit();
}

// Variables del profesor y estudiante
$id_profesor = $_SESSION['profesor']['id'];
$idGrupo = isset($_GET['id_grupo']) ? intval($_GET['id_grupo']) : null;
$idEstudiante = isset($_GET['id_estudiante']) ? intval($_GET['id_estudiante']) : null;
$nombre_estudiante = isset($_GET['nombre_estudiante']) ? htmlspecialchars($_GET['nombre_estudiante']) : "Estudiante";

// ✅ Aquí definimos una conexión PDO si tu archivo usa solo mysqli
// (Para evitar errores, unificamos a PDO)
try {
    $conn = new PDO("mysql:host=localhost;dbname=colegio;charset=utf8", "root", "");
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Error en la conexión: " . $e->getMessage());
}

// Cargar las materias del profesor
$sql = "SELECT * FROM materia WHERE id_profesor = :id_profesor";
$stmt = $conn->prepare($sql);
$stmt->execute([':id_profesor' => $id_profesor]);
$materias = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Verificar si se envió el formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['guardar_falta'])) {
    $fecha = $_POST['fecha'] ?? date('Y-m-d');
    $hora = $_POST['hora'] ?? '';
    $tipo = $_POST['tipo'] ?? '';
    $idMateria = $_POST['id_materia'] ?? '';

    // Validaciones
    if (empty($idMateria)) {
        echo "<script>alert('⚠️ Debe seleccionar una materia.');</script>";
    } elseif ($tipo === '' || $hora === '') {
        echo "<script>alert('⚠️ Por favor seleccione la hora y el tipo de falta.');</script>";
    } else {
        // Inserción segura
        $sqlInsert = "INSERT INTO falta (fecha, hora, tipo, id_materia, id_estudiante)
                      VALUES (:fecha, :hora, :tipo, :id_materia, :id_estudiante)";
        $stmtInsert = $conn->prepare($sqlInsert);
        $stmtInsert->execute([
            ':fecha' => $fecha,
            ':hora' => $hora,
            ':tipo' => $tipo,
            ':id_materia' => $idMateria,
            ':id_estudiante' => $idEstudiante
        ]);

        echo "<script>alert('✅ Falta registrada correctamente');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registrar Falta</title>
    <link rel="stylesheet" href="../../diseño/mune.css">
    <link rel="icon" href="../../imagenes/zipwp-image-5610-120x120.png">
</head>
<body>
<header>
    <div class="logo">
        <img src="../../imagenes/zipwp-image-5610-120x120.png" alt="Instituto Tebaida">
    </div>
    <h1 class="titulo">Registrar Falta de <?= htmlspecialchars($nombre_estudiante) ?></h1>
</header>

<nav>
    <ul class="menu">
        <li><a href="../menu_grupos.php">Grupo</a></li>
        <li><a href="../../logeo/materia/registro.php">Materia</a></li>
        <li><a href="../ver_estudiantes.php?id_grupo=<?= htmlspecialchars($idGrupo) ?>">Volver al grupo</a></li>
        <li><a href="../nota_final/nota_final.php">📝 Nota definitiva</a></li>
        <li><a href="./gestionar_faltas.php?id_estudiante=<?= htmlspecialchars($idEstudiante) ?>&id_grupo=<?= htmlspecialchars($idGrupo) ?>">Gestión de Faltas</a></li>
    </ul>
</nav>

<!-- FORMULARIO -->
<form method="POST" class="formulario-falta">
    <center><h1>Registrar Falta de <?= htmlspecialchars($nombre_estudiante) ?></h1></center>

    <div class="form-container">
        <!-- Fecha -->
        <label for="fecha">📅 Fecha:</label>
        <input type="date" name="fecha" id="fecha" value="<?= date('Y-m-d') ?>" required><br>

        <!-- Rango de hora -->
        <label for="hora">⏰ Hora (bloque actual):</label>
        <input type="range" id="hora" name="hora_range" min="0" max="14" step="1" value="0"><br>
        <output id="hora-valor">Detectando hora...</output>
        <input type="hidden" name="hora" id="hora_real" required><br>

        <!-- Tipo de falta -->
        <label for="tipo">🚫 Tipo de falta:</label>
        <select name="tipo" id="tipo" required>
            <option value="">Seleccione...</option>
            <option value="Inasistencia">Inasistencia</option>
            <option value="Llegada tarde">Llegada tarde</option>
            <option value="Justificada">Justificada</option>
        </select><br><br>

        <!-- Materia -->
        <label for="id_materia">📘 Materia:</label>
        <select name="id_materia" id="id_materia" required>
            <option value="">Seleccione una materia...</option>
            <?php foreach ($materias as $row): ?>
                <option value="<?= $row['id_materia'] ?>"><?= htmlspecialchars($row['nombre_materia']) ?></option>
            <?php endforeach; ?>
        </select><br><br>

        <!-- Botón -->
        <button type="submit" name="guardar_falta">💾 Guardar Falta</button>
    </div>
</form>

<!-- Menú lateral -->
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
<script src="../../js/falta.js"></script>
</body>
</html>
