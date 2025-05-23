<?php
include 'db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre_club = $_POST['nombre_club'];
    $direccion = $_POST['direccion'];
    $telefono = $_POST['telefono'];
    $correo = $_POST['correo'];

    $query = "INSERT INTO clubes (nombre, direccion, telefono, correo) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ssss", $nombre_club, $direccion, $telefono, $correo);

    if ($stmt->execute()) {
        header("Location: registrar_clubes.html?success=1");
    } else {
        header("Location: registrar_clubes.html?error=1");
    }
    $stmt->close();
    $conn->close();
}
?>
