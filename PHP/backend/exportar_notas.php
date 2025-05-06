<!-- filepath: c:\wamp64\www\ProjetoMesquita\backend\exportar_notas.php -->
<?php
require_once 'config/db.php'; // Ajuste o caminho conforme necessário

header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=notas_cadastradas.xls");
header("Pragma: no-cache");
header("Expires: 0");

// Consulta as notas cadastradas
$sql = "SELECT N.ID, N.Pontos, N.Desconto_Nota, N.Data_Nota, 
               A.Nome AS Aluno, P.Nome AS Professor, 
               M.Nome AS Materia, MO.Descricao AS Motivo
        FROM nota N
        LEFT JOIN alunos A ON N.Aluno_ID = A.ID
        LEFT JOIN professor P ON N.Professor_ID = P.ID
        LEFT JOIN materia M ON N.Materia_ID = M.ID
        LEFT JOIN motivos MO ON N.Motivos_ID = MO.ID";
$notas = $pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);

// Gera o conteúdo do Excel
echo "ID\tAluno\tProfessor\tData\tDesconto\tPontos\tMateria\tMotivo\n";
foreach ($notas as $nota) {
    echo "{$nota['ID']}\t{$nota['Aluno']}\t{$nota['Professor']}\t{$nota['Data_Nota']}\t{$nota['Desconto_Nota']}\t{$nota['Pontos']}\t{$nota['Materia']}\t{$nota['Motivo']}\n";
}
?>