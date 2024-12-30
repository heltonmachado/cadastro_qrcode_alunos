<?php
// Conectar ao banco de dados
$servername = "localhost"; // ou o endereço do seu servidor
$username = "root"; // ou o seu usuário
$password = ""; // ou a sua senha
$dbname = "sistema_cadastro_qrcode"; // Nome do banco de dados

// Criar conexão
$conn = new mysqli($servername, $username, $password, $dbname);

// Checar conexão
if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}

// Buscar o número de alunos cadastrados
$sqlAlunos = "SELECT COUNT(*) AS total FROM usuarios";
$resultAlunos = $conn->query($sqlAlunos);
$alunosCadastrados = 0;
if ($resultAlunos->num_rows > 0) {
    $row = $resultAlunos->fetch_assoc();
    $alunosCadastrados = $row['total'];
}

// Buscar o número de presenças registradas
$sqlPresencas = "SELECT COUNT(*) AS total FROM presencas";
$resultPresencas = $conn->query($sqlPresencas);
$presencasRegistradas = 0;
if ($resultPresencas->num_rows > 0) {
    $row = $resultPresencas->fetch_assoc();
    $presencasRegistradas = $row['total'];
}

// Buscar o número de cursos oferecidos (usando a tabela usuarios ou outra tabela)
$sqlCursos = "SELECT COUNT(DISTINCT curso) AS total FROM usuarios";
$resultCursos = $conn->query($sqlCursos);
$cursosOferecidos = 0;
if ($resultCursos->num_rows > 0) {
    $row = $resultCursos->fetch_assoc();
    $cursosOferecidos = $row['total'];
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Dashboard - Home</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="/public/css/home.css">
   <style>
        .card {
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .card-title {
            font-size: 1.5rem;
        }
        .dashboard-container {
            margin-top: 2rem;
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
                <img src="/public/images/EAPLOGO.png" alt="Logo" class="img-fluid" style="max-width: 100px; height: auto;" />
            </div>
            <ul class="navbar-nav w-100 justify-content-center">
               
                <li class="nav-item">
                    <a class="nav-link" href="/app/views/home.php">Dasboard</a>
                </li>
              
               <li class="nav-item">
                    <a class="nav-link" href="/public/gerador_qrcode.php">Cadastro</a>
                </li>

                <li class="nav-item active">
                    <a class="nav-link" href="/public/alunos.php">Alunos</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/public/leitor.php">Leitor Qrcode</a>
                </li>
              
                <li class="nav-item">
                    <a class="nav-link" href="/public/logout.php">Logout</a>
                </li>
            </ul>
        </div>
    </nav>

    <!-- Dashboard -->
    <div class="container dashboard-container">
        <div class="row">
            <!-- Número de alunos cadastrados -->
            <div class="col-12 col-md-4 mb-4">
                <div class="card text-center">
                    <div class="card-body">
                        <h5 class="card-title">Alunos Cadastrados</h5>
                        <p class="card-text fs-2 fw-bold"><?= $alunosCadastrados ?></p>
                    </div>
                </div>
            </div>

            <!-- Presenças registradas -->
            <div class="col-12 col-md-4 mb-4">
                <div class="card text-center">
                    <div class="card-body">
                        <h5 class="card-title">Presenças Registradas</h5>
                        <p class="card-text fs-2 fw-bold"><?= $presencasRegistradas ?></p>
                    </div>
                </div>
            </div>

            <!-- Cursos oferecidos -->
            <div class="col-12 col-md-4 mb-4">
                <div class="card text-center">
                    <div class="card-body">
                        <h5 class="card-title">Cursos Oferecidos</h5>
                        <p class="card-text fs-2 fw-bold"><?= $cursosOferecidos ?></p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>
</body>
</html>
