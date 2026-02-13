<?php
require_once 'config/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = filter_input(INPUT_POST, 'aluno_id', FILTER_VALIDATE_INT);
    $nome = trim($_POST['nome'] ?? '');
    $casa_id = filter_input(INPUT_POST, 'casa_id', FILTER_VALIDATE_INT);
    $turma_id = filter_input(INPUT_POST, 'turma_id', FILTER_VALIDATE_INT);

    // Validação simples
    if (!$nome || !$casa_id || !$turma_id) {
        echo "Todos os campos são obrigatórios!";
        exit;
    }

    if ($id) {
        // Atualiza o aluno existente
        $sql = "UPDATE tb_alunos SET nome = :nome, id_casa = :casa_id, id_turma = :turma_id WHERE id_aluno = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    } else {
        // Insere um novo aluno
        $sql = "INSERT INTO tb_alunos (nome, id_casa, id_turma, ativo) VALUES (:nome, :casa_id, :turma_id, 1)";
        $stmt = $pdo->prepare($sql);
    }

    $stmt->bindParam(':nome', $nome, PDO::PARAM_STR);
    $stmt->bindParam(':casa_id', $casa_id, PDO::PARAM_INT);
    $stmt->bindParam(':turma_id', $turma_id, PDO::PARAM_INT);

    if ($stmt->execute()) {
        header("Location: ../frontend/cadastro_alunos.php?success=true");
        exit;
    } else {
        echo "Erro ao salvar o aluno.";
    }
}
?>