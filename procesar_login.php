<?php
session_start();
include __DIR__ . '/db_connection.php'; // Asegúrate de que la ruta sea correcta

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $query = "SELECT id, password FROM usuarios WHERE email = ? LIMIT 1";
    $stmt = $conn->prepare($query); // $conn ahora estará definido correctamente
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            header("Location: index.html");
            exit();
        }
    }
    header("Location: login.html?error=invalid_credentials");
    exit();
}
?>
