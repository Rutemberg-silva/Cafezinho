<?php
session_start();
include 'db_connect.php';
header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Usuário não autenticado', 'session_data' => $_SESSION]);
    exit;
}

$user_id = $_SESSION['user_id'];
$product_id = $_POST['product_id'] ?? null;

if ($product_id) {
    $stmt = $conn->prepare("INSERT INTO lista_desejos (usuario_id, product_id) VALUES (?, ?)");
    $stmt->bind_param("ii", $user_id, $product_id);
    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Produto adicionado à lista de desejos']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Erro ao adicionar à lista de desejos']);
    }
    $stmt->close();
} else {
    echo json_encode(['success' => false, 'message' => 'ID do produto ausente']);
}
$conn->close();
?>
