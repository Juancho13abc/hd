<?php
include 'db_connection.php'; 

header('Content-Type: text/html; charset=utf-8');


$query = "SELECT id, nombre FROM clubes";
$result = $conn->query($query);

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {

        echo '<option value="' . htmlspecialchars($row['id'], ENT_QUOTES, 'UTF-8') . '">' . htmlspecialchars($row['nombre'], ENT_QUOTES, 'UTF-8') . '</option>';
    }
} else {
    echo '<option value="">No hay clubes registrados</option>';
}

$conn->close(); 
?>
