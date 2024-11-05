<?php
session_start();
header('Content-Type: application/json');
include 'db_connect.php';

// Verifica se o usuário está logado
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Usuário não autenticado']);
    exit;
}

$user_id = $_SESSION['user_id'];

// Consulta para obter os itens do carrinho do usuário
$sql = "SELECT id, imagem, nome_produto, preco, quantidade FROM carrinho WHERE usuario_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

$cartItems = [];
$totalPrice = 0;

while ($row = $result->fetch_assoc()) {
    // Converte o valor de 'preco' para decimal para realizar operações matemáticas
    $preco = floatval(str_replace(',', '.', $row['preco'])); 
    $quantidade = intval($row['quantidade']);

    // Calcula o preço total do item (preco * quantidade)
    $itemTotal = $preco * $quantidade;
    $totalPrice += $itemTotal;

    // Adiciona o item ao array e formata o preço para exibição
    $row['preco'] = number_format($preco, 2, ',', '.'); // Converte para BRL formatado
    $cartItems[] = $row;
}

// Retorna os itens do carrinho e o total ao frontend, ambos formatados
echo json_encode([
    'success' => true,
    'cartItems' => $cartItems,
    'totalPrice' => number_format($totalPrice, 2, ',', '.')
]);

$stmt->close();
$conn->close();
?>
