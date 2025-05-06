<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/ProjetoMesquita/backend/config/db.php';

// Verifica se o parâmetro 'motivo_nota' está presente na requisição GET
if (isset($_GET['motivo_nota'])) {
    $motivo_nota = $_GET['motivo_nota'];

    // Consulta SQL para obter os motivos
    $sql = "SELECT * FROM motivos WHERE descricao LIKE :motivo_nota";
    
    // Preparando a consulta SQL
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':motivo_nota', "%$motivo_nota%", PDO::PARAM_STR);

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
    $motivos = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (empty($motivos)) {
        error_log("Nenhum motivo encontrado para o filtro: $motivo_nota");
    }

    // Retorna os motivos em formato JSON
    echo json_encode($motivos);
} else {
    // Se o parâmetro 'motivo_nota' não for passado, retorna todos os motivos
    $sql = "SELECT * FROM motivos";
    $stmt = $pdo->query($sql);

    // Verifica se há erros na consulta
    if ($stmt === false) {
        error_log("Erro na consulta SQL: " . $pdo->errorInfo());
        echo json_encode([]);
        exit;
    }

    // Pega os resultados da consulta
    $motivos = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (empty($motivos)) {
        error_log("Nenhum motivo encontrado na tabela 'motivos'.");
    }

    // Retorna todos os motivos em formato JSON
    echo json_encode($motivos);
}
?>
