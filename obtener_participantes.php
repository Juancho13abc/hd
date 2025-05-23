<?php
include 'conexion.php';

$club_id = $_GET['club_id'] ?? null;

if ($club_id) {
    $query = "SELECT id, nombre, apellidos, tipo_documento, numero_documento FROM participantes WHERE club_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $club_id);
    $stmt->execute();
    $result = $stmt->get_result();

    $participants = [];
    while ($row = $result->fetch_assoc()) {
        $participants[] = $row;
    }

    echo json_encode($participants);
} else {
    echo json_encode([]);
}

$conn->close();
?>
