<?php
require_once 'config/db.php';

$id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
$ativo = filter_input(INPUT_POST, 'ativo', FILTER_VALIDATE_INT);

if ($id === null || $ativo === null) {
    http_response_code(400);
    echo "Dados inválidos.";
    exit;
}

$sql = "UPDATE tb_alunos SET ativo = :ativo WHERE id_aluno = :id";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':ativo', $ativo, PDO::PARAM_INT);
$stmt->bindParam(':id', $id, PDO::PARAM_INT);

if ($stmt->execute()) {
    echo "Status atualizado!";
} else {
    http_response_code(500);
    $errorInfo = $stmt->errorInfo();
    echo "Erro ao atualizar status: " . $errorInfo[2];
}
?>