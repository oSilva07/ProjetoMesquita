
-- Despejando dados para a tabela `alunos`
INSERT INTO `alunos` (`ID`, `Nome`, `Matricula`, `Casa_ID`, `Turma_ID`, `ativo`) VALUES
(1, 'Pedrinho', 7522031, 1, 1, 1),
(2, 'Victoria Kavillyn', 7524003, 1, 5, 1),
(3, 'Sara Sther ', 7524004, 1, 5, 1),
(4, 'Sabrina Oliveira', 7523002, 1, 5, 1),
(5, 'Yasmim Moraes', 7524002, 1, 5, 1),
(6, 'Maria Luiza', 7524001, 3, 2, 1),
(7, 'Roberta Rey', 7524006, 2, 2, 1),
(8, 'Alma rey', 6635467, 1, 6, 1),
(9, 'Mia Colucci', 4567345, 4, 6, 1),
(10, 'Roberta Pardo', 34563434, 3, 2, 1),
(11, 'Diego Monevan', 2616346, 4, 4, 0),
(12, 'Rebeca Vitoria', 2314995, 2, 1, 1),
(13, 'Murilo Chagas', 1188548, 3, 5, 1),
(14, 'Leandro Simoes', 1234567, 1, 5, 1),
(15, 'Kaua Amorin', 2468101, 4, 9, 0),
(16, 'Monica Alonso', 1223547, 3, 14, 1);


-- Despejando dados para a tabela `casas`
INSERT INTO `casas` (`ID`, `Nome`) VALUES
(1, 'Grifinória'),
(2, 'Lufa-lufa'),
(3, 'Sonserina'),
(4, 'Corvinal');


-- Despejando dados para a tabela `materia`
INSERT INTO `materia` (`ID`, `Nome`) VALUES
(1, 'Português'),
(2, 'Matemática'),
(3, 'História'),
(4, 'Geografia'),
(5, 'Filosofia');


-- Despejando dados para a tabela `turmas'
INSERT INTO `turmas` (`ID`, `Nome`) VALUES
(1, 'Turma 1A'),
(2, 'Turma 1B'),
(3, 'Turma 2A'),
(4, 'Turma 2B'),
(5, 'Turma 3A'),
(6, 'Turma 3B'),
(7, 'Turma 1C'),
(8, 'Turma 1D'),
(9, 'Turma 2C'),
(10, 'Turma 2D'),
(11, 'Turma 3C'),
(12, 'Turma 3D'),
(13, 'Turma VK'),
(14, 'Turma ss'),
(15, 'turma rg'),
(16, 'turma ls');


-- Despejando dados para a tabela `usuarios`
INSERT INTO `usuarios` (`ID`, `Nome`, `Senha`, `Classe`, `Data_Criacao`, `login`, `ativo`) VALUES
(6, 'Leonardo Silva', '$2y$10$prtWHlM5alCjsPcp3qCE/OeDZlAFuBbuGagWvLfxdgoGozXuk2wma', 'Coordenacao', '2025-04-29 11:49:20', 'leosilva', 1),
(9, 'Administrador', '$2y$10$Ogzyo50cDaSrBurOz3VgOuWShEyLchLTH0QjheI5pvOA/ilRq7V3q', 'ADM', '2025-04-29 13:52:21', 'adm', 1),
(7, 'Rafael Gomes', '$2y$10$HgqFxoGvZt/BSdQ/SKnvMO0sTmUxzZfhsDeiUXklDjwPNWitVpvL.', 'Professor Coordenador', '2025-04-29 11:52:32', 'rafagomes', 1),
(8, 'Henrique Alves', '$2y$10$07KaGpeFkRNhwfQiYhG1fuQk9b9sGLWLK1SQZ0LbnCWkZT3e5oOwW', 'Professor', '2025-04-29 11:52:47', 'henrialves', 0),
(10, 'Victoria Kavillyn', '$2y$10$TgX8DB2zRN7raFUiWg.PPuzjfwBl/EHPxjL73cBcuru/3XRaO6zXO', 'Professor', '2025-04-29 23:47:09', 'vic', 0),
(11, 'Sara Sther', '$2y$10$Ga0jv78sD1KM9jUqoxLzzudJmm5suR0D5TtRUxux74DdiDfOnLG8G', 'Professor Coordenador', '2025-04-30 00:42:45', 'sah', 1);

COMMIT;