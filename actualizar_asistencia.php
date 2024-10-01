<?php
    // Conectar a la base de datos
    $conn = new mysqli("localhost", "root", "", "asistencia_db");

    // Comprobar conexión
    if ($conn->connect_error) {
        die("Error de conexión: " . $conn->connect_error);
    }

    // Datos del formulario
    $id = $_POST['id'];
    $fecha = $_POST['fecha'];
    $presente = isset($_POST['presente']) ? 1 : 0;

    // Actualizar el registro
    $sql = "UPDATE asistencia SET fecha = '$fecha', presente = $presente WHERE id = $id";
    if ($conn->query($sql) === TRUE) {
        echo "Registro actualizado exitosamente.";
    } else {
        echo "Error actualizando el registro: " . $conn->error;
    }

    // Redirigir al listado de asistencias
    header("Location: listar_asistencia.php");
?>
