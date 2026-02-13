<?php
require_once 'config/db.php';
header('Content-Type: application/json');
if (isset($_GET['casa_id'])) {
    $casa_id = (int)$_GET['casa_id'];
    $sql = "
        SELECT DISTINCT t.id_turma, t.nome
        FROM tb_turmas t
        INNER JOIN tb_alunos a ON t.id_turma = a.id_turma
        WHERE a.id_casa = :casa_id AND a.ativo = 1
        ORDER BY t.nome
    ";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':casa_id', $casa_id, PDO::PARAM_INT);
    $stmt->execute();
    $turmas = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($turmas);
    exit;
}
echo json_encode([]);
?>