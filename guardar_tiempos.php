<?php
$conn = new mysqli('localhost', 'root', '', 'liga_natacion');
if ($conn->connect_error) {
    echo json_encode(['success' => false, 'error' => $conn->connect_error]);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);

if (!isset($data['tiempos'])) {
    echo json_encode(['success' => false, 'error' => 'No data']);
    exit;
}

// Asegúrate de que la tabla resultados existe
$conn->query("CREATE TABLE IF NOT EXISTS resultados (
    id INT AUTO_INCREMENT PRIMARY KEY,
    inscripcion_id INT NOT NULL,
    minutos INT NOT NULL,
    segundos INT NOT NULL,
    centesimas INT NOT NULL,
    FOREIGN KEY (inscripcion_id) REFERENCES inscripciones(id) ON DELETE CASCADE
)");

$ok = true;
$errorMsg = '';
foreach ($data['tiempos'] as $t) {
    $inscripcion_id = intval($t['inscripcion_id']);
    $minutos = intval($t['minutos']);
    $segundos = intval($t['segundos']);
    $centesimas = intval($t['centesimas']);
    $res = $conn->query("SELECT id FROM resultados WHERE inscripcion_id = $inscripcion_id");
    if ($res && $res->num_rows > 0) {
        $conn->query("UPDATE resultados SET minutos=$minutos, segundos=$segundos, centesimas=$centesimas WHERE inscripcion_id=$inscripcion_id");
    } else {
        $conn->query("INSERT INTO resultados (inscripcion_id, minutos, segundos, centesimas) VALUES ($inscripcion_id, $minutos, $segundos, $centesimas)");
    }
    if ($conn->error) {
        $ok = false;
        $errorMsg = $conn->error;
        break;
    }
}

echo json_encode(['success' => $ok, 'error' => $errorMsg]);
$conn->close();
?>
