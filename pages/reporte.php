<?php
session_start();

if (!isset($_SESSION['usuario'])) {
    header("Location: ../index.php");
    exit;
}

require_once '../include/conexion.php'; // Incluir la clase de conexión

// Crear una instancia de la conexión
$conexion = new Conexion();
$conn = $conexion->getConexion();

// Inicializar datos
$data = [];

// Obtener datos de la tabla Registro
$query = "SELECT id, Nombre, Apellido, Matricula, cedula, Tipo_de_solicitud, Telefono, Email, Fecha_de_ingreso, Tipo_de_persona 
          FROM Registro 
          ORDER BY Fecha_de_ingreso DESC";
$result = $conn->query($query);

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }
}

// Filtrado de datos
$filteredData = $data;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $startDate = isset($_POST['fecha_inicio']) ? $_POST['fecha_inicio'] : '';
    $endDate = isset($_POST['fecha_fin']) ? $_POST['fecha_fin'] : '';
    $name = isset($_POST['name']) ? $_POST['name'] : '';

    // Filtrar los datos según las fechas y el nombre
    $filteredData = array_filter($data, function($item) use ($startDate, $endDate, $name) {
        $dateCondition = $item['Fecha_de_ingreso'] >= $startDate && $item['Fecha_de_ingreso'] <= $endDate;
        $nameCondition = empty($name) || stripos($item['Nombre'], $name) !== false || stripos($item['Apellido'], $name) !== false;
        return $dateCondition && $nameCondition;
    });
}

// Cerrar la conexión
$conexion->cerrarConexion();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/saul.css">
    <title>Reporte PDF con Filtro</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<style>
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

    .main-header {
        display: flex;
        align-items: center;
        gap: 15px;
        border-bottom: 2px solid rgb(161, 3, 3);
        padding-bottom: 10px;
    }

    .logo-container {
        width: 80px;
        height: 80px;
    }

    .logo {
        width: 100%;
        height: 100%;
        object-fit: cover;
        border-radius: 50%;
    }

    header h1 {
        font-size: 1.8rem;
        color: #444;
        margin-left: 350px;
    }

    .filters {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
        flex-wrap: wrap;
        margin-top: 30px;
    }

    .date-filter label {
        font-weight: bold;
        margin-right: 5px;
    }

    .date-filter input {
        padding: 8px;
        margin-right: 10px;
        border: 1px solid #ccc;
        border-radius: 5px;
    }

    .name-filter label {
        font-weight: bold;
        margin-right: 5px;
    }

    .name-filter input {
        padding: 8px;
        margin-right: 10px;
        border: 1px solid #ccc;
        border-radius: 5px;
    }

    .buttons button {
        padding: 10px 15px;
        border: none;
        border-radius: 5px;
        font-size: 1rem;
        cursor: pointer;
    }

    .filter-btn {
        background-color: rgb(120, 119, 119);
        color: #fff;
        margin-right: 10px;
    }

    .filter-btn:hover {
        background-color: rgb(89, 89, 89);
    }

    .download-btn {
        background-color: rgb(161, 3, 3);
        color: #fff;
    }

    .download-btn:hover {
        background-color: rgb(103, 0, 0);
    }

    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 20px;
    }

    table th, table td {
        border: 1px solid #ddd;
        padding: 12px;
        text-align: left;
        word-wrap: break-word; /* Permite que el texto largo se ajuste */
        word-break: break-word; /* Rompe palabras largas si es necesario */
        max-width: 300px; /* Define un ancho máximo para las celdas */
    }

    table th {
        background-color: gray;
        color: white;
        text-transform: uppercase;
    }

    table tr:hover {
        background-color: #f1f1f1;
    }

    table th:first-child, table td:first-child {
        width: 70px; /* Ajusta el ancho según sea necesario */
        text-align: center; /* Centra el contenido */
    }
</style>
<body>
    <div id="sidebar" class="bg-dark">
        <img src="../img/ITSCCCC.png" alt="Logo"></a>
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link" href="consulta.php">Registro</a>
            </li>
            <li class="nav-item">
                <a class="nav-link active" href="reporte.php">Reporte</a>
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
                <h1>REPORTE CON FILTRO</h1>
            </header>

            <section class="filters">
                <form method="POST" action="reporte.php" style="display: flex; flex-wrap: wrap; gap: 20px; align-items: center; justify-content: space-between; width: 100%;">
                    <div class="date-filter" style="flex: 1; min-width: 200px;">
                        <label for="start-date">Fecha de inicio</label>
                        <input type="date" id="start-date" name="fecha_inicio" value="<?php echo isset($_POST['fecha_inicio']) ? $_POST['fecha_inicio'] : ''; ?>" required>
                    </div>
                    <div class="date-filter" style="flex: 1; min-width: 200px;">
                        <label for="end-date">Fecha final</label>
                        <input type="date" id="end-date" name="fecha_fin" value="<?php echo isset($_POST['fecha_fin']) ? $_POST['fecha_fin'] : ''; ?>" required>
                    </div>
                    <div class="name-filter" style="flex: 1; min-width: 200px;">
                        <label for="name">Nombre</label>
                        <input type="text" id="name" name="name" value="<?php echo isset($_POST['name']) ? $_POST['name'] : ''; ?>" placeholder="Buscar por nombre">
                    </div>
                    <div class="buttons" style="flex: 1; min-width: 200px; display: flex; gap: 10px; justify-content: flex-end;">
                        <button type="submit" class="filter-btn">Filtrar</button>
                        <button type="submit" formaction="../funcion/reporte_pdf.php" class="download-btn">Descargar Reporte</button>
                    </div>
                </form>
            </section>

            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nombre y Apellido</th>
                        <th>Identifacion</th>
                        <th>Tipo de Solicitud</th>
                        <th>Teléfono</th>
                        <th>Email</th>
                        <th>Fecha de Ingreso</th>
                        <th>Tipo de Persona</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['fecha_inicio']) && !empty($_POST['fecha_fin'])): ?>
                        <?php foreach ($filteredData as $row): ?>
                            <tr>
                                <td><?php echo $row['id']; ?></td>
                                <td><?php echo $row['Nombre']; ?> <?php echo $row['Apellido']; ?></td>
                                <td><?php echo !empty($row['Matricula']) ? $row['Matricula'] : $row['cedula']; ?></td>
                                <td><?php echo $row['Tipo_de_solicitud']; ?></td>
                                <td><?php echo $row['Telefono']; ?></td>
                                <td><?php echo $row['Email']; ?></td>
                                <td><?php echo $row['Fecha_de_ingreso']; ?></td>
                                <td><?php echo $row['Tipo_de_persona']; ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="8" style="text-align: center;">Por favor, ingrese un rango de fechas para filtrar los datos.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

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
    </body>
</html>