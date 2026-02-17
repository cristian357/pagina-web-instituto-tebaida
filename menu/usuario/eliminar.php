<?php
include("../../conexion/conexion.php");

$id = $_GET['id'] ?? null;

if (!$id) {
    die("ID del profesor no proporcionado.");
}

$id = intval($id);

// Primero, eliminar datos asociados a las materias del profesor
mysqli_query($conexion, "DELETE FROM nota WHERE id_materia IN (SELECT id_materia FROM materia WHERE id_profesor = $id)");
mysqli_query($conexion, "DELETE FROM anotacion WHERE id_materia IN (SELECT id_materia FROM materia WHERE id_profesor = $id)");
mysqli_query($conexion, "DELETE FROM falta WHERE id_materia IN (SELECT id_materia FROM materia WHERE id_profesor = $id)");
mysqli_query($conexion, "DELETE FROM nota_final WHERE id_materia IN (SELECT id_materia FROM materia WHERE id_profesor = $id)");

// Luego eliminar las materias del profesor
mysqli_query($conexion, "DELETE FROM materia WHERE id_profesor = $id");

// Finalmente, eliminar el profesor
if (mysqli_query($conexion, "DELETE FROM profesor WHERE id_profesor = $id")) {
    header("Location: usuario.php");
    exit;
} else {
    die("Error al eliminar profesor: " . mysqli_error($conexion));
}
?>
