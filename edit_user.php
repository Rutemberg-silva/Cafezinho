<?php
include 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $tipo_usuario = $_POST['tipo_usuario'];
    $endereco = $_POST['endereco']; 
    $telefone = $_POST['telefone']; 

    $stmt = $conn->prepare("UPDATE usuarios SET nome = ?, email = ?, tipo_usuario = ?, endereco = ?, telefone = ? WHERE id = ?");
    $stmt->bind_param("ssssis", $nome, $email, $tipo_usuario, $endereco, $telefone, $id); // Bind atualizado para incluir os novos campos

    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Erro ao editar usuário.']);
    }

    $stmt->close();
}

$conn->close();
?>
