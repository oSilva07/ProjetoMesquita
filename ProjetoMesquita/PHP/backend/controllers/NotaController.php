<?php
require_once '../config/db.php';

class NotaController {
    /**
     * Adiciona uma pontuação para um aluno.
     * @param int $usuario_id ID do usuário (professor)
     * @param int $aluno_id ID do aluno
     * @param int $disciplina_id ID da disciplina (matéria)
     * @param int $motivo_id ID do motivo
     * @param float $pontuacao Valor da pontuação
     * @param int $tipo Tipo da pontuação (ex: 1 para positiva, 0 para negativa)
     * @return bool
     */
    public function adicionarPontuacao($usuario_id, $aluno_id, $disciplina_id, $motivo_id, $pontuacao, $tipo) {
        global $pdo;

        try {
            // Verifica se o usuário existe e é professor
            $sqlUsuario = "SELECT nivel FROM tb_usuarios WHERE id_usuario = :usuario_id";
            $stmtUsuario = $pdo->prepare($sqlUsuario);
            $stmtUsuario->bindParam(':usuario_id', $usuario_id, PDO::PARAM_INT);
            $stmtUsuario->execute();
            $usuario = $stmtUsuario->fetch(PDO::FETCH_ASSOC);

            if (!$usuario) {
                throw new Exception("Usuário não encontrado.");
            }

            // Busca o id_discProf correspondente ao professor e disciplina
            $sqlDiscProf = "SELECT id_discProf FROM tb_disciplinaprofessor WHERE id_usuario = :usuario_id AND id_disciplina = :disciplina_id";
            $stmtDiscProf = $pdo->prepare($sqlDiscProf);
            $stmtDiscProf->bindParam(':usuario_id', $usuario_id, PDO::PARAM_INT);
            $stmtDiscProf->bindParam(':disciplina_id', $disciplina_id, PDO::PARAM_INT);
            $stmtDiscProf->execute();
            $discProf = $stmtDiscProf->fetch(PDO::FETCH_ASSOC);

            if (!$discProf) {
                throw new Exception("Relação professor/disciplina não encontrada.");
            }

            $id_discProf = $discProf['id_discProf'];

            // Insere a pontuação
            $sql = "INSERT INTO tb_pontuacao (id_discProf, id_aluno, id_motivo, pontuacao, tipo, data_atribuicao)
                    VALUES (:id_discProf, :id_aluno, :id_motivo, :pontuacao, :tipo, CURDATE())";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':id_discProf', $id_discProf, PDO::PARAM_INT);
            $stmt->bindParam(':id_aluno', $aluno_id, PDO::PARAM_INT);
            $stmt->bindParam(':id_motivo', $motivo_id, PDO::PARAM_INT);
            $stmt->bindParam(':pontuacao', $pontuacao);
            $stmt->bindParam(':tipo', $tipo, PDO::PARAM_INT);

            return $stmt->execute();
        } catch (Exception $e) {
            echo "Erro ao adicionar pontuação: " . $e->getMessage();
            return false;
        }
    }

    public function obterPontuacoes() {
        global $pdo;
        $sql = "SELECT * FROM tb_pontuacao";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obterPontuacoesPorCasa($id_casa) {
        global $pdo;
        $sql = "SELECT p.* FROM tb_pontuacao p
                JOIN tb_alunos a ON p.id_aluno = a.id_aluno
                WHERE a.id_casa = :id_casa";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':id_casa', $id_casa, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}

?>



