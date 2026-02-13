<!-- filepath: c:\wamp64\www\ProjetoMesquita\backend\salvar_usuario.php -->
<?php
require_once 'config/db.php';
session_start();
$nivel_usuario_logado = $_SESSION['usuario_nivel'] ?? '';
if (
    isset($_POST['nivel']) &&
    strtolower($_POST['nivel']) === 'administrador' &&
    strtolower($nivel_usuario_logado) !== 'administrador'
) {
    die('Você não tem permissão para cadastrar ou editar usuários Administrador.');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = filter_input(INPUT_POST, 'usuario_id', FILTER_VALIDATE_INT);
    $nome = trim($_POST['nome'] ?? '');
    $login = trim($_POST['login'] ?? '');
    $senha = trim($_POST['senha'] ?? '');
    $nivel = trim($_POST['nivel'] ?? '');

    // Validação simples
    if (!$nome || !$login || !$nivel) {
        echo "Todos os campos obrigatórios precisam ser preenchidos!";
        exit;
    }

    // Verifica se já existe login igual (exceto se for edição do próprio usuário)
    $stmt = $pdo->prepare("SELECT id_usuario FROM tb_usuarios WHERE login = ? " . ($id ? "AND id_usuario != ?" : ""));
    $params = $id ? [$login, $id] : [$login];
    $stmt->execute($params);
    if ($stmt->fetch()) {
        echo "Já existe um usuário com este login!";
        exit;
    }

    if ($id) {
        // Atualiza o usuário existente
        if ($senha) {
            $senhaHash = password_hash($senha, PASSWORD_DEFAULT);
            $sql = "UPDATE tb_usuarios SET nome = :nome, login = :login, senha = :senha, nivel = :nivel WHERE id_usuario = :id_usuario";
        } else {
            $sql = "UPDATE tb_usuarios SET nome = :nome, login = :login, nivel = :nivel WHERE id_usuario = :id_usuario";
        }
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':id_usuario', $id, PDO::PARAM_INT);
    } else {
        // Insere um novo usuário
        if ($senha) {
            $senhaHash = password_hash($senha, PASSWORD_DEFAULT);
        } else {
            // Defina uma senha padrão ou NULL, conforme sua modelagem
            $senhaHash = password_hash('123456', PASSWORD_DEFAULT); // Exemplo: senha padrão
        }
        $sql = "INSERT INTO tb_usuarios (nome, login, senha, nivel, data_cadastro, ativo) VALUES (:nome, :login, :senha, :nivel, CURDATE(), 1)";
        $stmt = $pdo->prepare($sql);
    }

    $stmt->bindParam(':nome', $nome, PDO::PARAM_STR);
    $stmt->bindParam(':login', $login, PDO::PARAM_STR);
    $stmt->bindParam(':nivel', $nivel, PDO::PARAM_STR);
    if (!$id || $senha) {
        $stmt->bindParam(':senha', $senhaHash, PDO::PARAM_STR);
    }

    if ($stmt->execute()) {
        // Se for novo usuário, pega o id gerado
        if (!$id) {
            $id = $pdo->lastInsertId();
        }

        // Verifica o nível do usuário
        $nivel = strtolower($_POST['nivel'] ?? '');

        // Se for Coordenação ou Administrador, associa todas as disciplinas
        if ($nivel === 'professor coordenador' || $nivel === 'coordenação' || $nivel === 'administrador') {
            // Associa todas as disciplinas
            $disciplinas = $pdo->query("SELECT id_disciplina FROM tb_disciplinas")->fetchAll(PDO::FETCH_COLUMN);
            foreach ($disciplinas as $id_disciplina) {
                $stmt = $pdo->prepare("INSERT INTO tb_disciplinaprofessor (id_usuario, id_disciplina, ativo, data_cadastro) VALUES (?, ?, 1, CURDATE())");
                $stmt->execute([$id, $id_disciplina]);
            }
        } else {
            // Marca vínculos antigos como inativos e atualiza data_atualizacao
            $pdo->prepare("UPDATE tb_disciplinaprofessor SET ativo = 0, data_atualizacao = CURDATE() WHERE id_usuario = ? AND ativo = 1")->execute([$id]);
            // Associa apenas as disciplinas selecionadas no formulário
            if (!empty($_POST['disciplinas']) && is_array($_POST['disciplinas'])) {
                foreach ($_POST['disciplinas'] as $id_disciplina) {
                    $stmt = $pdo->prepare("INSERT INTO tb_disciplinaprofessor (id_usuario, id_disciplina, ativo, data_cadastro) VALUES (?, ?, 1, CURDATE())");
                    $stmt->execute([$id, $id_disciplina]);
                }
            }
        }
        header("Location: ../frontend/cadastro_usuarios.php");
        exit;
    } else {
        echo "Erro ao salvar o usuário.";
    }
}
?>