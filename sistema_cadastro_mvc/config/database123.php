<?php
// Definir as constantes de configuração
define('DB_HOST', 'localhost');
define('DB_NAME', 'sistema_cadastro_qrcode');
define('DB_USER', 'root');
define('DB_PASS', '');

// Função para estabelecer a conexão com o banco de dados
function getConnection() {
    try {
        // Usar PDO para conexão com o banco de dados
        $conn = new PDO('mysql:host=' . DB_HOST . ';dbname=' . DB_NAME, DB_USER, DB_PASS);
        // Definir o modo de erro
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $conn;
    } catch (PDOException $e) {
        // Em caso de erro, exibir uma mensagem de erro
        die("Erro de conexão: " . $e->getMessage());
    }
}
?>
