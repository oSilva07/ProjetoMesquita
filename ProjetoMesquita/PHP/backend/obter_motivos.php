<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/ProjetoMesquita/PHP/backend/config/db.php';

header('Content-Type: application/json');

// Verifica se o parâmetro 'motivo_nota' está presente na requisição GET
if (isset($_GET['motivo_nota'])) {
    $motivo_nota = trim($_GET['motivo_nota']);

    // Consulta SQL para obter os motivos filtrando pela descrição
    $sql = "SELECT id_motivo, descricao FROM tb_motivos WHERE descricao LIKE :motivo_nota";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':motivo_nota', "%$motivo_nota%", PDO::PARAM_STR);

    try {
        $stmt->execute();
        $motivos = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($motivos);
        exit;
    } catch (Exception $e) {
        error_log("Erro ao executar consulta: " . $e->getMessage());
        echo json_encode([]);
        exit;
    }
} else {
    // Se o parâmetro 'motivo_nota' não for passado, retorna todos os motivos
    $sql = "SELECT id_motivo, descricao FROM tb_motivos";
    try {
        $stmt = $pdo->query($sql);
        $motivos = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($motivos);
        exit;
    } catch (Exception $e) {
        error_log("Erro ao executar consulta: " . $e->getMessage());
        echo json_encode([]);
        exit;
    }
}
?>
