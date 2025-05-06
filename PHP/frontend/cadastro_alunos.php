<!-- filepath: c:\wamp64\www\ProjetoMesquita\frontend\cadastro_alunos.php -->
<?php
require_once '../backend/config/db.php';
session_start();

// Verifica se o usuário está logado
if (!isset($_SESSION['usuario_id'])) {
    header("Location: index.php?error=unauthorized");
    exit;
}

// Verifica classe do usuário
if ($_SESSION['usuario_classe'] != 'ADM' && $_SESSION['usuario_classe'] != 'Coordenacao') {
    header("Location: access_denied.php");
    exit;
}

// Carrega as casas disponíveis no banco de dados
$sqlCasas = "SELECT ID, Nome FROM Casas";
$casas = $pdo->query($sqlCasas)->fetchAll(PDO::FETCH_ASSOC);

// Carrega as turmas disponíveis no banco de dados
$sqlTurmas = "SELECT ID, Nome FROM Turmas";
$turmas = $pdo->query($sqlTurmas)->fetchAll(PDO::FETCH_ASSOC);

// Busca os alunos cadastrados no banco de dados
$sqlAlunos = "SELECT A.ID, A.Nome, A.Matricula, A.Ativo, C.ID AS Casa_ID, C.Nome AS Casa, T.ID AS Turma_ID, T.Nome AS Turma 
              FROM Alunos A
              JOIN Casas C ON A.Casa_ID = C.ID
              JOIN Turmas T ON A.Turma_ID = T.ID";
$alunos = $pdo->query($sqlAlunos)->fetchAll(PDO::FETCH_ASSOC);

// Processa o formulário de cadastro ou edição
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = filter_input(INPUT_POST, 'aluno_id', FILTER_VALIDATE_INT);
    $nome = filter_input(INPUT_POST, 'nome', FILTER_SANITIZE_STRING);
    $matricula = filter_input(INPUT_POST, 'matricula', FILTER_SANITIZE_STRING);
    $casa_id = filter_input(INPUT_POST, 'casa_id', FILTER_SANITIZE_NUMBER_INT);
    $turma_id = filter_input(INPUT_POST, 'turma_id', FILTER_SANITIZE_NUMBER_INT);

    try {
        if ($id) {
            // Atualiza o aluno existente
            $sql = "UPDATE Alunos SET Nome = :nome, Matricula = :matricula, Casa_ID = :casa_id, Turma_ID = :turma_id WHERE ID = :id";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        } else {
            // Insere um novo aluno
            $sql = "INSERT INTO Alunos (Nome, Matricula, Casa_ID, Turma_ID, Ativo) 
                    VALUES (:nome, :matricula, :casa_id, :turma_id, 1)";
            $stmt = $pdo->prepare($sql);
        }

        $stmt->bindParam(':nome', $nome, PDO::PARAM_STR);
        $stmt->bindParam(':matricula', $matricula, PDO::PARAM_STR);
        $stmt->bindParam(':casa_id', $casa_id, PDO::PARAM_INT);
        $stmt->bindParam(':turma_id', $turma_id, PDO::PARAM_INT);
        $stmt->execute();

        // Redireciona com mensagem de sucesso
        header("Location: cadastro_alunos.php?success=true");
        exit;
    } catch (PDOException $e) {
        echo "Erro ao salvar aluno: " . $e->getMessage();
    }
}

