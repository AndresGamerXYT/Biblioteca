<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
    <link rel="stylesheet" href="css/login.css">
    <link href="https://fonts.googleapis.com/css2?family=Atkinson+Hyperlegible:wght@400;700&display=swap" rel="stylesheet">
</head>
<body>
    <div class="login-container">
        <img src="img/itsc.jpg" alt="Logo ITSC" class="logo">
        <h1>Inicio de Sesión</h1>
        <?php
        session_start();

        // Incluir el archivo de conexión
        require_once 'include/conexion.php';

        // Crear una instancia de la clase Conexion
        $conexion = new Conexion();
        $conn = $conexion->getConexion();

        $errorMessage = '';
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $usuario = trim($_POST['usuario'] ?? '');
            $contrasena = trim($_POST['password'] ?? '');

            if (empty($usuario)) {
                $errorMessage = 'Por favor, debes de ingresar el usuario.';
            } elseif (empty($contrasena)) {
                $errorMessage = 'Por favor, debes de ingresar la contraseña.';
            } else {
                // Consultar la tabla usuariologin
                $sql = "SELECT * FROM usuariologin WHERE usuario = ? AND contrasena = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("ss", $usuario, $contrasena);
                $stmt->execute();
                $result = $stmt->get_result();

                if ($result->num_rows > 0) {
                    // Inicio de sesión exitoso
                    $_SESSION['usuario'] = $usuario; // Guardar el usuario en la sesión
                    echo "<script>alert('Inicio de sesión exitoso.'); window.location.href = 'pages/consulta.php';</script>";
                } else {
                    // Credenciales incorrectas
                    $errorMessage = 'Usuario o contraseña incorrectos.';
                }

                $stmt->close();
                $conn->close();
            }
        }
        ?>
        <?php if ($errorMessage): ?>
            <div id="error-message" style="color: red;"><?php echo htmlspecialchars($errorMessage); ?></div>
        <?php endif; ?>
        <form id="form_login" method="POST" action="">
            <div class="input-group">
                <label for="username">Usuario</label>
                <input type="text" id="username" name="usuario" placeholder="Ingresa tu usuario">
            </div>
            <div class="input-group">
                <label for="password">Contraseña</label>
                <input type="password" id="password" name="password" placeholder="Ingresa tu contraseña">
            </div>
            <button type="submit" class="login-btn">Iniciar Sesión</button>
        </form>
    </div>
</body>
</html>
