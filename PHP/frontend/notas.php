<?php
require_once '../backend/session_manager.php';
?>
<?php
require_once '../backend/controllers/NotaController.php';

$notaController = new NotaController();
$notas = $notaController->obterNotas();

if ($notas) {
    foreach ($notas as $nota) {
        echo "<p>Professor: " . $nota['Professor_ID'] . " - Aluno: " . $nota['Aluno_ID'] . " - Matéria: " . $nota['Materia'] . " - Pontos: " . $nota['Pontos'] . "</p>";
    }
} else {
    echo "<p>Não há notas registradas.</p>";
}
?>
