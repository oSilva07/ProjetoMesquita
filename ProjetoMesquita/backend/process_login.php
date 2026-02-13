<?php
require_once '../backend/config/db.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // FILTER_SANITIZE_STRING está depreciado, use FILTER_DEFAULT ou só trim
    $login = trim($_POST['username'] ?? '');
    $senha = trim($_POST['password'] ?? '');

    try {
        // Consulta para buscar o usuário pelo login
        $sql = "SELECT * FROM tb_usuarios WHERE login = :login";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':login', $login, PDO::PARAM_STR);
        $stmt->execute();

        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$usuario) {
            // Login não encontrado
            header("Location: ../frontend/index.php?error=invalid_login");
            exit;
        }

        if (!password_verify($senha, $usuario['senha'])) {
            // Senha incorreta
            header("Location: ../frontend/index.php?error=invalid_password");
            exit;
        }

        // Login e senha corretos, armazena as informações na sessão
        $_SESSION['usuario_id'] = $usuario['id_usuario'];
        $_SESSION['usuario_nome'] = $usuario['nome'];
        $_SESSION['usuario_nivel'] = $usuario['nivel'];
        $_SESSION['usuario_login'] = $usuario['login'];

        // Redireciona para o menu principal
        header('Location: ../frontend/index.php?login=success');
        exit;
    } catch (PDOException $e) {
        echo "Erro ao processar login: " . $e->getMessage();
    }
}
?>