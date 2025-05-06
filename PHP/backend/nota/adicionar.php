<?php
require_once '../controllers/NotaController.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $professor_id = $_POST['professor_id'] ?? null; // Obtém o ID do professor do formulário
    $aluno_id = $_POST['aluno_id'];
    $materia = $_POST['materia'];
    $motivo_nota = $_POST['motivo_nota'];
    $desconto_nota = $_POST['desconto_nota'];
    $pontos = $_POST['pontos'];

    // Sanitização de entradas
    $professor_id = filter_var($professor_id, FILTER_SANITIZE_NUMBER_INT);
    $aluno_id = filter_var($aluno_id, FILTER_SANITIZE_NUMBER_INT);
    $materia = filter_var($materia, FILTER_SANITIZE_STRING);
    $motivo_nota = filter_var($motivo_nota, FILTER_SANITIZE_STRING);
    $desconto_nota = filter_var($desconto_nota, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
    $pontos = filter_var($pontos, FILTER_SANITIZE_NUMBER_INT);

    $notaController = new NotaController();

    if ($notaController->adicionarNota($professor_id, $aluno_id, $materia, $motivo_nota, $desconto_nota, $pontos)) {
        // Redireciona para uma página de sucesso ou confirma a operação
        header("Location: /backend/notas.php?sucesso=true");
        exit;
    } else {
        echo "Erro ao adicionar nota.";
    }
}
?>
