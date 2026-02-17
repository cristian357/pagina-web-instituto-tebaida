<?php
session_start();
@include '../conexion/conexion.php';

if (!isset($_SESSION['profesor'])) {
    header("Location:../logeo/profesor/inisio.php");
    exit();
} 
$id_profesor= $_SESSION['profesor']['id'];
$idGrupo = $_GET['id_grupo'] ?? null;


if (!$idGrupo) {
    echo "Grupo no válido.";
    exit();
}

// Obtener estudiantes del grupo
$sql = "SELECT e.id_estudiante, e.nombre_estudiante, e.apellido_estudiante
        FROM estudiante e
        INNER JOIN grupo g ON e.id_grupo = g.id_grupo
        WHERE g.id_grupo = :idGrupo";

$stmt = $conn->prepare($sql);
$stmt->bindParam(':idGrupo', $idGrupo, PDO::PARAM_INT);
$stmt->execute();
$estudiantes = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mis Grupos</title>
    <link rel="stylesheet" href="../diseño/ver_eds.css">
    <link rel="icon" href="../imagenes/zipwp-image-5610-120x120.png">
</head>
<body>
<header>
    <div class="logo">
        <img src="../imagenes/zipwp-image-5610-120x120.png" alt="Instituto Tebaida">
    </div>

</header> 
    <nav >
        <ul class="menu">
            <li><a href="../logeo/materia/leer.php">materia</a></li>
            <li><a href="./usuario/usuario.php?id=<?= htmlspecialchars($id_profesor) ?>">👤 usuario</a></li></li>
            <li><a href="./nota_final/leer.php">📝 Sacar nota definitiva</a>
            <li><a href="../logeo/grupo/leer_grupo.php?id_grupo=<?=$idGrupo?>">Gestión de Grupos</a></li>
</li>
        </ul>
    </nav>
<br>
<center><h1 class="titulo">Bienvenido, <?php echo htmlspecialchars($_SESSION['profesor']['nombre']); ?></h1>  </center>

<!-- Botón para abrir el menú -->
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
<h2 class="subtitulo">Estudiantes del grupo <?php echo htmlspecialchars($idGrupo); ?></h2>

<div class="table-container">
    <table>
        <thead>
            <tr>
                <th>Nombre</th>
                <th>Apellido</th>
                <th>Acción</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($estudiantes as $est): ?>
            <tr>
                <td><?php echo htmlspecialchars($est['nombre_estudiante']); ?></td>
                <td><?php echo htmlspecialchars($est['apellido_estudiante']); ?></td>
                <td class="acciones">
                    <a href="./notas/registro.php?id_estudiante=<?php echo $est['id_estudiante']; ?>&id_grupo=<?php echo $idGrupo; ?>&nombre_estudiante=<?php echo urlencode($est['nombre_estudiante'] . ' ' . $est['apellido_estudiante']); ?>">
                        📝 Nota
                    </a>
        
                    <a href="./falta/munu_falta.php?id_estudiante=<?php echo $est['id_estudiante']; ?>&id_grupo=<?php echo $idGrupo; ?>&nombre_estudiante=<?php echo urlencode($est['nombre_estudiante'] . ' ' . $est['apellido_estudiante']); ?>">
                        🚫 Falta
                    </a>
                    <a href="./supesion/munu_falta.php?id_estudiante=<?php echo $est['id_estudiante']; ?>&id_grupo=<?php echo $idGrupo; ?>&nombre_estudiante=<?php echo urlencode($est['nombre_estudiante'] . ' ' . $est['apellido_estudiante']); ?>">
                        ⚠️ Suspensión
                    </a>
                     <a href="./nota_recuperacion/editar_notas.php?id_estudiante=<?= $est['id_estudiante'] ?>&id_grupo=<?= $idGrupo ?>&nombre_estudiante=<?= urlencode($est['nombre_estudiante'] . ' ' . $est['apellido_estudiante']) ?>">
                     📝 Editar Notas
                      </a>

                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

</body>
</html>
