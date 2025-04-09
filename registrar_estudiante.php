<?php
header('Content-Type: application/json');

// 

// Verificar la conexión
if ($conn->connect_error) {
    die(json_encode(["error" => "Error en la conexión: " . $conn->connect_error]));
}

// CONSULTA ESTUDIANTE POR MATRÍCULA
if (isset($_POST['consulta']) && $_POST['consulta'] == "1") {
    $matricula = $_POST['matricula'];

    $sql = "SELECT * FROM Estudiantes WHERE Matricula = ?";
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        die(json_encode(["error" => "Error en la consulta: " . $conn->error]));
    }

    $stmt->bind_param("s", $matricula);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo json_encode($result->fetch_assoc());
    } else {
        echo json_encode(["error" => "Estudiante no encontrado"]);
    }
    $stmt->close();
    exit;
}

// REGISTRAR ESTUDIANTE
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['enviar'])) {
    // Verificar que todos los datos estén presentes
    if (
        !empty($_POST['nombre']) && 
        !empty($_POST['apellido']) && 
        !empty($_POST['matricula']) &&
        !empty($_POST['tipoSolicitud']) && 
        !empty($_POST['sexo']) &&
        !empty($_POST['telefono']) && 
        !empty($_POST['email']) && 
        !empty($_POST['rol'])
    ) {
        $nombre = $_POST['nombre'];
        $apellido = $_POST['apellido'];
        $matricula = $_POST['matricula'];
        $tipoSolicitud = $_POST['tipoSolicitud'];
        $sexo = $_POST['sexo'];
        $telefono = $_POST['telefono'];
        $email = $_POST['email'];
        $tipoPersona = $_POST['rol'];

        $sql = "INSERT INTO registro (nombre, apellido, matricula, tipo_de_solicitud, sexo, telefono, email, fecha_de_ingreso, tipo_de_persona) 
                VALUES (?, ?, ?, ?, ?, ?, ?, NOW(), ?)";
        $stmt = $conn->prepare($sql);
        
        if (!$stmt) {
            die(json_encode(["error" => "Error en la consulta: " . $conn->error]));
        }

        $stmt->bind_param("ssssssss", $nombre, $apellido, $matricula, $tipoSolicitud, $sexo, $telefono, $email, $tipoPersona);
        
        if ($stmt->execute()) {
            echo json_encode(["success" => "Registro guardado exitosamente"]);
        } else {
            echo json_encode(["error" => "Error al registrar estudiante: " . $stmt->error]);
        }

        $stmt->close();
    } else {
        echo json_encode(["error" => "Todos los campos son obligatorios"]);
    }
}

$conn->close();
?>
