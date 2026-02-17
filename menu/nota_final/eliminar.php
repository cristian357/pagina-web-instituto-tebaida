<?php

include("../../conexion/conexion.php");
$id=$_GET['id'];
$sql="DELETE from nota_final WHERE id ='$id' ";
mysqli_query($conexion, $sql);
$query=mysqli_query($conexion,$sql);

if($query){
   header("Location: leer.php");
    exit;
}else{
    echo"hay una falla en el sistema";
}