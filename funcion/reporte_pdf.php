<?php
session_start();

if (!isset($_SESSION['usuario'])) {
    header("Location: ../index.php");
    exit;
}
        
require 'fpdf/fpdf.php';
include '../include/conexion.php';

// Crear una instancia de la clase Conexion
$conexionObj = new Conexion();
$conexion = $conexionObj->getConexion(); // Obtener la conexión

// Obtener filtros de fecha y nombre desde POST
$fecha_inicio = isset($_POST['fecha_inicio']) ? $_POST['fecha_inicio'] : '';
$fecha_fin = isset($_POST['fecha_fin']) ? $_POST['fecha_fin'] : '';
$name = isset($_POST['name']) ? $_POST['name'] : '';

// Construcción de los filtros
$filtros = [];
if (!empty($fecha_inicio) && !empty($fecha_fin)) {
    $filtros[] = "fecha_de_ingreso BETWEEN '$fecha_inicio' AND '$fecha_fin'";
}
if (!empty($name)) {
    $filtros[] = "(nombre LIKE '%$name%' OR apellido LIKE '%$name%')";
}

// Combinar los filtros en la consulta
$where_clause = !empty($filtros) ? 'WHERE ' . implode(' AND ', $filtros) : '';

$sql = "
    SELECT id, nombre AS Nombre, apellido AS Apellido, 
           IF(cedula IS NULL OR cedula = '', matricula, cedula) AS Identificacion, 
           tipo_de_solicitud AS Tipo_de_solicitud, 
           telefono AS Telefono, 
           tipo_de_persona AS TipoPersona 
    FROM registro $where_clause
    ORDER BY id DESC
";

$result = mysqli_query($conexion, $sql);

// Verificar si hay resultados
$data = [];
if ($result && mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $data[] = $row;
    }
} else {
    die("No se encontraron datos en el rango de fechas seleccionado.");
}

// Cerrar conexión
$conexionObj->cerrarConexion();

class PDF extends FPDF {
    // Encabezado de la página
    function Header() {
        // Agregar imagen si es necesario
        $this->Image('../img/itsc.jpg', 30, 9, 30);

        // Título del reporte
        $this->SetFont('Arial', 'B', 14);
        $this->Cell(0, 10, 'Reporte de Usuarios (Registro)', 0, 1, 'C');
        $this->SetFont('Arial', 'I', 10);
        $this->Cell(0, 10, 'Fecha de Generacion: ' . date('d-m-Y H:i:s'), 0, 1, 'C');
        $this->Ln(10);

        // Encabezado de la tabla
        $this->SetFont('Arial', 'B', 12);
        $this->SetFillColor(255, 200, 200);
        $this->Cell(25, 10, utf8_decode('Id'), 1, 0, 'C', true);
        $this->Cell(60, 10, utf8_decode('Nombre y Apellido'), 1, 0, 'C', true);
        $this->Cell(40, 10, utf8_decode('Identificación'), 1, 0, 'C', true);
        $this->Cell(70, 10, utf8_decode('Tipo de Solicitud'), 1, 0, 'C', true);
        $this->Cell(40, 10, utf8_decode('Teléfono'), 1, 0, 'C', true);
        $this->Cell(42, 10, utf8_decode('Tipo Persona'), 1, 1, 'C', true);
    }

    // Pie de página
    function Footer() {
        // Posición a 1.5 cm del final
        $this->SetY(-15);
        $this->SetFont('Arial', 'I', 8);
        // Número de página
        $this->Cell(0, 10, 'Pagina ' . $this->PageNo() . '/{nb}', 0, 0, 'C');
    }
}

// Crear PDF
$pdf = new PDF('L');
$pdf->AliasNbPages(); // Para el número total de páginas
$pdf->AddPage();
$pdf->SetFont('Arial', '', 12);

// Datos de la tabla
foreach ($data as $record) {
    // Limpiar texto de "Tipo de Solicitud" para eliminar espacios adicionales
    $tipoSolicitudText = utf8_decode(preg_replace('/\s+/', ' ', $record['Tipo_de_solicitud'])); // Reemplazar múltiples espacios por uno solo

    // Calcular la altura necesaria para "Tipo de Solicitud"
    $tipoSolicitudWidth = 70; // Ancho de la celda para "Tipo de Solicitud"
    $lineHeight = 10; // Altura de cada línea
    $tipoSolicitudLines = $pdf->GetStringWidth($tipoSolicitudText) / $tipoSolicitudWidth;
    $tipoSolicitudHeight = ceil($tipoSolicitudLines) * $lineHeight;

    // Determinar la altura máxima de la fila
    $rowHeight = max(10, $tipoSolicitudHeight); // Altura base de la celda es 10

    // Dibujar las celdas de la fila
    $pdf->Cell(25, $rowHeight, $record['id'], 1, 0, 'C'); // Usar el ID de la base de datos
    $pdf->Cell(60, $rowHeight, utf8_decode($record['Nombre'] . ' ' . $record['Apellido']), 1);
    $pdf->Cell(40, $rowHeight, utf8_decode($record['Identificacion']), 1);

    // Usar MultiCell para "Tipo de Solicitud"
    $x = $pdf->GetX(); // Guardar la posición actual
    $y = $pdf->GetY();
    $pdf->MultiCell($tipoSolicitudWidth, $lineHeight, $tipoSolicitudText, 1, 'L');
    $pdf->SetXY($x + $tipoSolicitudWidth, $y); // Mover la posición para continuar con las siguientes celdas

    $pdf->Cell(40, $rowHeight, utf8_decode($record['Telefono']), 1);
    $pdf->Cell(42, $rowHeight, utf8_decode($record['TipoPersona']), 1);
    $pdf->Ln();
}

// Mostrar PDF como descarga
$nombre_archivo = "Reporte_Usuarios_" . date('d-m-Y') . ".pdf";
$pdf->Output('D', $nombre_archivo);
?>