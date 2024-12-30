<?php
// Inclui o arquivo de conexão com o banco de dados
include_once(__DIR__ . '/../config/conexao.php');
// Verifica se o usuário está logado
if (!isset($_SESSION['usuario_id'])) {
    header('Location: /public/login.php'); // Redireciona para a página de login
    exit;
}

require '../config/conexao.php';
require '../config/config.php';
require '../app/controllers/HomeController.php';

$controller = new HomeController();
$controller->index();
?>