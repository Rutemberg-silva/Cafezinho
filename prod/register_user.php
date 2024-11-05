<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Inclui a conexão com o banco de dados
include 'db_connect.php';

// Verifica a conexão
if ($conn->connect_error) {
    die(json_encode(['success' => false, 'message' => 'Conexão com o banco de dados falhou: ' . $conn->connect_error]));
}

// Define o cabeçalho para JSON
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Captura os dados do formulário
    $name = $_POST['name'];
    $email = $_POST['email'];
    $address = $_POST['address'];
    $phone = $_POST['phone'];
    $password = $_POST['password'];

    // Verifica se o e-mail já está cadastrado
    $stmt = $conn->prepare("SELECT * FROM usuarios WHERE email = ?");
    if (!$stmt) {
        die(json_encode(['success' => false, 'message' => 'Erro na preparação da consulta: ' . $conn->error]));
    }
    
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo json_encode(['success' => false, 'message' => 'E-mail já cadastrado']);
        exit;
    }

    // Criptografa a senha antes de salvar no banco de dados
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Insere os dados no banco de dados
    $stmt = $conn->prepare("INSERT INTO usuarios (nome, email, senha, tipo_usuario, endereco, telefone, data_criacao) VALUES (?, ?, ?, 'cliente', ?, ?, NOW())");
    
    if (!$stmt) {
        die(json_encode(['success' => false, 'message' => 'Erro na preparação da inserção: ' . $conn->error]));
    }

    // Corrigindo as variáveis aqui
    $stmt->bind_param("sssss", $name, $email, $hashedPassword, $address, $phone);
    
    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Erro ao cadastrar usuário: ' . $stmt->error]);
    }
    
    $stmt->close();
}

$conn->close();
?>
