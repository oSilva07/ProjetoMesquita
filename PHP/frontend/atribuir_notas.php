<?php
require_once '../backend/config/db.php';
require_once '../backend/session_manager.php'; // Já inicia a sessão

$professor_id = $_SESSION['usuario_id'] ?? null; // Obtém o ID do professor logado

// Carrega as casas
$sqlCasas = "SELECT * FROM Casas";
$casas = $pdo->query($sqlCasas)->fetchAll(PDO::FETCH_ASSOC);

// Carrega as matérias
$sqlMaterias = "SELECT ID, Nome FROM materia";
$materias = $pdo->query($sqlMaterias)->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Formulário de Notas</title>
  <style>
    /* Estilo geral da página */
    body {
      font-family: Arial, sans-serif;
      background-image:  url('../Imagens/Fundo.png');
      margin: 0;
      padding: 20px;
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
    }

    /* Contêiner do formulário */
    form {
      background-color: #fff;
      padding: 20px 30px;
      border-radius: 8px;
      box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
      width: 100%;
      max-width: 600px;
      display: grid;
      grid-template-columns: 1fr 1fr; /* Duas colunas */
      gap: 15px;
      transform: translateY(-110px);
    }

    /* Estilo dos rótulos */
    form label {
      font-size: 14px;
      font-weight: bold;
      color: #333;
    }

    /* Campos que ocupam uma coluna */
    form select,
    form input {
      padding: 10px;
      font-size: 14px;
      border: 1px solid #ccc;
      border-radius: 5px;
      width: 100%;
      box-sizing: border-box;
    }

    /* Campos que ocupam duas colunas */
    form button,
    .back-btn {
      grid-column: span 2; /* Botões ocupam as duas colunas */
    }

    /* Estilo dos botões */
    form button {
      padding: 10px;
      width: 290px;
      font-size: 16px;
      background-color: #9d4edd;
      color: #fff;
      border: none;
      border-radius: 5px;
      cursor: pointer;
      transition: background-color 0.3s ease;
    }

    form button:hover {
      background-color: rgb(197, 153, 233);
    }

    #texto{
      text-align: center;
    }

    .export-btn {
      display: inline-block;
      padding: 10px;
      width: 270px;
      font-size: 16px;
      background-color: #28a745;
      color: #fff;
      text-decoration: none;
      border: none;
      border-radius: 5px;
      text-align: center;
      cursor: pointer;
      transition: background-color 0.3s ease;
    }

    .export-btn:hover {
      background-color: #218838;
    }

    /* Estilo do botão de voltar */
    .back-btn {
      display: inline-block;
      padding: 10px 20px;
      background-color: #6c757d;
      color: #fff;
      text-decoration: none;
      border-radius: 5px;
      font-size: 16px;
      text-align: center;
      transition: background-color 0.3s ease;
    }

    .back-btn:hover {
      background-color: #5a6268;
    }

    /* Alinhamento dos botões */
    .button-group {
      display: flex;
      justify-content: space-between;
      gap: 10px;
      grid-column: span 2; /* Ocupa as duas colunas */
    }
  </style>
  <script>
    function carregarTurmas() {
        const casaId = document.getElementById('casas').value;

        if (casaId) {
            const xhr = new XMLHttpRequest();
            xhr.open('GET', '../backend/obter_turmas.php?casa_id=' + casaId, true);
            xhr.onload = function () {
                if (xhr.status === 200) {
                    const turmas = JSON.parse(xhr.responseText);
                    const turmaSelect = document.getElementById('turma');

                    // Limpa as opções existentes
                    turmaSelect.innerHTML = '<option value="">Selecione uma Turma</option>';

                    // Adiciona as novas opções
                    turmas.forEach(turma => {
                        const option = document.createElement('option');
                        option.value = turma.ID;
                        option.textContent = turma.Nome;
                        turmaSelect.appendChild(option);
                    });
                } else {
                    alert('Erro ao carregar as turmas.');
                }
            };
            xhr.send();
        } else {
            // Limpa as opções se nenhuma casa for selecionada
            document.getElementById('turma').innerHTML = '<option value="">Selecione uma Turma</option>';
        }
    }

    function carregarAlunos() {
        const casaId = document.getElementById('casas').value;
        const turmaId = document.getElementById('turma').value;

        if (casaId && turmaId) {
            const xhr = new XMLHttpRequest();
            xhr.open('GET', '../backend/obter_alunos.php?casa_id=' + casaId + '&turma_id=' + turmaId, true);
            xhr.onload = function () {
                if (xhr.status === 200) {
                    const alunos = JSON.parse(xhr.responseText);
                    const alunosSelect = document.getElementById('aluno_id');

                    // Limpa as opções existentes
                    alunosSelect.innerHTML = '<option value="">Selecione um Aluno</option>';

                    // Adiciona as novas opções
                    alunos.forEach(aluno => {
                        const option = document.createElement('option');
                        option.value = aluno.ID;
                        option.textContent = aluno.Nome;
                        alunosSelect.appendChild(option);
                    });
                } else {
                    alert('Erro ao carregar os alunos.');
                }
            };
            xhr.send();
        } else {
            // Limpa as opções se nenhuma casa ou turma for selecionada
            document.getElementById('aluno_id').innerHTML = '<option value="">Selecione um Aluno</option>';
        }
    }
  </script>
</head>
<body>
  <form action="../backend/nota/adicionar.php" method="POST">
    <input type="hidden" name="professor_id" value="<?= $professor_id ?>">

    <!-- Linha 1 -->
    <label for="materia">Matéria:</label>
    <label for="casas">Casas:</label>
    <select name="materia" id="materia" required>
      <option value="" id="texto">Selecione uma Matéria</option>
      <?php foreach ($materias as $materia): ?>
        <option value="<?= $materia['ID'] ?>"><?= $materia['Nome'] ?></option>
      <?php endforeach; ?>
    </select>
    <select name="casas" id="casas" onchange="carregarTurmas()" required>
      <option value="" id="texto">Selecione uma Casa</option>
      <?php foreach ($casas as $casa): ?>
        <option value="<?= $casa['ID'] ?>"><?= $casa['Nome'] ?></option>
      <?php endforeach; ?>
    </select>

    <!-- Linha 2 -->
    <label for="turma">Turma:</label>
    <label for="aluno_id">Aluno:</label>
    <select name="turma" id="turma" onchange="carregarAlunos()" required>
      <option value="" id="texto">Selecione uma Turma</option>
    </select>
    <select name="aluno_id" id="aluno_id" required>
      <option value="" id="texto">Selecione um Aluno</option>
    </select>

    <!-- Linha 3 -->
    <label for="motivo_nota" >Motivo da Nota:</label>
    <label for="desconto_nota">Desconto:</label>
    <select name="motivo_nota" id="motivo_nota">
      <option value="" id="texto">Selecione o Motivo</option>
    </select>
    <input type="number" step="0.01" id="desconto_nota" name="desconto_nota" required>

    <!-- Linha 4 -->
    <label for="pontos">Pontos:</label>
    <input type="number" id="pontos" name="pontos" required> 

    <!-- Botões -->
    <div class="button-group">
      <button type="submit">Adicionar Nota</button>
      <a href="../backend/exportar_notas.php" class="export-btn">Exportar Excel</a>
    </div>
    <a href="menu.php" class="back-btn">Voltar ao Menu</a>
  </form>
</body>
</html>