<?php
session_start();
@include '../../conexion/conexion.php';

if (!isset($_SESSION['profesor'])) {
    header("Location: ../../logeo/profesor/inisio.php");
    exit();
}

$idProfesor = $_SESSION['profesor']['id'];
$idEstudiante = $_GET['id_estudiante'] ?? null;
$idGrupo = $_GET['id_grupo'] ?? null;
$nombre_estudiante = $_GET['nombre_estudiante'] ?? null;

// Obtener materias del profesor
$sqlMaterias = "SELECT id_materia, nombre_materia FROM materia WHERE id_profesor = :id_profesor";
$stmtMaterias = $conn->prepare($sqlMaterias);
$stmtMaterias->execute([':id_profesor' => $idProfesor]);
$materias = $stmtMaterias->fetchAll(PDO::FETCH_ASSOC);

$idMateria = $_POST['id_materia'] ?? null;

// Verificar si el estudiante está suspendido actualmente
$estaSuspendido = false;
$suspInfo = [];
$hoy = date('Y-m-d');

if ($idEstudiante) {
    $stmtSusp = $conn->prepare("SELECT fecha, duracion FROM anotacion WHERE id_estudiante = :est");
    $stmtSusp->execute([':est' => $idEstudiante]);

    while ($row = $stmtSusp->fetch(PDO::FETCH_ASSOC)) {
        if ($hoy >= $row['fecha'] && $hoy <= $row['duracion']) {
            $estaSuspendido = true;
            $suspInfo = $row;
            break;
        }
    }
}

// Procesar formulario
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['guardar_notas'])) {
    if ($estaSuspendido) {
        echo "<script>alert('🚫 El estudiante está suspendido. No se pueden registrar notas.');</script>";
        exit();
    }

    if ($idMateria) {
        $notas = $_POST['notas'];
        $periodos = $_POST['periodos'];

        foreach ($notas as $i => $notasDelPeriodo) {
            if (!isset($periodos[$i])) continue;
            $periodo = $periodos[$i];

            foreach ($notasDelPeriodo as $nota) {
                if ($nota === '') continue;

                try {
                    $sql = "INSERT INTO nota (id_estudiante, id_materia, nota, periodo) 
                            VALUES (:id_estudiante, :id_materia, :nota, :periodo)";
                    $stmt = $conn->prepare($sql);
                    $stmt->execute([
                        ':id_estudiante' => $idEstudiante,
                        ':id_materia' => $idMateria,
                        ':nota' => $nota,
                        ':periodo' => $periodo
                    ]);
                } catch (PDOException $e) {
                    echo "<script>alert('❌ Error al guardar: " . $e->getMessage() . "');</script>";
                }
            }
        }

        echo "<script>alert('✅ Notas guardadas correctamente');</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registrar Notas</title>
    <link rel="stylesheet" href="../../diseño/mune.css">
    <link rel="icon" href="../../imagenes/zipwp-image-5610-120x120.png">
</head>
<body>
<header>
    <div class="logo">
        <img src="../../imagenes/zipwp-image-5610-120x120.png" alt="Instituto Tebaida">
    </div>
</header>
     <nav >
            <ul class="menu">
    <li><a href="../menu_grupos.php">Grupo</a></li>
            <li><a href="../../logeo/materia/registro.php">Materia</a></li>
           <li><a href="../ver_estudiantes.php?id_grupo=<?= htmlspecialchars($idGrupo)?>">grupo</a></li>
           <li><a href="../nota_final/nota_final.php?id_grupo=<?= $idGrupo ?>">📝 Sacar nota definitiva</a>
</li>
            </ul>
        </nav>
<br>
<center><h1 >Bienvenido, <?php echo htmlspecialchars($_SESSION['profesor']['nombre']); ?></h1> </center> 

<form method="POST" <?= $estaSuspendido ? 'onsubmit="return false;"' : '' ?>>
    <label for="id_materia">📘 Seleccione la materia:</label>
    <select name="id_materia" required>
        <option value="">-- Seleccione materia --</option>
        <?php foreach ($materias as $materia): ?>
            <option value="<?= $materia['id_materia']; ?>" <?= ($idMateria == $materia['id_materia']) ? 'selected' : '' ?>>
                <?= htmlspecialchars($materia['nombre_materia']); ?>
            </option>
        <?php endforeach; ?>
    </select>
    <br><br>

    <table border="1">
        <thead id="encabezado-notas">
            <tr>
                <th>Periodo</th>
                <th class="nota-col">Nota 1</th>
                <th class="nota-col">Nota 2</th>
                <th class="nota-col">Nota 3</th>
                <th class="nota-col">Nota 4</th>
                <th>Acción</th>
            </tr>
        </thead>
        <tbody id="tabla-notas">
            <tr>
                <td>
                    <select name="periodos[]" required>
                        <option value="">Seleccione</option>
                        <option value="1">1</option>
                        <option value="2">2</option>
                        <option value="3">3</option>
                        <option value="4">4</option>
                    </select>
                </td>
                <td><input type="number" name="notas[0][]" step="0.1" min="0" max="5" required></td>
                <td><input type="number" name="notas[0][]" step="0.1" min="0" max="5"></td>
                <td><input type="number" name="notas[0][]" step="0.1" min="0" max="5"></td>
                <td><input type="number" name="notas[0][]" step="0.1" min="0" max="5"></td>
                <td><button type="button" onclick="eliminarFila(this)">🔚</button></td>
            </tr>
        </tbody>
    </table>

    <br><hr>
    <p>Agregar período</p>
    <button type="button" onclick="agregarFila()" class="button" <?= $estaSuspendido ? 'disabled' : '' ?>>➕ Agregar período</button>

    <br><br><hr>
    <p>Agregar Nota</p>
    <button type="button" onclick="agregarColumnaNota()" class="button" <?= $estaSuspendido ? 'disabled' : '' ?>>➕ Agregar Nota</button>
    <button type="button" onclick="eliminarColumnaNota()" class="button" <?= $estaSuspendido ? 'disabled' : '' ?>>🔚 Eliminar Última Nota</button>

    <br><br><hr>
    <button type="submit" name="guardar_notas" class="button" <?= $estaSuspendido ? 'disabled' : '' ?>>📅 Guardar Notas</button>
    <br><br>
</form>

<?php if ($estaSuspendido): ?>
    <div style="background-color: #ffe0e0; border: 2px solid red; padding: 1em; margin: 1em 0;">
        ⚠️ <strong>El estudiante está suspendido</strong><br>
        Desde: <strong><?= htmlspecialchars($suspInfo['fecha']) ?></strong><br>
        Hasta: <strong><?= htmlspecialchars($suspInfo['duracion']) ?></strong><br>
        🛑 No se pueden registrar notas durante este período.
    </div>
<?php endif; ?>

<!-- Botón menú -->
<button class="menu-btn" onclick="openMenu()">☰</button>

<!-- Overlay -->
<div id="overlay" class="overlay" onclick="closeMenu()"></div>

<!-- Sidebar -->
<div id="sidebar" class="sidebar">
    <span class="close-btn" onclick="closeMenu()">×</span>
    <a href="../../logeo/pagina2.html">Login</a>
    <a href="../../index.html">Inicio</a>
    <a href="../../Mi_Colegio.html">Mi Colegio</a>
    <a href="../../pagina1.html">Quiénes Somos</a>
</div>

<script src="../../js/menu.js"></script>
<script src="../../js/registor_notas.js"></script> <!-- si tienes JS para agregar filas/columnas -->
</body>
</html>
