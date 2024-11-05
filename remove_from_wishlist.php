<?php
session_start();
include 'db_connect.php';

header('Content-Type: application/json');

// Verifica se o usuário está autenticado
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Usuário não autenticado']);
    exit;
}

$data = json_decode(file_get_contents("php://input"), true);
$product_id = $data['product_id'] ?? null;
$user_id = $_SESSION['user_id'];

if (!$product_id) {
    echo json_encode(['success' => false, 'message' => 'ID do produto inválido']);
    exit;
}

// Remove o produto da lista de desejos do usuário logado
$sql = "DELETE FROM lista_desejos WHERE product_id = ? AND usuario_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $product_id, $user_id);

if ($stmt->execute()) {
    echo json_encode(['success' => true, 'message' => 'Produto removido da lista de desejos']);
} else {
    echo json_encode(['success' => false, 'message' => 'Erro ao remover produto da lista de desejos']);
}

$stmt->close();
$conn->close();
?>
