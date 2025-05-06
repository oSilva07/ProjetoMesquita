<!-- filepath: c:\wamp64\www\ProjetoMesquita\frontend\cadastro_turmas.php -->
<?php
require_once '../backend/config/db.php';
require_once '../backend/session_manager.php';

// Verifica se o usuário logado é administrador ou coordenação
if ($usuario_classe != 'ADM' && $usuario_classe != 'Coordenacao') {
    header("Location: access_denied.php");
    exit;
}

// Busca as turmas cadastradas no banco de dados
$sqlTurmas = "SELECT ID, Nome FROM Turmas";
$turmas = $pdo->query($sqlTurmas)->fetchAll(PDO::FETCH_ASSOC);

// Processa o formulário de cadastro
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = filter_input(INPUT_POST, 'nome', FILTER_SANITIZE_STRING);

    try {
        // Insere a turma no banco de dados
        $sql = "INSERT INTO Turmas (Nome) VALUES (:nome)";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':nome', $nome, PDO::PARAM_STR);
        $stmt->execute();

        // Redireciona com mensagem de sucesso
        header("Location: cadastro_turmas.php?success=true");
        exit;
    } catch (PDOException $e) {
        echo "Erro ao cadastrar turma: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Turmas</title>
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
        }

        .form-container, .list-container {
            background: #fff;
            padding: 20px 30px;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            width: 100%;
            transform: translateY(-110px);
        }

        .form-container {
            max-width: 400px;
        }

        .form-container h1, .list-container h1 {
            text-align: center;
            margin-bottom: 20px;
            color: #333;
        }

        .form-container form {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        .form-container input {
            padding: 10px;
            font-size: 16px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .form-container button {
            margin-top: 10px;
            padding: 10px;
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

        #texto{
            text-align:center;
        }

        .back-btn {
            display: inline-block;
            margin-top: 15px;
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

        .success-message {
            color: green;
            margin-bottom: 15px;
        }
    </style>
</head>

<body>
    <div class="container">
        <!-- Formulário de Cadastro -->
        <div class="form-container">
            <h1>Cadastro de Turmas</h1>
            <?php if (isset($_GET['success']) && $_GET['success'] === 'true'): ?>
                <p class="success-message">Turma cadastrada com sucesso!</p>
            <?php endif; ?>
            <form action="cadastro_turmas.php" method="POST">
                <input type="text" name="nome" id="texto" placeholder="Nome da Turma" required>
                <button type="submit">Cadastrar</button>
            </form>
            <a href="menu.php" class="back-btn">Voltar ao Menu</a>
        </div>

        <!-- Lista de Turmas -->
        <div class="list-container">
            <h1>Turmas Cadastradas</h1>
            <table>
                <thead>
                    <tr>
                        <th id="texto">ID</th>
                        <th id="texto">Nome</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($turmas as $turma): ?>
                        <tr>
                            <td id="texto"><?= htmlspecialchars($turma['ID']) ?></td>
                            <td id="texto"><?= htmlspecialchars($turma['Nome']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>

</html>