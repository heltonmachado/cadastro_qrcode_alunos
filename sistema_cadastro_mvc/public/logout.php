<?php
// Inclui o arquivo de conexão com o banco de dados
include_once(__DIR__ . '/../config/conexao.php');
session_start();

// Verifica se o usuário está logado
if (!isset($_SESSION['usuario_id'])) {
    header('Location: /public/login.php'); // Redireciona para a página de login
    exit;
}

session_start();
session_destroy();
header('Location: login.php');
exit;
?>
