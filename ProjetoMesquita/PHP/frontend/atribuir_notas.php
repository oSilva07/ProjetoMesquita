<?php
require_once '../backend/config/db.php';
session_start();

$usuario_id = $_SESSION['usuario_id'] ?? null;

// Buscar apenas as disciplinas relacionadas ao usuário logado
$sql = "
    SELECT d.id_disciplina, d.nome
    FROM tb_disciplinaprofessor dp
    INNER JOIN tb_disciplinas d ON dp.id_disciplina = d.id_disciplina
    WHERE dp.id_usuario = :usuario_id AND dp.ativo = 1
    ORDER BY d.nome
";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':usuario_id', $usuario_id, PDO::PARAM_INT);
$stmt->execute();
$disciplinas = $stmt->fetchAll(PDO::FETCH_ASSOC);

$casas = $pdo->query("SELECT id_casa, nome FROM tb_casas ORDER BY nome")->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
  <meta charset="UTF-8">
  <title>Atribuir Pontuação</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      background-image: url('../Imagens/Fundo.png');
      margin: 0;
      padding: 0;
      min-height: 100vh;
      display: flex;
      justify-content: center;
      align-items: center;
    }

    form {
      background-color: #fff;
      padding: 20px 30px;
      border-radius: 8px;
      box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
      width: 100%;
      max-width: 600px;
      display: flex;
      flex-direction: column;
      gap: 15px;
      /* Remova o transform para centralizar corretamente */
    }

    form label {
      font-size: 14px;
      font-weight: bold;
      color: #333;
    }

    form select,
    form input {
      padding: 10px;
      font-size: 14px;
      border: 1px solid #ccc;
      border-radius: 5px;
      width: 100%;
      box-sizing: border-box;
      text-align: center;
    }

    .button-group {
      display: flex;
      justify-content: space-between;
      gap: 10px;
      margin-top: 10px;
      width: 100%;
    }

    form button,
    .back-btn {
      width: 100%;
      margin: 0;
      grid-column: unset;
    }

    form button {
      padding: 10px;
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

    .back-btn {
      display: inline-block;
      padding: 10px 2px;
      background-color: #6c757d;
      color: #fff;
      text-decoration: none;
      border-radius: 5px;
      font-size: 16px;
      text-align: center;
      transition: background-color 0.3s ease;
      margin-top: 10px;
    }

    .back-btn:hover {
      background-color: #5a6268;
    }

    .hidden {
      display: none;
    }

    label[for="atribuir_por_aluno"] {
      align-items: center;
      gap: 0px;
      font-size: 14px;
      font-weight: bold;
      color: #333;
      text-align: center;
    }

    #btn-historico {
      background:rgb(252, 0, 0);
      color: #fff;
      border: none;
      padding: 12px 24px;
      border-radius: 5px;
      font-size: 16px;
      cursor: pointer;
      z-index: 1000;
      transition: background 0.2s;
    }

    #btn-historico:hover {
      background:rgb(146, 0, 0);
    }


  </style>
  <script>
    function toggleCamposAluno() {
      const checkbox = document.getElementById('atribuir_por_aluno');
      const camposAluno = document.querySelectorAll('.campos-aluno');
      camposAluno.forEach(campo => {
        campo.style.display = checkbox.checked ? 'block' : 'none';
      });
    }

    function carregarTurmas() {
      const casaId = document.getElementById('casas').value;
      if (casaId) {
        const xhr = new XMLHttpRequest();
        xhr.open('GET', '../backend/obter_turmas.php?casa_id=' + casaId, true);
        xhr.onload = function() {
          if (xhr.status === 200) {
            const turmas = JSON.parse(xhr.responseText);
            const turmaSelect = document.getElementById('turma');
            turmaSelect.innerHTML = '<option value="">Selecione uma Turma</option>';
            turmas.forEach(turma => {
              const option = document.createElement('option');
              option.value = turma.id_turma;
              option.textContent = turma.nome;
              turmaSelect.appendChild(option);
            });
          }
        };
        xhr.send();
      } else {
        document.getElementById('turma').innerHTML = '<option value="">Selecione uma Turma</option>';
      }
    }

    function carregarAlunos() {
      const turmaId = document.getElementById('turma').value;
      const casaId = document.getElementById('casas').value;
      if (turmaId && casaId) {
        const xhr = new XMLHttpRequest();
        xhr.open('GET', '../backend/obter_alunos.php?turma_id=' + turmaId + '&casa_id=' + casaId, true);
        xhr.onload = function() {
          if (xhr.status === 200) {
            const alunos = JSON.parse(xhr.responseText);
            const alunosSelect = document.getElementById('aluno_id');
            alunosSelect.innerHTML = '<option value="">Selecione um Aluno</option>';
            alunos.forEach(aluno => {
              const option = document.createElement('option');
              option.value = aluno.id_aluno;
              option.textContent = aluno.nome;
              alunosSelect.appendChild(option);
            });
          }
        };
        xhr.send();
      } else {
        document.getElementById('aluno_id').innerHTML = '<option value="">Selecione um Aluno</option>';
      }
    }
    // Carrega motivos e limites
    window.onload = function() {
      const motivoSelect = document.getElementById('motivo_id');
      motivoSelect.innerHTML = '<option value="">Selecione o Motivo</option>';
      const xhr = new XMLHttpRequest();
      xhr.open('GET', '../backend/obter_motivos.php', true);
      xhr.onload = function() {
        if (xhr.status === 200) {
          const motivos = JSON.parse(xhr.responseText);
          window.limitesMotivos = {}; // objeto global para limites
          motivos.forEach(motivo => {
            const option = document.createElement('option');
            option.value = motivo.id_motivo;
            option.textContent = motivo.descricao;
            motivoSelect.appendChild(option);
            // Salva limites
            window.limitesMotivos[motivo.id_motivo] = {
              min: parseInt(motivo.pont_min, 10),
              max: parseInt(motivo.pont_max, 10)
            };
          });
        }
      };
      xhr.send();
    }

    // Validação e envio manual
    document.addEventListener('DOMContentLoaded', function() {
      document.getElementById('form-nota').addEventListener('submit', function(e) {
        e.preventDefault(); // Impede envio padrão

        const motivoId = document.getElementById('motivo_id').value;
        const pontuacaoInput = document.getElementById('pontuacao');
        const pontuacao = parseInt(pontuacaoInput.value, 10);

        if (!motivoId || isNaN(pontuacao)) {
          this.submit(); // Envia normalmente se campos obrigatórios não preenchidos
          return;
        }

        const limites = window.limitesMotivos ? window.limitesMotivos[motivoId] : null;
        if (limites) {
          if (pontuacao < limites.min || pontuacao > limites.max) {
            alert(`A pontuação para este motivo deve estar entre ${limites.min} e ${limites.max}.`);
            this.reset();
            toggleCamposAluno();
            return;
          }
        }

        // Se passou na validação, envia para o backend
        this.action = "../backend/nota/adicionar.php";
        this.submit();
      });
    });

    function abrirHistorico() {
      window.open('historico_pontuacao.php', '_blank', 'width=1200,height=700,scrollbars=yes');
    }
  </script>
