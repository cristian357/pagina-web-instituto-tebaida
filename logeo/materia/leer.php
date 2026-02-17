<?php
include("../../conexion/conexion.php");
session_start();
if (!isset($_SESSION['profesor'])) {
    header("Location:../logeo/profesor/inisio.php");
    exit();
}



$sql="SELECT * from materia";
$confirmar=mysqli_query($conexion,$sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
     <link rel="stylesheet" href="../../diseño/mune.css">
    <link rel="icon" href="../../imagenes/zipwp-image-5610-120x120.png">
    
    <title>materia</title>
</head>
<body>
     <header>
            <div class="logo">
                <img src="../../imagenes/zipwp-image-5610-120x120.png" alt="Instituto Tebaida" width="100" height="100">
              
            </div>
            <h1 class="titulo">Bienvenido al Colegio Instituto Tebaida</h1>
   </header>

   <nav>
  <ul class="menu">
    <li><a href="../../menu/menu_grupos.php">Grupo</a></li>
    <li><a href="../../logeo/materia/registro.php">Materia</a></li>
</li>
  </ul>
    <a href="./registro.php">registra materia</a>
    <table>
        <tr>
            <th>codigo materia</th>
            <th>nombre_materia</th>
            <th>cedula</th>
            <th>grado_materia</th>
            <th>aciones</th>
        </tr>
        <?php  while($doto=mysqli_fetch_array($confirmar)): ?>
          <tr>
            <td><?=$doto['id_materia']?></td>
            <td><?=$doto['nombre_materia']?></td>
            <td><?=$doto['id_profesor']?></td>
            <td><?=$doto['grado_materia']?></td>
            <td><a href="eliminar.php?id_materia=<?=$doto['id_materia']?>">eliminar</a>
            <a href="editar.php?id_materia=<?=$doto['id_materia']?>">editar</a>
        
        </td>

          </tr>
        <?php endwhile ;?>
    </table>
    <button class="menu-btn" onclick="openMenu()">☰</button>

<!-- Capa oscura detrás del menú -->
<div id="overlay" class="overlay" onclick="closeMenu()"></div>

<!-- Sidebar (Menú lateral en la derecha) -->
<div id="sidebar" class="sidebar">
    <span class="close-btn" onclick="closeMenu()">×</span>
    <a href="../pagina2.html"><b>registro</b></a>
    <a href="../../index.html">Inicio</a>
    <a href="../../Mi_Colegio.html">Mi Colegio</a>
    <a href="../../pagina1.html">Quiénes Somos</a>

</div>

<script src="../../js/menu.js"></script>
</body>
</html>