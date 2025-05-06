<!-- filepath: c:\wamp64\www\ProjetoMesquita\frontend\ver_perfil.php -->
<?php
require_once '../backend/config/db.php';
session_start();


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $login = $_POST['username']; // Campo "username" do formulário
    $senha = $_POST['password']; // Campo "password" do formulário

    // Sanitiza os dados
    $login = filter_var($login, FILTER_SANITIZE_STRING);
    $senha = filter_var($senha, FILTER_SANITIZE_STRING);

    try {
        // Consulta para buscar o usuário pelo login
        $sql = "SELECT * FROM usuarios WHERE login = :login";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':login', $login, PDO::PARAM_STR);
        $stmt->execute();

        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

        // Verifica se o usuário existe e se a senha está correta
        if ($usuario && password_verify($senha, $usuario['Senha'])) {
            // Armazena as informações do usuário na sessão
            $_SESSION['usuario_id'] = $usuario['id'];
            $_SESSION['usuario_nome'] = $usuario['Nome'];
            $_SESSION['usuario_classe'] = $usuario['Classe'];
            $_SESSION['usuario_login'] = $usuario['login']; // Adiciona o login à sessão

            // Redireciona para o menu principal
            header("Location: ../frontend/menu.php");
            exit;
        } else {
            // Login ou senha inválidos
            header("Location: ../frontend/index.php?error=invalid_credentials");
            exit;
        }
    } catch (PDOException $e) {
        echo "Erro ao verificar login: " . $e->getMessage();
    }
}

// Verifica se o usuário está logado
if (!isset($_SESSION['usuario_id'])) {
    header("Location: index.php?error=unauthorized");
    exit;
}

// Obtém as informações do usuário logado
$usuario_id = $_SESSION['usuario_id'];
$usuario_nome = $_SESSION['usuario_nome'];
$usuario_classe = $_SESSION['usuario_classe'];
$usuario_login = $_SESSION['usuario_login'] ?? 'Não informado'; // Adiciona o login à sessão
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ver Perfil</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-image:  url('../Imagens/Fundo.png');
            margin: 0;
            padding: 0;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .profile-container {
            background: #fff;
            padding: 20px 30px;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
            text-align: center;
        }

        .profile-container h1 {
            margin-bottom: 20px;
            color: #333;
        }

        .profile-container p {
            margin: 10px 0;
            font-size: 16px;
            color: #555;
        }

        .back-btn {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 20px;
            background-color: #9d4edd;
            color: #fff;
            text-decoration: none;
            border-radius: 5px;
            font-size: 16px;
        }

        .back-btn:hover {
            background-color: rgb(197, 153, 233);
        }

        .update-btn {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 20px;
            background-color: #28a745;
            color: #fff;
            text-decoration: none;
            border-radius: 5px;
            font-size: 16px;
        }

        .update-btn:hover {
            background-color: #218838;
        }
    </style>
</head>

<body>
    <div class="profile-container">
        <h1>Perfil do Usuário</h1>
        <p><strong>Nome:</strong> <?= htmlspecialchars($usuario_nome) ?></p>
        <p><strong>Login:</strong> <?= htmlspecialchars($usuario_login) ?></p>
        <p><strong>Nível de Acesso:</strong> <?= htmlspecialchars($usuario_classe) ?></p>
        <a href="menu.php" class="back-btn">Voltar ao Menu</a>
        <a href="atualizar_perfil.php" class="update-btn">Atualizar Perfil</a>
    </div>
</body>

</html>