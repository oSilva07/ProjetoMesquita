<?php
require_once '../backend/config/db.php';
require_once '../backend/session_manager.php';

// Verifica se o usuário logado é administrador
if ($usuario_classe != 'ADM' && $usuario_classe != 'Coordenação') {
    header("Location: access_denied.php");
    exit;
}

// Busca os usuários cadastrados no banco de dados
$sqlUsuarios = "SELECT ID, Nome, login, Classe, Ativo FROM usuarios";
$usuarios = $pdo->query($sqlUsuarios)->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Usuários</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-image:  url('../Imagens/Fundo.png');
            display: flex;
            justify-content: center;
            align-items: flex-start;
            height: 100vh;
            margin: 0;
            padding: 20px;
        }

        @media screen and (max-width: 768px){
            .list-container{
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

        .form-container, .list-container {
            background: #fff;
            padding: 20px 30px;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            width: 100%;
        }

        .form-container {
            max-width: 400px;
        }

        .form-container h1, .list-container h1 {
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

        .form-group select{
            width: 105%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
        }

        #nome{
            text-align: center;
        }

        #login{
            text-align: center;
        }

        #texto{
            text-align: center;
        }

        .form-group input:focus, .form-group select:focus {
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
        .list-container table th, .list-container table td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
        }
        .list-container table th {
            background-color: #f4f4f9;
            color: #333;
        }
        .list-container table tr.inactive {
            background-color:rgb(239, 233, 233); /* Cor ligeiramente mais escura para usuários inativos */
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
            margin: 0 auto; /* Isso centraliza o botão */
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

        input:checked + .slider {
            background-color: #007bff;
        }

        input:checked + .slider:before {
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
    </style>
    <script>
        function carregarDadosUsuario(id, nome, login, classe) {
            // Preenche os campos do formulário com os dados do usuário
            document.getElementById('usuario_id').value = id;
            document.getElementById('nome').value = nome;
            document.getElementById('login').value = login;
            document.getElementById('classe').value = classe;

            // Altera o texto do botão para "Atualizar"
            document.getElementById('submit-btn').textContent = 'Atualizar';
        }

        function toggleAtivo(userId, isActive) {
            const xhr = new XMLHttpRequest();
            xhr.open("POST", "../backend/toggle_usuario.php", true);
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
            xhr.onload = function () {
                if (xhr.status === 200) {
                    alert("Status do usuário atualizado com sucesso!");
                    location.reload(); // Recarrega a página para atualizar a lista
                } else {
                    alert("Erro ao atualizar o status do usuário.");
                }
            };
            xhr.send(`id=${userId}&ativo=${isActive}`);
        }

        function filtrarUsuarios() {
            const searchInput = document.getElementById('search-bar').value.toLowerCase();
            const table = document.getElementById('usuarios-table');
            const rows = table.getElementsByTagName('tr');

            for (let i = 1; i < rows.length; i++) { // Começa em 1 para ignorar o cabeçalho
                const nomeCell = rows[i].getElementsByTagName('td')[1]; // Coluna "Nome"
                if (nomeCell) {
                    const nome = nomeCell.textContent || nomeCell.innerText;
                    rows[i].style.display = nome.toLowerCase().includes(searchInput) ? '' : 'none';
                }
            }
        }
    </script>
</head>
<body>
    <div class="container">
        <!-- Formulário de Cadastro/Edição -->
        <div class="form-container">
            <h1>Cadastro de Usuários</h1>
            <form action="../backend/salvar_usuario.php" method="POST">
                <input type="hidden" id="usuario_id" name="usuario_id" value="">
                <div class="form-group">
                    <label for="nome">Nome</label>
                    <input type="text" id="nome" name="nome" placeholder="Digite o nome completo" required>
                </div>
                <div class="form-group">
                    <label for="login">Login</label>
                    <input type="text" id="login" name="login" placeholder="Digite o login" required>
                </div>
                <div class="form-group">
                    <label for="classe">Classe</label>
                    <select id="classe" name="classe" required>
                        <option value="" id="texto">Selecione a Classe</option>
                        <option value="ADM" id="texto">ADM</option>
                        <option value="Coordenação" id="texto">Coordenação</option>
                        <option value="Professor Coordenador" id="texto">Professor Coordenador</option>
                        <option value="Professor" id="texto">Professor</option>
                    </select>
                </div>
                <button type="submit" id="submit-btn" class="submit-btn">Cadastrar</button>
            </form>
            <a href="menu.php" class="back-btn">Voltar ao Menu</a>
        </div>

        <!-- Lista de Usuários -->
        <div class="list-container">
            <h1>Usuários Cadastrados</h1>
            
            <!-- Barra de Pesquisa -->
            <div class="search-container">
                <input type="text" id="search-bar" placeholder="Pesquisar por nome..." onkeyup="filtrarUsuarios()">
            </div>
            
            <table id="usuarios-table">
                <thead>
                    <tr>
                        <th id="texto">Ações</th>
                        <th id="texto">Nome</th>
                        <th id="texto">Login</th>
                        <th id="texto">Classe</th>
                        <th id="texto">Ativo</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($usuarios as $usuario): ?>
                        <tr class="<?= $usuario['Ativo'] ? '' : 'inactive' ?>">
                            <td>
                                <button class="edit-btn" onclick="carregarDadosUsuario(
                                    <?= $usuario['ID'] ?>,
                                    '<?= htmlspecialchars($usuario['Nome'], ENT_QUOTES) ?>',
                                    '<?= htmlspecialchars($usuario['login'], ENT_QUOTES) ?>',
                                    '<?= htmlspecialchars($usuario['Classe'], ENT_QUOTES) ?>'
                                )">Editar</button>
                            </td>
                            <td id="texto"><?= htmlspecialchars($usuario['Nome']) ?></td>
                            <td id="texto"><?= htmlspecialchars($usuario['login']) ?></td>
                            <td id="texto"><?= htmlspecialchars($usuario['Classe']) ?></td>
                            <td>
                                <label class="toggle-switch">
                                    <input type="checkbox" <?= $usuario['Ativo'] ? 'checked' : '' ?> 
                                           onchange="toggleAtivo(<?= $usuario['ID'] ?>, this.checked ? 1 : 0)">
                                    <span class="slider"></span>
                                </label>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>