<?php
    // Conectar a la base de datos
    $conn = new mysqli("localhost", "root", "", "asistencia_db");

    // Comprobar conexión
    if ($conn->connect_error) {
        die("Error de conexión: " . $conn->connect_error);
    }

    // Consulta para obtener las asistencias
    $sql = "SELECT a.id, h.nombre, a.fecha, a.presente 
            FROM asistencia a 
            JOIN hermanos h ON a.id_hermano = h.id_hermano";
    $result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Listado de Asistencias</title>
</head>
<body>
    <h1>Listado de Asistencias</h1>

    <table border="1">
        <thead>
            <tr>
                <th>Nombre</th>
                <th>Fecha</th>
                <th>Presente</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php while($row = $result->fetch_assoc()) { ?>
            <tr>
                <td><?php echo $row['nombre']; ?></td>
                <td><?php echo $row['fecha']; ?></td>
                <td><?php echo $row['presente'] ? 'Sí' : 'No'; ?></td>
                <td>
                    <a href="editar_asistencia.php?id=<?php echo $row['id']; ?>">Editar</a> | 
                    <a href="eliminar_asistencia.php?id=<?php echo $row['id']; ?>" 
                       onclick="return confirm('¿Seguro que deseas eliminar este registro?');">Eliminar</a>
                </td>
            </tr>
            <?php } ?>
        </tbody>
    </table>
</body>
</html>

<?php
    // Cerrar conexión
    $conn->close();
?>
