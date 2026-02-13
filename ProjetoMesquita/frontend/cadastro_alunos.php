<?php
session_start();
$nivel_usuario_logado = $_SESSION['usuario_nivel'] ?? '';

// Redireciona se não for permitido
$nivel = strtolower($nivel_usuario_logado);
if (
    $nivel !== 'professor coordenador' &&
    $nivel !== 'coordenação' &&
    $nivel !== 'administrador'
) {
    header('Location: access_denied.php');
    exit;
}

require_once '../backend/config/db.php';

// Buscar alunos do banco com os nomes corretos dos campos e relacionamentos
$stmt = $pdo->query("
    SELECT a.id_aluno, a.nome AS nome_aluno, a.ativo, 
           c.nome AS nome_casa, c.id_casa, 
           t.nome AS nome_turma, t.id_turma
    FROM tb_alunos a
    LEFT JOIN tb_casas c ON a.id_casa = c.id_casa
    LEFT JOIN tb_turmas t ON a.id_turma = t.id_turma
");
$alunos = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Carregar casas e turmas do banco para os selects
$casas = $pdo->query("SELECT id_casa, nome FROM tb_casas")->fetchAll(PDO::FETCH_ASSOC);
$turmas = $pdo->query("SELECT id_turma, nome FROM tb_turmas")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <script>
        window.onload = function() {
            if (performance.navigation.type === 2) {
                document.querySelectorAll('form imput').forEach(input => input.value = '');
                document.getElementById('nome').value = '';
                document.getElementById('casa_id').value = '';
                document.getElementById('turma_id').value = '';
            }
        };
    </script>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Alunos</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-image: url('../Imagens/Fundo.png');
            display: flex;
            justify-content: center;
            align-items: flex-start;
            height: 100vh;
            margin: 0;
            padding: 20px;
        }

        @media screen and (max-width: 768px) {
            .list-container {
                width: 100%;
                flex-direction: column;
            }
        }

        .container {
            display: flex;
            gap: 20px;
            width: 100%;
            max-width: 1200px;
        }

        .form-container,
        .list-container {
            background: #fff;
            padding: 20px 30px;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            width: 100%;
        }

        .form-container {
            max-width: 400px;
        }

        .form-container h1,
        .list-container h1 {
            text-align: center;
            margin-bottom: 20px;
            color: #333;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
            color: #555;
        }

        .form-group input {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
        }

        .form-group select {
            width: 105%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
            text-align: center
        }

        #nome {
            text-align: center;
        }

        #login {
            text-align: center;
        }

        #texto {
            text-align: center;
        }

        .form-group input:focus,
        .form-group select:focus {
            border-color: #9d4edd;
            outline: none;
        }

        .submit-btn {
            width: 105%;
            padding: 10px;
            background-color: #9d4edd;
            color: #fff;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
        }

        .submit-btn:hover {
            background-color: rgb(197, 153, 233);
        }

        .back-btn {
            width: 100%;
            padding: 10px;
            background-color: #6c757d;
            color: #fff;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            margin-top: 10px;
        }

        .back-btn:hover {
            background-color: #5a6268;
        }

        .list-container table {
            width: 100%;
            border-collapse: collapse;
        }

        .list-container table th,
        .list-container table td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
        }

        .list-container table th {
            background-color: #f4f4f9;
            color: #333;
        }

        .list-container table tr.inactive {
            background-color: rgb(239, 233, 233);
            /* Cor ligeiramente mais escura para usuários inativos */
        }

        .edit-btn {
            background-color: #ffc107;
            color: #fff;
            border: none;
            padding: 5px 10px;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
            font-size: 14px;
            display: block;
            margin: 0 auto;
            /* Isso centraliza o botão */
        }

        .edit-btn:hover {
            background-color: #e0a800;
        }

        /* Estilo do interruptor (toggle switch) */
        .toggle-switch {
            position: relative;
            display: inline-block;
            width: 40px;
            height: 20px;
        }

        .toggle-switch input {
            opacity: 0;
            width: 0;
            height: 0;
        }

        .slider {
            position: absolute;
            cursor: pointer;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: #ccc;
            transition: 0.4s;
            border-radius: 20px;
        }

        .slider:before {
            position: absolute;
            content: "";
            height: 14px;
            width: 14px;
            left: 3px;
            bottom: 3px;
            background-color: white;
            transition: 0.4s;
            border-radius: 50%;
        }

        input:checked+.slider {
            background-color: #007bff;  
        }

        input:checked+.slider:before {
            transform: translateX(20px);
        }

        .search-container {
            margin-bottom: 15px;
        }

        #search-bar {
            width: 100%;
            padding: 10px;
            font-size: 16px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
        }

        .cancel-btn {
            background-color: #dc3545 !important;
            color: #fff !important;
            border: none;
            padding: 5px 10px;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
            font-size: 14px;
            display: block;
            margin: 0 auto;
        }

        .cancel-btn:hover {
            background-color: #a71d2a !important;
        }
    </style>
    <script>
        let alunoEmEdicao = null;

        function carregarDadosAluno(id, nome, casaId, turmaId) {
            alunoEmEdicao = id;
            document.getElementById('aluno_id').value = id;
            document.getElementById('nome').value = nome;
            document.getElementById('casa_id').value = casaId;
            document.getElementById('turma_id').value = turmaId;
            document.getElementById('submit-btn').textContent = 'Atualizar';
            atualizarBotoesEditar();
        }

        function cancelarEdicao() {
            alunoEmEdicao = null;
            document.getElementById('aluno_id').value = '';
            document.getElementById('nome').value = '';
            document.getElementById('casa_id').value = '';
            document.getElementById('turma_id').value = '';
            document.getElementById('submit-btn').textContent = 'Cadastrar';
            atualizarBotoesEditar();
        }

        function toggleAtivo(alunoId, isActive) {
            const xhr = new XMLHttpRequest();
            xhr.open("POST", "../backend/toggle_aluno.php", true);
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
            xhr.onload = function() {
                if (xhr.status === 200) {
                    alert("Status do aluno atualizado com sucesso!");
                    location.reload();
                } else {
                    alert("Erro ao atualizar o status do aluno.");
                }
            };
            xhr.send(`id=${alunoId}&ativo=${isActive}`);
        }

        function filtrarAlunos() {
            const searchInput = document.getElementById('search-bar').value.toLowerCase();
            const table = document.getElementById('alunos-table');
            const rows = table.getElementsByTagName('tr');
            for (let i = 1; i < rows.length; i++) {
                const nomeCell = rows[i].getElementsByTagName('td')[1];
                if (nomeCell) {
                    const nome = nomeCell.textContent || nomeCell.innerText;
                    rows[i].style.display = nome.toLowerCase().includes(searchInput) ? '' : 'none';
                }
            }
        }

        function atualizarBotoesEditar() {
            const botoes = document.querySelectorAll('.btn-editar-cancelar');
            botoes.forEach(botao => {
                const id = botao.getAttribute('data-id');
                if (alunoEmEdicao && id == alunoEmEdicao) {
                    botao.textContent = 'Cancelar';
                    botao.classList.add('cancel-btn');
                    botao.classList.remove('edit-btn');
                    botao.onclick = cancelarEdicao;
                } else {
                    botao.textContent = 'Editar';
                    botao.classList.add('edit-btn');
                    botao.classList.remove('cancel-btn');
                    botao.onclick = function() {
                        carregarDadosAluno(
                            this.getAttribute('data-id'),
                            this.getAttribute('data-nome'),
                            this.getAttribute('data-casa'),
                            this.getAttribute('data-turma')
                        );
                    };
                }
            });
        }

        function limparFormularioAluno() {
    var form = document.querySelector('.form-container form');
    if (form) {
        form.reset();
    }
    var btn = document.getElementById('submit-btn');
    if (btn) btn.textContent = 'Cadastrar';
    alunoEmEdicao = null; // <-- Limpa o estado de edição
    atualizarBotoesEditar(); // <-- Atualiza todos os botões para "Editar"
}

