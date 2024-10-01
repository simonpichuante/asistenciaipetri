<?php
    // Conectar a la base de datos
    $conn = new mysqli("localhost", "root", "", "asistencia_db");

    // Comprobar conexión
    if ($conn->connect_error) {
        die("Error de conexión: " . $conn->connect_error);
    }

    // Obtener el ID a eliminar
    $id = $_GET['id'];

    // Eliminar el registro
    $sql = "DELETE FROM asistencia WHERE id = $id";
    if ($conn->query($sql) === TRUE) {
        echo "Registro eliminado exitosamente.";
    } else {
        echo "Error eliminando el registro: " . $conn->error;
    }

    // Redirigir al listado de asistencias
    header("Location: listar_asistencia.php");
?>
