<?php
include 'db_connect.php';  // Inclui o arquivo de conexão com o banco de dados
header('Content-Type: application/json'); // Define o tipo de conteúdo como JSON

if (isset($_GET['id'])) {
    $produto_id = $_GET['id'];

    // Prepara a consulta para obter o preço do produto
    $stmt = $conn->prepare("SELECT preco FROM produtos WHERE id = ?");
    $stmt->bind_param('i', $produto_id);
    $stmt->execute();
    $stmt->bind_result($preco);
    $stmt->fetch();
    $stmt->close();

    // Retorna o preço em formato JSON
    echo json_encode(['preco' => $preco]);
} else {
    // Se o ID do produto não for passado, retorna um erro
    echo json_encode(['error' => 'ID do produto não fornecido.']);
}
?>
