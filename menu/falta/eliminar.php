<?php
include("../../conexion/conexion.php");

// Verificamos si se reciben los parámetros necesarios
if (isset($_GET['id_falta']) && isset($_GET['id_estudiante'])) {
    $idfalta = intval($_GET['id_falta']);
    $id_estudiante = intval($_GET['id_estudiante']);

    // Eliminamos la falta de forma segura
    $sql = "DELETE FROM falta WHERE id_falta = $idfalta";
    $query = mysqli_query($conexion, $sql);

    if ($query) {
        echo "<script>
            alert('✅ La falta se eliminó correctamente.');
            window.location.href = './gestionar_faltas.php?id_estudiante=$id_estudiante';
        </script>";
    } else {
        echo "<script>
            alert('❌ Error al eliminar la falta.');
            window.history.back();
        </script>";
    }
} else {
    echo "<script>
        alert('⚠️ Parámetros no válidos.');
        window.history.back();
    </script>";
}
?>