// Processa a ativação/inativação de alunos
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['toggle_ativo'])) {
    $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
    $ativo = filter_input(INPUT_POST, 'ativo', FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);

    if ($id !== null && $ativo !== null) {
        $sql = "UPDATE Alunos SET Ativo = :ativo WHERE ID = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':ativo', $ativo, PDO::PARAM_BOOL);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Alunos</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-image:  url('../Imagens/Fundo.png');
            margin: 0;
            padding: 20px;
            display: flex;
            justify-content: center;
            align-items: flex-start;
            height: 100vh;
        }

        .container {
            margin-top: 10%;
            display: flex;
            gap: 20px;
            width: 100%;
            max-width: 1200px;
            transform: translateY(-110px);
        }

        .form-container {
            background: #fff;
            padding: 20px 30px;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            width: 100%;
            
        }

        .list-container{
            background: #fff;
            padding: 20px 30px;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            width: 100%;

        }

        .form-container {
            max-width: 400px;
        }

        .form-container h1, .list-container h1 {
            text-align: center;
            margin-bottom: 20px;
            color: #333;
        }

        #nome{
            text-align: center;
        }

        #matricula{
            text-align: center;
        }

        #texto{
            text-align: center;
        }

        .form-container form {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .form-container input {
            padding: 10px 110px;
            font-size: 16px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .form-container select {
            padding: 10px 130px;
            font-size: 16px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }


        .form-container button {
            padding: 10px 150px;
            font-size: 16px;
            background-color: #9d4edd;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .form-container button:hover {
            background-color: rgb(197, 153, 233);
        }

        .back-btn {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 146px;
            background-color: #6c757d;
            color: #fff;
            text-decoration: none;
            border-radius: 5px;
            font-size: 16px;
        }

        .back-btn:hover {
            background-color: #5a6268;
        }

        .list-container table {
            width: 100%;
            border-collapse: collapse;
        }

        .list-container table th, .list-container table td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
        }

        .list-container table th {
            background-color: #f4f4f9;
            color: #333;
        }

        .list-container table tr.inactive {
            background-color: #e9ecef; /* Cor ligeiramente mais escura para alunos inativos */
        }

        .search-container {
            margin-bottom: 15px;
        }

        #search-bar {
            width: 100%;
            padding: 10px;
            font-size: 16px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
        }

        .edit-btn {
            background-color: #ffc107;
            color: #fff;
            border: none;
            padding: 5px 10px;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
            font-size: 14px;
            display: block;
            margin: 0 auto; /* Isso centraliza o botão */
        }

        .edit-btn:hover {
            background-color: #e0a800;
        }

        /* Estilo do interruptor (toggle switch) */
        .toggle-switch {
            position: relative;
            display: inline-block;
            width: 40px;
            height: 20px;
        }

        .toggle-switch input {
            opacity: 0;
            width: 0;
            height: 0;
        }

        .slider {
            position: absolute;
            cursor: pointer;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: #ccc;
            transition: 0.4s;
            border-radius: 20px;
        }

        .slider:before {
            position: absolute;
            content: "";
            height: 14px;
            width: 14px;
            left: 3px;
            bottom: 3px;
            background-color: white;
            transition: 0.4s;
            border-radius: 50%;
        }

        input:checked + .slider {
            background-color: #007bff;
        }

        input:checked + .slider:before {
            transform: translateX(20px);
        }
    </style>
    <script>
        function carregarDadosAluno(id, nome, matricula, casaId, turmaId) {
            // Preenche os campos do formulário com os dados do aluno
            document.getElementById('aluno_id').value = id;
            document.getElementById('nome').value = nome;
            document.getElementById('matricula').value = matricula;
            document.getElementById('casa_id').value = casaId;
            document.getElementById('turma_id').value = turmaId;

            // Altera o texto do botão para "Atualizar"
            document.getElementById('submit-btn').textContent = 'Atualizar';
        }

        function filtrarAlunos() {
            const searchInput = document.getElementById('search-bar').value.toLowerCase();
            const table = document.getElementById('alunos-table');
            const rows = table.getElementsByTagName('tr');

            for (let i = 1; i < rows.length; i++) { // Começa em 1 para ignorar o cabeçalho
                const nomeCell = rows[i].getElementsByTagName('td')[1]; // Coluna "Nome"
                if (nomeCell) {
                    const nome = nomeCell.textContent || nomeCell.innerText;
                    rows[i].style.display = nome.toLowerCase().includes(searchInput) ? '' : 'none';
                }
            }
        }

        function toggleAtivo(id, ativo) {
            const xhr = new XMLHttpRequest();
            xhr.open("POST", "cadastro_alunos.php", true);
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
            xhr.onload = function() {
                if (xhr.status === 200) {
                    location.reload(); // Recarrega a página para atualizar a tabela
                } else {
                    alert("Erro ao atualizar o status do aluno.");
                }
            };
            xhr.send(`id=${id}&ativo=${ativo}&toggle_ativo=true`);
        }
    </script>
