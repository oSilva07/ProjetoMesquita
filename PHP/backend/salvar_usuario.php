<!-- filepath: c:\wamp64\www\ProjetoMesquita\backend\salvar_usuario.php -->
<?php
require_once 'config/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = filter_input(INPUT_POST, 'usuario_id', FILTER_VALIDATE_INT);
    $nome = filter_input(INPUT_POST, 'nome', FILTER_SANITIZE_STRING);
    $login = filter_input(INPUT_POST, 'login', FILTER_SANITIZE_STRING);
    $classe = filter_input(INPUT_POST, 'classe', FILTER_SANITIZE_STRING);

    if ($id) {
        // Atualiza o usuário existente
        $sql = "UPDATE usuarios SET Nome = :nome, login = :login, Classe = :classe WHERE ID = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    } else {
        // Insere um novo usuário
        $sql = "INSERT INTO usuarios (Nome, login, Classe) VALUES (:nome, :login, :classe)";
        $stmt = $pdo->prepare($sql);
    }

    $stmt->bindParam(':nome', $nome, PDO::PARAM_STR);
    $stmt->bindParam(':login', $login, PDO::PARAM_STR);
    $stmt->bindParam(':classe', $classe, PDO::PARAM_STR);

    if ($stmt->execute()) {
        header("Location: ../frontend/cadastro_usuarios.php");
        exit;
    } else {
        echo "Erro ao salvar o usuário.";
    }
}
?>