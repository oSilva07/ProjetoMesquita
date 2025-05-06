<!-- filepath: c:\wamp64\www\ProjetoMesquita\frontend\cadastro_casas.php -->
<?php
require_once '../backend/config/db.php';
require_once '../backend/session_manager.php';

// Verifica se o usuário logado é administrador ou coordenação
if ($usuario_classe != 'ADM' && $usuario_classe != 'Coordenacao') {
    header("Location: access_denied.php");
    exit;
}

// Busca as casas cadastradas no banco de dados
$sqlCasas = "SELECT ID, Nome FROM Casas";
$casas = $pdo->query($sqlCasas)->fetchAll(PDO::FETCH_ASSOC);

// Processa o formulário de cadastro
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['nome'])) {
    $nome = filter_input(INPUT_POST, 'nome', FILTER_SANITIZE_STRING);

    try {
        // Insere a casa no banco de dados
        $sql = "INSERT INTO Casas (Nome) VALUES (:nome)";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':nome', $nome, PDO::PARAM_STR);
        $stmt->execute();

        // Redireciona com mensagem de sucesso
        header("Location: cadastro_casas.php?success=true");
        exit;
    } catch (PDOException $e) {
        echo "Erro ao cadastrar casa: " . $e->getMessage();
    }
}

// Processa a exclusão de uma casa
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_id'])) {
    $delete_id = filter_input(INPUT_POST, 'delete_id', FILTER_VALIDATE_INT);

    try {
        // Exclui a casa do banco de dados
        $sql = "DELETE FROM Casas WHERE ID = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':id', $delete_id, PDO::PARAM_INT);
        $stmt->execute();

        // Redireciona com mensagem de sucesso
        header("Location: cadastro_casas.php?deleted=true");
        exit;
    } catch (PDOException $e) {
        echo "Erro ao excluir casa: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Casas</title>
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

        #texto{
            text-align: center;
        }

        .form-container button {
            margin-top: 25px;
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

        .back-btn {
            display: inline-block;
            margin-top: 15px;
            padding: 10px 145px;
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

        .delete-btn {  
            background-color: #dc3545;
            color: #fff;
            border: none;
            padding: 5px 10px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
            display: block;
            margin: 0 auto; /* Isso centraliza o botão */
            
        }

        #centro{
            align-items: center;
        }

        .delete-btn:hover {
            background-color: #c82333;
        }

        .success-message {
            color: green;
            margin-bottom: 15px;
        }

        .deleted-message {
            color: red;
            margin-bottom: 15px;
        }
    </style>
</head>

<body>
    <div class="container">
        <!-- Formulário de Cadastro -->
        <div class="form-container">
            <h1>Cadastro de Casas</h1>
            <?php if (isset($_GET['success']) && $_GET['success'] === 'true'): ?>
                <p class="success-message">Casa cadastrada com sucesso!</p>
            <?php endif; ?>
            <?php if (isset($_GET['deleted']) && $_GET['deleted'] === 'true'): ?>
                <p class="deleted-message">Casa excluída com sucesso!</p>
            <?php endif; ?>
            <form action="cadastro_casas.php" method="POST">
                <input type="text" id="texto" name="nome" placeholder="Nome da Casa" required>
                <button type="submit">Cadastrar</button>
            </form>
            <a href="menu.php" class="back-btn">Voltar ao Menu</a>
        </div>

        <!-- Lista de Casas -->
        <div class="list-container">
            <h1>Casas Cadastradas</h1>
            <table>
                <thead>
                    <tr>
                        <th id="texto">ID</th>
                        <th id="texto">Nome</th>
                        <th id="texto">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($casas as $casa): ?>
                        <tr>
                            <td id="texto"><?= htmlspecialchars($casa['ID']) ?></td>
                            <td id="texto"><?= htmlspecialchars($casa['Nome']) ?></td>
                            <td>
                                <form action="cadastro_casas.php" method="POST" style="display:inline;">
                                    <input type="hidden" name="delete_id" value="<?= $casa['ID'] ?>">
                                    <button type="submit" class="delete-btn" id="centro" >Deletar</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>

</html>