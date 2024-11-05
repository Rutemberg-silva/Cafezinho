<?php
session_start();
require 'db_connect.php';

if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'admin') {
    echo json_encode(['success' => false, 'message' => 'Acesso negado.']);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);
$orderId = $data['id'];
$newStatus = $data['status'];

$query = "UPDATE pedidos SET status = ? WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param('si', $newStatus, $orderId);

if ($stmt->execute()) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => 'Erro ao atualizar o pedido.']);
}
?>
