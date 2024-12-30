<?php
// Inclui o arquivo de conexão com o banco de dados
include_once(__DIR__ . '/../config/conexao.php');
session_start();

// Verifica se o usuário está logado
if (!isset($_SESSION['usuario_id'])) {
    header('Location: /public/login.php'); // Redireciona para a página de login
    exit;
}

// Obter a conexão com o banco de dados
$conn = getConnection();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Leitor de QR Code</title>

    <!-- Estilos -->
    <link rel="stylesheet" href="css/leitor.css" />

    <!-- Fonte Roboto -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@100;300;400;500;700;900&display=swap" rel="stylesheet" />

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" />

    <style>
        .scanner-container {
            position: relative;
            width: 100%;
            max-width: 400px; /* Tamanho máximo da tela */
            margin: 0 auto; /* Centralizar */
        }
        canvas {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: auto;
        }
        #video {
            width: 100%;
            height: auto;
        }
    </style>
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
                <li class="nav-item"><a class="nav-link" href="../app/views/home.php">Dasboard</a></li>
                <li class="nav-item"><a class="nav-link" href="../gerador_qrcode.html">Cadastro</a></li>
                <li class="nav-item active"><a class="nav-link" href="/public/alunos.php">Alunos</a></li>
                <li class="nav-item"><a class="nav-link" href="/public/leitor.php">Leitor Qrcode</a></li>
                <li class="nav-item"><a class="nav-link" href="/public/logout.php">Logout</a></li>
            </ul>
        </div>
    </nav>

    <div class="container mt-5">
        <p class="text-center">Aponte a câmera para o QR Code.</p>
        <div class="scanner-container">
            <video id="video" autoplay></video>
            <canvas id="canvas"></canvas>
        </div>

        <!-- Container de erro -->
        <div class="alert alert-danger mt-3" id="output" style="display: none;"></div>
    </div>

    <!-- Bootstrap JS e dependências -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>

    <!-- Biblioteca jsQR -->
    <script src="https://cdn.jsdelivr.net/npm/jsqr/dist/jsQR.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/qrcode/build/qrcode.min.js"></script>

    <script>
        const video = document.getElementById('video');
        const canvas = document.getElementById('canvas');
        const canvasContext = canvas.getContext('2d');
        const output = document.getElementById('output');

        // Configura a câmera
        navigator.mediaDevices.getUserMedia({ video: { facingMode: 'environment' } })
            .then((stream) => {
                video.srcObject = stream;
                video.setAttribute('playsinline', true); // Compatibilidade com iOS
                video.play();
                requestAnimationFrame(tick);
            })
            .catch(err => {
                showError(`Erro ao acessar a câmera: ${err.message}`);
            });

        // Função para mostrar mensagens de erro personalizadas
        function showMessage(message, isSuccess = false) {
            output.style.display = 'block';
            output.textContent = message;
            output.classList.remove('alert-danger', 'alert-info');
            output.classList.add(isSuccess ? 'alert-info' : 'alert-danger');
            // Mensagem desaparecerá após 5 segundos
            setTimeout(() => {
                output.style.display = 'none';
            }, 5000);
        }

        // Função para mostrar mensagens de erro
        function showError(message) {
            showMessage(message, false);
        }

        // Função para mostrar mensagens de sucesso
        function showSuccess(message) {
            showMessage(message, true);
            // Redirecionar para outra página após 5 segundos
            setTimeout(() => {
                window.location.href = 'gerador_qrcode.php';  // Substitua 'index.html' com o URL desejado
            }, 10000); // 10 segundos
        }

        // Analisa o frame da câmera
        function tick() {
            if (video.readyState === video.HAVE_ENOUGH_DATA) {
                canvas.width = video.videoWidth;
                canvas.height = video.videoHeight;
                canvasContext.drawImage(video, 0, 0, canvas.width, canvas.height);

                const imageData = canvasContext.getImageData(0, 0, canvas.width, canvas.height);
                
                // Aumente a sensibilidade alterando a correção de erro ou outras opções do jsQR
                const code = jsQR(imageData.data, imageData.width, imageData.height, {
                    inversionAttempts: 'dontInvert', // Para tentar não inverter a imagem
                    // Use configurações mais sensíveis
                    correctLevel: 'H' // Nível de correção de erro mais alto
                });

                if (code) {
                    // Enviar os dados para o backend
                    fetch('api/salvar_presencas.php', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json' },
                        body: JSON.stringify({ qrCodeData: code.data })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            showSuccess('Presença registrada com sucesso!');
                            // Redireciona para página de cadastro
                            window.location.href = 'gerador_qrcode.php'; // ou o caminho correto para o cadastro
                        } else {
                            if (data.message === 'Presença já registrada') {
                                showError('Aluno já marcou presença.');
                            } else {
                                showError(`Erro ao enviar dados: ${data.message}`);
                            }
                        }
                    })
                    .catch(err => showError('Já Confirmou a Presença.'));
                }
            }
            requestAnimationFrame(tick);
        }
    </script>
</body>
</html>
