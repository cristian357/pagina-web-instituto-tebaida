<?php
session_start();
@include '../../conexion/conexion.php';

if (!isset($_SESSION['profesor'])) {
    header("Location: ../../logeo/profesor/login.php");
    exit();
}

$idGrupo = $_GET['id_grupo'] ?? null;
$idEstudiante = $_GET['id_estudiante'] ?? null;

if (!$idGrupo || !$idEstudiante) {
    echo "Grupo o estudiante no válidos.";
    exit();
}

$idProfesor = $_SESSION['profesor']['id'];

$sql = "SELECT e.id_estudiante, e.nombre_estudiante, m.nombre_materia, m.id_materia, n.periodo, n.nota, n.id_nota
        FROM nota n
        INNER JOIN estudiante e ON e.id_estudiante = n.id_estudiante
        INNER JOIN materia m ON m.id_materia = n.id_materia
        WHERE m.id_profesor = :id_profesor
          AND e.id_grupo = :id_grupo
          AND e.id_estudiante = :id_estudiante
          AND n.es_recuperacion = 0
        ORDER BY m.nombre_materia, e.nombre_estudiante, n.periodo";


$stmt = $conn->prepare($sql);
$stmt->bindParam(':id_profesor', $idProfesor, PDO::PARAM_INT);
$stmt->bindParam(':id_grupo', $idGrupo, PDO::PARAM_INT);
$stmt->bindParam(':id_estudiante', $idEstudiante, PDO::PARAM_INT);
$stmt->execute();

$notas = $stmt->fetchAll(PDO::FETCH_ASSOC);



?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Notas</title>
    <link rel="stylesheet" href="../../diseño/nota.css">
    
</head>
<body>
    <header>
         <div class="logo">
            <img src="../../imagenes/zipwp-image-5610-120x120.png" alt="Instituto Tebaida">
        </div>
        <h1 class="titulo">Instituto Tebaida - Panel Profesor</h1>
    </header>
   <nav>
  <ul class="menu">
    <li><a href="../menu_grupos.php">Grupo</a></li>
    <li><a href="../../logeo/materia/registro.php">Materia</a></li>
    <li><a href="../ver_estudiantes.php?id_grupo=<?= htmlspecialchars($idGrupo) ?>"><-volver</a></li>
    <li><a href="../nota_final/nota_final.php?id_grupo=<?= $idGrupo ?>">📝 Sacar nota definitiva</a>
</li>
  </ul>
</nav>

 

    <div class="container">

        <?php if (isset($_GET['success']) && $_GET['success'] == 1): ?>
            <div class="success-message">
                ✅ La validación del código fue correcta .
            </div>
        <?php endif; ?>

        <h2>✏️ Editar Notas y Agregar Recuperaciones</h2>

        <form action="guardar_notas.php" method="POST">
            <table>
                <thead>
                    <tr>
                        <th>Estudiante</th>
                        <th>Materia</th>
                        <th>Periodo</th>
                        <th>Nota</th>
                        <th>Recuperación</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($notas) === 0): ?>
                        <tr>
                            <td colspan="5" style="text-align:center;">No se encontraron notas para este estudiante en el grupo seleccionado.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($notas as $nota): ?>
                            <tr>
                                <td><?= htmlspecialchars($nota['nombre_estudiante']) ?></td>
                                <td><?= htmlspecialchars($nota['nombre_materia']) ?></td>
                                <td><?= htmlspecialchars($nota['periodo']) ?></td>
                                <td>
                                    <input type="number" name="nota[<?= $nota['id_nota'] ?>]" step="0.01" value="<?= $nota['nota'] ?>" min="0" max="5" required>
                                </td>
                                <td>
                                    <input type="number" name="recuperacion[<?= $nota['id_nota'] ?>]" step="0.01" min="0" max="5" placeholder="Opcional">
                                </td>
                                <td>
                                        <a href="eliminar.php?id=<?= urlencode($nota['id_nota']) ?>&id_grupo=<?= urlencode($idGrupo) ?>&id_estudiante=<?= urlencode($idEstudiante) ?>" 
                                        onclick="return confirm('¿Eliminar esta nota?')" 
                                      class="btn delete">🗑️ Eliminar</a>
                                </td>

                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>

            <input type="hidden" name="id_grupo" value="<?= htmlspecialchars($idGrupo) ?>">
            <input type="hidden" name="id_estudiante" value="<?= htmlspecialchars($idEstudiante) ?>">

            <button type="submit">💾 Guardar Cambios</button>
        </form>
    </div>
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
</body>

</html>
