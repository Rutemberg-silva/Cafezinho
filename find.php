<?php
include 'db_connect.php'; // Conexão com o banco de dados

$searchTerm = isset($_GET['search']) ? trim($_GET['search']) : '';

if (!empty($searchTerm)) {
    $query = "SELECT nome, preco, imagem FROM produtos WHERE nome LIKE ?";
    $stmt = $conn->prepare($query);
    $searchTerm = '%' . $searchTerm . '%';
    $stmt->bind_param("s", $searchTerm);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $nomeProduto = htmlspecialchars($row['nome']);
            $precoProduto = htmlspecialchars($row['preco']);
            $imagem = htmlspecialchars($row['imagem']);

            echo "<div class='product'>
                    <img src='$imagem' alt='$nomeProduto' width='239' height='300' class='product-image-find'>
                    <p>$nomeProduto</p>
                    <p>R$ $precoProduto</p>
                  </div>";
        }
    } else {
        echo "<p>Nenhum produto encontrado com este nome.</p>";
    }
    $stmt->close();
} else {
    echo "<p>Nenhum termo de pesquisa fornecido.</p>";
}

$conn->close();
?>
