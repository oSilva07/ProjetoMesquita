<?php
require_once 'config/db.php';

// Verifica se a variável 'casa_id' foi passada via GET
if (isset($_GET['casa_id'])) {
    $casa_id = $_GET['casa_id'];

    // Consulta para obter as turmas baseadas no 'casa_id'
    $sql = "
        SELECT T.ID, T.Nome 
        FROM turmas T
        JOIN alunos A ON T.ID = A.Turma_ID
        WHERE A.Casa_ID = :Casa_id
        GROUP BY T.ID, T.Nome
    ";
    
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':Casa_id', $casa_id, PDO::PARAM_INT);
    $stmt->execute();

    $turmas = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Retorna as turmas como um JSON
    echo json_encode($turmas);
}
?>
