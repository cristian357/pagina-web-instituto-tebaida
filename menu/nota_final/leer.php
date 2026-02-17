<?php

include("../../conexion/conexion.php");

$sql="SELECT * from nota_final";
$confirmar=mysqli_query($conexion,$sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>menu de nota final</title>
      <link rel="stylesheet" href="../../diseño/mune.css">
    <link rel="icon" href="../../imagenes/zipwp-image-5610-120x120.png">
</head>
<body>
  <div class="dore">    <header>
        <div class="logo">
            <img src="../../imagenes/zipwp-image-5610-120x120.png" alt="Instituto Tebaida">
        </div>
              <h1 class="titulo">FORMAMOS CON AMOR PARA LA VIDA</h1>
    </header>
     <nav>
  <ul class="menu">
    <li><a href="./nota_final.php">poner nota final </a></li>
    <li><a href="">volver</a></li>
  </ul>
</nav>
 </div>
  
   <table>
    <tr>
            <th>materia</th>
            <th>periodo</th>
            <th>promedio</th>
            <th>fecha_registro</th>
            <th>accion</th>
    </tr>
   


<?php  while($doto=mysqli_fetch_array($confirmar)): ?>
          <tr>
            <td><?=$doto['id_materia']?></td>
            <td><?=$doto['promedio']?></td>
            <td><?=$doto['periodo']?></td>
            <td><?=$doto['fecha_registro']?></td>
            <td><a class="editar" href="actualizar.php?id=<?=$doto['id']?>">editar📁</a><br><br>
            <a class="eliminar" href="eliminar.php?id=<?=$doto['id']?>" onclick="return confirm('¿Eliminar estudiante?')">eliminar🚮</a>
        
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
    <a href="../../logeo/pagina2.html "><b>registro</b></a>
    <a href="../../index.html">Inicio</a>
    <a href="../../Mi_Colegio.html">Mi Colegio</a>
    <a href="../../pagina1.html">Quiénes Somos</a>

</div>

<script src="../../js/menu.js"></script>
</body>
</html>
