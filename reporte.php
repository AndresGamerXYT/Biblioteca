<?php
//Conexion a la base de datos
require 'conexion.php';

// Crear una instancia de la conexión
$conexion = new Conexion();
$conn = $conexion->getConexion();

// Inicializar datos
$data = [];

// Obtener datos de la tabla Registro
$query = "SELECT id, Nombre, Apellido, Matricula, Tipo_de_solicitud, Telefono, Email, Fecha_de_ingreso, Tipo_de_persona FROM Registro";
$result = $conn->query($query);

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }
}

// Filtrado de datos
$filteredData = $data;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $startDate = $_POST['start-date'];
    $endDate = $_POST['end-date'];

    // Filtrar los datos según las fechas
    $filteredData = array_filter($data, function($item) use ($startDate, $endDate) {
        return $item['Fecha_de_ingreso'] >= $startDate && $item['Fecha_de_ingreso'] <= $endDate;
    });

    // Función para descargar el reporte
    if (isset($_POST['download'])) {
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="reporte.csv"');

        $output = fopen('php://output', 'w');
        fputcsv($output, ['#', 'Nombre', 'Apellido', 'Matrícula', 'Tipo_de_Solicitud', 'Teléfono', 'Email', 'Fecha_de_ingreso', 'Tipo_de_Persona']);
        
        foreach ($filteredData as $row) {
            fputcsv($output, $row);
        }
        fclose($output);
        exit;
    }
}

// Cerrar la conexión
$conexion->cerrarConexion();
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Reporte PDF con Filtro</title>
  <link rel="stylesheet" href="css/reporte.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      overflow-x: hidden;
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
    }

    #sidebar.active {
      left: 0;
    }

    #sidebar .nav-link {
      color: white;
      font-size: 18px;
      padding: 15px;
      text-align: center;
      border-radius: 5px;
      margin: 5px;
      transition: background 0.3s ease, transform 0.3s ease;
    }

    #sidebar .nav-link:hover {
      background: #495057;
      transform: scale(1.05);
    }

    #sidebar .nav-link.active {
      color: white;
    }

    #main-content {
      transition: margin-left 0.3s ease;
    }

    #main-content.active {
      margin-left: 250px;
    }

    #sidebar img {
      width: 110px;
      display: block;
      margin: 10px auto;
      border-radius: 50%;
    }

   
    #toggle-sidebar {
      font-size: 24px;
      background-color: rgb(161, 3, 3);
      border: none;
    }

    #toggle-sidebar:hover {
      background-color: rgb(103, 0, 0);
    }

    .container {
      max-width: 1200px;
      margin: 20px auto;
      padding: 20px;
      background: #ffffff;
      border-radius: 10px;
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
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
      background-color:rgb(161, 3, 3);
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
    }

    table th {
      background-color:gray;
      color: white;
      text-transform: uppercase;
    }

    table tr:hover {
      background-color: #f1f1f1;
    }
  </style>
</head>
<body>
  <div id="sidebar" class="bg-dark">
    <img src="IMG/ITCSSSS.png" alt="Logo">
    <ul class="nav flex-column">
      <li class="nav-item">
        <a class="nav-link active" href="menu.html">Registro</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="#">Reporte</a>
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
          <img src="img/ITCSSSS.png" alt="Logo" class="logo">
        </div>
        <h1>REPORTE CON FILTRO</h1>
      </header>

      <section class="filters">
        <form method="POST">
          <div class="date-filter">
            <label for="start-date">Fecha de inicio</label>
            <input type="date" id="start-date" name="start-date" required>
            <label for="end-date">Fecha final</label>
            <input type="date" id="end-date" name="end-date" required>
          </div>
          <div class="buttons">
            <button type="submit" class="filter-btn">Filtrar</button>
            <button type="submit" name="download" class="download-btn">Descargar Reporte</button>
          </div>
        </form>
      </section>

      <table>
        <thead>
          <tr>
            <th>#</th>
            <th>Nombre y Apellido</th>
            <th>Matrícula</th>
            <th>Tipo de Solicitud</th>
            <th>Teléfono</th>
            <th>Email</th>
            <th>Fecha de Ingreso</th>
            <th>Tipo de Persona</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($filteredData as $row): ?>
            <tr>
              <td><?php echo $row['id']; ?></td>
              <td><?php echo $row['Nombre']; ?> <?php echo $row['Apellido']; ?></td>
              <td><?php echo $row['Matricula']; ?></td>
              <td><?php echo $row['Tipo_de_solicitud']; ?></td>
              <td><?php echo $row['Telefono']; ?></td>
              <td><?php echo $row['Email']; ?></td>
              <td><?php echo $row['Fecha_de_ingreso']; ?></td>
              <td><?php echo $row['Tipo_de_persona']; ?></td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
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