<?php
require 'db_connect.php';

// Lê os dados JSON enviados pela requisição
$data = json_decode(file_get_contents('php://input'), true);

// Obtém os dados do JSON
$user_id = isset($data['usuario_id']) ? (int)$data['usuario_id'] : 0; // ID do usuário que está avaliando
$avaliacao = isset($data['avaliacao']) ? (int)$data['avaliacao'] : null; // Nota de avaliação
$comentario = isset($data['comentario']) ? $data['comentario'] : ''; // Comentário da avaliação
$order_id = isset($data['orderId']) ? (int)$data['orderId'] : null; // ID do pedido relacionado

$response = ['success' => false];

// Debug: Exibir os valores recebidos (opcional)
error_log("user_id: $user_id, avaliacao: $avaliacao, comentario: '$comentario', order_id: $order_id");

// Verificações de validação
if ($user_id <= 0) {
    $response['message'] = 'Usuário não identificado. Faça login para enviar uma avaliação.';
    echo json_encode($response);
    exit;
}

if ($avaliacao < 1 || $avaliacao > 5) {
    $response['message'] = 'A avaliação deve estar entre 1 e 5.';
    echo json_encode($response);
    exit;
}

if (empty($comentario)) {
    $response['message'] = 'O comentário não pode estar vazio.';
    echo json_encode($response);
    exit;
}

if (!$order_id) {
    $response['message'] = 'ID do pedido inválido.';
    echo json_encode($response);
    exit;
}

// Prepara a consulta SQL
$sql = "INSERT INTO avaliacoes (usuario_id, avaliacao, comentario, data_avaliacao) 
        VALUES (?, ?, ?, NOW())";

$stmt = $conn->prepare($sql);
$stmt->bind_param('iis', $user_id, $avaliacao, $comentario);

// Executa a consulta
if ($stmt->execute()) {
    $response['success'] = true;
} else {
    $response['message'] = 'Erro ao executar a consulta: ' . $stmt->error;
}

// Retorna a resposta como JSON
echo json_encode($response);
$conn->close();
?>
