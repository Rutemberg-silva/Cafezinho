<?php
require 'db_connect.php';

$user_id = isset($_GET['user_id']) ? (int)$_GET['user_id'] : 0;
$response = ['canReview' => false];

if ($user_id > 0) {
    $sql = "SELECT p.id 
            FROM pedidos AS p 
            LEFT JOIN avaliacoes AS a ON p.id = a.produto_id AND a.usuario_id = ?
            WHERE p.usuario_id = ? AND p.status = 'concluido' AND a.id IS NULL";
            
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ii', $user_id, $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $response['canReview'] = true;
    }
}

echo json_encode($response);
$conn->close();
