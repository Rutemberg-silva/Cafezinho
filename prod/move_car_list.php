<?php
include 'db_connect.php';
header('Content-Type: application/json');

// Decodificar o JSON recebido
$data = json_decode(file_get_contents('php://input'), true);

// Verificar se product_id e user_id estão presentes
if (empty($data['product_id']) || empty($data['user_id'])) {
    http_response_code(400); // Bad Request
    echo json_encode(['success' => false, 'message' => 'Dados incompletos']);
    exit;
}

$product_id = $data['product_id'];
$user_id = $data['user_id'];

// Buscar o item na lista de desejos para garantir que ele existe
$sql = "SELECT * FROM lista_desejos WHERE product_id = ? AND usuario_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('ii', $product_id, $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    http_response_code(404); // Not Found
    echo json_encode(['success' => false, 'message' => 'Item não encontrado na lista de desejos']);
    exit;
}

// Agora buscar os dados detalhados do produto na tabela `produtos`
$sql = "SELECT nome AS nome_produto, preco, imagem FROM produtos WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $product_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    http_response_code(500); // Internal Server Error
    echo json_encode(['success' => false, 'message' => 'Dados do produto incompletos']);
    exit;
}

$item = $result->fetch_assoc();

// Inserir o item na tabela carrinho
$sql = "INSERT INTO carrinho (produto_id, usuario_id, nome_produto, preco, imagem, quantidade, data_adicao)
        VALUES (?, ?, ?, ?, ?, ?, NOW())";

$stmt = $conn->prepare($sql);
$quantidade = 1; // Definindo a quantidade padrão como 1
$stmt->bind_param(
    'iisssi',
    $product_id,
    $user_id,
    $item['nome_produto'],
    $item['preco'],
    $item['imagem'],
    $quantidade
);

if ($stmt->execute()) {
    // Remover o item da lista de desejos
    $sql = "DELETE FROM lista_desejos WHERE product_id = ? AND usuario_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ii', $product_id, $user_id);
    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Produto movido para o carrinho com sucesso']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Erro ao remover o produto da lista de desejos']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Erro ao mover o produto para o carrinho']);
}

$stmt->close();
$conn->close();
?>
