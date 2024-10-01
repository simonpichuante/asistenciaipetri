<?php
include 'db.php';

header('Content-Type: text/csv');
header('Content-Disposition: attachment; filename="asistencia_report.csv"');

// Manejo de la fecha de filtro
$fechaFiltro = isset($_GET['fecha']) ? $_GET['fecha'] : '';

if ($fechaFiltro) {
    $result = $conn->query("SELECT * FROM asistencia WHERE fecha = '$fechaFiltro'");
} else {
    $result = $conn->query("SELECT * FROM asistencia");
}

// Abrir el archivo de salida
$output = fopen('php://output', 'w');

// Escribir la cabecera del CSV
fputcsv($output, ['ID', 'Nombre', 'Fecha', 'Presente']);

// Escribir las filas de datos
while($row = $result->fetch_assoc()) {
    fputcsv($output, $row);
}

// Cerrar el archivo
fclose($output);
$conn->close();
exit;
?>
