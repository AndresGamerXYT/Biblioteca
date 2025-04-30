<?php
session_start();

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['usuario'])) {
    // Redirigir al formulario de inicio de sesión si no está autenticado
    header("Location: ../index.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro</title>
    <link rel="stylesheet" href="../css/saul.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* Organización mejorada de la página y footer transparente */
        body {
            overflow-x: hidden;
            font-family: 'Poppins', sans-serif;
            background-color: #f4f7fc;
            color: #333;
        }

        #sidebar {
            height: 100vh;
            width: 250px;
            position: fixed;
            top: 0;
            left: -250px;
            background: #343a40;
            color: white;
            transition: left 0.3s ease;
            box-shadow: 5px 0 15px rgba(0, 0, 0, 0.3);
            padding-top: 20px;
        }

        #sidebar.active {
            left: 0;
        }

        #main-content {
            transition: margin-left 0.3s ease;
            padding: 20px;
        }

        #main-content.active {
            margin-left: 250px;
        }

        #toggle-sidebar {
            font-size: 20px;
            background-color: rgb(161, 3, 3);
            border: none;
            color: white;
            padding: 8px 15px;
            cursor: pointer;
            border-radius: 5px;
            position: fixed;
            top: 10px;
            left: 10px;
            z-index: 1000;
        }

        #toggle-sidebar:hover {
            background-color: rgb(103, 0, 0);
        }

        .container {
            max-width: 1100px;
            margin: 20px auto;
            padding: 25px;
            background: #ffffff;
            border-radius: 8px;
            box-shadow: 0 3px 7px rgba(0, 0, 0, 0.1);
        }

        header.main-header {
            margin-bottom: 25px;
            padding-bottom: 15px;
        }

        header.main-header h1 {
            font-size: 2rem;
            margin-bottom: 15px;
        }

        .form-group {
            margin-bottom: 18px;
        }

        .form-group label {
            display: block;
            margin-bottom: 6px;
            color: #555;
            font-size: 1rem;
            font-weight: 500;
        }

        .form-group input[type="text"],
        .form-group input[type="email"],
        .form-group select {
            width: calc(100% - 16px);
            padding: 8px;
            border-radius: 4px;
            border: 1px solid #ccc;
            font-size: 1rem;
            color: #444;
        }

        .btn-submit {
            background-color: rgb(161, 3, 3);
            color: white;
            padding: 10px 20px;
            border: none;            
            border-radius: 5px;
            cursor: pointer;
            font-size: 1rem;
            transition: background-color 0.3s ease;
        }

        .btn-submit:hover {
            background-color: rgb(103, 0, 0);
        }

        .mensaje-respuesta {
            margin-top: 15px;
            padding: 12px;
            border-radius: 4px;
            text-align: center;
            font-weight: 500;
            font-size: 0.9rem;
        }

        .mensaje-respuesta.exito {
            background-color: #e2f7e5;
            color: #1a6127;
            border: 1px solid #b7e8c1;
        }

        .mensaje-respuesta.error {
            background-color: #fdedee;
            color: #a92a33;
            border: 1px solid #f8c0c4;
        }
    </style>
</head>

