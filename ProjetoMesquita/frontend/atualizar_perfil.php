<!-- filepath: c:\wamp64\www\ProjetoMesquita\frontend\atualizar_perfil.php -->
<?php
require_once '../backend/config/db.php';
session_start();
$nivel_usuario_logado = $_SESSION['usuario_nivel'] ?? '';


// Verifica se o usuário está logado
if (!isset($_SESSION['usuario_id'])) {
    header("Location: index.php?error=unauthorized");
    exit;
}

// Obtém as informações do usuário logado
$usuario_id = $_SESSION['usuario_id'];
$usuario_nome = $_SESSION['usuario_nome'] ?? 'Não informado';
$usuario_login = $_SESSION['usuario_login'] ?? 'Não informado';

// Processa o formulário de atualização
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
$novo_nome = filter_input(INPUT_POST, 'nome', FILTER_DEFAULT);
$novo_login = filter_input(INPUT_POST, 'login', FILTER_DEFAULT);
// Ou só use trim/strip_tags se quiser garantir limpeza:
$novo_nome = trim(strip_tags($_POST['nome'] ?? ''));
$novo_login = trim(strip_tags($_POST['login'] ?? ''));
$nova_senha = !empty($_POST['senha']) ? password_hash($_POST['senha'], PASSWORD_DEFAULT) : null;

    // Inicializa uma lista para rastrear os campos alterados
    $campos_alterados = [];

    try {
        // Busca os dados atuais do usuário no banco de dados
        $sql = "SELECT nome, login FROM tb_usuarios WHERE id_usuario = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':id', $usuario_id, PDO::PARAM_INT);
        $stmt->execute();
        $usuario_atual = $stmt->fetch(PDO::FETCH_ASSOC);

        // Verifica quais campos foram alterados
        if ($novo_nome !== $usuario_atual['nome']) {
            $campos_alterados[] = 'Nome';
        }
        if ($novo_login !== $usuario_atual['login']) {
            $campos_alterados[] = 'Login';
        }
        if (!empty($nova_senha)) {
            $campos_alterados[] = 'Senha';
        }

        // Monta a consulta dinamicamente com base nos campos preenchidos
        $sql = "UPDATE tb_usuarios SET nome = :nome, login = :login";
        $params = [
            ':nome' => $novo_nome,
            ':login' => $novo_login,
            ':id' => $usuario_id
        ];

        if (!empty($nova_senha)) {
            $sql .= ", senha = :senha";
            $params[':senha'] = $nova_senha;
        }

        $sql .= " WHERE id_usuario = :id";

        // Prepara e executa a consulta
        $stmt = $pdo->prepare($sql);
        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value);
        }
        $stmt->execute();

        // Atualiza as informações na sessão
        $_SESSION['usuario_nome'] = $novo_nome;
        $_SESSION['usuario_login'] = $novo_login;

        // Redireciona com mensagem de sucesso e campos alterados
        header("Location: atualizar_perfil.php?success=true&alterados=" . urlencode($alterados));
        exit;
    } catch (PDOException $e) {
        echo "Erro ao atualizar perfil: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Atualizar Perfil</title>
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

        .profile-container form {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        .profile-container input {
            padding: 10px;
            font-size: 16px;
            border: 1px solid #ccc;
            border-radius: 5px;
            width: 100%;
            text-align: center;
            
        }

        .profile-container button {
            padding: 10px;
            font-size: 16px;
            background-color: #9d4edd;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .profile-container button:hover {
            background-color: rgb(197, 153, 233);
        }

        #texto{
            text-align: center;
        }

        .back-btn {
            display: inline-block;
            margin-top: 20px;
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

        .success-message {
            color: green;
            margin-bottom: 15px;
        }
    </style>
</head>

<body>
    <div class="profile-container">
        <h1>Atualizar Perfil</h1>
        <?php if (isset($_GET['success']) && $_GET['success'] === 'true'): ?>
            <p class="success-message">Perfil atualizado com sucesso!</p>
        <?php endif; ?>
        <form action="atualizar_perfil.php" method="POST">
            <div style="text-align:left;" class="form-grup">
                <label for="nome">Nome</label><br>
                <input type="text" id="nome" name="nome" value="<?= htmlspecialchars($usuario_nome) ?>" readonly style="background: #f5f5f5; border: 1px solid #ccc; border-radius: 5px; padding: 10px; font-size: 16px; display: block; margin-bottom: 10px;">
            </div>
            <div style="text-align:left;" class="form-grup">
                <label for="login">Login</label><br>
                <input type="text" id="texto" name="login" value="<?= htmlspecialchars($usuario_login) ?>" readonly style="background: #f5f5f5; border: 1px solid #ccc; border-radius: 5px; padding: 10px; font-size: 16px; display: block; margin-bottom: 10px;">
            </div>
            <div style="text-align:left;" class="form-grup">
                <label for="senha">Alterar senha</label><br>
                <input type="password" id="senha" name="senha" placeholder="Nova Senha">
            </div>
            <div style="text-align:left;" class="form-grup">
                <label for="senha">Confirmar senha</label><br>
                <input type="password" id="senha" name="senha" placeholder="Nova Senha">
            </div>
            <button type="submit">Atualizar</button>
        </form>
        <a href="menu.php" class="back-btn">Voltar ao Menu</a>
    </div>
    <script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('.profile-container form');
    form.addEventListener('submit', function(e) {
        const senha = form.querySelector('input[name="senha"]').value;
        const confirmarSenha = form.querySelectorAll('input[name="senha"]')[1].value;
        // Só valida se algum campo de senha foi preenchido
        if (senha || confirmarSenha) {
            if (senha !== confirmarSenha) {
                e.preventDefault();
                mostrarErro('As senhas não coincidem. Tente novamente.');
            }
        }
    });

    function mostrarErro(msg) {
        let erro = document.getElementById('erro-senha');
        if (!erro) {
            erro = document.createElement('p');
            erro.id = 'erro-senha';
            erro.style.color = 'red';
            erro.style.marginBottom = '10px';
            form.insertBefore(erro, form.firstChild);
        }
        erro.textContent = msg;
    }
});
</script>
</body>

</html>
