<?php

// Inclui o arquivo de conexão com o banco de dados
include_once(__DIR__ . '/../config/conexao.php');
session_start();

// Verifica se o usuário está logado
if (!isset($_SESSION['usuario_id'])) {
    header('Location: /public/login.php'); // Redireciona para a página de login
    exit;
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Gerador de QR Code</title>

    <!-- Estilos -->
    <link rel="stylesheet" href="css/home.css" />
    <link rel="stylesheet" href="css/gerador.css" />

    <!-- Fonte Roboto -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@100;300;400;500;700;900&display=swap" rel="stylesheet" />

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" />
    <script src="js/home.js" defer></script>
</head>

<body>
    <!-- Barra de navegação -->
    <nav class="navbar navbar-expand-lg navbar-light">
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
            <div class="text-center w-100">
                <img src="images/EAPLOGO.png" alt="Logo" class="img-fluid" style="max-width: 100px; height: auto;" />
            </div>
            <ul class="navbar-nav w-100 justify-content-center">
                <li class="nav-item">
                    <a class="nav-link" href="/app/views/home.php">Dashboard</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/public/gerador_qrcode.php">Cadastro</a>
                </li>
                <li class="nav-item active">
                    <a class="nav-link" href="/public/alunos.php">Alunos</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/public/leitor.php">Leitor QR Code</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/public/logout.php">Logout</a>
                </li>
            </ul>
        </div>
    </nav>

    <div class="container d-flex justify-content-center align-items-center" style="min-height: 100vh;">
        <div class="col-12 col-md-4">
            <header id="qr-header" class="text-center mb-4">
                <h3>Gerador de QR Code</h3>
                <p>Preencha os campos abaixo para gerar seu QR Code.</p>
            </header>

            <!-- Formulário para entrada de dados -->
            <div id="qr-form" class="mb-4">
                <div class="mb-2">
                    <label for="nome" class="form-label required">Nome:</label>
                    <input type="text" id="nome" class="form-control" placeholder="Digite seu nome" required />
                </div>

                <div class="mb-2">
                    <label for="cpf" class="form-label required">CPF:</label>
                    <input type="text" id="cpf" class="form-control" placeholder="Digite seu CPF" required />
                </div>

                <div class="mb-2">
                    <label for="endereco" class="form-label required">Endereço:</label>
                    <input type="text" id="endereco" class="form-control" placeholder="Digite seu endereço" required />
                </div>

                <div class="mb-2">
                    <label for="matricula" class="form-label required">Matrícula:</label>
                    <input type="text" id="matricula" class="form-control" placeholder="Digite sua matrícula" required />
                </div>

                <div class="mb-2">
                    <label for="curso" class="form-label required">Curso:</label>
                    <input type="text" id="curso" class="form-control" placeholder="Digite seu curso" required />
                </div>

                <div class="mb-2">
                    <label for="estabelecimento" class="form-label required">Estabelecimento:</label>
                    <input type="text" id="estabelecimento" class="form-control" placeholder="Digite o nome do estabelecimento" required />
                </div>

                <button class="btn btn-dark mt-2 w-100" type="submit" id="send-form">Enviar Formulário</button>
            </div>
        </div>
    </div>

    <!-- Modal para exibir o QR Code -->
    <div class="modal fade" id="qrModal" tabindex="-1" aria-labelledby="qrModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="qrModalLabel">𝙌𝙍 𝘾𝙤𝙙𝙚 𝙂𝙚𝙧𝙖𝙙𝙤 𝙘𝙤𝙢 𝙎𝙪𝙘𝙚𝙨𝙨𝙤!</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center">
                    <img id="qr-img" src="" alt="QR Code" class="img-fluid" />
                    <p id="qr-text" class="mt-3"></p>
                    <a id="download-link" class="btn btn-primary mt-3" download="qrcode.png">𝘽𝙖𝙞𝙭𝙖𝙧 𝙌𝙍 𝘾𝙤𝙙𝙚</a>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"> 𝑭𝒆𝒄𝒉𝒂𝒓 </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS e dependências -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/qrcode/build/qrcode.min.js"></script>
    <script src="js/gerador.js"></script>
</body>
</html>
