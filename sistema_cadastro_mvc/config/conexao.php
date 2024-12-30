<?php
// Configurações do banco de dados
define('DB_HOST', 'mysql');  // Nome do serviço no Docker
define('DB_NAME', 'sistema_cadastro_qrcode');
define('DB_USER', 'root');
define('DB_PASS', '');

// Função para obter a conexão com o banco de dados utilizando PDO
function getConnection() {
    try {
        // Criação da conexão PDO
        $conn = new PDO('mysql:host=' . DB_HOST . ';dbname=' . DB_NAME, DB_USER, DB_PASS);
        // Configuração do modo de erro do PDO
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $conn;
    } catch (PDOException $e) {
        // Mensagem de erro em caso de falha na conexão
        die("Erro ao conectar ao banco de dados: " . $e->getMessage());
    }
}

// Teste de conexão (opcional - remova em produção)
try {
    $conexao = getConnection();
    echo "Conectado com sucesso ao banco de dados.";
} catch (Exception $e) {
    echo $e->getMessage();
}
?>
