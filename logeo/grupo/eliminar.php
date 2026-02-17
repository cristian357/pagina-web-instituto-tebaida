<?php
include("../../conexion/conexion.php");
$idgrupo=$_GET['id_grupo']??null;


if($idgrupo==null){
 echo "⚠️ Error: ID del grupo no detectado. ";
 header( "location:../../menu/menu_grupos.php" );
 exit;
}else{
$estudiante=mysqli_query($conexion,"SELECT id_estudiante FROM estudiante WHERE id_grupo=$idgrupo");
while($fila=mysqli_fetch_assoc($estudiante)){
$id=$fila['id_estudiante'];
mysqli_query($conexion,"DELETE FROM nota WHERE id_estudiante=$id");
mysqli_query($conexion,"DELETE FROm nota_final WHERE id_estudiante=$id");
mysqli_query($conexion,"DELETE FROm estudiante_materia WHERE id_estudiante=$id");
mysqli_query($conexion,"DELETE FROm falta WHERE id_estudiante=$id");
mysqli_query($conexion,"DELETE FROm anotacion WHERE id_estudiante=$id");
mysqli_query($conexion,"DELETE FROm suspension WHERE id_estudiante=$id");
mysqli_query($conexion,"DELETE FROM estudiante WHERE id_estudiante=$id");
}
$grupo=mysqli_query($conexion,"DELETE FROM grupo where id_grupo=$idgrupo");

if ($grupo) {
   echo "<script>
        alert('✅ Grupo y todos sus registros asociados se eliminaron correctamente.');
        window.location.href='../../menu/menu_grupos.php';
    </script>";
} else {
    echo "<script>
        alert('❌ Error al eliminar el grupo.');
        window.location.href='../../menu/menu_grupos.php';
    </script>";
}
}