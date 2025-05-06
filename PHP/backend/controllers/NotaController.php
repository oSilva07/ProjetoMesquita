<?php
require_once '../config/db.php';

class NotaController {
    public function adicionarNota($professor_id, $aluno_id, $materia, $motivo_nota, $desconto_nota, $pontos) {
        global $pdo;
        
        try {
            $sql = "INSERT INTO Nota (Professor_ID, Aluno_ID, Materia, Motivo_Nota, Desconto_Nota, Pontos)
                    VALUES (:professor_id, :aluno_id, :materia, :motivo_nota, :desconto_nota, :pontos)";
            
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':professor_id', $professor_id);
            $stmt->bindParam(':aluno_id', $aluno_id);
            $stmt->bindParam(':materia', $materia);
            $stmt->bindParam(':motivo_nota', $motivo_nota);
            $stmt->bindParam(':desconto_nota', $desconto_nota);
            $stmt->bindParam(':pontos', $pontos);
            
            return $stmt->execute();
        } catch (PDOException $e) {
            // Se ocorrer erro ao adicionar a nota, será exibido
            echo "Erro ao adicionar nota: " . $e->getMessage();
            return false;
        }
    }
    
    
    

    public function obterNotas() {
        global $pdo;
        
        $sql = "SELECT * FROM Nota";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function obterNotasPorCasa($casa_id) {
        global $pdo;
        
        $sql = "SELECT * FROM Nota 
                JOIN Alunos ON Nota.Aluno_ID = Alunos.ID
                WHERE Alunos.Casa_ID = :casa_id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':casa_id', $casa_id);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }  

}

?>



