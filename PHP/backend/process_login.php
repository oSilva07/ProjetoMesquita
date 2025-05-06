<?php
require_once '../backend/config/db.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $login = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING);
    $senha = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING);

    try {
        // Consulta para buscar o usuário pelo login
        $sql = "SELECT * FROM usuarios WHERE login = :login";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':login', $login, PDO::PARAM_STR);
        $stmt->execute();

        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$usuario) {
            // Login não encontrado
            header("Location: ../frontend/index.php?error=invalid_login");
            exit;
        }

        if (!password_verify($senha, $usuario['Senha'])) {
            // Senha incorreta
            header("Location: ../frontend/index.php?error=invalid_password");
            exit;
        }

        // Login e senha corretos, armazena as informações na sessão
        $_SESSION['usuario_id'] = $usuario['ID']; // Corrigido para usar 'ID' em maiúsculas
        $_SESSION['usuario_nome'] = $usuario['Nome'];
        $_SESSION['usuario_classe'] = $usuario['Classe'];
        $_SESSION['usuario_login'] = $usuario['login'];

        // Redireciona para o menu principal
        header("Location: ../frontend/menu.php");
        exit;
    } catch (PDOException $e) {
        echo "Erro ao processar login: " . $e->getMessage();
    }
}
?>