<?php
header('Content-Type: application/json');

// Conectar ao banco de dados
$host = 'localhost';
$db = 'sistema_cadastro_qrcode';
$user = 'root';
$pass = '';
$dsn = "mysql:host=$host;dbname=$db;charset=utf8";

try {
    // Conectar ao banco de dados usando PDO
    $pdo = new PDO($dsn, $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Receber os dados do formulário (esperando dados em formato JSON)
    $data = json_decode(file_get_contents('php://input'), true);
    $nome = $data['nome'];
    $cpf = $data['cpf'];
    $endereco = $data['endereco'];
    $matricula = $data['matricula'];
    $curso = $data['curso'];
    $estabelecimento = $data['estabelecimento'];

    // Verificar se o CPF já está cadastrado
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM usuarios WHERE cpf = :cpf");
    $stmt->execute([':cpf' => $cpf]);
    $cpfExistente = $stmt->fetchColumn();

    // Verificar se a matrícula já está cadastrada
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM usuarios WHERE matricula = :matricula");
    $stmt->execute([':matricula' => $matricula]);
    $matriculaExistente = $stmt->fetchColumn();

    if ($cpfExistente > 0) {
        // Se CPF já existe, retornar erro
        echo json_encode(['success' => false, 'message' => 'CPF já cadastrado.']);
        exit;
    }

    if ($matriculaExistente > 0) {
        // Se matrícula já existe, retornar erro
        echo json_encode(['success' => false, 'message' => 'Matrícula já cadastrada.']);
        exit;
    }

    // Inserir os dados do usuário no banco de dados
    $stmt = $pdo->prepare("
        INSERT INTO usuarios (nome, cpf, endereco, matricula, curso, estabelecimento) 
        VALUES (:nome, :cpf, :endereco, :matricula, :curso, :estabelecimento)
    ");

    // Executar a inserção
    $stmt->execute([
        ':nome' => $nome,
        ':cpf' => $cpf,
        ':endereco' => $endereco,
        ':matricula' => $matricula,
        ':curso' => $curso,
        ':estabelecimento' => $estabelecimento
    ]);

    // Retornar resposta de sucesso
    echo json_encode(['success' => true, 'message' => 'Usuário cadastrado com sucesso!']);

} catch (PDOException $e) {
    // Caso ocorra algum erro durante a inserção
    echo json_encode(['success' => false, 'message' => 'Erro ao salvar no banco de dados: ' . $e->getMessage()]);
}
?>
