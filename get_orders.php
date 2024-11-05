<?php
include 'db_connect.php';
session_start();

$user_id = $_SESSION['user_id'];
$user_type = $_SESSION['user_type'];

if (!$user_id) {
    echo json_encode(['success' => false, 'message' => 'Usuário não autenticado.']);
    exit;
}

if ($user_type === 'admin') {
    // Admin: seleciona todos os pedidos
    $sql = "SELECT * FROM pedidos";
    $stmt = $conn->prepare($sql);
} else {
    // Usuário comum: seleciona apenas os pedidos do próprio usuário
    $sql = "SELECT * FROM pedidos WHERE usuario_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $user_id);
}

$stmt->execute();
$result = $stmt->get_result();
$orders = [];

while ($row = $result->fetch_assoc()) {
    $orders[] = $row;
}

echo json_encode(['success' => true, 'orders' => $orders, 'userType' => $user_type]);
$stmt->close();
$conn->close();
?>
