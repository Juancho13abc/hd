<?php
session_start();
header('Content-Type: application/json');
include 'conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    $torneo_id = $data['torneo_id'] ?? null;
    $categorias = $data['categorias'] ?? [];
    $user_id = $_SESSION['user_id'] ?? null;

    if (!$user_id) {
        echo json_encode(['success' => false, 'message' => 'Usuario no autenticado.']);
        exit;
    }

    if (!$torneo_id || empty($categorias)) {
        echo json_encode(['success' => false, 'message' => 'Datos incompletos.']);
        exit;
    }

    foreach ($categorias as $categoria) {
        // Verificar si ya existe una inscripción para esta categoría
        $query_check = "SELECT id FROM inscripciones WHERE torneo_id = ? AND usuario_id = ? AND categoria = ?";
        $stmt_check = $conn->prepare($query_check);
        $stmt_check->bind_param("iis", $torneo_id, $user_id, $categoria);
        $stmt_check->execute();
        $result_check = $stmt_check->get_result();

        if ($result_check->num_rows > 0) {
            echo json_encode(['success' => false, 'message' => "Ya estás inscrito en la categoría: $categoria."]);
            exit;
        }

        // Insertar la inscripción si no existe
        $query = "INSERT INTO inscripciones (torneo_id, usuario_id, categoria) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("iis", $torneo_id, $user_id, $categoria);
        if (!$stmt->execute()) {
            echo json_encode(['success' => false, 'message' => 'Error al inscribirse en la categoría: ' . $categoria]);
            exit;
        }
    }

    echo json_encode(['success' => true, 'message' => 'Inscripción realizada exitosamente.']);
    $stmt->close();
    $conn->close();
} else {
    echo json_encode(['success' => false, 'message' => 'Método no permitido.']);
}
