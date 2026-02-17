<?php
session_start();
require_once '../../conexion/conexion.php'; 

$idGrupo = $_GET['id_grupo'] ?? null;



// Obtener grados disponibles
$grados = $conn->query("SELECT DISTINCT id_grupo FROM estudiante ORDER BY id_grupo")->fetchAll(PDO::FETCH_ASSOC);

// Inicializar variables
$gradoSeleccionado = $_POST['grado'] ?? '';
$id_estudiante = $_POST['id_estudiante'] ?? '';
$idMateria = $_POST['id_materia'] ?? '';
$periodoSeleccionado = $_POST['periodo'] ?? '';
$promedio = null;
$nombre_estudiante = '';

// Obtener estudiantes del grado seleccionado
$estudiantes = [];
if ($gradoSeleccionado) {
    $stmt_est = $conn->prepare("SELECT id_estudiante, nombre_estudiante FROM estudiante WHERE id_grupo = :grado ORDER BY nombre_estudiante");
    $stmt_est->execute([':grado' => $gradoSeleccionado]);
    $estudiantes = $stmt_est->fetchAll(PDO::FETCH_ASSOC);
}

// Obtener materias
$materias = $conn->query("SELECT id_materia, nombre_materia FROM materia ORDER BY nombre_materia")->fetchAll(PDO::FETCH_ASSOC);

// Obtener nombre del estudiante
if ($id_estudiante) {
    $stmt_nombre = $conn->prepare("SELECT nombre_estudiante FROM estudiante WHERE id_estudiante = :id");
    $stmt_nombre->execute([':id' => $id_estudiante]);
    $estudiante = $stmt_nombre->fetch(PDO::FETCH_ASSOC);
    $nombre_estudiante = $estudiante['nombre_estudiante'] ?? '';
}