</head>

<body>
    <div class="container">
        <!-- Formulário de Cadastro/Edição -->
        <div class="form-container">
            <h1>Cadastro de Alunos</h1>
            <?php if (isset($_GET['success']) && $_GET['success'] === 'true'): ?>
                <p class="success-message">Aluno cadastrado/atualizado com sucesso!</p>
            <?php endif; ?>
            <form action="cadastro_alunos.php" method="POST">
                <input type="hidden" id="aluno_id" name="aluno_id" value="">
                <div class="form-group">
                    <label for="nome">Nome</label> <br>
                    <input type="text" id="nome" name="nome" placeholder="Nome do Aluno" required>
                </div>
                <div class="form-group">
                    <label for="matricula">Matrícula</label> <br>
                    <input type="text" id="matricula" name="matricula" placeholder="Matrícula" required>
                </div>
                <div class="form-group">
                    <label for="casa_id">Casa</label> <br>
                    <select id="casa_id" name="casa_id" required>
                        <option value="">Selecione a Casa</option>
                        <?php foreach ($casas as $casa): ?>
                            <option value="<?= $casa['ID'] ?>"><?= htmlspecialchars($casa['Nome']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="turma_id">Turma</label> <br>
                    <select id="turma_id" name="turma_id" required>
                        <option value="">Selecione a Turma</option>
                        <?php foreach ($turmas as $turma): ?>
                            <option value="<?= $turma['ID'] ?>"><?= htmlspecialchars($turma['Nome']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <button type="submit" id="submit-btn" class="submit-btn">Cadastrar</button>
            </form>
            <a href="menu.php" class="back-btn">Voltar ao Menu</a>
        </div>

        <!-- Lista de Alunos -->
        <div class="list-container">
            <h1>Alunos Cadastrados</h1>
            <div class="search-container">
                <input type="text" id="search-bar" placeholder="Pesquisar por nome..." onkeyup="filtrarAlunos()">
            </div>
            <table id="alunos-table">
                <thead>
                    <tr>
                        <th id="texto">Ações</th>
                        <th id="texto">Nome</th>
                        <th id="texto">Matrícula</th>
                        <th id="texto">Casa</th>
                        <th id="texto">Turma</th>
                        <th id="texto">Ativo</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($alunos as $aluno): ?>
                        <tr class="<?= $aluno['Ativo'] ? '' : 'inactive' ?>">
                            <td>
                                <button class="edit-btn" onclick="carregarDadosAluno(
                                    <?= $aluno['ID'] ?>,
                                    '<?= htmlspecialchars($aluno['Nome'], ENT_QUOTES) ?>',
                                    '<?= htmlspecialchars($aluno['Matricula'], ENT_QUOTES) ?>',
                                    <?= $aluno['Casa_ID'] ?>,
                                    <?= $aluno['Turma_ID'] ?>
                                )">Editar</button>
                            </td>
                            <td id="texto"><?= htmlspecialchars($aluno['Nome']) ?></td>
                            <td id="texto"><?= htmlspecialchars($aluno['Matricula']) ?></td>
                            <td id="texto"><?= htmlspecialchars($aluno['Casa']) ?></td>
                            <td id="texto"><?= htmlspecialchars($aluno['Turma']) ?></td>
                            <td>
                                <label class="toggle-switch">
                                    <input type="checkbox" <?= $aluno['Ativo'] ? 'checked' : '' ?>
                                        onchange="toggleAtivo(<?= $aluno['ID'] ?>, this.checked ? 1 : 0)">
                                    <span class="slider"></span>
                                </label>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>

</html>