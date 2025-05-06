<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/ProjetoMesquita/backend/config/db.php';

// Verifica se os parâmetros 'casa_id' e 'turma_id' estão presentes na requisição GET
if (isset($_GET['casa_id']) && isset($_GET['turma_id'])) {
    $casa_id = $_GET['casa_id'];
    $turma_id = $_GET['turma_id'];

    // Log para verificar os parâmetros recebidos
    error_log("casa_id: " . $casa_id . " turma_id: " . $turma_id);

    // Consulta SQL para obter os alunos baseados na Casa e Turma
    $sql = "SELECT A.ID, A.Nome 
            FROM Alunos A
            WHERE A.Casa_ID = :casa_id AND A.Turma_ID = :turma_id";

    // Preparando a consulta SQL
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':casa_id', $casa_id, PDO::PARAM_INT);
    $stmt->bindParam(':turma_id', $turma_id, PDO::PARAM_INT);

    // Executa a consulta
    try {
        $stmt->execute();
    } catch (Exception $e) {
        // Se houver erro na execução da consulta, será registrado
        error_log("Erro ao executar consulta: " . $e->getMessage());
        echo json_encode([]);
        exit;
    }

    // Pega os resultados da consulta
    $alunos = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Log para verificar o retorno da consulta
    error_log("Alunos encontrados: " . json_encode($alunos));

    // Retorna os alunos em formato JSON
    echo json_encode($alunos);
} else {
    // Se os parâmetros 'casa_id' ou 'turma_id' não forem passados, retorna um array vazio
    error_log("Parametros 'casa_id' ou 'turma_id' não fornecidos.");
    echo json_encode([]);
}
?>
