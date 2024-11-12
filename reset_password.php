<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Inclua a conexão com o banco de dados
include 'db_connect.php';

// Verifica se a requisição é POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Captura o ID do usuário enviado pelo JavaScript
    $userId = $_POST['id'];

    // Gera a nova senha 
    $newPassword = password_hash('cafezinho123', PASSWORD_DEFAULT);
    
    // Prepara a consulta para atualizar a senha no banco de dados
    $stmt = $conn->prepare("UPDATE usuarios SET senha = ? WHERE id = ?");
    $stmt->bind_param("si", $newPassword, $userId);
    
    // Tenta executar a consulta
    if ($stmt->execute()) {
        // Resposta de sucesso
        echo json_encode(['success' => true, 'message' => 'Senha redefinida com sucesso!']);
    } else {
        // Resposta de erro
        echo json_encode(['success' => false, 'message' => 'Erro ao redefinir a senha.']);
    }
    
    // Fecha a declaração
    $stmt->close();
}

// Fecha a conexão com o banco de dados
$conn->close();
?>
