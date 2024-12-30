<?php
// Inclui o arquivo de conexão com o banco de dados
include_once(__DIR__ . '/../config/conexao.php');
session_start();

// Verifica se o usuário está logado
if (!isset($_SESSION['usuario_id'])) {
    header('Location: /public/login.php'); // Redireciona para a página de login
    exit;
}

// Conectar ao banco de dados
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "sistema_cadastro_qrcode"; // Nome do banco de dados

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Função para buscar alunos
function buscarAlunos($termo) {
    global $conn;
    $sql = "SELECT DISTINCT p.nome, p.curso, COUNT(p.id) AS frequencia
            FROM presencas p
            WHERE p.nome LIKE ? OR p.curso LIKE ?
            GROUP BY p.nome, p.curso";
    $stmt = $conn->prepare($sql);
    $termo = "%$termo%"; // Adiciona os % para pesquisa parcial
    $stmt->bind_param("ss", $termo, $termo);
    $stmt->execute();
    return $stmt->get_result();
}

$termo = isset($_GET['termo']) ? $_GET['termo'] : '';
$alunos = buscarAlunos($termo);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Lista de Alunos</title>

    <!-- Estilos -->
    <link rel="stylesheet" href="/public/css/home.css" />

    <!-- Fonte Roboto -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@100;300;400;500;700;900&display=swap" rel="stylesheet" />

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" />
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

    <div class="container mt-5">
        

    <form method="GET" class="mb-4">
    <div class="row justify-content-center">
        <div class="col-md-5"> <!-- Aumentar largura para campo único -->
            <input type="text" class="form-control" name="termo" value="<?= htmlspecialchars($termo) ?>" placeholder="Digite o nome ou curso" />
        </div>
        <div class="col-md-2">
            <button type="submit" class="btn btn-primary w-100">Buscar</button>
        </div>
    </div>
</form>



        <?php if ($alunos->num_rows > 0): ?>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Nome</th>
                        <th>Curso</th>
                        <th>Frequência</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $alunos->fetch_assoc()): ?>
                        <tr>
                            <td><?= htmlspecialchars($row['nome']) ?></td>
                            <td><?= htmlspecialchars($row['curso']) ?></td>
                            <td><?= htmlspecialchars($row['frequencia']) ?> Presenças</td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p class="text-center">Nenhum aluno encontrado.</p>
        <?php endif; ?>
    </div>

    <!-- Bootstrap JS e dependências -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>
</body>
</html>

<?php
// Fechar a conexão com o banco de dados
$conn->close();
?>
