<?php
require_once '../backend/config/db.php';

// Carregar apenas ALUNOS que têm pontuação
$alunos = $pdo->query("
    SELECT DISTINCT a.id_aluno, a.nome
    FROM tb_alunos a
    INNER JOIN tb_pontuacao p ON p.id_aluno = a.id_aluno
    WHERE a.ativo = 1 AND p.id_aluno > 0
    ORDER BY a.nome
")->fetchAll(PDO::FETCH_ASSOC);

// Carregar apenas PROFESSORES que têm pontuação registrada
$professores = $pdo->query("
    SELECT DISTINCT u.id_usuario, u.nome
    FROM tb_usuarios u
    INNER JOIN tb_disciplinaprofessor dp ON dp.id_usuario = u.id_usuario
    INNER JOIN tb_pontuacao p ON p.id_discProf = dp.id_discProf
    ORDER BY u.nome
")->fetchAll(PDO::FETCH_ASSOC);

// Carregar apenas CASAS que têm pontuação registrada
$casas = $pdo->query("
    SELECT DISTINCT c.id_casa, c.nome
    FROM tb_casas c
    INNER JOIN tb_pontuacao p ON p.id_casa = c.id_casa
    ORDER BY c.nome
")->fetchAll(PDO::FETCH_ASSOC);

// Carregar apenas DISCIPLINAS que têm pontuação registrada
$disciplinas = $pdo->query("
    SELECT DISTINCT d.id_disciplina, d.nome
    FROM tb_disciplinas d
    INNER JOIN tb_disciplinaprofessor dp ON dp.id_disciplina = d.id_disciplina
    INNER JOIN tb_pontuacao p ON p.id_discProf = dp.id_discProf
    ORDER BY d.nome
")->fetchAll(PDO::FETCH_ASSOC);

// Carregar apenas MOTIVOS que têm pontuação registrada
$motivos = $pdo->query("
    SELECT DISTINCT m.id_motivo, m.descricao
    FROM tb_motivos m
    INNER JOIN tb_pontuacao p ON p.id_motivo = m.id_motivo
    ORDER BY m.descricao
")->fetchAll(PDO::FETCH_ASSOC);

// Montar filtros dinâmicos
$filtros = [];
$params = [];

if (!empty($_GET['aluno_id'])) {
    $filtros[] = 'p.id_aluno = :aluno_id';
    $params[':aluno_id'] = $_GET['aluno_id'];
}
if (!empty($_GET['professor_id'])) {
    $filtros[] = 'dp.id_usuario = :professor_id';
    $params[':professor_id'] = $_GET['professor_id'];
}
if (!empty($_GET['casa_id'])) {
    $filtros[] = 'p.id_casa = :casa_id';
    $params[':casa_id'] = $_GET['casa_id'];
}
if (!empty($_GET['disciplina_id'])) {
    $filtros[] = 'dp.id_disciplina = :disciplina_id';
    $params[':disciplina_id'] = $_GET['disciplina_id'];
}
if (!empty($_GET['motivo_id'])) {
    $filtros[] = 'p.id_motivo = :motivo_id';
    $params[':motivo_id'] = $_GET['motivo_id'];
}

$where = $filtros ? 'WHERE ' . implode(' AND ', $filtros) : '';

$sql = "
    SELECT 
        p.id_pontuacao,
        p.pontuacao,
        p.data_atribuicao,
        a.nome AS nome_aluno,
        d.nome AS nome_disciplina,
        t.nome AS nome_turma,
        c.nome AS nome_casa,
        m.descricao AS motivo
    FROM tb_pontuacao p
    LEFT JOIN tb_alunos a ON p.id_aluno = a.id_aluno
    LEFT JOIN tb_disciplinaprofessor dp ON p.id_discProf = dp.id_discProf
    LEFT JOIN tb_disciplinas d ON dp.id_disciplina = d.id_disciplina
    LEFT JOIN tb_turmas t ON a.id_turma = t.id_turma
    LEFT JOIN tb_casas c ON p.id_casa = c.id_casa
    LEFT JOIN tb_motivos m ON p.id_motivo = m.id_motivo
    $where
    ORDER BY p.data_atribuicao DESC
";
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$historico = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Histórico de Pontuações</title>
    <style>
    body {
        font-family: Arial, sans-serif;
        background-image: url('../Imagens/Fundo.png');
        margin: 0;
        padding: 20px;
        justify-content: center;
        align-items: center;

    }
    h1 { 
    text-align: center;
    color: #ccc;
     }
        
    form.filtros { 
        margin-bottom: 20px; 
        display: 
        flex; gap: 10px; 
        flex-wrap: wrap; 
        justify-content: center;
    }
    form.filtros select { 
            padding: 5px; 
            border-radius: 5px;
    }

    table { 
        width: 100%; 
        border-collapse: collapse; 
        margin-top: 20px;
    }

    th, td { 
        border: 1px solid #ccc; 
        padding: 8px; 
        text-align: center;
    }

    th {
        background-color: #9d4edd;
    }
    td {
        background-color: #f9f9f9;
    }


    </style>
</head>
<body>
    <h1>Histórico de Pontuações</h1>
    <form method="get" class="filtros">
        <select name="aluno_id">
            <option value="">Todos os Alunos</option>
            <?php foreach ($alunos as $aluno): ?>
                <option value="<?= $aluno['id_aluno'] ?>" <?= isset($_GET['aluno_id']) && $_GET['aluno_id'] == $aluno['id_aluno'] ? 'selected' : '' ?>>
                    <?= htmlspecialchars($aluno['nome']) ?>
                </option>
            <?php endforeach; ?>
        </select>
        <select name="professor_id">
            <option value="">Todos os Professores</option>
            <?php foreach ($professores as $prof): ?>
                <option value="<?= $prof['id_usuario'] ?>" <?= isset($_GET['professor_id']) && $_GET['professor_id'] == $prof['id_usuario'] ? 'selected' : '' ?>>
                    <?= htmlspecialchars($prof['nome']) ?>
                </option>
            <?php endforeach; ?>
        </select>
        <select name="casa_id">
            <option value="">Todas as Casas</option>
            <?php foreach ($casas as $casa): ?>
                <option value="<?= $casa['id_casa'] ?>" <?= isset($_GET['casa_id']) && $_GET['casa_id'] == $casa['id_casa'] ? 'selected' : '' ?>>
                    <?= htmlspecialchars($casa['nome']) ?>
                </option>
            <?php endforeach; ?>
        </select>
        <select name="disciplina_id">
            <option value="">Todas as Disciplinas</option>
            <?php foreach ($disciplinas as $disc): ?>
                <option value="<?= $disc['id_disciplina'] ?>" <?= isset($_GET['disciplina_id']) && $_GET['disciplina_id'] == $disc['id_disciplina'] ? 'selected' : '' ?>>
                    <?= htmlspecialchars($disc['nome']) ?>
                </option>
            <?php endforeach; ?>
        </select>
        <select name="motivo_id">
            <option value="">Todos os Motivos</option>
            <?php foreach ($motivos as $motivo): ?>
                <option value="<?= $motivo['id_motivo'] ?>" <?= isset($_GET['motivo_id']) && $_GET['motivo_id'] == $motivo['id_motivo'] ? 'selected' : '' ?>>
                    <?= htmlspecialchars($motivo['descricao']) ?>
                </option>
            <?php endforeach; ?>
        </select>
        <button type="submit">Filtrar</button>
    </form>
    <table>
        <thead>
            <tr>
                <th>Pontuação</th>
                <th>Data</th>
                <th>Aluno</th>
                <th>Disciplina</th>
                <th>Turma</th>
                <th>Casa</th>
                <th>Motivo</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($historico as $row): ?>
                <tr>
                    <td><?= htmlspecialchars($row['pontuacao']) ?></td>
                    <td><?= htmlspecialchars($row['data_atribuicao']) ?></td>
                    <td><?= htmlspecialchars($row['nome_aluno'] ?? '') ?></td>
                    <td><?= htmlspecialchars($row['nome_disciplina'] ?? '') ?></td>
                    <td><?= htmlspecialchars($row['nome_turma'] ?? '') ?></td>
                    <td><?= htmlspecialchars($row['nome_casa'] ?? '') ?></td>
                    <td><?= htmlspecialchars($row['motivo'] ?? '') ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>
