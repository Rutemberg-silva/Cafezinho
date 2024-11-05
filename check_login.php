<?php
session_start();
include 'db_connect.php';

header('Content-Type: application/json');

// Verifica se o usuário está logado
if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
    echo json_encode([
        'loggedIn' => true,
        'username' => $_SESSION['username'],
        'userType' => $_SESSION['user_type']
    ]);
} else {
    echo json_encode(['loggedIn' => false]);
}
?>

