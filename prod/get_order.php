<?php
session_start();
require 'db_connect.php';

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Usuário não logado.']);
    exit;
}

$userId = $_SESSION['user_id'];
$status = isset($_GET['status']) ? $_GET['status'] : 'pendente';

// Valida e sanitiza o status para evitar injeções
$allowed_status = ['pendente', 'concluido', 'cancelado'];
if (!in_array($status, $allowed_status)) {
    echo json_encode(['success' => false, 'message' => 'Status inválido.']);
    exit;
}

// Adiciona depuração para verificar userId e status
file_put_contents('log.txt', "UserId: $userId, Status: $status\n", FILE_APPEND);

$query = "SELECT p.*, GROUP_CONCAT(prod.nome SEPARATOR ', ') AS produtos 
          FROM pedidos p
          JOIN pedido_itens pi ON p.id = pi.pedido_id
          JOIN produtos prod ON pi.produto_id = prod.id
          WHERE p.usuario_id = ? AND p.status = ?
          GROUP BY p.id";
$stmt = $conn->prepare($query);
$stmt->bind_param('is', $userId, $status);

if (!$stmt->execute()) {
    file_put_contents('log.txt', "Erro na execução da consulta: " . $stmt->error . "\n", FILE_APPEND);
    echo json_encode(['success' => false, 'message' => 'Erro ao buscar pedidos.']);
    exit;
}

$result = $stmt->get_result();

$orders = [];
while ($order = $result->fetch_assoc()) {
    $order['total'] = floatval($order['total']);
    $orders[] = $order;
}

if (empty($orders)) {
    file_put_contents('log.txt', "Nenhum pedido encontrado para o usuário $userId com status $status\n", FILE_APPEND);
}

echo json_encode(['success' => true, 'orders' => $orders]);
