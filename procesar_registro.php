<?php
include __DIR__ . '/db_connection.php'; // Asegúrate de que la ruta sea correcta

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['nombre'];
    $apellidos = $_POST['apellidos'];
    $fecha_nacimiento = $_POST['fecha_nacimiento'];
    $tipo_documento = $_POST['tipo_documento'];
    $numero_documento = $_POST['numero_documento'];
    $email = $_POST['correo'];
    $password = password_hash($_POST['contraseña'], PASSWORD_BCRYPT);
    $genero = $_POST['genero'];
    $club = $_POST['club'];
    $foto_perfil = $_FILES['foto_perfil']['name'];

    // Validar que el número de documento pertenece al club seleccionado
    $query_check_document = "SELECT id FROM participantes WHERE numero_documento = ? AND club_id = ?";
    $stmt_check_document = $conn->prepare($query_check_document);
    $stmt_check_document->bind_param("si", $numero_documento, $club);
    $stmt_check_document->execute();
    $result_check_document = $stmt_check_document->get_result();

    if ($result_check_document->num_rows === 0) {
        // Redirigir con mensaje de error si el documento no pertenece al club
        header("Location: login.html?error=document_not_in_club");
        exit();
    }
    $stmt_check_document->close();

    // Subir la foto de perfil
    $target_dir = "uploads/";
    $target_file = $target_dir . basename($foto_perfil);
    move_uploaded_file($_FILES['foto_perfil']['tmp_name'], $target_file);

    // Insertar el usuario en la base de datos
    $query = "INSERT INTO usuarios (nombre, apellidos, fecha_nacimiento, tipo_documento, numero_documento, email, password, genero, club, foto_perfil) 
              VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ssssssssss", $nombre, $apellidos, $fecha_nacimiento, $tipo_documento, $numero_documento, $email, $password, $genero, $club, $target_file);

    if ($stmt->execute()) {
        header("Location: login.html?success=registered");
        exit();
    } else {
        header("Location: login.html?error=registration_failed");
        exit();
    }
}
?>
