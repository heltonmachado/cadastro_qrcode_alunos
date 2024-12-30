<?php
// salvar_presenca.php

// Conectar ao banco de dados
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "sistema_cadastro_qrcode"; // Nome do banco de dados

$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar conexão com o banco de dados
if ($conn->connect_error) {
    die(json_encode(['success' => false, 'message' => 'Erro ao conectar ao banco de dados: ' . $conn->connect_error]));
}

// Obter os dados do QR Code enviados via POST
$data = json_decode(file_get_contents('php://input'), true);
$qrCodeData = $data['qrCodeData'] ?? null;

if (!$qrCodeData) {
    echo json_encode(['success' => false, 'message' => 'Dados do QR Code não fornecidos.']);
    exit;
}

// Inicializar variáveis
$nome = '';
$cpf = '';
$endereco = '';
$matricula = '';
$curso = '';
$estabelecimento = '';

// Validar e extrair informações do QR Code
if (is_array($qrCodeData) && isset($qrCodeData['nome']) && isset($qrCodeData['cpf']) && isset($qrCodeData['endereco']) && isset($qrCodeData['matricula']) && isset($qrCodeData['curso']) && isset($qrCodeData['estabelecimento'])) {
    // Caso os dados sejam enviados como JSON estruturado
    $nome = $qrCodeData['nome']; 
    $cpf = $qrCodeData['cpf']; 
    $endereco = $qrCodeData['endereco']; 
    $matricula = $qrCodeData['matricula']; 
    $curso = $qrCodeData['curso']; 
    $estabelecimento = $qrCodeData['estabelecimento']; 
} else {
    // Caso os dados sejam enviados como uma string delimitada
    $dados = explode(';', $qrCodeData); // Supondo separação por ";"
    $nome = $dados[0] ?? '';
    $cpf = $dados[1] ?? '';
    $endereco = $dados[2] ?? '';
    $matricula = $dados[3] ?? '';
    $curso = $dados[4] ?? 'Curso não especificado';
    $estabelecimento = $dados[5] ?? '';
}

// Sanitizar os dados para evitar SQL Injection
$nome = $conn->real_escape_string(trim($nome));
$cpf = $conn->real_escape_string(trim($cpf));
$endereco = $conn->real_escape_string(trim($endereco));
$matricula = $conn->real_escape_string(trim($matricula));
$curso = $conn->real_escape_string(trim($curso));
$estabelecimento = $conn->real_escape_string(trim($estabelecimento));

// Garantir que os campos obrigatórios não estejam vazios
if (empty($nome) || empty($cpf) || empty($endereco) || empty($matricula) || empty($curso) || empty($estabelecimento)) {
    echo json_encode(['success' => false, 'message' => 'Todos os campos são obrigatórios.']);
    exit;
}

// Data atual para verificar presença
$data_presenca = date('Y-m-d');

// Verificar se a presença já foi registrada
$checkQuery = $conn->prepare("SELECT id FROM presencas WHERE cpf = ? AND curso = ? AND data = ?");
$checkQuery->bind_param("sss", $cpf, $curso, $data_presenca);
$checkQuery->execute();
$checkQuery->store_result();

if ($checkQuery->num_rows > 0) {
    echo json_encode(['success' => false, 'message' => 'Presença já registrada.']);
    $checkQuery->close();
    $conn->close();
    exit;
}
$checkQuery->close();

// Data e hora atuais para inserção
$hora_presenca = date('Y-m-d H:i:s');

// Inserir os dados na tabela 'presencas'
$stmt = $conn->prepare("INSERT INTO presencas (nome, cpf, endereco, matricula, curso, estabelecimento, data, hora) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
if (!$stmt) {
    echo json_encode(['success' => false, 'message' => 'Erro ao preparar a consulta: ' . $conn->error]);
    exit;
}

$stmt->bind_param("ssssssss", $nome, $cpf, $endereco, $matricula, $curso, $estabelecimento, $data_presenca, $hora_presenca);

if ($stmt->execute()) {
    echo json_encode(['success' => true, 'message' => 'Presença registrada com sucesso.']);
} else {
    echo json_encode(['success' => false, 'message' => 'Erro ao salvar presença: ' . $stmt->error]);
}

$stmt->close();
$conn->close();
?>
