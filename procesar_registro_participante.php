<?php
include 'conexion.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $club_id = $_POST['club'];
    $nombre = $_POST['nombre'];
    $apellidos = $_POST['apellidos'];
    $tipo_documento = $_POST['tipo_documento'];
    $numero_documento = $_POST['numero_documento'];

    // Validar que los campos no estén vacíos
    if (empty($club_id) || empty($nombre) || empty($apellidos) || empty($tipo_documento) || empty($numero_documento)) {
        echo json_encode(['success' => false, 'message' => 'Todos los campos son obligatorios.']);
        exit;
    }

    // Insertar el participante en la base de datos
    $query = "INSERT INTO participantes (club_id, nombre, apellidos, tipo_documento, numero_documento) 
              VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("issss", $club_id, $nombre, $apellidos, $tipo_documento, $numero_documento);

    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Participante registrado exitosamente.']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error al registrar el participante.']);
    }

    $stmt->close();
    $conn->close();
} else {
    echo json_encode(['success' => false, 'message' => 'Método no permitido.']);
}
