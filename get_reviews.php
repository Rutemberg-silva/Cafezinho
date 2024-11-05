<?php
include 'db_connect.php';

$sql = "SELECT avaliacoes.avaliacao, avaliacoes.comentario, avaliacoes.data_avaliacao, usuarios.nome
        FROM avaliacoes 
        JOIN usuarios ON avaliacoes.usuario_id = usuarios.id";
$result = $conn->query($sql);

$reviews = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $reviews[] = [
            'avaliacao' => $row['avaliacao'],
            'comentario' => $row['comentario'],
            'data_avaliacao' => $row['data_avaliacao'],
            'nome' => $row['nome']
        ];
    }
}

echo json_encode(['success' => true, 'reviews' => $reviews]);
?>
