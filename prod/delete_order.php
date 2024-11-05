<?php
session_start();
require 'db_connection.php';

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Usuário não logado.']);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);
$orderId = $data['id'];

$query = "DELETE FROM pedidos WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param('i', $orderId);
$stmt->execute();

if ($stmt->affected_rows > 0) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => 'Erro ao excluir pedido.']);
}
?>
