<?php
include 'db_connect.php';

// Receber os dados via POST
$id = $_POST['id'];
$nome = $_POST['nome'];
$email = $_POST['email'];
$endereco = $_POST['endereco'];
$telefone = $_POST['telefone'];
$tipo_usuario = $_POST['tipo_usuario'];

// Atualizar as informações do usuário
$sql = "UPDATE usuarios SET nome = ?, email = ?, endereco = ?, telefone = ?, tipo_usuario = ? WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("sssi", $nome, $email, $endereco, $telefone, $tipo_usuario, $id);

if ($stmt->execute()) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'error' => $stmt->error]);
}

$conn->close();
?>
