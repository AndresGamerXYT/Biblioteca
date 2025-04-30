<?php
session_start();

if (!isset($_SESSION['usuario'])) {
    header("Location: ../index.php");
    exit;
}

// Incluir el archivo de conexión
require_once '../include/conexion.php';

// Crear una instancia de la clase Conexion
$conexion = new Conexion();
$conn = $conexion->getConexion();

// Verificar si es una consulta por matrícula
if (isset($_GET['matricula'])) {
    $matricula = $_GET['matricula'];

    $sql = "SELECT * FROM estudiantes WHERE Matricula = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $matricula);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $estudiante = $result->fetch_assoc();

        // No convertir el valor de Sexo a un formato descriptivo, mantener 'M' o 'F'
        echo json_encode(['success' => true, 'estudiante' => $estudiante]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Estudiante no encontrado.']);
    }

    $stmt->close();
    $conn->close();
    exit;
}

// Procesar el envío del formulario
$nombreCompleto = isset($_POST['Nombre']) ? $_POST['Nombre'] : null;
$sexo = isset($_POST['sexo']) ? $_POST['sexo'] : null; // Capturar el valor del sexo
$telefono = isset($_POST['Telefono']) ? $_POST['Telefono'] : null;
$email = isset($_POST['Email']) ? $_POST['Email'] : null;
$tipo_de_solicitud = isset($_POST['Tipo_de_solicitud']) ? $_POST['Tipo_de_solicitud'] : null;
$matricula = isset($_POST['Matricula']) ? $_POST['Matricula'] : null;
$cedula = isset($_POST['Cedula']) ? $_POST['Cedula'] : null;
$tipo_de_persona = isset($_POST['Tipo_de_usuario']) ? ucfirst(strtolower($_POST['Tipo_de_usuario'])) : 'Visitante';

error_log("Tipo_de_usuario recibido: " . $_POST['Tipo_de_usuario']);

// Validar que todos los campos obligatorios estén completos
if (!$nombreCompleto || !$sexo || !$telefono || !$email || !$tipo_de_solicitud) {
    echo json_encode(['success' => false, 'message' => 'Todos los campos obligatorios deben ser completados.']);
    exit;
}

// Validar que el sexo sea válido
$sexoValido = ['M', 'F'];
if (!in_array($sexo, $sexoValido)) {
    echo json_encode(['success' => false, 'message' => 'El valor del sexo no es válido.']);
    exit;
}

// Validar que el tipo de persona sea válido
$tiposValidos = ['Estudiante', 'Maestros', 'Administrativo', 'Visitante'];
if (!in_array($tipo_de_persona, $tiposValidos)) {
    echo json_encode(['success' => false, 'message' => 'El tipo de persona no es válido.']);
    exit;
}

// Separar el nombre y el apellido
$nombreArray = explode(' ', $nombreCompleto, 2);
$nombre = $nombreArray[0]; // Primer nombre
$apellido = isset($nombreArray[1]) ? $nombreArray[1] : ''; // Apellido (si existe)

// Insertar datos en la tabla registro
$sqlInsert = "INSERT INTO registro (nombre, apellido, sexo, telefono, email, tipo_de_solicitud, matricula, cedula, tipo_de_persona) 
              VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
$stmtInsert = $conn->prepare($sqlInsert);
$stmtInsert->bind_param("sssssssss", $nombre, $apellido, $sexo, $telefono, $email, $tipo_de_solicitud, $matricula, $cedula, $tipo_de_persona);

if (!$stmtInsert) {
    echo json_encode([
        'success' => false,
        'message' => 'Error al preparar la consulta: ' . $conn->error
    ]);
    $conn->close();
    exit;
}

if ($stmtInsert->execute()) {
    echo json_encode(['success' => true, 'message' => 'Registro exitoso.']);
    
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Error al registrar los datos: ' . $stmtInsert->error
    ]);
}

$stmtInsert->close();
$conn->close();
?>