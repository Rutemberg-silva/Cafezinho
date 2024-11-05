<?php
session_start();
header('Content-Type: application/json');

// Verifica se o usuário está autenticado
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Usuário não autenticado']);
    exit;
}

// Retorna o ID do usuário logado
$user_id = $_SESSION['user_id'];
echo json_encode(['success' => true, 'user_id' => $user_id]);
?>
