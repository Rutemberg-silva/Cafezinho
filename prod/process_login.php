<?php
session_start(); // Inicia a sessão

// Inclui o arquivo de conexão
include 'db_connect.php'; // Certifique-se de que esse arquivo existe e está no mesmo diretório

// Define o tipo de resposta como JSON
header('Content-Type: application/json');

// Obtém os dados do formulário de login
$username = $_POST['username'] ?? ''; // Pode ser nome ou email
$password = $_POST['password'] ?? '';

// Prepara a consulta SQL
$sql = "SELECT * FROM usuarios WHERE nome = ? OR email = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $username, $username);
$stmt->execute();
$result = $stmt->get_result(); // Obtém o resultado da consulta

// Verifica se o usuário foi encontrado
if ($result->num_rows > 0) {
    $user = $result->fetch_assoc(); // Obtém o usuário

    // Verifica se a senha está correta
    if (password_verify($password, $user['senha'])) {
        // Define que o usuário está logado e armazena as informações necessárias na sessão
        $_SESSION['loggedin'] = true;
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['nome'];
        $_SESSION['user_type'] = $user['tipo_usuario']; // Adiciona o tipo de usuário na sessão

        // Retorna uma resposta JSON de sucesso
        echo json_encode([
            'success' => true,
            'message' => 'Login realizado com sucesso!',
            'username' => $user['nome'],
            'userType' => $user['tipo_usuario'] // Retorna o tipo de usuário
        ]);
    } else {
        // Retorna uma resposta JSON de erro de senha
        echo json_encode([
            'success' => false,
            'message' => 'Senha incorreta'
        ]);
    }
} else {
    // Retorna uma resposta JSON de erro de usuário não encontrado
    echo json_encode([
        'success' => false,
        'message' => 'Usuário não encontrado'
    ]);
}

// Fecha a conexão e libera o statement
$stmt->close();
$conn->close();
?>
