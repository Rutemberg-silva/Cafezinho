<?php
$host = "localhost";
$database = "removido"; 
$username = "removido";       
$password = "removido";  

// Criando a conexão
$conn = new mysqli($host, $username, $password, $database);

// Verificando a conexão
if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}
?>
