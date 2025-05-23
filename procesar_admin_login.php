<?php
session_start();

$admin_email = 'admin@admin.com';
$admin_password = 'admin1234';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    if ($email === $admin_email && $password === $admin_password) {
        $_SESSION['admin_logged_in'] = true;
        header('Location: registrar_clubes.html');
        exit;
    } else {
        header('Location: admin_login.html?error=1');
        exit;
    }
} else {
    header('Location: admin_login.html');
    exit;
}
?>