// Calcular promedio si se envió el formulario completo
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $id_estudiante && $idMateria && $periodoSeleccionado) {
    try {
        $stmt_prom = $conn->prepare("SELECT AVG(nota) as promedio FROM nota 
                                     WHERE id_estudiante = :id_estudiante 
                                       AND id_materia = :id_materia 
                                       AND periodo = :periodo");
        $stmt_prom->execute([
            ':id_estudiante' => $id_estudiante,
            ':id_materia' => $idMateria,
            ':periodo' => $periodoSeleccionado
        ]);
        $resultado = $stmt_prom->fetch(PDO::FETCH_ASSOC);
        $promedio = $resultado['promedio'];

        if ($promedio !== null) {
            $stmt_check = $conn->prepare("SELECT id FROM nota_final 
                                          WHERE id_estudiante = :id_estudiante 
                                            AND id_materia = :id_materia 
                                            AND periodo = :periodo");
            $stmt_check->execute([
                ':id_estudiante' => $id_estudiante,
                ':id_materia' => $idMateria,
                ':periodo' => $periodoSeleccionado
            ]);

            if ($stmt_check->rowCount() > 0) {
                // Actualizar si ya existe
                $stmt_update = $conn->prepare("UPDATE nota_final 
                                               SET promedio = :promedio, fecha_registro = NOW()
                                               WHERE id_estudiante = :id_estudiante 
                                                 AND id_materia = :id_materia 
                                                 AND periodo = :periodo");
                $stmt_update->execute([
                    ':promedio' => $promedio,
                    ':id_estudiante' => $id_estudiante,
                    ':id_materia' => $idMateria,
                    ':periodo' => $periodoSeleccionado
                ]);
            } else {
                // Insertar nuevo
                $stmt_insert = $conn->prepare("INSERT INTO nota_final 
                                               (id_estudiante, id_materia, periodo, promedio) 
                                               VALUES (:id_estudiante, :id_materia, :periodo, :promedio)");
                $stmt_insert->execute([
                    ':id_estudiante' => $id_estudiante,
                    ':id_materia' => $idMateria,
                    ':periodo' => $periodoSeleccionado,
                    ':promedio' => $promedio
                ]);
            }

            echo "<script>alert('✅ Promedio registrado correctamente: " . number_format($promedio, 2) . "');</script>";
        } else {
            echo "<script>alert('❌ No hay notas para este estudiante en ese período.');</script>";
        }
    } catch (PDOException $e) {
        echo "<script>alert('Error en la base de datos: " . htmlspecialchars($e->getMessage()) . "');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <title>Seleccionar Periodo - Notas</title>
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
            <li><a href="../menu_grupos.php">Grupo</a></li>
            <li><a href="../../logeo/materia/registro.php">Materia</a></li>
            <li><a  style="color:black;">📝 Sacar nota definitiva</a></li>
        </ul>
    </nav>
 

<div class="table-container">
    <h2>Notas finales de los estudiantes</h2>
    
    <form method="POST">
        <table>
            <tr>
                <td><label for="grado">Seleccione grado:</label></td>
                <td>
                    <select name="grado" id="grado" required onchange="this.form.submit()">
                        <option value="">-- Seleccione grado --</option>
                        <?php foreach ($grados as $g): ?>
                            <option value="<?= $g['id_grupo']; ?>" <?= ($gradoSeleccionado == $g['id_grupo']) ? 'selected' : ''; ?>>
                                Grado <?= htmlspecialchars($g['id_grupo']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </td>
            </tr>

            <tr>
                <td><label for="id_estudiante">Seleccione estudiante:</label></td>
                <td>
                    <select name="id_estudiante" id="id_estudiante" required>
                        <option value="">-- Seleccione estudiante --</option>
                        <?php foreach ($estudiantes as $est): ?>
                            <option value="<?= $est['id_estudiante']; ?>" <?= ($id_estudiante == $est['id_estudiante']) ? 'selected' : ''; ?>>
                                <?= htmlspecialchars($est['nombre_estudiante']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </td>
            </tr>

            <tr>
                <td><label for="id_materia">Seleccione materia:</label></td>
                <td>
                    <select name="id_materia" id="id_materia" required>
                        <option value="">-- Seleccione materia --</option>
                        <?php foreach ($materias as $materia): ?>
                            <option value="<?= $materia['id_materia']; ?>" <?= ($idMateria == $materia['id_materia']) ? 'selected' : ''; ?>>
                                <?= htmlspecialchars($materia['nombre_materia']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </td>
            </tr>

            <tr>
                <td><label for="periodo">Seleccione período:</label></td>
                <td>
                    <select name="periodo" id="periodo" required>
                        <option value="">-- Seleccione período --</option>
                        <?php for ($i = 1; $i <= 4; $i++): ?>
                            <option value="<?= $i; ?>" <?= ($periodoSeleccionado == "$i") ? 'selected' : ''; ?>>
                                Periodo <?= $i; ?>
                            </option>
                        <?php endfor; ?>
                    </select>
                </td>
            </tr>
        </table>
        <br>
        <button type="submit">Mostrar promedio</button>
    </form>

    <?php if ($promedio !== null): ?>
        <h3>Promedio período <?= htmlspecialchars($periodoSeleccionado); ?>: <?= number_format($promedio, 2); ?></h3>
    <?php endif; ?>
    <!-- Botón de menú -->
<button class="menu-btn" onclick="openMenu()">☰</button>

<!-- Overlay y menú lateral -->
<div id="overlay" class="overlay" onclick="closeMenu()"></div>
<div id="sidebar" class="sidebar">
    <span class="close-btn" onclick="closeMenu()">×</span>
    <a href="../../logeo/pagina2.html">Login</a>
    <a href="../../index.html">Inicio</a>
    <a href="../../Mi_Colegio.html">Mi Colegio</a>
    <a href="../../pagina1.html">Quiénes Somos</a>
</div>

<!-- Scripts -->
<script src="../../js/menu.js"></script>
</div>
</body>
</html>
