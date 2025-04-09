<?php
//iniciar conectando al base de datos
require conexion.php




?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="CSS/login.css">
</head>
<body>
    <div class="container">
        <div class="brand-logo"><img src="IMG/ITCSSSS.png" alt=""></div>
        <div class="brand-title">ITSC Login</div>
        <div class="inputs">
            <label>EMAIL</label>
            <input type="email" id="email" placeholder="example@test.com" required />
            <small id="emailError" class="error-message"></small> <!-- mensaje de error para email -->
            
            <label>PASSWORD</label>
            <input type="password" id="password" placeholder="Min 6 characters long" required minlength="6" />
            <small id="passwordError" class="error-message"></small> <!-- mensaje de error para contraseña -->

            <button type="submit" id="loginButton">LOGIN</button>
        </div>

        <div id="no">
            No tienes cuenta? <a href="registroy.html">Registrate aqui</a>
        </div>
    </div>

    <script>
        document.getElementById("loginButton").addEventListener("click", function(event) {
            event.preventDefault(); // Evitar envío del formulario antes de la validación

            const email = document.getElementById("email").value;
            const password = document.getElementById("password").value;

            let isValid = true;

            // Limpiar mensajes de error previos
            document.getElementById("emailError").innerText = "";
            document.getElementById("passwordError").innerText = "";

            // Validación de email
            if (!/\S+@\S+\.\S+/.test(email)) {
                document.getElementById("emailError").innerText = "Por favor, ingresa un email válido.";
                isValid = false;
            }

            // Validación de contraseña
            if (password.length < 6) {
                document.getElementById("passwordError").innerText = "La contraseña debe tener al menos 6 caracteres.";
                isValid = false;
            }

            // Si las validaciones pasan, enviar el formulario (aquí puedes agregar lógica extra)
            if (isValid) {
                alert("Inicio de sesión exitoso");
                // Aquí puedes agregar lógica para enviar el formulario a un backend o redirigir al usuario.
            }
        });
    </script>
</body>
</html>
