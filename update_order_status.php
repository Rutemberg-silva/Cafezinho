<?php
include 'db_connect.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    $order_id = $data['id'];
    $new_status = $data['status'];
    $user_type = $_SESSION['user_type'];

    if ($user_type !== 'admin') {
        echo json_encode(['success' => false, 'message' => 'Permissão negada']);
        exit;
    }

    $stmt = $conn->prepare("UPDATE pedidos SET status = ? WHERE id = ?");
    $stmt->bind_param('si', $new_status, $order_id);

    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Status do pedido atualizado com sucesso']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Erro ao atualizar o status do pedido']);
    }

    $stmt->close();
    $conn->close();
}
?>
