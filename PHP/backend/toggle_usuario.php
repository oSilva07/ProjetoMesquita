<!-- filepath: c:\wamp64\www\ProjetoMesquita\backend\toggle_usuario.php -->
<?php
require_once 'config/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
    $ativo = filter_input(INPUT_POST, 'ativo', FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);

    if ($id !== null && $ativo !== null) {
        $sql = "UPDATE usuarios SET Ativo = :ativo WHERE ID = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':ativo', $ativo, PDO::PARAM_BOOL);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);

        if ($stmt->execute()) {
            http_response_code(200);
            echo "Status atualizado com sucesso.";
        } else {
            http_response_code(500);
            echo "Erro ao atualizar o status.";
        }
    } else {
        http_response_code(400);
        echo "Dados inválidos.";
    }
}
?>