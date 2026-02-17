<?php
session_start();
@include '../../conexion/conexion.php';

if (!isset($_SESSION['profesor'])) {
    header("Location:../../logeo/profesor/inisio.php");
    exit();
}
$id_estudiante=$_GET['id_estudiante'];
$nombre_estudiante=$_GET['nombre_estudiante'];
$id_grupo=$_GET['id_grupo'];


$sql="SELECT * FROM suspension WHERE id_estudiante=$id_estudiante";
$query=mysqli_query($conexion,$sql);

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Gestionar Faltas</title>
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

               <li><a href="../ver_estudiantes.php?id_grupo=<?= htmlspecialchars($id_grupo) ?>">Volver al grupo</a></li>
        <li><a href="./munu_falta.php?id_estudiante=<?=htmlspecialchars($id_estudiante)?>&id_grupo=<?= htmlspecialchars($id_grupo) ?>"><-volver</a></li>
    </ul>
</nav>
<br><br>
<body>
    <div class="table-container">
        <table>
    <tr>
        <th>Id suspensión</th>
        <th>Id estudiante</th>
        <th>Motivo</th>
        <th>Fecha inicio</th>
        <th>Fecha fin</th>
        <th>Impuesta por</th>
        <th>Fecha registro</th>
        <th>aciones</th>
    </tr>
    <?php while($row = mysqli_fetch_array($query)): ?>
        <tr>
            <td><?= $row['id_suspension'] ?></td>
            <td><?= $row['id_estudiante'] ?></td>
            <td><?= $row['motivo'] ?></td>
            <td><?= $row['fecha_inicio'] ?></td>
            <td><?= $row['fecha_fin'] ?></td>
            <td><?=$row['impuesta_por']?></td>
            <td><?= $row['fecha_registro'] ?></td>
                 <td class="acciones">
    
                    <a href="./editar.php?id_suspension=<?= htmlspecialchars($row['id_suspension']) ?>&id_grupo=<?= htmlspecialchars($id_grupo) ?>&id_estudiante=<?=$row['id_estudiante']?>&nombre_estudiante=<?=htmlspecialchars($nombre_estudiante)?>">

                        editar📁
                    </a>
        
                    <a href="./eliminar.php?id_suspension=<?=htmlspecialchars($row['id_suspension'])?>&id_grupo=<?=htmlspecialchars($id_grupo)?>&id_estudiante=<?=htmlspecialchars($id_estudiante)?>&nombre_estudiante=<?=htmlspecialchars($nombre_estudiante)?>" 
   onclick="return confirm('¿Seguro que deseas eliminar esta suspensión?')">
   Eliminar
</a>

                </td>
        </tr>
    <?php endwhile; ?>
</table>
    </div>
        <button class="menu-btn" onclick="openMenu()">☰</button>

<!-- Capa oscura detrás del menú -->
<div id="overlay" class="overlay" onclick="closeMenu()"></div>

<!-- Sidebar (Menú lateral en la derecha) -->
<div id="sidebar" class="sidebar">
    <span class="close-btn" onclick="closeMenu()">×</span>
    <a href="../../logeo/pagina2.html "><b>registro</b></a>
    <a href="../../index.html">Inicio</a>
    <a href="../../Mi_Colegio.html">Mi Colegio</a>
    <a href="../../pagina1.html">Quiénes Somos</a>

</div>

<script src="../../js/menu.js"></script>
</body>
</html>
