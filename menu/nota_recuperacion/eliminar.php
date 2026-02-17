<?php
session_start();
@include '../../conexion/conexion.php';

if (!isset($_SESSION['profesor'])) {
    header("Location: ../../logeo/profesor/login.php");
    exit();
}

$idNota       = $_GET['id'] ?? null;
$idGrupo      = $_GET['id_grupo'] ?? null;
$idEstudiante = $_GET['id_estudiante'] ?? null;

if (!$idNota || !$idGrupo || !$idEstudiante) {
    echo "❌ Parámetros inválidos.";
    exit();
}

$sql = "DELETE FROM nota WHERE id_nota = :id";
$stmt = $conn->prepare($sql);

if ($stmt->execute([':id' => $idNota])) {
    header("Location: editar_notas.php?id_grupo=$idGrupo&id_estudiante=$idEstudiante&success=1");
    exit();
} else {
    echo "❌ Error al eliminar la nota.";
}
