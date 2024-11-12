<?php
// Conecta ao banco de dados
include 'db_connect.php';

// Configura o cabeçalho para JSON
header('Content-Type: application/json');

// Consulta para selecionar todos os produtos
$sql = "SELECT id, nome, preco, descricao, imagem, sugestoes FROM produtos ORDER BY data_cadastro DESC";
$result = $conn->query($sql);

// Cria um array para armazenar os produtos
$products = [];

// Verifica se a consulta retornou resultados
if ($result->num_rows > 0) {
    // Adiciona cada linha ao array de produtos
    while ($row = $result->fetch_assoc()) {
        $products[] = [
            'id' => $row['id'],
            'nome' => $row['nome'],
            'preco' => $row['preco'],
            'descricao' => $row['descricao'],
            'imagem' => $row['imagem'],
            'sugestoes' => $row['sugestoes'] 
        ];
    }
    echo json_encode(['success' => true, 'products' => $products]);
} else {
    echo json_encode(['success' => false, 'message' => 'Nenhum produto encontrado']);
}

// Fecha a conexão
$conn->close();
?>
