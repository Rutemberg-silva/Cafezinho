<?php
session_start();
require 'db_connect.php';

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Usuário não logado.']);
    exit;
}

$userId = $_SESSION['user_id'];
$data = json_decode(file_get_contents('php://input'), true);

$selectedItems = $data['selectedItems'];
$paymentMethod = $data['paymentMethod'];
$deliveryOption = $data['deliveryOption'];

$total = 0;

// Calcula o total dos itens selecionados e os remove do carrinho
foreach ($selectedItems as $itemId) {
    $query = "SELECT preco, quantidade FROM carrinho WHERE id = ? AND usuario_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('ii', $itemId, $userId);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($row = $result->fetch_assoc()) {
        $total += (float) str_replace(',', '.', $row['preco']);
        // Remove o item do carrinho
        $deleteQuery = "DELETE FROM carrinho WHERE id = ?";
        $deleteStmt = $conn->prepare($deleteQuery);
        $deleteStmt->bind_param('i', $itemId);
        $deleteStmt->execute();
    }
}

// Insere o pedido na tabela pedidos
$query = "INSERT INTO pedidos (usuario_id, total, metodo_pagamento, endereco_entrega, status, data_pedido) VALUES (?, ?, ?, ?, 'pendente', NOW())";
$stmt = $conn->prepare($query);
$enderecoEntrega = "Endereço cadastrado: Rua Exemplo, 123"; // Aqui você pode pegar o endereço do banco de dados
$stmt->bind_param('idss', $userId, $total, $paymentMethod, $enderecoEntrega);
$stmt->execute();

// Responde com sucesso
echo json_encode(['success' => true, 'message' => 'Pedido realizado com sucesso!']);
?>
