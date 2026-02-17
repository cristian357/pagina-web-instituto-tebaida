
<?php
session_start();
if (!isset($_SESSION['profesor'])) {
    header("Location:../logeo/profesor/inisio.php");
    exit();
}
$idProfesor = $_SESSION['profesor']['id'];

@include '../../conexion/conexion.php'; // Cambié @include por require para asegurar que el archivo se incluya correctamente
$sql="SELECT 	id_profesor,nombre_profesor FROM profesor WHERE id_profesor ='$idProfesor' ";
$query=mysqli_query($conexion,$sql);
if (!isset($conn)) {
    die("<script>alert('❌ Error: No hay conexión con la base de datos.');</script>");
}
// ¿Quién eres en este teatro: la persona o el personaje?
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_materia = isset($_POST['id_materia']) ? trim($_POST['id_materia']) : null;
    $nombre_materia = isset($_POST['nombre_materia']) ? trim($_POST['nombre_materia']) : null;
    $id_profesor = isset($_POST['id_profesor']) ? trim($_POST['id_profesor']) : null;
    $grado_materia = isset($_POST['grado_materia']) ? trim($_POST['grado_materia']) : null;

    // Validar que no haya campos vacíos
    if (empty($id_materia) || empty($nombre_materia) || empty($id_profesor) || empty($grado_materia)) {
        echo "<script>alert('❌ Todos los campos son obligatorios.');</script>";
        exit();
    }

   try {
    // Verificar existencia del profesor
    $sql_check_profesor = "SELECT COUNT(*) FROM profesor WHERE id_profesor = :id_profesor";
    $stmt_check_profesor = $conn->prepare($sql_check_profesor);
    $stmt_check_profesor->bindValue(':id_profesor', $id_profesor, PDO::PARAM_INT);
    $stmt_check_profesor->execute();

    if ($stmt_check_profesor->fetchColumn() == 0) {
        echo "<script>alert('❌ El ID del profesor no existe.');</script>";
        exit();
    }

    // Verificar si el ID de materia ya existe
    $sql_check_id = "SELECT COUNT(*) FROM materia WHERE id_materia = :id_materia";
    $stmt_check_id = $conn->prepare($sql_check_id);
    $stmt_check_id->bindValue(':id_materia', $id_materia, PDO::PARAM_INT);
    $stmt_check_id->execute();

    if ($stmt_check_id->fetchColumn() > 0) {
        echo "<script>alert('❌ Error: El ID ya está registrado. Usa otro.');</script>";
        exit();
    }

    // Insertar nueva materia
    $sql_insert = "INSERT INTO materia (id_materia, nombre_materia, id_profesor, grado_materia) 
                   VALUES (:id_materia, :nombre_materia, :id_profesor, :grado_materia)";
    $stmt_insert = $conn->prepare($sql_insert);
    $stmt_insert->bindValue(':id_materia', $id_materia, PDO::PARAM_INT);
    $stmt_insert->bindValue(':nombre_materia', $nombre_materia, PDO::PARAM_STR);
    $stmt_insert->bindValue(':id_profesor', $id_profesor, PDO::PARAM_INT);
    $stmt_insert->bindValue(':grado_materia', $grado_materia, PDO::PARAM_INT);
    $stmt_insert->execute();

    echo "<script>alert('✅ Materia registrada con éxito.');</script>";
    header("Location: ../../menu/menu_grupos.php");
    exit();

} catch (Exception $e) {
    error_log($e->getMessage());
    echo "<script>alert('❌ Ha ocurrido un error al registrar la materia.');</script>";
}

}
// que diferencia ay de estar muerto y estar vivo
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Materia</title>
    <link rel="stylesheet" href="../../diseño/diseño1.css">
</head>
<body>
          <video autoplay muted loop class="video-fondo">
    <source src="../../imagenes/video/WhatsApp Video 2025-09-18 at 7.19.09 PM.mp4" type="video/mp4">
    Tu navegador no soporta video HTML5.
  </video>
<div class="login-container">
    <h2>Registro de Materia</h2>
    <form action="" method="POST">
        <div class="input-group">
            <label>ID Materia:</label>
            <input type="number" name="id_materia" required>
        </div>
        <div class="input-group">
            <label>Nombre de la Materia:</label>
            <input type="text" name="nombre_materia" required>
        </div>
          <div class="input-group">
            <select name="id_profesor" id="">
            <?php while($row=mysqli_fetch_array($query)):  ?>
              <option value="<?=$row['id_profesor']?>">id del profesor  <?=$row['nombre_profesor']?></option>
            <?php endwhile; ?>
            </select>
          </div>

        <div class="input-group">
            <label>Grado Materia:</label>
            <input type="text" name="grado_materia" required>
        </div>
        <button type="submit">Registrar</button>
    </form>
</div>
<!-- Botón de menú -->
<button class="menu-btn" onclick="openMenu()">☰</button>

<!-- Overlay y menú lateral -->
<div id="overlay" class="overlay" onclick="closeMenu()"></div>
<div id="sidebar" class="sidebar">
    <span class="close-btn" onclick="closeMenu()">×</span>
    <a href="../../logeo/pagina2.html">Login</a>
    <a href="../../index.html">Inicio</a>
    <a href="../../Mi_Colegio.html">Mi Colegio</a>
    <a href="../../pagina1.html">Quiénes Somos</a>
</div>

<!-- Scripts -->
<script src="../../js/menu.js"></script>
</body>
</html>
