<?php
require 'conexion.php'; 

$torneo_id = isset($_GET['torneo_id']) ? intval($_GET['torneo_id']) : 0;
$categoria = isset($_GET['categoria']) ? $conn->real_escape_string($_GET['categoria']) : '';

$query = "SELECT CONCAT(u.nombre, ' ', u.apellidos) AS usuario, 
                 u.foto_perfil AS foto, 
                 c.nombre AS club,
                 i.id AS id
          FROM inscripciones i
          JOIN torneos t ON i.torneo_id = t.id
          JOIN usuarios u ON i.usuario_id = u.id
          JOIN clubes c ON u.club = c.id
          WHERE i.torneo_id = $torneo_id";

if (!empty($categoria)) {
    $query .= " AND i.categoria = '$categoria'";
}

$result = $conn->query($query);

$inscripciones = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $row['inscripcion_id'] = $row['id'];

        $resTiempo = $conn->query("SELECT minutos, segundos, centesimas FROM resultados WHERE inscripcion_id = " . intval($row['id']));
        if ($resTiempo && $resTiempo->num_rows > 0) {
            $tiempo = $resTiempo->fetch_assoc();
            $row['minutos'] = $tiempo['minutos'];
            $row['segundos'] = $tiempo['segundos'];
            $row['centesimas'] = $tiempo['centesimas'];
        } else {
            $row['minutos'] = '';
            $row['segundos'] = '';
            $row['centesimas'] = '';
        }
        $inscripciones[] = $row;
    }
}

header('Content-Type: application/json');
echo json_encode($inscripciones);
?>
