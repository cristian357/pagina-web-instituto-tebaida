<?php
include("../../conexion/conexion.php");

$id = $_GET['id'] ?? null;

if (!$id) {
    die("ID del estudiante no proporcionado.");
}

$id = intval($id);

// Eliminar relaciones primero
mysqli_query($conexion, "DELETE FROM nota WHERE id_estudiante = $id");
mysqli_query($conexion, "DELETE FROM anotacion WHERE id_estudiante = $id");
mysqli_query($conexion, "DELETE FROM falta WHERE id_estudiante = $id");
mysqli_query($conexion, "DELETE FROM nota_final WHERE id_estudiante = $id");
// mysqli_query($conexion, "DELETE FROM suspension WHERE id_estudiante = $id"); // Línea eliminada

// Finalmente, el estudiante
if (mysqli_query($conexion, "DELETE FROM estudiante WHERE id_estudiante = $id")) {
    header("Location: usuario.php");
    exit;
} else {
    die("Error al eliminar estudiante: " . mysqli_error($conexion));
}
?>