// Limpa ao carregar normalmente
document.addEventListener('DOMContentLoaded', limparFormularioAluno);

// Limpa ao voltar do histórico (cache/back)
window.addEventListener('pageshow', function(event) {
    if (event.persisted) {
        limparFormularioAluno();
    }
});
    </script>
</head>
<body>
    <div class="container">
        <!-- Formulário de Cadastro/Edição -->
        <div class="form-container">
            <h1>Cadastro de Alunos</h1>
            <form action="../backend/salvar_aluno.php" method="POST">
                <input type="hidden" id="aluno_id" name="aluno_id" value="">
                <div class="form-group">
                    <label for="nome">Nome</label>
                    <input type="text" id="nome" name="nome" placeholder="Digite o nome do aluno" required>
                </div>
                <div class="form-group">
                    <label for="casa_id">Casa</label>
                    <select id="casa_id" name="casa_id" required>
                        <option value="">Selecione a Casa</option>
                        <?php foreach ($casas as $casa): ?>
                            <option value="<?= $casa['id_casa'] ?>"><?= htmlspecialchars($casa['nome']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="turma_id">Turma</label>
                    <select id="turma_id" name="turma_id" required>
                        <option value="">Selecione a Turma</option>
                        <?php foreach ($turmas as $turma): ?>
                            <option value="<?= $turma['id_turma'] ?>"><?= htmlspecialchars($turma['nome']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <button type="submit" id="submit-btn" class="submit-btn">Cadastrar</button>
            </form>
            <a href="menu.php" class="back-btn">Voltar ao Menu</a>
        </div>

        <!-- Lista de Alunos -->
        <div class="list-container">
            <h1>Alunos Cadastrados</h1>
            <div class="search-container">
                <input type="text" id="search-bar" placeholder="Pesquisar por nome..." onkeyup="filtrarAlunos()">
            </div>
            <table id="alunos-table">
                <thead>
                    <tr>
                        <th id="texto">Ações</th>
                        <th id="texto">Nome</th>
                        <th id="texto">Casa</th>
                        <th id="texto">Turma</th>
                        <?php if (
                            strtolower($nivel_usuario_logado) === 'administração' ||
                            strtolower($nivel_usuario_logado) === 'admin' ||
                            strtolower($nivel_usuario_logado) === 'administrador' ||
                            strtolower($nivel_usuario_logado) === 'coordenação'
                        ): ?>
                            <th id="texto">Ativo</th>
                        <?php endif; ?>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($alunos as $aluno): ?>
                        <tr class="<?= $aluno['ativo'] ? '' : 'inactive' ?>">
                            <td>
                                <button
                                    type="button"
                                    class="btn-editar-cancelar edit-btn"
                                    data-id="<?= $aluno['id_aluno'] ?>"
                                    data-nome="<?= htmlspecialchars($aluno['nome_aluno'], ENT_QUOTES) ?>"
                                    data-casa="<?= $aluno['id_casa'] ?>"
                                    data-turma="<?= $aluno['id_turma'] ?>"
                                    onclick="carregarDadosAluno(
                                        <?= $aluno['id_aluno'] ?>,
                                        '<?= htmlspecialchars($aluno['nome_aluno'], ENT_QUOTES) ?>',
                                        <?= $aluno['id_casa'] ?>,
                                        <?= $aluno['id_turma'] ?>
                                    )"
                                    <?php
                                    $nivel = strtolower($nivel_usuario_logado);
                                    if (
                                        !$aluno['ativo'] &&
                                        $nivel !== 'administrador' &&
                                        $nivel !== 'coordenação'
                                    ): ?>
                                        disabled
                                        style="opacity:0.5;cursor:not-allowed;"
                                    <?php endif; ?>
                                >Editar</button>
                            </td>
                            <td id="texto"><?= htmlspecialchars($aluno['nome_aluno']) ?></td>
                            <td id="texto"><?= htmlspecialchars($aluno['nome_casa']) ?></td>
                            <td id="texto"><?= htmlspecialchars($aluno['nome_turma']) ?></td>
                            <?php if (
                                strtolower($nivel_usuario_logado) === 'administração' ||
                                strtolower($nivel_usuario_logado) === 'admin' ||
                                strtolower($nivel_usuario_logado) === 'administrador' ||
                                strtolower($nivel_usuario_logado) === 'coordenação'
                            ): ?>
                                <td>
                                    <label class="toggle-switch">
                                        <input type="checkbox" <?= $aluno['ativo'] ? 'checked' : '' ?>
                                            onchange="toggleAtivo(<?= $aluno['id_aluno'] ?>, this.checked ? 1 : 0)">
                                        <span class="slider"></span>
                                    </label>
                                </td>
                            <?php endif; ?>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>

</html>
