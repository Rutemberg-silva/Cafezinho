<?php
$host = "localhost";
$database = "u158450547_cafezinhodb"; // Certifique-se de que este é o nome correto do banco de dados
$username = "u158450547_admin";       // Substitua pelo nome de usuário correto
$password = "Senhaadministrador123";   // Substitua pela senha correta

// Criando a conexão
$conn = new mysqli($host, $username, $password, $database);

// Verificando a conexão
if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}
?>
