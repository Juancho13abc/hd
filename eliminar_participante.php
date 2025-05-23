<?php
include 'conexion.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    $participantId = $data['id'] ?? null;

    if (!$participantId) {
        echo json_encode(['success' => false, 'message' => 'ID del participante no proporcionado.']);
        exit;
    }

    // Eliminar el participante de la base de datos
    $query = "DELETE FROM participantes WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $participantId);

    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Participante eliminado exitosamente.']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error al eliminar el participante.']);
    }

    $stmt->close();
    $conn->close();
} else {
    echo json_encode(['success' => false, 'message' => 'Método no permitido.']);
}
