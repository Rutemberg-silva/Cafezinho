<?php
session_start();
header('Content-Type: application/json');
include 'db_connect.php';

// Verifica se o usuário está logado
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Usuário não autenticado']);
    exit;
}

$data = json_decode(file_get_contents("php://input"), true);
$selectedItems = $data['selectedItems'] ?? [];

// Verifica se há itens para remover
if (empty($selectedItems)) {
    echo json_encode(['success' => false, 'message' => 'Nenhum item selecionado para remover']);
    exit;
}

$placeholders = implode(',', array_fill(0, count($selectedItems), '?'));
$sql = "DELETE FROM carrinho WHERE id IN ($placeholders)";
$stmt = $conn->prepare($sql);
$stmt->bind_param(str_repeat('i', count($selectedItems)), ...$selectedItems);

// Executa a remoção e envia a resposta
if ($stmt->execute()) {
    echo json_encode(['success' => true, 'message' => 'Itens removidos com sucesso']);
} else {
    echo json_encode(['success' => false, 'message' => 'Erro ao remover itens']);
}

$stmt->close();
$conn->close();
?>
