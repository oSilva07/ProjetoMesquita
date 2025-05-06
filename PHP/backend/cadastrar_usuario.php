<?php
require_once 'config/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = filter_var($_POST['nome'], FILTER_SANITIZE_STRING);
    $login = filter_var($_POST['login'], FILTER_SANITIZE_STRING);
    $senha = password_hash($_POST['senha'], PASSWORD_DEFAULT); // Hash da senha
    $classe = filter_var($_POST['classe'], FILTER_SANITIZE_STRING);

    try {
        $sql = "INSERT INTO usuarios (Nome, login, Senha, Classe, Data_Criacao) 
                VALUES (:nome, :login, :senha, :classe, NOW())";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':nome', $nome);
        $stmt->bindParam(':login', $login);
        $stmt->bindParam(':senha', $senha);
        $stmt->bindParam(':classe', $classe);

        if ($stmt->execute()) {
            header("Location: ../frontend/cadastro_usuarios.php?success=true");
            exit;
        } else {
            header("Location: ../frontend/cadastro_usuarios.php?error=failed");
            exit;
        }
    } catch (PDOException $e) {
        header("Location: ../frontend/cadastro_usuarios.php?error=failed");
        exit;
    }
}
?>