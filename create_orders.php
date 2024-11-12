<?php
include 'db_connection.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario_id = $_SESSION['user_id'];
    $produtos = $_POST['produtos']; 
    $total = $_POST['total'];

    // Inicia a transação
    $conn->begin_transaction();

    try {
        // Insere o pedido
        $stmt = $conn->prepare("INSERT INTO pedidos (usuario_id, total, data_pedido) VALUES (?, ?, NOW())");
        $stmt->bind_param('id', $usuario_id, $total);
        $stmt->execute();
        $pedido_id = $stmt->insert_id;

        // Insere os itens do pedido
        foreach ($produtos as $produto) {
            $produto_id = $produto['id'];
            $quantidade = $produto['quantidade'];
            $stmt = $conn->prepare("INSERT INTO pedido_itens (pedido_id, produto_id, quantidade) VALUES (?, ?, ?)");
            $stmt->bind_param('iii', $pedido_id, $produto_id, $quantidade);
            $stmt->execute();
        }

        // Comita a transação
        $conn->commit();
        echo json_encode(['success' => true]);
    } catch (Exception $e) {
        // Em caso de erro, reverte a transação
        $conn->rollback();
        echo json_encode(['success' => false, 'message' => 'Erro ao criar pedido.']);
    }
}
?>
