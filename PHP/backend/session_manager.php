<?php
session_start();

// Verifica se o usuário está logado
if (!isset($_SESSION['usuario_id'])) {
    header("Location: ../frontend/index.php?error=unauthorized");
    exit;
}

// Obtém as informações do usuário
$usuario_id = $_SESSION['usuario_id'];
$usuario_nome = $_SESSION['usuario_nome'];
$usuario_classe = $_SESSION['usuario_classe'];
?>