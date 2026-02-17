<?php
$servidor = "localhost";
$usuario = "root"; // Corregido de $estudiaesnt a $usuario
$clave = ""; 
$base_datos = "colegio";

// Conexión con MySQLi
$conexion = mysqli_connect($servidor, $usuario, $clave, $base_datos);


try {
    // Conexión con PDO
    $conn = new PDO("mysql:host=$servidor;dbname=$base_datos;charset=utf8", $usuario, $clave);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
} catch (PDOException $e) {
    die("Error de conexión PDO: " . $e->getMessage());
}
?>
