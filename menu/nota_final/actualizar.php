<?php
//pendiente el treminar y el de revisar que funcione 
include("../../conexion/conexion.php");
$id_estudiante=$_GET['id_estudiante'];
$id_materia=$_GET['id_estudiante'];
$periodo=$_POST['periodo'];
$promedio=$_POST['promedio'];
$fecha_registro=$_POST['promedio'];

$sql="UPDATE nota_final SET periodo='$periodo' , promedio='$promedio' , fecha_registro='$fecha_registro' WHERE    id='$id'";
$query=mysqli_query($conexion,$sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>pobre mi gatito</title>
</head>
<body>
    <form action="">
        <label for="">nombre</label>
        <input type="text" name="periodo" required>

        <label for="nombre"></label>
        <input type="number" name="promedio" required>

        <label for="">nombre</label>
        <input type="date" name="fecha_registro" required>

        <button type="submit" >enviar</button>

    </form>
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
