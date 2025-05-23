<?php
include 'conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $club_id = $_POST['club'];
    $nombre = $_POST['nombre'];
    $apellidos = $_POST['apellidos'];
    $tipo_documento = $_POST['tipo_documento'];
    $numero_documento = $_POST['numero_documento'];

    if (empty($club_id) || empty($nombre) || empty($apellidos) || empty($tipo_documento) || empty($numero_documento)) {
        echo "Todos los campos son obligatorios.";
        exit;
    }

    $query = "INSERT INTO participantes (club_id, nombre, apellidos, tipo_documento, numero_documento) 
              VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("issss", $club_id, $nombre, $apellidos, $tipo_documento, $numero_documento);

    if ($stmt->execute()) {
        echo "Participante registrado exitosamente.";
    } else {
        echo "Error al registrar el participante: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
} else {
    echo "Método no permitido.";
}
?>
