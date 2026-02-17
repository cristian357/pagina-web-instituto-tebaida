<?php
session_start();
@include '../../conexion/conexion.php'; // Debe crear $conn (PDO)

// Verificar si el profesor inició sesión
if (!isset($_SESSION['profesor'])) {
    header("Location: ../../logeo/profesor/inisio.php");
    exit();
}

$idprofesor = $_SESSION['profesor']['id'] ?? null;
$idEstudiante = $_GET['id_estudiante'] ?? null;
$id_grupo = $_GET['id_grupo'] ?? null;
$nombre_estudiante = $_GET['nombre_estudiante'] ?? 'Estudiante';

// Validar que los parámetros estén presentes
if (!$idEstudiante || !$id_grupo) {
    echo "<script>
        alert('⚠️ Faltan parámetros requeridos (estudiante o grupo).');
        window.history.back();
    </script>";
    exit();
}

// Procesar formulario al enviar
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $motivo = trim($_POST['motivo'] ?? '');
    $fecha_inicio = $_POST['fecha_inicio'] ?? date('Y-m-d');
    $fecha_fin = $_POST['fecha_fin'] ?? date('Y-m-d');

    if ($motivo === '') {
        echo "<script>alert('⚠️ Debe escribir un motivo.');</script>";
    } elseif (strtotime($fecha_fin) < strtotime($fecha_inicio)) {
        echo "<script>
            alert('⚠️ La fecha final no puede ser anterior a la inicial.');
            window.location.href = './munu_falta.php?id_estudiante=$idEstudiante&id_grupo=$id_grupo&nombre_estudiante=$nombre_estudiante';
        </script>";
        exit();
    } else {
        try {
            // Verificar duplicados el mismo día
            $verifica = $conn->prepare("
                SELECT COUNT(*) 
                FROM suspension 
                WHERE id_estudiante = :id_estudiante 
                AND fecha_inicio = :fecha_inicio
            ");
            $verifica->execute([
                ':id_estudiante' => $idEstudiante,
                ':fecha_inicio' => $fecha_inicio
            ]);

            if ($verifica->fetchColumn() > 0) {
                echo "<script>
                    alert('⚠️ Ya existe una suspensión para ese estudiante en esa fecha.');
                    window.location.href = './munu_falta.php?id_estudiante=$idEstudiante&id_grupo=$id_grupo&nombre_estudiante=$nombre_estudiante';
                </script>";
                exit();
            }

            // Guardar suspensión
            $sql = "INSERT INTO suspension 
                    (id_estudiante, motivo, fecha_inicio, fecha_fin, impuesta_por) 
                    VALUES (:id_estudiante, :motivo, :fecha_inicio, :fecha_fin, :impuesta_por)";
            $stmt = $conn->prepare($sql);
            $stmt->execute([
                ':id_estudiante' => $idEstudiante,
                ':motivo' => $motivo,
                ':fecha_inicio' => $fecha_inicio,
                ':fecha_fin' => $fecha_fin,
                ':impuesta_por' => $idprofesor
            ]);

            echo "<script>
                alert('✅ Suspensión registrada correctamente.');
                window.location.href = './leer_supesion.php?id_estudiante=$idEstudiante&id_grupo=$id_grupo&nombre_estudiante=$nombre_estudiante';
            </script>";
        } catch (PDOException $e) {
            echo "<script>
                alert('❌ Error al guardar la suspensión: " . addslashes($e->getMessage()) . "');
            </script>";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registrar Suspensión</title>
    <link rel="stylesheet" href="../../diseño/mune.css">
    <link rel="icon" href="../../imagenes/zipwp-image-5610-120x120.png">
</head>
<body>
<header>
    <div class="logo">
        <img src="../../imagenes/zipwp-image-5610-120x120.png" alt="Instituto Tebaida">
    </div>
    <h1 class="titulo">Registrar Suspensión de <?= htmlspecialchars($nombre_estudiante) ?></h1>
</header>

<nav>
    <ul class="menu">
        <li><a href="../ver_estudiantes.php?id_grupo=<?= $id_grupo ?>">← Volver al grupo</a></li>
        <li><a href="./leer_supesion.php?id_estudiante=<?= $idEstudiante ?>&nombre_estudiante=<?= urlencode($nombre_estudiante) ?>&id_grupo=<?= $id_grupo ?>">📋 Ver suspensiones</a></li>
    </ul>
</nav>

<div class="form-container">
    <form method="POST" class="formulario-anotacion">
        <label for="motivo">📝 Motivo de la suspensión:</label>
        <input type="text" name="motivo" id="motivo" maxlength="255" required>

        <label for="fecha_inicio">📅 Fecha de inicio:</label>
        <input type="date" name="fecha_inicio" id="fecha_inicio" value="<?= date('Y-m-d') ?>" required>

        <label for="fecha_fin">📅 Fecha de finalización:</label>
        <input type="date" name="fecha_fin" id="fecha_fin" value="<?= date('Y-m-d') ?>" required>

        <button type="submit" name="guardar_suspension">💾 Guardar Suspensión</button>
    </form>
</div>

<!-- Botón menú lateral -->
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
