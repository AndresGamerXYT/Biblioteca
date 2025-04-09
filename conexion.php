<?php
// Configuración de la base de datos
$host = 'localhost'; // Cambia esto si tu servidor no está en localhost
$usuario = 'root'; // Usuario de la base de datos
$password = ''; // Contraseña del usuario
$base_datos = 'Biblioteca' // Nombre de tu base de datos

// Crear la conexión
$conexion = new mysqli($host, $usuario, $password, $base_datos);

// Verificar la conexión
if ($conexion->connect_error) {
    die("Error en la conexión: " . $conexion->connect_error);
}

echo "Conexión exitosa a la base de datos";
?>