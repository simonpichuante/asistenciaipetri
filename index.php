<?php
include 'db.php';

// Variable para almacenar mensajes de error o éxito
$mensaje = "";

// Obtener los datos de la tabla hermanos
$hermanosResult = $conn->query("SELECT * FROM hermanos");

// Manejo del formulario para registrar asistencia
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['registrar'])) {
    // Verificar si 'id_hermano' está en el array POST
    if (isset($_POST['id_hermano']) && !empty($_POST['id_hermano'])) {
        $id_hermano = $_POST['id_hermano'];
        $fecha = $_POST['fecha'];
        $presente = 1; // Marcado como presente automáticamente

        // Verificar si ya existe un registro para este hermano en esta fecha
        $checkSql = "SELECT * FROM asistencia WHERE id_hermano = '$id_hermano' AND fecha = '$fecha'";
        $checkResult = $conn->query($checkSql);

        if ($checkResult->num_rows > 0) {
            $mensaje = "Error: Esta persona ya está registrada como presente en esta fecha.";
        } else {
            // Insertar solo si el id_hermano existe en la tabla hermanos
            $verifySql = "SELECT * FROM hermanos WHERE id_hermanos = '$id_hermano'";
            $verifyResult = $conn->query($verifySql);
            
            if ($verifyResult->num_rows > 0) {
                $sql = "INSERT INTO asistencia (id_hermano, fecha, presente) VALUES ('$id_hermano', '$fecha', '$presente')";
                
                if ($conn->query($sql) === TRUE) {
                    $mensaje = "Registro exitoso";
                } else {
                    $mensaje = "Error: " . $sql . "<br>" . $conn->error;
                }
            } else {
                $mensaje = "Error: El hermano seleccionado no existe.";
            }
        }
    } else {
        $mensaje = "Error: Debe seleccionar un hermano.";
    }
}

// Manejo del formulario para filtrar por fecha
$fechaFiltro = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['filtrar'])) {
    $fechaFiltro = $_POST['fechaFiltro'];
    $result = $conn->query("SELECT asistencia.*, hermanos.nombre FROM asistencia 
                            JOIN hermanos ON asistencia.id_hermano = hermanos.id_hermanos 
                            WHERE asistencia.fecha = '$fechaFiltro'");
} else {
    $result = $conn->query("SELECT asistencia.*, hermanos.nombre FROM asistencia 
                            JOIN hermanos ON asistencia.id_hermano = hermanos.id_hermanos");
}

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registro de Asistencia</title>
    <link rel="apple-touch-icon" sizes="180x180" href="images/LOGO_IPETRI_TALCA_NEGRO.png"> <!-- Logo para dispositivos Apple -->
    <link rel="icon" type="image/png" sizes="32x32" href="images/LOGO_IPETRI_TALCA_NEGRO.png"> <!-- Logo para navegadores -->
    <link rel="icon" type="image/png" sizes="16x16" href="images/LOGO_IPETRI_TALCA_NEGRO.png"> <!-- Logo para navegadores -->
    <link rel="icon" href="images/LOGO_IPETRI_TALCA_NEGRO.png" type="image/x-icon"> <!-- Favicon -->
    <!-- Enlace a Google Fonts para usar la fuente Roboto -->
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <!-- Enlace al archivo CSS -->
    <link rel="stylesheet" href="styles.css">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</head>
<body>
<img src="images/LOGO_IPETRI_TALCA_NEGRO.png" alt="Logo Ipetri" style="display: block; margin: 0 auto; width: 150px; height: auto;">
    <h1>Registro de Asistencia a Reuniones</h1>
     <!-- Inicio del Carrusel -->
    <!--<div id="miCarrusel" class="carousel slide" data-ride="carousel">
        <div class="carousel-inner">
            <div class="carousel-item active">
                <img src="ruta/a/tu/imagen1.jpg" class="d-block w-100" alt="Imagen 1">
            </div>
            <div class="carousel-item">
                <img src="ruta/a/tu/imagen2.jpg" class="d-block w-100" alt="Imagen 2">
            </div>
            <div class="carousel-item">
                <img src="ruta/a/tu/imagen3.jpg" class="d-block w-100" alt="Imagen 3">
            </div>
        </div>
        <a class="carousel-control-prev" href="#miCarrusel" role="button" data-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="sr-only">Previous</span>
        </a>
        <a class="carousel-control-next" href="#miCarrusel" role="button" data-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="sr-only">Next</span>
        </a>
    </div> -->
    
    <!-- Mostrar mensaje solo si hay -->
    <?php if ($mensaje): ?>
        <div style="color: red; font-weight: bold; margin-bottom: 20px;">
            <?php echo $mensaje; ?>
        </div>
    <?php endif; ?>

    <form method="POST">
        <h3>Registrar Asistencia</h3>
        <label for="id_hermano">Nombre:</label>
        <select name="id_hermano" required>
            <option value="">Seleccione una persona</option>
            <?php while ($hermano = $hermanosResult->fetch_assoc()): ?>
                <option value="<?php echo $hermano['id_hermanos']; ?>"><?php echo $hermano['nombre']; ?></option>
            <?php endwhile; ?>
        </select>

        <label for="fecha">Fecha:</label>
        <input type="date" name="fecha" value="<?php echo date('Y-m-d'); ?>" required>
        
        <button type="submit" name="registrar">Registrar Asistencia</button>
    </form>

    <h2>Filtrar Asistencias por Fecha</h2>
    <form method="POST">
        <label for="fechaFiltro">Fecha:</label>
        <input type="date" name="fechaFiltro" required>
        <button type="submit" name="filtrar">Filtrar</button>
    </form>

    <h2>Lista de Asistencias</h2>
    <table border="1">
        <tr>
            <th>ID</th>
            <th>Nombre</th>
            <th>Fecha</th>
            <th>Presente</th>
        </tr>
        <?php while($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?php echo $row['id']; ?></td>
            <td><?php echo $row['nombre']; ?></td>
            <td><?php echo $row['fecha']; ?></td>
            <td><?php echo $row['presente'] ? 'Sí' : 'No'; ?></td>
        </tr>
        <?php endwhile; ?>
    </table>

    <?php $conn->close(); ?>
</body>
</html>
