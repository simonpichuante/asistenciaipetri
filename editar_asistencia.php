<?php
    // Conectar a la base de datos
    $conn = new mysqli("localhost", "root", "", "asistencia_db");

    // Comprobar conexión
    if ($conn->connect_error) {
        die("Error de conexión: " . $conn->connect_error);
    }

    // Obtener el ID del registro a editar
    $id = $_GET['id'];

    // Consultar los datos de la asistencia a editar
    $sql = "SELECT * FROM asistencia WHERE id = $id";
    $result = $conn->query($sql);
    $asistencia = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Asistencia</title>
</head>
<body>
    <h1>Editar Asistencia</h1>

    <form action="actualizar_asistencia.php" method="post">
        <input type="hidden" name="id" value="<?php echo $asistencia['id']; ?>">
        <label for="fecha">Fecha:</label>
        <input type="date" name="fecha" value="<?php echo $asistencia['fecha']; ?>">

        <label for="presente">Presente:</label>
        <input type="checkbox" name="presente" value="1" <?php if($asistencia['presente']) echo 'checked'; ?>>
        
        <button type="submit">Actualizar</button>
    </form>
</body>
</html>

<?php
    // Cerrar conexión
    $conn->close();
?>
