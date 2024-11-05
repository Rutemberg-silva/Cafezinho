<?php
require 'db_connect.php';
$data = json_decode(file_get_contents('php://input'), true);

$user_id = isset($data['user_id']) ? (int)$data['user_id'] : 0;
$rating = isset($data['rating']) ? (int)$data['rating'] : null;
$comment = isset($data['comment']) ? $data['comment'] : '';
$response = ['success' => false];

if ($user_id > 0 && $rating >= 1 && $rating <= 5 && !empty($comment)) {
    $sql = "INSERT INTO avaliacoes (usuario_id, produto_id, avaliacao, comentario, data_avaliacao) 
            VALUES (?, ?, ?, ?, NOW())";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param('iiis', $user_id, $produto_id, $rating, $comment);
    
    if ($stmt->execute()) {
        $response['success'] = true;
    }
}

echo json_encode($response);
$conn->close();
