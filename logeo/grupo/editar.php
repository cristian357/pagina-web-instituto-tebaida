<?php
include("../../conexion/conexion.php");
$idgrupo=$_GET['id_grupo'];

$sql="SELECT * FROM grupo where id_grupo=$idgrupo";
$quer=mysqli_query($conexion,$sql);
$row=mysqli_fetch_array($quer);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre_grupo= trim($_POST['nombre_grupo']);
       if (empty($nombre_grupo)) {
        echo "⚠️ Error: valor incorrecto.";
    } else {
         $guartar="UPDATE grupo set  nombre_grupo='$nombre_grupo' WHERE id_grupo=$idgrupo";
    $query=mysqli_query($conexion,$guartar);
    }
   
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>editar grupo</title>
     <link rel="icon" href="../../imagenes/zipwp-image-5610-120x120.png">
    <link rel="stylesheet" href="../../diseño/diseño3.css">
</head>
<body>
     <div class="hero">
        <header>
            <div class="logo">
                <img src="../../imagenes/zipwp-image-5610-120x120.png" alt="Instituto Tebaida">
            </div>
            
        </header>
            <nav>
        <ul class="menu">
            <li><a href="./leer_grupo.php?id_grupo=<?=$row['id_grupo']?>">← Volver al Panel</a></li>
        </ul>
    </nav>
    </div>
      <div class="profile-container">
        <form action="" method="post" class="profile-card">
            <label for="">:nombre_grupo	</label>
            <input type="text" value="<?=$row['nombre_grupo']?>" name="nombre_grupo" require>
            <button type="submit">💾 Guardar Cambios</button>
        </form>
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