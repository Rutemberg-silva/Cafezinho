<?php
// Inclui o arquivo de conexão com o banco de dados
include 'db_connect.php';

// Configura o cabeçalho da resposta como JSON
header('Content-Type: application/json');

// Adiciona isso logo após a linha que configura o cabeçalho para JSON
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Verifica se os dados necessários foram enviados via POST
if (isset($_POST['nome'], $_POST['preco'], $_POST['descricao'], $_POST['sugestoes']) && isset($_FILES['imagem'])) {
    $nome = $_POST['nome'];
    $preco = $_POST['preco'];
    $descricao = $_POST['descricao'];
    $sugestoes = $_POST['sugestoes']; // Captura o campo sugestões

    // Tratamento do upload de imagem
    $imagem = $_FILES['imagem'];
    $imagemPath = 'uploads/' . basename($imagem['name']); // Define o caminho do arquivo

    // Move o arquivo de imagem para o diretório de uploads
    if (move_uploaded_file($imagem['tmp_name'], $imagemPath)) {
        // Insere o produto no banco de dados
        $stmt = $conn->prepare("INSERT INTO produtos (nome, preco, descricao, sugestoes, imagem, data_cadastro) VALUES (?, ?, ?, ?, ?, NOW())");
        $stmt->bind_param("sdsss", $nome, $preco, $descricao, $sugestoes, $imagemPath); // Adiciona o parâmetro para sugestões

        // Verifica se a execução foi bem-sucedida
        if ($stmt->execute()) {
            echo json_encode(['success' => true, 'message' => 'Produto cadastrado com sucesso']);
        } else {
            echo json_encode(['success' => false, 'error' => 'Erro ao cadastrar o produto: ' . $stmt->error]);
        }
        $stmt->close();
    } else {
        echo json_encode(['success' => false, 'error' => 'Falha no upload da imagem']);
    }
} else {
    echo json_encode(['success' => false, 'error' => 'Dados incompletos']);
}

// Fecha a conexão com o banco
$conn->close();
?>
