document.getElementById('send-form').addEventListener('click', function (e) {
    e.preventDefault(); // Evitar o envio padrão do formulário

    // Coletando os dados do formulário
    const nome = document.getElementById('nome').value.trim();
    const cpf = document.getElementById('cpf').value.trim();
    const endereco = document.getElementById('endereco').value.trim();
    const matricula = document.getElementById('matricula').value.trim();
    const curso = document.getElementById('curso').value.trim();
    const estabelecimento = document.getElementById('estabelecimento').value.trim();

    if (!nome || !cpf || !endereco || !matricula || !curso || !estabelecimento) {
        alert('Todos os campos são obrigatórios!');
        return;
    }

    // Enviar os dados para o backend para verificar se o CPF já está cadastrado
    fetch('api/salvar_dados.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ nome, cpf, endereco, matricula, curso, estabelecimento })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Exibe a mensagem de sucesso antes de gerar o QR Code
            const userConfirmed = confirm('Usuário cadastrado com sucesso. Clique em OK para exibir seu QR Code.');

            if (userConfirmed) {
                // Gerar o QR Code após a confirmação
                const qrData = `${nome};${cpf};${endereco};${matricula};${curso};${estabelecimento}`;
                document.getElementById('send-form').innerText = 'Gerando...';

                // Gerar o QR Code
                QRCode.toDataURL(qrData, { width: 300, errorCorrectionLevel: 'H' }, function (err, url) {
                    if (err) {
                        alert('Erro ao gerar QR Code');
                        document.getElementById('send-form').innerText = 'Enviar Formulário';
                        return;
                    }

                    const qrImg = document.getElementById('qr-img');
                    const downloadLink = document.getElementById('download-link');
                    qrImg.src = url;
                    downloadLink.href = url;

                    const qrModal = new bootstrap.Modal(document.getElementById('qrModal'));
                    qrModal.show();
                });
            } else {
                // Caso o usuário não clique em OK, apenas retorne ao estado original
                document.getElementById('send-form').innerText = 'Enviar Formulário';
            }
        } else {
            // Exibe a mensagem de erro, como "CPF já cadastrado"
            alert(data.message); 
            document.getElementById('send-form').innerText = 'Enviar Formulário';
        }
    })
    .catch(err => {
        alert('Erro ao enviar dados: ' + err.message);
        document.getElementById('send-form').innerText = 'Enviar Formulário';
    });
});
