<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/ProjetoMesquita/PHP/backend/config/db.php';
header('Content-Type: application/json');
if (isset($_GET['turma_id'])) {
    $turma_id = (int)$_GET['turma_id'];
    $sql = "SELECT id_aluno, nome FROM tb_alunos WHERE id_turma = :turma_id AND ativo = 1";
    $params = [':turma_id' => $turma_id];
    if (isset($_GET['casa_id'])) {
        $casa_id = (int)$_GET['casa_id'];
        $sql .= " AND id_casa = :casa_id";
        $params[':casa_id'] = $casa_id;
    }
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $alunos = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($alunos);
    exit;
}
echo json_encode([]);
