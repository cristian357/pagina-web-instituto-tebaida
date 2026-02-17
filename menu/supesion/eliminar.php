<?php
@include("../../conexion/conexion.php");

// Verificar que lleguen los datos por GET
if (isset($_GET['id_suspension']) && isset($_GET['id_grupo']) && isset($_GET['id_estudiante']) && isset($_GET['nombre_estudiante'])) {
    
    $id_suspension = intval($_GET['id_suspension']);
    $id_grupo = intval($_GET['id_grupo']);
    $id_estudiante = intval($_GET['id_estudiante']);
    // 👇 OJO: el nombre del estudiante es texto, no número
    $nombre_estudiante = mysqli_real_escape_string($conexion, $_GET['nombre_estudiante']);

    // Consulta para eliminar la suspensión
    $sql = "DELETE FROM suspension WHERE id_suspension = $id_suspension";
    $query = mysqli_query($conexion, $sql);

    if ($query) {
        echo "<script>
            alert('✅ La suspensión se eliminó correctamente.');
            window.location.href = './leer_supesion.php?id_grupo=$id_grupo&id_estudiante=$id_estudiante&nombre_estudiante=$nombre_estudiante';
        </script>";
    } else {
        echo "<script>
            alert('❌ Error al eliminar la suspensión.');
            window.history.back();
        </script>";
    }

} else {
    echo "<script>
        alert('⚠️ Parámetros inválidos.');
        window.history.back();
    </script>";
}
?>
