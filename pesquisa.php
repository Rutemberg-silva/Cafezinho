<?php
session_start(); // Inicia a sessão para acessar o usuario_id

require 'db_connect.php';

// Lê os dados JSON enviados pela requisição
$data = json_decode(file_get_contents('php://input'), true);

// Obtém os dados do JSON
$orderId = isset($data['orderId']) ? (int)$data['orderId'] : 0; // Pedidos_id
$avaliacao = isset($data['avaliacao']) ? (int)$data['avaliacao'] : null;
$comentario = isset($data['comentario']) ? $data['comentario'] : '';
$response = ['success' => false];

if ($orderId > 0 && $avaliacao >= 1 && $avaliacao <= 5 && !empty($comentario)) {
    // Busca o usuario_id a partir do pedidos_id
    $sql = "SELECT usuario_id FROM pedidos WHERE id = ?"; // Usando 'id' como chave primária
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $orderId);
    $stmt->execute();
    $stmt->bind_result($user_id);
    $stmt->fetch();
    $stmt->close();

    if ($user_id) { // Verifica se o usuario_id foi encontrado
        // Prepara a consulta SQL para inserir a avaliação
        $sql = "INSERT INTO avaliacoes (usuario_id, avaliacao, comentario, data_avaliacao) 
                VALUES (?, ?, ?, NOW())";

        $stmt = $conn->prepare($sql);
        // Bind dos parâmetros
        $stmt->bind_param('iis', $user_id, $avaliacao, $comentario);
        
        // Executa a consulta
        if ($stmt->execute()) {
            // Atualiza o status do pedido para 'avaliado'
            $updateSql = "UPDATE pedidos SET status = 'avaliado' WHERE id = ?";
            $updateStmt = $conn->prepare($updateSql);
            $updateStmt->bind_param('i', $orderId);

            if ($updateStmt->execute()) {
                $response['success'] = true;
            } else {
                $response['message'] = 'Erro ao atualizar o status do pedido: ' . $updateStmt->error;
            }
            $updateStmt->close();
        } else {
            $response['message'] = 'Erro ao executar a consulta: ' . $stmt->error;
        }
        $stmt->close();
    } else {
        $response['message'] = 'Pedido não encontrado ou usuário não associado.';
    }
} else {
    $response['message'] = 'Dados inválidos. Verifique os valores enviados.';
}

// Retorna a resposta como JSON
echo json_encode($response);
$conn->close();
?>
