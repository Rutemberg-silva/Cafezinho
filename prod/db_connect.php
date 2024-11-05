<?php
$host = 'localhost';
$user = 'root';
$pass = ''; // Deixe em branco para a configuração padrão do XAMPP
$db = 'cafezinho';

$conn = new mysqli($host, $user, $pass, $db);

// Verificar se a conexão falhou
if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}
?>
