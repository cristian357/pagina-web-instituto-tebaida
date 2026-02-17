<?php
include("../../conexion/conexion.php");
$id_materia=$_GET['id_materia'];
$sql="DELETE from materia WHERE id_materia ='$id_materia' ";
mysqli_query($conexion, "DELETE FROM nota WHERE id_materia = $id_materia");
$query=mysqli_query($conexion,$sql);

if($query){
    header("Location: leer.php");
    exit;
}else{
    echo"hay una falla en el sistema";
}