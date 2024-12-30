<?php
// Inclui o arquivo de conexão com o banco de dados
include_once(__DIR__ . '/../config/conexao.php');
session_start();
// Verifica se o usuário está logado
if (isset($_SESSION['usuario_id'])) {
    header('Location: /public/login.php');  // Redireciona para home.php
    exit;
}

// Verifica se foi enviado o formulário de login
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login'])) {
    $cpf = $_POST['cpf'];
    $senha = $_POST['senha'];

    // Conexão com o banco de dados
    $conn = new mysqli('localhost', 'root', '', 'sistema_cadastro_qrcode');
    if ($conn->connect_error) {
        die("Conexão falhou: " . $conn->connect_error);
    }

    // Consulta para verificar o usuário
    $sql = "SELECT * FROM usuario_admin WHERE cpf = ? LIMIT 1";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('s', $cpf);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $usuario = $result->fetch_assoc();
        // Verifica a senha
        if (password_verify($senha, $usuario['senha'])) {
            $_SESSION['usuario_id'] = $usuario['id'];
            $_SESSION['nome_usuario'] = $usuario['nome'];
            header('Location: /app/views/home.php');  // Redireciona para home.php
            exit;
        } else {
            $erro = "Usuário ou senha incorretos.";
        }
    } else {
        $erro = "Usuário não encontrado.";
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Sistema de Cadastro</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script> <!-- jQuery -->
    <script>
        // Função para limpar a formatação do CPF (para envio ao banco)
        function clearCPF(cpf) {
            return cpf.replace(/\D/g, ''); // Remove qualquer caractere não numérico
        }

        // Limpar o CPF antes de enviar o formulário (enviar apenas números)
        $(document).ready(function() {
            $('form').submit(function(event) {
                var cpf = $('#cpf').val();
                // Remove a formatação do CPF antes de enviar
                $('#cpf').val(clearCPF(cpf));
            });
        });
    </script>
    <style>
        body {
            background-image: url('images/logosunf.jpg'); /* Substitua pelo link ou caminho da sua imagem */
            background-size: 28%; /* Mantém o tamanho desejado da imagem */
            background-position: center center; /* Centraliza a imagem horizontal e verticalmente */
            background-repeat: no-repeat; /* Evita a repetição da imagem */
            background-attachment: fixed; /* Fixa a imagem ao fundo */
            min-height: 100vh; /* Garante que a altura mínima seja sempre o tamanho da tela */
            color: white; /* Muda a cor do texto para branco */
        }
        .form-container {
            padding: 20px;
            border-radius: 8px;
            border: 5px solid white; /* Borda branca ao redor do formulário */
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            background-color: rgba(0, 0, 0, 0.7); /* Escurece o fundo da caixa de formulário */
        }
        .alert {
            color: white; /* Muda a cor das mensagens de erro para branco */
        }
        .form-label, .form-control {
            color: white; /* Muda a cor das labels e campos de entrada para branco */
        }
        .form-control {
            background-color: rgba(0, 0, 0, 0.3); /* Deixa o fundo dos campos mais escuro para contraste */
        }
        .btn {
            color: white; /* Muda a cor do texto dos botões para branco */
        }
        .btn-primary {
            background-color: #007bff; /* Cor do botão 'Entrar' */
        }
        .btn-secondary {
            background-color: #6c757d; /* Cor do botão 'Cadastre-se' */
        }
        @media (max-width: 768px) {
            /* Fazendo os botões ficarem 100% de largura em telas menores */
            .d-flex {
                background-size: 28%; /* Mantém o tamanho desejado da imagem */
            }
            .w-48 {
                width: 100%; /* Faz os botões ocuparem 100% da largura em telas pequenas */
            }
            body {
                background-image: url('images/logosunf.jpg'); /* Substitua pelo link ou caminho da sua imagem */
                background-size: 100%; /* Mantém o tamanho desejado da imagem */
                background-position: center center; /* Centraliza a imagem horizontal e verticalmente */
                background-repeat: no-repeat; /* Evita a repetição da imagem */
                background-attachment: fixed; /* Fixa a imagem ao fundo */
                min-height: 100vh; /* Garante que a altura mínima seja sempre o tamanho da tela */
                color: white; /* Muda a cor do texto para branco */
            }
        }
    </style>
</head>
<body>
    <div class="container d-flex justify-content-center align-items-center" style="min-height: 100vh;">
        <div class="col-12 col-md-4 form-container">
            <h3 class="text-center mb-4">Login</h3>
            <form method="POST">
                <div class="mb-3">
                    <label for="cpf" class="form-label">CPF:</label>
                    <input type="text" id="cpf" name="cpf" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="senha" class="form-label">Senha:</label>
                    <input type="password" id="senha" name="senha" class="form-control" required>
                </div>
                <div class="d-flex justify-content-between">
                    <a href="cadastro.php" class="btn btn-secondary w-48">
                        <i class="bi bi-person-plus"></i> Cadastre-se
                    </a>
                    <button type="submit" name="login" class="btn btn-primary w-48">
                        <i class="bi bi-box-arrow-in-right"></i> Entrar
                    </button>
                </div>
            </form>
        </div>
    </div>

<!-- Modal de Erro -->
<div class="modal fade" id="errorModal" tabindex="-1" aria-labelledby="errorModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content" style="background-color: black; color: white;">
            <div class="modal-header">
                <h5 class="modal-title" id="errorModalLabel">𝑴𝒆𝒏𝒔𝒂𝒈𝒆𝒎</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
            𝑼𝒔𝒖𝒂́𝒓𝒊𝒐 𝒐𝒖 𝑺𝒆𝒏𝒉𝒂 𝑰𝒏𝒗𝒂𝒍𝒊𝒅𝒐𝒔.
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">𝑭𝒆𝒄𝒉𝒂𝒓</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal de Sucesso -->
<div class="modal fade" id="successModal" tabindex="-1" aria-labelledby="successModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content" style="background-color: black; color: white;">
            <div class="modal-header">
                <h5 class="modal-title" id="successModalLabel">𝑴𝒆𝒏𝒔𝒂𝒈𝒆𝒎</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
            𝑳𝒐𝒈𝒊𝒏 𝒓𝒆𝒂𝒍𝒊𝒛𝒂𝒅𝒐 𝒄𝒐𝒎 𝒔𝒖𝒄𝒆𝒔𝒔𝒐!
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success" data-bs-dismiss="modal">𝑭𝒆𝒄𝒉𝒂𝒓</button>
            </div>
        </div>
    </div>
</div>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Ícones do Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

    <script>
        // Exibe o modal de erro se houver uma mensagem de erro
        <?php if (isset($erro)) { ?>
            var errorModal = new bootstrap.Modal(document.getElementById('errorModal'));
            errorModal.show();
        <?php } ?>
        
        // Exibe o modal de sucesso após login
        <?php if (isset($_SESSION['usuario_id'])) { ?>
            var successModal = new bootstrap.Modal(document.getElementById('successModal'));
            successModal.show();
        <?php } ?>
    </script>
</body>
</html>
