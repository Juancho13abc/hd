<?php
require 'conexion.php'; 

$torneo_id = isset($_GET['torneo_id']) ? intval($_GET['torneo_id']) : 0;

$query = "SELECT DISTINCT categoria FROM inscripciones WHERE torneo_id = $torneo_id";
$result = $conn->query($query);

$categorias = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $categorias[] = $row['categoria'];
    }
}

header('Content-Type: application/json');
echo json_encode($categorias);
?>
