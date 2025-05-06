<?php
$path = realpath('../backend/session_manager.php');
if (!$path) {
    die('Arquivo session_manager.php não encontrado no caminho especificado.');
}
require_once $path;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menu Principal</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-image:  url('../Imagens/Fundo.png');
            margin: 0;
            padding: 0;
            height: 100vh;
            display: flex;
            flex-direction: column; /* Permite empilhar o menu suspenso e o conteúdo */
        }

        .dropdown {
            position: absolute;
            top: 10px;
            right: 10px;
        }

        .dropdown button {
            background-color: #9d4edd;
            color: #fff;
            border: none;
            border-radius: 5px;
            padding: 10px 15px;
            font-size: 16px;
            cursor: pointer;
        }

        .dropdown button:hover {
            background-color:rgb(197, 153, 233);
        }

        .dropdown-content {
            display: none;
            position: absolute;
            top: 40px;
            right: 0;
            background-color: #fff;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            border-radius: 5px;
            overflow: hidden;
            z-index: 1000;
            width: 150px; /* Define uma largura fixa para o menu */
        }

        .dropdown-content a {
            display: block;
            padding: 10px 15px;
            text-decoration: none;
            color: #333;
            background-color: #fff;
            border-bottom: 1px solid #ddd;
        }

        .dropdown-content a:hover {
            background-color: #f4f4f9;
        }

        .dropdown-content a:last-child {
            border-bottom: none;
        }

        .main-content {
            flex: 1; /* Faz o conteúdo principal ocupar o espaço restante */
            display: flex;
            justify-content: center;
            align-items: center; /* Centraliza o conteúdo principal vertical e horizontalmente */
            width: 100%;
        }

        .menu-container {
            background: #fff;
            padding: 20px 30px;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
            text-align: center;
            position: relative;
        }

        .menu-container h1 {
            margin-bottom: 20px;
            color: #333;
        }

        .menu-grid {
            display: flex;
            flex-direction: column; /* Alinha os itens verticalmente */
            gap: 15px; /* Espaçamento entre os itens */
            align-items: center; /* Centraliza os itens horizontalmente */
        }

        .menu-grid a {
            display: block;
            padding: 15px 20px;
            background-color: #9d4edd;
            color: #fff;
            text-decoration: none;
            border-radius: 5px;
            font-size: 16px;
            text-align: center;
            width: 100%; /* Faz os botões ocuparem toda a largura disponível */
            max-width: 300px; /* Define uma largura máxima */
        }

        .menu-grid a:hover {
            background-color: rgb(197, 153, 233);
        }
    </style>
    <script>
        // Script para abrir/fechar o menu suspenso
        function toggleDropdown() {
            const dropdownContent = document.getElementById('dropdown-content');
            dropdownContent.style.display = dropdownContent.style.display === 'block' ? 'none' : 'block';
        }

        // Fecha o menu suspenso ao clicar fora dele
        window.onclick = function(event) {
            if (!event.target.matches('.dropdown button')) {
                const dropdownContent = document.getElementById('dropdown-content');
                if (dropdownContent) {
                    dropdownContent.style.display = 'none';
                }
            }
        };
    </script>
</head>

<body>
    <!-- Menu suspenso no topo -->
    <div class="dropdown">
        <button onclick="toggleDropdown()">Menu</button>
        <div id="dropdown-content" class="dropdown-content">
            <a href="ver_perfil.php">Ver Perfil</a>
            <a href="atualizar_perfil.php">Atualizar Perfil</a>
            <a href="logout.php">Sair</a>
        </div>
    </div>

    <!-- Conteúdo principal -->
    <div class="main-content">
        <div class="menu-container">
            <h1>Bem-vindo(a), <br><?= htmlspecialchars($usuario_nome) ?>!</h1>
            <p>Seu nível de acesso é:&nbsp;<?= htmlspecialchars($usuario_classe) ?></p>
            <div class="menu-grid">
                <a href="cadastro_usuarios.php">Cadastro de Usuários</a>
                <a href="cadastro_alunos.php">Cadastro de Alunos</a>
                <a href="cadastro_turmas.php">Cadastro de Turmas</a>
                <a href="cadastro_casas.php">Cadastro de Casas</a>
                <a href="atribuir_notas.php">Atribuição de Notas</a>
            </div>
        </div>
    </div>
</body>

</html>