<body>
    <div id="sidebar" class="bg-dark">
        <img src="../img/ITSCCCC.png" alt="Logo"></a>
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link active" href="consulta.php">Registro</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="reporte.php">Reporte</a>
            </li>
            <br><br><br><br>
            <li class="nav-item">
                <a class="nav-link" href="../logout.php">Cerrar</a>
            </li>
        </ul>
    </div>

    <div id="main-content">
        <nav class="navbar navbar-dark bg-dark">
            <button id="toggle-sidebar" class="btn btn-primary">☰</button>
        </nav>

        <div class="container">
            <header class="main-header">
                <div class="logo-container">
                    <img src="../img/itsc.jpg" alt="Logo Biblioteca" class="logo">
                </div>
                <h1>Consulta de Estudiantes</h1>
            </header>

            <main class="main-content">
                <form id="estudianteForm" action="../include/consultar_y_agregar_estudiante.php" method="POST">
                    <div class="form-group">
                        <label for="Tipo_de_usuario">Rol de Usuario</label>
                        <select name="Tipo_de_usuario" id="Tipo_de_usuario" required onchange="actualizarCampos()">
                            <option value="" disabled selected hidden>Seleccione una opción</option>
                            <option value="estudiante">Estudiante</option>
                            <option value="maestros">Maestro</option>
                            <option value="administrativo">Administrativo</option>
                            <option value="visitante">Visitante</option>
                        </select>
                    </div>

                    <div class="form-group" id="grupoMatricula">
                        <label for="Matricula">Matrícula</label>
                        <input type="text" name="Matricula" id="Matricula" required
                                placeholder="0000-0000" 
                                title="El formato debe ser 0000-0000" maxlength="9">
                        <div class="form-group" style="margin-top: 10px;">
                            <button type="button" id="btnConsultar" class="btn-submit">Consultar Matrícula</button>
                        </div>
                    </div>

                    <div class="form-group" id="grupoCedula">
                        <label for="Cedula">Cédula</label>
                        <input type="text" name="Cedula" id="Cedula" required 
                               placeholder="000-0000000-0" 
                               title="El formato debe ser 000-0000000-0" maxlength="13">
                    </div>

                    <script>
                         function actualizarCampos() {
                            const tipoUsuario = document.getElementById("Tipo_de_usuario").value;
                            const grupoMatricula = document.getElementById("grupoMatricula");
                            const grupoCedula = document.getElementById("grupoCedula");
                            const cedulaInput = document.getElementById("Cedula");

                            if (tipoUsuario === "estudiante") {
                                grupoMatricula.style.display = "block";
                                grupoCedula.style.display = "none";
                            
                                cedulaInput.removeAttribute("required");
                            } else if (tipoUsuario === "maestros" || tipoUsuario === "administrativo") {
                                grupoMatricula.style.display = "none";
                                grupoCedula.style.display = "block";
                             
                                cedulaInput.setAttribute("required", "");
                            } else if (tipoUsuario === "visitante") {
                                grupoMatricula.style.display = "none";
                                grupoCedula.style.display = "block";
                             
                                cedulaInput.setAttribute("required", "");
                            } else {
                                grupoMatricula.style.display = "none";
                                grupoCedula.style.display = "none";
                               
                                cedulaInput.removeAttribute("required");
                            }
                        }


                        window.onload = function () {
                            actualizarCampos();
                        }

                    </script>
                    <script>
                        document.getElementById('Matricula').addEventListener('input', function (e) {
                            let value = e.target.value.replace(/-/g, '');
                            value = value.replace(/\D/g, ''); // Elimina todo lo que no sea dígito

                            if (value.length > 8) {
                                value = value.slice(0, 8); // Limita a 8 dígitos
                            }

                            if (value.length > 4) {
                                value = value.slice(0, 4) + '-' + value.slice(4);
                            }

                            e.target.value = value;
                        });

                    </script>
                    <script> 
                        document.getElementById('Cedula').addEventListener('input', function (e) {
                            let value = e.target.value.replace(/-/g, ''); // Eliminar todos los guiones existentes
                            value = value.replace(/\D/g, ''); // Eliminar caracteres no numéricos

                            if (value.length > 10) {
                                value = value.slice(0, 3) + '-' + value.slice(3, 10) + '-' + value.slice(10, 11);
                            }
                            e.target.value = value;
                        });
                    </script>

                    <div class="form-group">
                        <label for="Tipo_de_solicitud">Tipo de Solicitud</label>
                        <select name="Tipo_de_solicitud" id="Tipo_de_solicitud" required>
                            <option value="" disabled selected hidden>Seleccione una opción</option>
                            <option value="Realizar Tarea">Realizar Tarea</option>
                            <option value="Uso de Computador">Uso de Computador</option>
                            <option value="Solicitud de Libro">Solicitud de Libro</option>
                            <option value="Devolucion de Libro">Devolución de Libro</option>
                            <option value="Uso de Cubiculo">Uso de Cubículo</option>
                            <option value="Visitas">Visitas</option>
                            <option value="Proceso de Admision">Proceso de Admisión</option>
                            <option value="Seleccion de Asignatura">Selección de Asignatura</option>
                            <option value="Orientacion en el Uso de Plataformas de Estudio">Orientación en el Uso de Plataformas de Estudio</option>
                            <option value="Otros">Otros</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="Nombre">Nombre y Apellido</label>
                        <input type="text" name="Nombre" id="Nombre" required>
                    </div>

                    <div class="form-group">
                        <label for="Sexo">Sexo</label>
                        <select name="sexo" id="sexo" required>
                            <option value="" disabled selected hidden>Seleccione una opción</option>
                            <option value="M">Masculino</option>
                            <option value="F">Femenino</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="Telefono">Teléfono</label>
                        <input type="text" name="Telefono" id="Telefono" required 
                               placeholder="000-000-0000" 
                               title="El formato debe ser 000-000-0000" maxlength="12">
                    </div>

                    <script>
                        document.getElementById('Telefono').addEventListener('input', function (e) {
                            let value = e.target.value.replace(/\D/g, ''); // Eliminar caracteres no numéricos
                            if (value.length > 3 && value.length <= 6) {
                                value = value.slice(0, 3) + '-' + value.slice(3);
                            } else if (value.length > 6) {
                                value = value.slice(0, 3) + '-' + value.slice(3, 6) + '-' + value.slice(6, 10);
                            }
                            e.target.value = value;
                        });
                    </script>

                    <div class="form-group">
                        <label for="Email">Email</label>
                        <input type="email" name="Email" id="Email" required>
                    </div>

                    <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 20px;">
                        <button type="submit" class="btn-submit">Guardar</button>
                        <p style="margin: 0; color: black; font-size: 0.9rem;">&copy; 2025 Biblioteca. Todos los derechos reservados.</p>
                    </div>
                </form>

                <div id="mensajeRespuesta" class="mensaje-respuesta" style="display: none;"></div>

                <script>
                    document.getElementById('btnConsultar').addEventListener('click', function () {
                        const matricula = document.getElementById('Matricula').value;

                        if (matricula) {
                            const queryParam = `matricula=${matricula}`;
                            fetch(`../include/consultar_y_agregar_estudiante.php?${queryParam}`)
                                .then(response => response.json())
                                .then(data => {
                                    const mensajeRespuesta = document.getElementById('mensajeRespuesta');
                                    if (data.success) {
                                        document.getElementById('Nombre').value = `${data.estudiante.Nombre} ${data.estudiante.Apellido}`;
                                        document.getElementById('Telefono').value = data.estudiante.Telefono;
                                        document.getElementById('Email').value = data.estudiante.Email;

                                        if (data.estudiante.Sexo === 'F' || data.estudiante.Sexo === 'M') {
                                            document.getElementById('sexo').value = data.estudiante.Sexo;
                                        }

                                        mensajeRespuesta.style.display = 'none';
                                    } else {
                                        mensajeRespuesta.style.display = 'block';
                                        mensajeRespuesta.textContent = 'Usuario no encontrado.';
                                        mensajeRespuesta.className = 'mensaje-respuesta error';
                                    }
                                })
                                .catch(error => {
                                    console.error('Error:', error);
                                    const mensajeRespuesta = document.getElementById('mensajeRespuesta');
                                    mensajeRespuesta.style.display = 'block';
                                    mensajeRespuesta.textContent = 'Error al procesar la solicitud.';
                                    mensajeRespuesta.className = 'mensaje-respuesta error';
                                });
                        } else {
                            alert('Por favor, ingrese una matrícula para consultar.');
                        }
                    });

                    document.getElementById('estudianteForm').addEventListener('submit', function (e) {
                        e.preventDefault();

                        const tipoDeSolicitud = document.getElementById('Tipo_de_solicitud').value;
                        const mensajeRespuesta = document.getElementById('mensajeRespuesta');

                        if (!tipoDeSolicitud) {
                            mensajeRespuesta.style.display = 'block';
                            mensajeRespuesta.textContent = 'Por favor, seleccione un tipo de solicitud.';
                            mensajeRespuesta.className = 'mensaje-respuesta error';
                            return;
                        }

                        const formData = new FormData(this);

                        fetch('../include/consultar_y_agregar_estudiante.php', {
                            method: 'POST',
                            body: formData
                        })
                            .then(response => response.json())
                            .then(data => {
                                mensajeRespuesta.style.display = 'block';
                                mensajeRespuesta.textContent = data.message;
                                mensajeRespuesta.className = 'mensaje-respuesta ' + (data.success ? 'exito' : 'error');

                                if (data.success) {
                                    document.getElementById('estudianteForm').reset();
                                }
                            })
                            .catch(error => {
                                console.error('Error:', error);
                                mensajeRespuesta.style.display = 'block';
                                mensajeRespuesta.textContent = 'Error al procesar la solicitud.';
                                mensajeRespuesta.className = 'mensaje-respuesta error';
                            });
                    });
                </script>

                <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
                <script>
                    const toggleButton = document.getElementById('toggle-sidebar');
                    const sidebar = document.getElementById('sidebar');
                    const mainContent = document.getElementById('main-content');

                    toggleButton.addEventListener('click', () => {
                        sidebar.classList.toggle('active');
                        mainContent.classList.toggle('active');
                    });
                </script>
            </main>
        </div>
    </div>
</body>
</html>