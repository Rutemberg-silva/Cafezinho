<?php
session_start(); // Inicia a sessão

// Remove todas as variáveis de sessão
$_SESSION = [];

// Se você quiser destruir a sessão, também pode fazer isso
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"], $params["secure"], $params["httponly"]
    );
}

// Destrói a sessão
session_destroy();

// Redireciona para a página principal ou página de login
header('Location: main.html'); // Altere para 'login.html' se preferir redirecionar para a página de login
exit();
?>
