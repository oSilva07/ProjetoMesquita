<?php
require_once '../config/db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $professor_id = $_POST['usuario_id'] ?? $_POST['professor_id'] ?? null;
    $aluno_id = $_POST['aluno_id'] ?? null;
    $disciplina_id = $_POST['disciplina'] ?? $_POST['materia'] ?? null;
    $motivo_id = $_POST['motivo_id'] ?? $_POST['motivo_nota'] ?? null;
    $pontuacao = $_POST['pontuacao'] ?? $_POST['pontos'] ?? null;
    $id_casa = $_POST['id_casa'] ?? null;

    // Sanitização de entradas
    $professor_id = filter_var($professor_id, FILTER_SANITIZE_NUMBER_INT);
    $aluno_id = filter_var($aluno_id, FILTER_SANITIZE_NUMBER_INT);
    $disciplina_id = filter_var($disciplina_id, FILTER_SANITIZE_NUMBER_INT);
    $motivo_id = filter_var($motivo_id, FILTER_SANITIZE_NUMBER_INT);
    $pontuacao = filter_var($pontuacao, FILTER_SANITIZE_NUMBER_INT);
    $id_casa = filter_var($id_casa, FILTER_SANITIZE_NUMBER_INT);

    // Se não for informado aluno, salva como 0 (pontuação para a casa)
    if (empty($aluno_id)) {
        $aluno_id = 0;
    }

    // Define tipo: 0 = negativo, 1 = positivo
    $tipo = ($pontuacao < 0) ? 0 : 1;

    // Buscar o id_discProf correspondente
    $sqlDiscProf = "SELECT id_discProf FROM tb_disciplinaprofessor WHERE id_usuario = :professor_id AND id_disciplina = :disciplina_id";
    $stmtDiscProf = $pdo->prepare($sqlDiscProf);
    $stmtDiscProf->bindParam(':professor_id', $professor_id, PDO::PARAM_INT);
    $stmtDiscProf->bindParam(':disciplina_id', $disciplina_id, PDO::PARAM_INT);
    $stmtDiscProf->execute();
    $id_discProf = $stmtDiscProf->fetchColumn();

    if (!$id_discProf) {
        echo "Erro: Não foi possível encontrar o vínculo do professor com a disciplina.";
        exit;
    }

    // Buscar limites do motivo
    $stmtMotivo = $pdo->prepare("SELECT pont_min, pont_max FROM tb_motivos WHERE id_motivo = ?");
    $stmtMotivo->execute([$motivo_id]);
    $motivoLimite = $stmtMotivo->fetch(PDO::FETCH_ASSOC);

    if ($motivoLimite) {
        if ($pontuacao < $motivoLimite['pont_min'] || $pontuacao > $motivoLimite['pont_max']) {
            echo "<div style='font-family:Arial;padding:30px;max-width:400px;margin:60px auto;background:#fff;border-radius:8px;box-shadow:0 2px 8px #0001;text-align:center;'>
                    <p style='color:#b91c1c;font-size:18px;'><strong>Erro:</strong> A pontuação para este motivo deve estar entre <strong>{$motivoLimite['pont_min']}</strong> e <strong>{$motivoLimite['pont_max']}</strong>.</p>
                    <p id='contador' style='color:#555;font-size:15px;'>Você será redirecionado para a tela de atribuição de notas em <span id='segundos'>3</span> segundos.</p>
                  </div>
                  <script>
                    var segundos = 3;
                    var intervalo = setInterval(function() {
                      segundos--;
                      document.getElementById('segundos').textContent = segundos;
                      if (segundos <= 0) {
                        clearInterval(intervalo);
                        window.location.href = '../../frontend/atribuir_notas.php';
                      }
                    }, 1000);
                  </script>";
            exit;
        }
    }

    try {
        $sql = "INSERT INTO tb_pontuacao (id_discProf, id_aluno, id_motivo, id_casa, pontuacao, tipo, data_atribuicao) 
                VALUES (:id_discProf, :id_aluno, :id_motivo, :id_casa, :pontuacao, :tipo, NOW())";

        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':id_discProf', $id_discProf, PDO::PARAM_INT);
        $stmt->bindParam(':id_aluno', $aluno_id, PDO::PARAM_INT);
        $stmt->bindParam(':id_motivo', $motivo_id, PDO::PARAM_INT);
        $stmt->bindParam(':id_casa', $id_casa, PDO::PARAM_INT);
        $stmt->bindParam(':pontuacao', $pontuacao, PDO::PARAM_INT);
        $stmt->bindParam(':tipo', $tipo, PDO::PARAM_INT);

        if ($stmt->execute()) {
            header("Location: ../../frontend/atribuir_notas.php?sucesso=true");
            exit;
        } else {
            echo "Erro ao adicionar pontuação.";
        }
    } catch (PDOException $e) {
        echo "Erro ao adicionar pontuação: " . $e->getMessage();
    }
}
?>