</head>

<body>
  <form id="form-nota" method="POST">
    <input type="hidden" name="usuario_id" value="<?= htmlspecialchars($usuario_id ?? '') ?>">

    <label for="disciplina">Disciplina:</label>
    <select name="disciplina" id="disciplina">
      <option value="">Selecione uma Disciplina</option>
      <?php foreach ($disciplinas as $disciplina): ?>
        <option value="<?= $disciplina['id_disciplina'] ?>"><?= htmlspecialchars($disciplina['nome']) ?></option>
      <?php endforeach; ?>
    </select>

    <label for="casas">Casa:</label>
    <select name="id_casa" id="casas" onchange="carregarTurmas()" required>
      <option value="">Selecione uma Casa</option>
      <?php foreach ($casas as $casa): ?>
        <option value="<?= $casa['id_casa'] ?>"><?= htmlspecialchars($casa['nome']) ?></option>
      <?php endforeach; ?>
    </select>

    <label for="atribuir_por_aluno" style="grid-column: span 2;" class="aluno-box">
    Atribuir por aluno <input type="checkbox" id="atribuir_por_aluno" onclick="toggleCamposAluno()"> 
    </label>

    <div class="campos-aluno hidden">
      <label for="turma">Turma:</label>
      <select name="turma" id="turma" onchange="carregarAlunos()">
        <option value="">Selecione uma Turma</option>
      </select>
    </div>
    <div class="campos-aluno hidden">
      <label for="aluno_id">Aluno:</label>
      <select name="aluno_id" id="aluno_id">
        <option value="">Selecione um Aluno</option>
      </select>
    </div>

    <label for="motivo_id">Motivo:</label>
    <select name="motivo_id" id="motivo_id" required>
      <option value="">Selecione o Motivo</option>
      <!-- Motivos serão carregados via AJAX -->
    </select>

    <label for="pontuacao">Pontuação:</label>
    <input type="number" id="pontuacao" name="pontuacao" required>
    <div class="button-group">
      <button type="submit">Adicionar Pontuação</button>
      <button type="button" onclick="abrirHistorico()" id="btn-historico">Histórico</button>
    </div>

    <a href="menu.php" class="back-btn">Voltar ao Menu</a>
    
  </form>
</body>

</html>
