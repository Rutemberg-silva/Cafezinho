<?php
session_start(); // Inicia a sessão

// Remove todas as variáveis de sessão
$_SESSION = [];


if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"], $params["secure"], $params["httponly"]
    );
}

// Destrói a sessão
session_destroy();

// Redireciona para a página principal ou página de login
header('Location: main.html'); 
exit();
?>
