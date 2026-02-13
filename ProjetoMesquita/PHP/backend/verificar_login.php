<?php
require_once 'config/db.php';

$login = trim($_GET['login'] ?? '');

if ($login === '') {
    echo json_encode(['existe' => false]);
    exit;
}

$stmt = $pdo->prepare("SELECT COUNT(*) FROM tb_usuarios WHERE login = ?");
$stmt->execute([$login]);
$existe = $stmt->fetchColumn() > 0;

echo json_encode(['existe' => $existe]);
?>