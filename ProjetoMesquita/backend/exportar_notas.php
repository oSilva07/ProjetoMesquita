<!-- filepath: c:\wamp64\www\ProjetoMesquita\backend\exportar_notas.php -->
<?php
require_once 'config/db.php';

header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=pontuacoes.xls");
header("Pragma: no-cache");
header("Expires: 0");

// 1. Buscar todas as datas em que houve pontuação
$datas = $pdo->query("SELECT DISTINCT DATE(data_atribuicao) as data FROM tb_pontuacao ORDER BY data")->fetchAll(PDO::FETCH_COLUMN);

// 2. Buscar todos os alunos e suas casas
$alunos = $pdo->query("
    SELECT a.id_aluno, a.nome AS aluno, c.nome AS casa
    FROM tb_alunos a
    LEFT JOIN tb_casas c ON a.id_casa = c.id_casa
    ORDER BY a.nome
")->fetchAll(PDO::FETCH_ASSOC);

// 3. Buscar todas as pontuações por aluno e data
$stmt = $pdo->query("
    SELECT id_aluno, DATE(data_atribuicao) as data, SUM(pontuacao) as pontos
    FROM tb_pontuacao
    GROUP BY id_aluno, DATE(data_atribuicao)
");
$pontuacoes = [];
foreach ($stmt as $row) {
    $pontuacoes[$row['id_aluno']][$row['data']] = $row['pontos'];
}

// 4. Buscar total de pontos por aluno
$stmtTotal = $pdo->query("
    SELECT id_aluno, SUM(pontuacao) as total
    FROM tb_pontuacao
    GROUP BY id_aluno
");
$totais = [];
foreach ($stmtTotal as $row) {
    $totais[$row['id_aluno']] = $row['total'];
}

// 5. Montar o cabeçalho
echo "Alunos\tCasas";
foreach ($datas as $data) {
    echo "\t" . date('d/m', strtotime($data));
}
echo "\tTotal de Pontos\n";

// 6. Montar as linhas
foreach ($alunos as $aluno) {
    echo "{$aluno['aluno']}\t{$aluno['casa']}";
    foreach ($datas as $data) {
        $pontos = $pontuacoes[$aluno['id_aluno']][$data] ?? '';
        echo "\t{$pontos}";
    }
    $total = $totais[$aluno['id_aluno']] ?? '';
    echo "\t{$total}\n";
}
?>