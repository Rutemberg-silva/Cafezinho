<?php
require 'db_connect.php';

$response = ['success' => false, 'reviews' => []];

$sql = "SELECT avaliacao, comentario, data_avaliacao FROM avaliacoes ORDER BY data_avaliacao DESC";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $response['reviews'][] = [
            'avaliacao' => (int)$row['avaliacao'],
            'comentario' => $row['comentario'],
            'data_avaliacao' => $row['data_avaliacao']
        ];
    }
    $response['success'] = true;
}

echo json_encode($response);
$conn->close();
