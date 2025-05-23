<?php
require 'conexion.php'; 

$query = "SELECT id, nombre FROM torneos";
$result = $conn->query($query);

$torneos = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $torneos[] = $row;
    }
}

header('Content-Type: application/json');
echo json_encode($torneos);

$conn->close();
?>
