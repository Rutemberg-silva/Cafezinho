<?php
session_start();
include 'db_connect.php';

header('Content-Type: application/json');

if (isset($_GET['debug']) && $_GET['debug'] == '1') {
    var_dump($_SESSION);
    exit;
}


// Verifica se o usuário está autenticado
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Usuário não autenticado']);
    exit;
}

$user_id = $_SESSION['user_id'];
$data = json_decode(file_get_contents("php://input"), true);

// Obtém os detalhes do produto
$product_id = $data['productId'] ?? null;
$nome_produto = $data['nomeProduto'] ?? '';
$preco = $data['preco'] ?? 0;
$imagem = $data['imagem'] ?? '';
$quantidade = $data['quantidade'] ?? 1;

if (!$product_id) {
    echo json_encode(['success' => false, 'message' => 'ID do produto inválido']);
    exit;
}

// Insere o produto no carrinho
$sql = "INSERT INTO carrinho (usuario_id, produto_id, nome_produto, preco, imagem, quantidade, data_adicao) 
        VALUES (?, ?, ?, ?, ?, ?, NOW())";
$stmt = $conn->prepare($sql);
$stmt->bind_param("iisdsi", $user_id, $product_id, $nome_produto, $preco, $imagem, $quantidade);

if ($stmt->execute()) {
    echo json_encode(['success' => true, 'message' => 'Produto adicionado ao carrinho']);
} else {
    echo json_encode(['success' => false, 'message' => 'Erro ao adicionar produto ao carrinho']);
}

$stmt->close();
$conn->close();
?>
