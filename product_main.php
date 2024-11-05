<?php
include 'db_connect.php'; // Conexão com o banco de dados

// Adicionando a coluna 'preco' à consulta SQL
$query = "SELECT nome, imagem, preco FROM produtos ORDER BY id ASC LIMIT 5";
$result = $conn->query($query);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $nomeProduto = htmlspecialchars($row['nome']);
        $imagem = htmlspecialchars($row['imagem']);
        $preco = htmlspecialchars($row['preco']); // Captura o preço do produto

        echo "<div class='product'>
                <img src='$imagem' alt='$nomeProduto' class='product-image-nosso'>
                <p>$nomeProduto</p>
                <p>Preço: R$ $preco</p> <!-- Exibindo o preço do produto -->
              </div>";
    }
} else {
    echo "<p>Nenhum produto encontrado.</p>";
}

$conn->close();
?>
