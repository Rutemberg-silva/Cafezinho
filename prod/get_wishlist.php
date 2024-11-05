<?php
session_start();
include 'db_connect.php';

header('Content-Type: application/json');

// Verifica se o usuário está autenticado
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Usuário não autenticado']);
    exit;
}

$user_id = $_SESSION['user_id'];

// Consulta os produtos na lista de desejos
$sql = "SELECT ld.id, p.id AS product_id, p.nome, p.preco, p.imagem
        FROM lista_desejos ld
        JOIN produtos p ON ld.product_id = p.id
        WHERE ld.usuario_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

$wishlist = [];
while ($row = $result->fetch_assoc()) {
    $wishlist[] = [
        'id' => $row['id'],
        'product_id' => $row['product_id'],
        'nome' => $row['nome'],
        'preco' => $row['preco'],
        'imagem' => $row['imagem']
    ];
}

$stmt->close();
$conn->close();

echo json_encode(['success' => true, 'wishlist' => $wishlist]);
?>
