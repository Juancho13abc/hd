<?php
session_start();
header('Content-Type: application/json');

include 'conexion.php';

if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    $query = "SELECT fecha_nacimiento FROM usuarios WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        echo json_encode([
            'logged_in' => true,
            'fecha_nacimiento' => $user['fecha_nacimiento']
        ]);
    } else {
        echo json_encode(['logged_in' => false]);
    }
    $stmt->close();
} else {
    echo json_encode(['logged_in' => false]);
}

$conn->close();
?>
