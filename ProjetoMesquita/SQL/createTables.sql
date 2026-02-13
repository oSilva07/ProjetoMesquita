--
-- Estrutura para tabela `tb_alunos`
--

DROP TABLE IF EXISTS `tb_alunos`;
CREATE TABLE IF NOT EXISTS `tb_alunos` (
  `id_aluno` int NOT NULL AUTO_INCREMENT,
  `id_casa` int NOT NULL,
  `id_turma` int NOT NULL,
  `nome` varchar(255) NOT NULL,
  `ativo` tinyint(1) NOT NULL,
  PRIMARY KEY (`id_aluno`),
  KEY `FK_casaAluno` (`id_casa`),
  KEY `FK_turmaAluno` (`id_turma`)
) ENGINE=MyISAM AUTO_INCREMENT=318 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Estrutura para tabela `tb_casas`
--

DROP TABLE IF EXISTS `tb_casas`;
CREATE TABLE IF NOT EXISTS `tb_casas` (
  `id_casa` int NOT NULL AUTO_INCREMENT,
  `nome` varchar(255) NOT NULL,
  PRIMARY KEY (`id_casa`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Estrutura para tabela `tb_categorias`
--

DROP TABLE IF EXISTS `tb_categorias`;
CREATE TABLE IF NOT EXISTS `tb_categorias` (
  `id_categoria` int NOT NULL AUTO_INCREMENT,
  `nome` varchar(255) NOT NULL,
  `tipo` enum('C','A') NOT NULL,
  PRIMARY KEY (`id_categoria`)
) ENGINE=MyISAM AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Estrutura para tabela `tb_disciplinaprofessor`
--

DROP TABLE IF EXISTS `tb_disciplinaprofessor`;
CREATE TABLE IF NOT EXISTS `tb_disciplinaprofessor` (
  `id_discProf` int NOT NULL AUTO_INCREMENT,
  `id_usuario` int NOT NULL,
  `id_disciplina` int DEFAULT NULL,
  `ativo` tinyint(1) NOT NULL,
  `data_cadastro` date DEFAULT NULL,
  `data_atualizacao` date DEFAULT NULL,
  PRIMARY KEY (`id_discProf`),
  KEY `FK_professor` (`id_usuario`),
  KEY `FK_disciplina` (`id_disciplina`)
) ENGINE=MyISAM AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Estrutura para tabela `tb_disciplinas`
--

DROP TABLE IF EXISTS `tb_disciplinas`;
CREATE TABLE IF NOT EXISTS `tb_disciplinas` (
  `id_disciplina` int NOT NULL AUTO_INCREMENT,
  `nome` varchar(255) NOT NULL,
  PRIMARY KEY (`id_disciplina`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Estrutura para tabela `tb_motivos`
--

DROP TABLE IF EXISTS `tb_motivos`;
CREATE TABLE IF NOT EXISTS `tb_motivos` (
  `id_motivo` int NOT NULL AUTO_INCREMENT,
  `id_categoria` int DEFAULT NULL,
  `descricao` varchar(255) NOT NULL,
  `pont_max` decimal(10,0) NOT NULL,
  `pont_min` decimal(10,0) NOT NULL,
  PRIMARY KEY (`id_motivo`),
  KEY `FK_categoriaMotivo` (`id_categoria`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Estrutura para tabela `tb_pontuacao`
--

DROP TABLE IF EXISTS `tb_pontuacao`;
CREATE TABLE IF NOT EXISTS `tb_pontuacao` (
  `id_pontuacao` int NOT NULL AUTO_INCREMENT,
  `id_discProf` int NOT NULL,
  `id_aluno` int NOT NULL,
  `id_casa` int NOT NULL,
  `id_motivo` int NOT NULL,
  `pontuacao` decimal(10,0) NOT NULL,
  `tipo` tinyint(1) NOT NULL,
  `data_atribuicao` date NOT NULL,
  PRIMARY KEY (`id_pontuacao`),
  KEY `FK_discProf` (`id_discProf`),
  KEY `FK_aluno` (`id_aluno`),
  KEY `FK_motivo` (`id_motivo`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Estrutura para tabela `tb_turmas`
--

DROP TABLE IF EXISTS `tb_turmas`;
CREATE TABLE IF NOT EXISTS `tb_turmas` (
  `id_turma` int NOT NULL AUTO_INCREMENT,
  `nome` varchar(30) NOT NULL,
  PRIMARY KEY (`id_turma`)
) ENGINE=MyISAM AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Estrutura para tabela `tb_usuarios`
--

DROP TABLE IF EXISTS `tb_usuarios`;
CREATE TABLE IF NOT EXISTS `tb_usuarios` (
  `id_usuario` int NOT NULL AUTO_INCREMENT,
  `nome` varchar(255) NOT NULL,
  `login` varchar(255) NOT NULL,
  `senha` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `nivel` enum('Professor','Professor coordenador','Coordenação','Administrador') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `data_cadastro` date NOT NULL,
  `ativo` tinyint(1) NOT NULL,
  PRIMARY KEY (`id_usuario`)
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;