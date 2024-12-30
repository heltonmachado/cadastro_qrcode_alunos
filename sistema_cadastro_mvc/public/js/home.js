const container = document.querySelector(".container");
const qrCodeBtn = document.querySelector("#qr-form button");
const qrCodeInput = document.querySelector("#qr-form input");
const qrCodeImg = document.querySelector("#qr-code img");

// Funções
function generateQrCode() {
   const qrCodeInputValue = qrCodeInput.value;

   if(!qrCodeInputValue) return;

   qrCodeBtn.innerText = "Gerando código...";

   qrCodeImg.src = `https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=${qrCodeInputValue}`;

   qrCodeImg.addEventListener("load", () => {
      container.classList.add("active");
      qrCodeBtn.innerText = "Código criado!";
      qrCodeBtn.style.backgroundColor = "#070";
   });
}
qrCodeBtn.addEventListener("click", () => {
   generateQrCode();
});

qrCodeInput.addEventListener("keydown", (e) => {
   if(e.code === "Enter") {
      generateQrCode();
   }
});

// Limpar área do QR Code
qrCodeInput.addEventListener("keyup", () => {
   if(!qrCodeInput.value) {
      container.classList.remove("active");
      qrCodeBtn.innerText = "Gerar QR Code!";
      qrCodeBtn.style.backgroundColor = "070";
   }
});

document.getElementById('send-form').addEventListener('click', function(event) {
   event.preventDefault();

   // Coleta os dados do formulário
   var nome = document.getElementById("nome").value;
   var cpf = document.getElementById("cpf").value;
   var endereco = document.getElementById("endereco").value;
   var matricula = document.getElementById("matricula").value;
   var curso = document.getElementById("curso").value;
   var estabelecimento = document.getElementById("estabelecimento").value;

   // Verifica se todos os campos estão preenchidos
   if (nome && cpf && endereco && matricula && curso && estabelecimento) {
     // Envia os dados para o backend (PHP)
     var formData = new FormData();
     formData.append('nome', nome);
     formData.append('cpf', cpf);
     formData.append('endereco', endereco);
     formData.append('matricula', matricula);
     formData.append('curso', curso);
     formData.append('estabelecimento', estabelecimento);

     // Utilizando Fetch API para enviar os dados
     fetch('salvar_dados.php', {
       method: 'POST',
       body: formData
     })
     .catch(error => {
       console.error('Erro:', error);
     });
   }
 });

 function gerarQRCode(nome, cpf, endereco, matricula, curso, estabelecimento) {
   var qrText = `
     Nome: ${nome}\n
     CPF: ${cpf}\n
     Endereço: ${endereco}\n
     Matrícula: ${matricula}\n
     Curso: ${curso}\n
     Estabelecimento: ${estabelecimento}
   `;

   QRCode.toDataURL(qrText, function (err, url) {
     if (!err) {
       var qrImg = document.getElementById("qr-img");
       qrImg.src = url;

       var myModal = new bootstrap.Modal(document.getElementById("qrModal"));
       myModal.show();
     }
   });
 }
