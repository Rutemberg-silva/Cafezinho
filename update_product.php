<?php
include 'db_connect.php';
header('Content-Type: application/json');

if (isset($_POST['id'])) {
    $id = $_POST['id'];
    $nome = $_POST['nome'] ?? null;
    $preco = $_POST['preco'] ?? null;
    $descricao = $_POST['descricao'] ?? null;
    $sugestoes = $_POST['sugestoes'] ?? null;

    // Inicia a query de atualização
    $query = "UPDATE produtos SET ";
    $params = [];
    $types = '';

    // Adiciona apenas os campos alterados na query
    if ($nome) {
        $query .= "nome = ?, ";
        $params[] = $nome;
        $types .= 's';
    }
    if ($preco) {
        $query .= "preco = ?, ";
        $params[] = $preco;
        $types .= 'd';
    }
    if ($descricao) {
        $query .= "descricao = ?, ";
        $params[] = $descricao;
        $types .= 's';
    }
    if ($sugestoes) {
        $query .= "sugestoes = ?, ";
        $params[] = $sugestoes;
        $types .= 's';
    }

    // Verifica se uma nova imagem foi enviada
    if (isset($_FILES['imagem'])) {
        $imagem = $_FILES['imagem'];
        $imagemPath = 'uploads/' . basename($imagem['name']);

        // Faz upload da nova imagem
        if (move_uploaded_file($imagem['tmp_name'], $imagemPath)) {
            $query .= "imagem = ?, ";
            $params[] = $imagemPath;
            $types .= 's';
        } else {
            echo json_encode(['success' => false, 'error' => 'Falha no upload da imagem']);
            exit;
        }
    }

    // Remove a última vírgula e espaço da query e adiciona a condição WHERE
    $query = rtrim($query, ', ') . " WHERE id = ?";
    $params[] = $id;
    $types .= 'i';

    // Prepara e executa a atualização no banco
    $stmt = $conn->prepare($query);
    $stmt->bind_param($types, ...$params);

    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Produto atualizado com sucesso']);
    } else {
        echo json_encode(['success' => false, 'error' => 'Erro ao atualizar o produto: ' . $stmt->error]);
    }

    $stmt->close();
} else {
    echo json_encode(['success' => false, 'error' => 'ID do produto não especificado']);
}

$conn->close();
?>
