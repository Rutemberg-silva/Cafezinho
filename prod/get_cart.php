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

// Consulta os itens do carrinho do usuário
$sql = "SELECT c.id, p.nome AS nome_produto, p.imagem, p.preco 
        FROM carrinho c 
        JOIN produtos p ON c.produto_id = p.id 
        WHERE c.user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

// Cria um array para armazenar os itens do carrinho
$cartItems = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $cartItems[] = [
            'id' => $row['id'],
            'nome_produto' => $row['nome_produto'],
            'imagem' => $row['imagem'],
            'preco' => $row['preco']
        ];
    }
}

// Retorna os itens do carrinho em formato JSON
echo json_encode(['success' => true, 'cartItems' => $cartItems]);

// Fecha a conexão
$stmt->close();
$conn->close();
?>
