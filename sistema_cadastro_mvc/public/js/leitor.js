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