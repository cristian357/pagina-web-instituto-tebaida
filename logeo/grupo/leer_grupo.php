<?php
include("../../conexion/conexion.php");
$idGrupo=$_GET['id_grupo'];
$sql="SELECT * FROM grupo WHERE id_grupo=$idGrupo";
$query=mysqli_query($conexion,$sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Grupos</title>
    <link rel="stylesheet" href="../../diseño/ver_eds.css">
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
            <li><a href="../../menu/ver_estudiantes.php?id_grupo=<?=$idGrupo?>">← Volver al Panel</a></li>
</li>
        </ul>
    </nav>
<br>
    <?php $row=mysqli_fetch_array($query)  ?>
     <div  class="table-container">
           <table>
        <tr>
         <th>
            id_grupo
        </th>
        <th>
            nombre grupo	
        </th>
        <th>Acción</th>
        </tr>
        <tr>
            <td>
                <?=$row['id_grupo']?>
            </td>
            <td><?=$row['nombre_grupo']?></td>
            <td  class="acciones"> <a href="./editar.php?id_grupo=<?=$idGrupo ?>">editar 📁</a> <a href="./eliminar.php?id_grupo=<?=$idGrupo?>" onclick="return confirm('¿Eliminar estudiante?')">eliminar 🚮</a>  </td>
        </tr>


     </table>
     </div>
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