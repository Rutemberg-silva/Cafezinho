<?php
// Inclui a conexão com o banco de dados
include 'db_connect.php';

// Configura o cabeçalho para JSON
header('Content-Type: application/json');

// Verifica se o usuário está logado e tem um ID de usuário válido
session_start();
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Usuário não autenticado']);
    exit;
}

$user_id = $_SESSION['user_id'];

// Recebe e decodifica a lista de IDs dos itens a serem removidos
$data = json_decode(file_get_contents("php://input"), true);
$idsToRemove = $data['ids'];

// Converte os IDs para uma lista de inteiros e prepara a consulta de exclusão
if (!empty($idsToRemove) && is_array($idsToRemove)) {
    $placeholders = implode(',', array_fill(0, count($idsToRemove), '?'));
    $sql = "DELETE FROM carrinho WHERE id IN ($placeholders) AND user_id = ?";
    $stmt = $conn->prepare($sql);
    $types = str_repeat('i', count($idsToRemove)) . 'i';
    $stmt->bind_param($types, ...$idsToRemove, $user_id);

    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Itens removidos com sucesso']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Erro ao remover itens']);
    }

    $stmt->close();
} else {
    echo json_encode(['success' => false, 'message' => 'Nenhum item selecionado para remoção']);
}

// Fecha a conexão
$conn->close();
?>
