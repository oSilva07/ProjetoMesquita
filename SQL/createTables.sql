
-- CRIANDO TABELA ALUNOS
DROP TABLE IF EXISTS `alunos`;
CREATE TABLE IF NOT EXISTS `alunos` (
  `ID` int NOT NULL AUTO_INCREMENT,
  `Nome` varchar(255) NOT NULL,
  `Matricula` int NOT NULL,
  `Casa_ID` int DEFAULT NULL,
  `Turma_ID` int DEFAULT NULL,
  `ativo` tinyint(1) NOT NULL,
  PRIMARY KEY (`ID`),
  KEY `idx_Casa_ID` (`Casa_ID`),
  KEY `idx_Turma_ID` (`Turma_ID`)
) ENGINE=MyISAM AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;


-- CRIANDO TABELA AUDITORIA_NOTA
DROP TABLE IF EXISTS `auditoria_nota`;
CREATE TABLE IF NOT EXISTS `auditoria_nota` (
  `ID` int NOT NULL AUTO_INCREMENT,
  `Nota_ID` int DEFAULT NULL,
  `Acao` enum('INSERT','UPDATE','DELETE') DEFAULT NULL,
  `Usuario_ID` int DEFAULT NULL,
  `Data` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`ID`),
  KEY `idx_Nota_ID` (`Nota_ID`),
  KEY `idx_Usuario_ID` (`Usuario_ID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;


-- CRIANDO TABELA MATERIA
DROP TABLE IF EXISTS `materia`;
CREATE TABLE IF NOT EXISTS `materia` (
  `ID` int NOT NULL AUTO_INCREMENT,
  `Nome` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;


-- CRIANDO TABELA MOTIVOS
DROP TABLE IF EXISTS `motivos`;
CREATE TABLE IF NOT EXISTS `motivos` (
  `ID` int NOT NULL AUTO_INCREMENT,
  `Descricao` varchar(255) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;


-- CRIANDO TABELA NOTA
DROP TABLE IF EXISTS `nota`;
CREATE TABLE IF NOT EXISTS `nota` (
  `ID` int NOT NULL AUTO_INCREMENT,
  `Professor_ID` int DEFAULT NULL,
  `Aluno_ID` int DEFAULT NULL,
  `Data_Nota` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `Desconto_Nota` decimal(5,2) DEFAULT NULL,
  `Pontos` int NOT NULL,
  `Materia_ID` int DEFAULT NULL,
  `Motivos_ID` int DEFAULT NULL,
  PRIMARY KEY (`ID`),
  KEY `idx_Professor_ID` (`Professor_ID`),
  KEY `idx_Aluno_ID` (`Aluno_ID`),
  KEY `idx_Materia_ID` (`Materia_ID`),
  KEY `idx_Motivos_ID` (`Motivos_ID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;


-- CRIANDO TABELA PONTUACAO
DROP TABLE IF EXISTS `pontuacao`;
CREATE TABLE IF NOT EXISTS `pontuacao` (
  `ID` int NOT NULL AUTO_INCREMENT,
  `Casa_ID` int DEFAULT NULL,
  `Aluno_ID` int DEFAULT NULL,
  `Turma` varchar(255) DEFAULT NULL,
  `Data_Nota` date DEFAULT NULL,
  `Professor_ID` int DEFAULT NULL,
  `Desconto_Nota` decimal(5,2) DEFAULT NULL,
  `Pontos` int DEFAULT NULL,
  `Materia_ID` int DEFAULT NULL,
  `Motivos_ID` int DEFAULT NULL,
  PRIMARY KEY (`ID`),
  KEY `idx_Casa_ID_Pontuacao` (`Casa_ID`),
  KEY `idx_Aluno_ID_Pontuacao` (`Aluno_ID`),
  KEY `idx_Professor_ID_Pontuacao` (`Professor_ID`),
  KEY `idx_Materia_ID_Pontuacao` (`Materia_ID`),
  KEY `idx_Motivos_ID_Pontuacao` (`Motivos_ID`),
  KEY `Turma` (`Turma`(250))
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;


-- CRIANDO TABELA PROFESSOR
DROP TABLE IF EXISTS `professor`;
CREATE TABLE IF NOT EXISTS `professor` (
  `ID` int NOT NULL AUTO_INCREMENT,
  `Nome` varchar(255) NOT NULL,
  `Cargo` varchar(255) NOT NULL,
  `Materia_ID` int DEFAULT NULL,
  PRIMARY KEY (`ID`),
  KEY `Materia_ID` (`Materia_ID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;


-- CRIANDO TABELA TURMAS
DROP TABLE IF EXISTS `turmas`;
CREATE TABLE IF NOT EXISTS `turmas` (
  `ID` int NOT NULL AUTO_INCREMENT,
  `Nome` varchar(255) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;


-- CRIANDO TABELA USUARIOS
DROP TABLE IF EXISTS `usuarios`;
CREATE TABLE IF NOT EXISTS `usuarios` (
  `ID` int NOT NULL AUTO_INCREMENT,
  `Nome` varchar(255) NOT NULL,
  `Senha` varchar(255) NOT NULL,
  `Classe` enum('ADM','Coordenacao','Professor Coordenador','Professor') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `Data_Criacao` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `login` varchar(20) NOT NULL,
  `ativo` tinyint(1) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

COMMIT;