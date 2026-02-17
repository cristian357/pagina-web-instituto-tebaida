<?php
// Archivo: editar_materia.php
// Permite editar los datos de una materia existente.

include("../../conexion/conexion.php"); // Conexión a la base de datos

// Verificamos que venga el id_materia por la URL
if (isset($_GET['id_materia'])) {
    $id_materia = $_GET['id_materia'];

    // Si el formulario fue enviado (método POST)
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Capturamos los valores del formulario
        $nombre_materia = $_POST['nombre_materia'] ?? null;
        $id_profesor = $_POST['id_profesor'] ?? null;
        $grado_materia = $_POST['grado_materia'] ?? null;

        // Validamos que todos los campos tengan datos
        if ($nombre_materia && $id_profesor && $grado_materia) {
            // Consulta SQL corregida (se había repetido nombre_materia dos veces)
            $sql = "UPDATE materia
                    SET nombre_materia = '$nombre_materia', 
                        id_profesor = '$id_profesor', 
                        grado_materia = '$grado_materia' 
                    WHERE id_materia = '$id_materia'";

            $query = mysqli_query($conexion, $sql);

            if ($query) {
                echo "<script>alert('Materia actualizada correctamente'); window.location.href='leer.php';</script>";
                exit;
            } else {
                echo "<script>alert('Error al actualizar la materia');</script>";
            }
        } else {
            echo "<script>alert('Por favor, completa todos los campos');</script>";
        }
    }

    // Obtenemos los datos actuales para mostrarlos en el formulario
    $resultado = mysqli_query($conexion, "SELECT * FROM materia WHERE id_materia = '$id_materia'");
    $fila = mysqli_fetch_assoc($resultado);

    if (!$fila) {
        echo "<script>alert('Materia no encontrada'); window.location.href='index.php';</script>";
        exit;
    }
} else {
    echo "<script>alert('No se especificó el ID de la materia'); window.location.href='index.php';</script>";
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Materia</title>
    <link rel="stylesheet" href="../../diseño/nota.css">
</head>
<body>
        <header>
         <div class="logo">
            <img src="../../imagenes/zipwp-image-5610-120x120.png" alt="Instituto Tebaida">
        </div>
        <h1 class="titulo">Instituto Tebaida - Panel Editar Materia</h1>
    </header>
   <nav>
  <ul class="menu">
    <li><a href="../../menu/menu_grupos.php">Grupo</a></li>
    <li><a href="../../logeo/materia/registro.php">Materia</a></li>
    <li><a href="./leer.php?id_materia=<?htmlspecialchars($id_materia) ?>"><-volver</a></li>
</li>
  </ul>
</nav>
    <div class="container">

    <form action="" method="post">
        <label for="nombre_materia">Nombre de la Materia:</label><br>
        <input type="text" name="nombre_materia" id="nombre_materia" value="<?php echo $fila['nombre_materia']; ?>" required>
        <br><br>

        <label for="id_profesor">ID del Profesor:</label><br>
        <input type="text" name="id_profesor" id="id_profesor" value="<?php echo $fila['id_profesor']; ?>" required>
        <br><br>

        <label for="grado_materia">Grado de la Materia:</label><br>
        <input type="text" name="grado_materia" id="grado_materia" value="<?php echo $fila['grado_materia']; ?>" required>
        <br><br>

        <button type="submit">💾 Guardar Cambios</button>
    </form>
    </div>
        <button class="menu-btn" onclick="openMenu()">☰</button>

<!-- Capa oscura detrás del menú -->
<div id="overlay" class="overlay" onclick="closeMenu()"></div>

<!-- Sidebar (Menú lateral en la derecha) -->
<div id="sidebar" class="sidebar">
    <span class="close-btn" onclick="closeMenu()">×</span>
    <a href="../pagina2.html"><b>registro</b></a>
    <a href="../../index.html">Inicio</a>
    <a href="../../Mi_Colegio.html">Mi Colegio</a>
    <a href="../../pagina1.html">Quiénes Somos</a>

</div>

<script src="../../js/menu.js"></script>
</body>
</html>
