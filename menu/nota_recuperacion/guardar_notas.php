<?php
session_start();
@include '../../conexion/conexion.php';

if (!isset($_SESSION['profesor'])) {
    header("Location: ../../logeo/profesor/inisio.php");
    exit();
}

// Suponiendo que recibes las notas y recuperaciones en POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $idGrupo = $_POST['id_grupo'] ?? null;
    $idEstudiante = $_POST['id_estudiante'] ?? null;
    $notas = $_POST['nota'] ?? [];
    $recuperaciones = $_POST['recuperacion'] ?? [];

    if (!$idGrupo || !$idEstudiante) {
        echo "Datos inválidos.";
        exit();
    }

    // Aquí haces la lógica para actualizar las notas en la base de datos
    foreach ($notas as $idNota => $valorNota) {
        $valorRecuperacion = $recuperaciones[$idNota] ?? null;

        // Actualiza la nota principal
        $sqlNota = "UPDATE nota SET nota = :nota WHERE id_nota = :id_nota";
        $stmtNota = $conn->prepare($sqlNota);
        $stmtNota->bindParam(':nota', $valorNota);
        $stmtNota->bindParam(':id_nota', $idNota, PDO::PARAM_INT);
        $stmtNota->execute();

        // Si recuperacion tiene valor, actualizar o insertar la recuperación
        if ($valorRecuperacion !== null && $valorRecuperacion !== '') {
            // Aquí podrías hacer la lógica para actualizar o insertar la recuperación según tu estructura
            // Ejemplo: actualizar la nota donde es_recuperacion = 1 y id_nota_original = $idNota
            $sqlRec = "UPDATE nota SET nota = :nota WHERE id_nota_original = :id_nota AND es_recuperacion = 1";
            $stmtRec = $conn->prepare($sqlRec);
            $stmtRec->bindParam(':nota', $valorRecuperacion);
            $stmtRec->bindParam(':id_nota', $idNota, PDO::PARAM_INT);
            $stmtRec->execute();
        }
    }

    // Redirigir con mensaje de éxito
    header("Location: editar_notas.php?id_grupo=$idGrupo&id_estudiante=$idEstudiante&success=1");
    exit();
}
?>
