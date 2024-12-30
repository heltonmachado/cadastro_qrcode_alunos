<?php
// Configuração de conexão com o banco de dados
define('DB_HOST', 'localhost');
define('DB_NAME', 'sistema_cadastro_qrcode');
define('DB_USER', 'root');
define('DB_PASS', '');

// Função para obter a conexão com o banco de dados
function getConnection() {
    try {
        $conn = new PDO('mysql:host=' . DB_HOST . ';dbname=' . DB_NAME, DB_USER, DB_PASS);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $conn;
    } catch (PDOException $e) {
        die('Erro ao conectar ao banco de dados: ' . $e->getMessage());
    }
}
