<?php
session_start();
include 'db_connect.php';

header('Content-Type: application/json');

// Recebe dados de login
$username = $_POST['username'] ?? '';
$password = $_POST['password'] ?? '';

// Verifica as credenciais
$sql = "SELECT id, nome, senha, tipo_usuario FROM usuarios WHERE nome = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
    
    // Depuração: Veja o que está retornando do banco de dados
    var_dump($user); // Adicione esta linha para ver os dados do usuário
    // Verifica se a senha fornecida corresponde à senha armazenada (hash)
    if (password_verify($password, $user['senha'])) {
        // Configura as variáveis de sessão
        $_SESSION['loggedin'] = true;
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['nome'];
        $_SESSION['user_type'] = $user['tipo_usuario'];
        
        echo json_encode(['success' => true, 'message' => 'Login realizado com sucesso']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Usuário ou senha incorretos']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Usuário ou senha incorretos']);
}

$stmt->close();
$conn->close();
?>
