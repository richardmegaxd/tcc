-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 21/11/2024 às 19:04
-- Versão do servidor: 10.4.32-MariaDB
-- Versão do PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `bd_glark`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `tb_autor`
--

CREATE TABLE `tb_autor` (
  `cd_autor` int(11) NOT NULL,
  `nm_autor` varchar(50) DEFAULT NULL,
  `ds_email` varchar(50) DEFAULT NULL,
  `ds_telefone` int(11) DEFAULT NULL,
  `checkbox_status` tinyint(1) DEFAULT 0,
  `cd_usuarioAutor` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `tb_autor`
--

INSERT INTO `tb_autor` (`cd_autor`, `nm_autor`, `ds_email`, `ds_telefone`, `checkbox_status`, `cd_usuarioAutor`) VALUES
(24, 'ANDRE SILVA', 'teste@gmail.com', 2147483647, 1, 51),
(25, 'ANDRE SILVA', 'andrelpos24@gmail.com', 2147483647, 1, NULL);

-- --------------------------------------------------------

--
-- Estrutura para tabela `tb_manga`
--

CREATE TABLE `tb_manga` (
  `cd_manga` int(11) NOT NULL,
  `nm_titulo` varchar(255) NOT NULL,
  `ds_sinopse` varchar(255) DEFAULT NULL,
  `ds_arquivo_zip` varchar(255) DEFAULT NULL,
  `dt_publicacao` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `tb_manga`
--

INSERT INTO `tb_manga` (`cd_manga`, `nm_titulo`, `ds_sinopse`, `ds_arquivo_zip`, `dt_publicacao`) VALUES
(1, 'O Menino Nemo', 'teste', '', '2024-11-21 12:06:10'),
(4, 'O Menino Nemo', 'teste', '', '2024-11-21 12:12:09'),
(5, 'O Menino Nemo', 'teste', '', '2024-11-21 12:13:25'),
(6, 'O Menino Nemo', 'teste', '', '2024-11-21 12:17:53'),
(7, 'O Menino Nemo', 'teste', '', '2024-11-21 12:18:13'),
(8, 'O Menino Nemo', 'teste', NULL, '2024-11-21 12:36:59'),
(9, 'O Menino Nemo', 'teste', NULL, '2024-11-21 12:40:46'),
(10, 'O Menino Nemo', 'teste', '../obras/mangas/o-menino-nemo-na-terra-dos-sonhos-1_page-0001.jpg', '2024-11-21 13:57:09'),
(11, 'O Menino Nemo', 'teste', '../obras/mangas/o-menino-nemo-na-terra-dos-sonhos-1_page-0001.jpg', '2024-11-21 13:57:26'),
(12, 'O Menino Nemo', 'teste', '../obras/mangas/o-menino-nemo-na-terra-dos-sonhos-1_page-0001.jpg', '2024-11-21 13:59:44'),
(13, 'O Menino Nemo', 'O Menino nemo', '../obras/mangas/o-menino-nemo-na-terra-dos-sonhos-1_page-0001.jpg', '2024-11-21 14:55:22');

-- --------------------------------------------------------

--
-- Estrutura para tabela `tb_obra`
--

CREATE TABLE `tb_obra` (
  `cd_obra` int(11) NOT NULL,
  `nm_obra` varchar(50) NOT NULL,
  `ds_sinopse` varchar(200) NOT NULL,
  `ds_status` enum('Em Andamento','Finalizado') NOT NULL DEFAULT 'Em Andamento',
  `ds_genero` text DEFAULT NULL,
  `ds_imagem` varchar(255) DEFAULT NULL,
  `checkbox2_status` tinyint(1) DEFAULT 0,
  `checkbox3_status` tinyint(1) DEFAULT 0,
  `cd_autorObra` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `tb_obra`
--

INSERT INTO `tb_obra` (`cd_obra`, `nm_obra`, `ds_sinopse`, `ds_status`, `ds_genero`, `ds_imagem`, `checkbox2_status`, `checkbox3_status`, `cd_autorObra`) VALUES
(1, '', '', 'Finalizado', 'acao, misterio, sobrenat', '', 0, 0, 3),
(2, '', '', 'Em Andamento', 'aventura, comedia, isekai', '', 0, 0, 4),
(3, '', '', 'Em Andamento', 'aventura, comedia, isekai', '', 0, 0, 5),
(4, '', '', 'Em Andamento', 'aventura, comedia, isekai', '', 0, 0, 6),
(6, 'teste', 'teste teste', 'Em Andamento', 'aventura, comedia, isekai', '../uploads_Obras/imagem_673b53cac1a7a1.24625840.jpg', 0, 0, 14),
(7, 'teste', 'teste teste', 'Em Andamento', 'aventura, comedia, isekai', '../uploads_Obras/imagem_673b556296a752.47243129.jpg', 0, 0, 15),
(8, 'teste', 'teste teste', 'Em Andamento', 'aventura, comedia, isekai', '../uploads_Obras/imagem_673b55e4aa9ac8.67877006.jpg', 0, 0, 16),
(9, 'teste', 'teste teste', 'Em Andamento', 'aventura, comedia, isekai', '../uploads_Obras/imagem_673b570a68d356.39710615.jpg', 0, 0, 17),
(10, 'teste', 'teste teste', 'Em Andamento', 'aventura, comedia, isekai', '../uploads_Obras/imagem_673b57d4e7a533.69971482.jpg', 1, 1, 18),
(11, 'teste', 'teste teste', 'Em Andamento', 'aventura, comedia, isekai', '../uploads_Obras/imagem_673b58d8a42929.81774319.jpg', 1, 1, 19),
(12, 'teste', 'teste teste', 'Em Andamento', 'aventura, comedia, isekai', '../uploads_Obras/imagem_673b596500c3d7.28884363.jpg', 1, 1, 20),
(13, 'teste', 'teste teste', 'Em Andamento', 'aventura, comedia, isekai', '../uploads_Obras/imagem_673b59877d5c49.35953750.jpg', 1, 1, 21),
(14, 'teste', 'teste teste teste', 'Finalizado', 'acao, sobrenat', '../uploads_Obras/imagem_673b5abb41c5f3.61977634.jpg', 1, 1, 22),
(15, 'teste', 'teste teste teste', 'Finalizado', 'acao, fantasia, ficcient', '../uploads_Obras/imagem_673f3581b31758.54102850.jpg', 1, 1, 23),
(16, 'teste', 'teste teste', 'Em Andamento', 'aventura, fantasia, misterio, terror', '../uploads_Obras/imagem_673f370e29a0f8.85123123.jpg', 1, 1, 24),
(17, 'teste', 'teste teste', 'Finalizado', 'acao, comedia, fantasia', '../uploads_Obras/imagem_673f3aba8cd5a6.18736807.jpg', 1, 1, 25);

-- --------------------------------------------------------

--
-- Estrutura para tabela `tb_paginas_manga`
--

CREATE TABLE `tb_paginas_manga` (
  `cd_pagina` int(11) NOT NULL,
  `cd_manga` int(11) NOT NULL,
  `id_numero_pagina` int(11) NOT NULL,
  `ds_caminho_arquivo` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `tb_paginas_manga`
--

INSERT INTO `tb_paginas_manga` (`cd_pagina`, `cd_manga`, `id_numero_pagina`, `ds_caminho_arquivo`) VALUES
(281, 13, 0, '../obras/mangas/o-menino-nemo-na-terra-dos-sonhos-1_page-0003.jpg'),
(282, 13, 0, '../obras/mangas/o-menino-nemo-na-terra-dos-sonhos-1_page-0004.jpg'),
(283, 13, 0, '../obras/mangas/o-menino-nemo-na-terra-dos-sonhos-1_page-0005.jpg'),
(284, 13, 0, '../obras/mangas/o-menino-nemo-na-terra-dos-sonhos-1_page-0006.jpg'),
(285, 13, 0, '../obras/mangas/o-menino-nemo-na-terra-dos-sonhos-1_page-0007.jpg'),
(286, 13, 0, '../obras/mangas/o-menino-nemo-na-terra-dos-sonhos-1_page-0008.jpg'),
(287, 13, 0, '../obras/mangas/o-menino-nemo-na-terra-dos-sonhos-1_page-0009.jpg'),
(288, 13, 0, '../obras/mangas/o-menino-nemo-na-terra-dos-sonhos-1_page-0010.jpg'),
(289, 13, 0, '../obras/mangas/o-menino-nemo-na-terra-dos-sonhos-1_page-0011.jpg'),
(290, 13, 0, '../obras/mangas/o-menino-nemo-na-terra-dos-sonhos-1_page-0012.jpg'),
(291, 13, 0, '../obras/mangas/o-menino-nemo-na-terra-dos-sonhos-1_page-0013.jpg'),
(292, 13, 0, '../obras/mangas/o-menino-nemo-na-terra-dos-sonhos-1_page-0014.jpg'),
(293, 13, 0, '../obras/mangas/o-menino-nemo-na-terra-dos-sonhos-1_page-0015.jpg'),
(294, 13, 0, '../obras/mangas/o-menino-nemo-na-terra-dos-sonhos-1_page-0016.jpg'),
(295, 13, 0, '../obras/mangas/o-menino-nemo-na-terra-dos-sonhos-1_page-0017.jpg'),
(296, 13, 0, '../obras/mangas/o-menino-nemo-na-terra-dos-sonhos-1_page-0018.jpg'),
(297, 13, 0, '../obras/mangas/o-menino-nemo-na-terra-dos-sonhos-1_page-0019.jpg'),
(298, 13, 0, '../obras/mangas/o-menino-nemo-na-terra-dos-sonhos-1_page-0020.jpg'),
(299, 13, 0, '../obras/mangas/o-menino-nemo-na-terra-dos-sonhos-1_page-0021.jpg'),
(300, 13, 0, '../obras/mangas/o-menino-nemo-na-terra-dos-sonhos-1_page-0022.jpg'),
(301, 13, 0, '../obras/mangas/o-menino-nemo-na-terra-dos-sonhos-1_page-0023.jpg'),
(302, 13, 0, '../obras/mangas/o-menino-nemo-na-terra-dos-sonhos-1_page-0024.jpg'),
(303, 13, 0, '../obras/mangas/o-menino-nemo-na-terra-dos-sonhos-1_page-0025.jpg'),
(304, 13, 0, '../obras/mangas/o-menino-nemo-na-terra-dos-sonhos-1_page-0026.jpg'),
(305, 13, 0, '../obras/mangas/o-menino-nemo-na-terra-dos-sonhos-1_page-0027.jpg'),
(306, 13, 0, '../obras/mangas/o-menino-nemo-na-terra-dos-sonhos-1_page-0028.jpg'),
(307, 13, 0, '../obras/mangas/o-menino-nemo-na-terra-dos-sonhos-1_page-0029.jpg'),
(308, 13, 0, '../obras/mangas/o-menino-nemo-na-terra-dos-sonhos-1_page-0030.jpg'),
(309, 13, 0, '../obras/mangas/o-menino-nemo-na-terra-dos-sonhos-1_page-0031.jpg'),
(310, 13, 0, '../obras/mangas/o-menino-nemo-na-terra-dos-sonhos-1_page-0032.jpg'),
(311, 13, 0, '../obras/mangas/o-menino-nemo-na-terra-dos-sonhos-1_page-0033.jpg'),
(312, 13, 0, '../obras/mangas/o-menino-nemo-na-terra-dos-sonhos-1_page-0034.jpg'),
(313, 13, 0, '../obras/mangas/o-menino-nemo-na-terra-dos-sonhos-1_page-0035.jpg'),
(314, 13, 0, '../obras/mangas/o-menino-nemo-na-terra-dos-sonhos-1_page-0036.jpg'),
(315, 13, 0, '../obras/mangas/o-menino-nemo-na-terra-dos-sonhos-1_page-0037.jpg'),
(316, 13, 0, '../obras/mangas/o-menino-nemo-na-terra-dos-sonhos-1_page-0038.jpg'),
(317, 13, 0, '../obras/mangas/o-menino-nemo-na-terra-dos-sonhos-1_page-0039.jpg'),
(318, 13, 0, '../obras/mangas/o-menino-nemo-na-terra-dos-sonhos-1_page-0040.jpg'),
(319, 13, 0, '../obras/mangas/o-menino-nemo-na-terra-dos-sonhos-1_page-0041.jpg'),
(320, 13, 0, '../obras/mangas/o-menino-nemo-na-terra-dos-sonhos-1_page-0042.jpg'),
(321, 13, 0, '../obras/mangas/o-menino-nemo-na-terra-dos-sonhos-1_page-0043.jpg'),
(322, 13, 0, '../obras/mangas/o-menino-nemo-na-terra-dos-sonhos-1_page-0044.jpg'),
(323, 13, 0, '../obras/mangas/o-menino-nemo-na-terra-dos-sonhos-1_page-0045.jpg'),
(324, 13, 0, '../obras/mangas/o-menino-nemo-na-terra-dos-sonhos-1_page-0046.jpg'),
(325, 13, 0, '../obras/mangas/o-menino-nemo-na-terra-dos-sonhos-1_page-0047.jpg'),
(326, 13, 0, '../obras/mangas/o-menino-nemo-na-terra-dos-sonhos-1_page-0048.jpg'),
(327, 13, 0, '../obras/mangas/o-menino-nemo-na-terra-dos-sonhos-1_page-0049.jpg'),
(328, 13, 0, '../obras/mangas/o-menino-nemo-na-terra-dos-sonhos-1_page-0050.jpg'),
(329, 13, 0, '../obras/mangas/o-menino-nemo-na-terra-dos-sonhos-1_page-0051.jpg'),
(330, 13, 0, '../obras/mangas/o-menino-nemo-na-terra-dos-sonhos-1_page-0052.jpg'),
(331, 13, 0, '../obras/mangas/o-menino-nemo-na-terra-dos-sonhos-1_page-0053.jpg'),
(332, 13, 0, '../obras/mangas/o-menino-nemo-na-terra-dos-sonhos-1_page-0054.jpg'),
(333, 13, 0, '../obras/mangas/o-menino-nemo-na-terra-dos-sonhos-1_page-0055.jpg'),
(334, 13, 0, '../obras/mangas/o-menino-nemo-na-terra-dos-sonhos-1_page-0056.jpg'),
(335, 13, 0, '../obras/mangas/o-menino-nemo-na-terra-dos-sonhos-1_page-0057.jpg'),
(336, 13, 0, '../obras/mangas/o-menino-nemo-na-terra-dos-sonhos-1_page-0058.jpg'),
(337, 13, 0, '../obras/mangas/o-menino-nemo-na-terra-dos-sonhos-1_page-0059.jpg'),
(338, 13, 0, '../obras/mangas/o-menino-nemo-na-terra-dos-sonhos-1_page-0060.jpg'),
(339, 13, 0, '../obras/mangas/o-menino-nemo-na-terra-dos-sonhos-1_page-0061.jpg'),
(340, 13, 0, '../obras/mangas/o-menino-nemo-na-terra-dos-sonhos-1_page-0062.jpg'),
(341, 13, 0, '../obras/mangas/o-menino-nemo-na-terra-dos-sonhos-1_page-0063.jpg'),
(342, 13, 0, '../obras/mangas/o-menino-nemo-na-terra-dos-sonhos-1_page-0064.jpg'),
(343, 13, 0, '../obras/mangas/o-menino-nemo-na-terra-dos-sonhos-1_page-0065.jpg'),
(344, 13, 0, '../obras/mangas/o-menino-nemo-na-terra-dos-sonhos-1_page-0066.jpg'),
(345, 13, 0, '../obras/mangas/o-menino-nemo-na-terra-dos-sonhos-1_page-0067.jpg'),
(346, 13, 0, '../obras/mangas/o-menino-nemo-na-terra-dos-sonhos-1_page-0068.jpg'),
(347, 13, 0, '../obras/mangas/o-menino-nemo-na-terra-dos-sonhos-1_page-0069.jpg'),
(348, 13, 0, '../obras/mangas/o-menino-nemo-na-terra-dos-sonhos-1_page-0070.jpg'),
(349, 13, 0, '../obras/mangas/o-menino-nemo-na-terra-dos-sonhos-1_page-0071.jpg'),
(350, 13, 0, '../obras/mangas/o-menino-nemo-na-terra-dos-sonhos-1_page-0072.jpg');

-- --------------------------------------------------------

--
-- Estrutura para tabela `tb_usuario`
--

CREATE TABLE `tb_usuario` (
  `cd_usuario` int(11) NOT NULL,
  `ds_email` varchar(50) DEFAULT NULL,
  `ds_senha` varchar(128) NOT NULL,
  `nm_user` varchar(50) DEFAULT NULL,
  `nm_apelido` varchar(50) NOT NULL,
  `sg_sexoUser` enum('M','F','N') DEFAULT NULL,
  `qt_idadeUsuario` int(11) DEFAULT NULL CHECK (`qt_idadeUsuario` >= 18 and `qt_idadeUsuario` <= 100),
  `ds_foto_perfil` varchar(255) NOT NULL,
  `login_google` tinyint(1) DEFAULT 0,
  `conta_ativa` tinyint(1) DEFAULT 1,
  `codigo_confirmacao` varchar(6) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `tb_usuario`
--

INSERT INTO `tb_usuario` (`cd_usuario`, `ds_email`, `ds_senha`, `nm_user`, `nm_apelido`, `sg_sexoUser`, `qt_idadeUsuario`, `ds_foto_perfil`, `login_google`, `conta_ativa`, `codigo_confirmacao`) VALUES
(6, 'andreluizposilva@gmail.com', '', 'André Luiz', '', NULL, NULL, 'https://lh3.googleusercontent.com/a/ACg8ocLwqcR7wp_wyFJo7RfwcHEPMf3nPxtgiCglnLkzky-OXxNmHUkqhg=s96-c', 1, 1, NULL),
(15, 'andre.silva@gmail.com', '$2y$10$eIhGokGVwnxCSZbpXR6rweRA1GiRuDt9knnd2ANj.F5krLtSSj8jW', 'André', '', NULL, NULL, '../uploads/perfil_15.jpg', 0, 1, NULL),
(51, 'teste@gmail.com', '$2y$10$Ym0TMTDsR6.l2n2Pjwg1K.KwqU.SvNWAVzBMlYFjsffAQhvQSuIL.', 'teste', '', NULL, NULL, '', 0, 1, NULL);

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `tb_autor`
--
ALTER TABLE `tb_autor`
  ADD PRIMARY KEY (`cd_autor`),
  ADD UNIQUE KEY `cd_usuarioAutor` (`cd_usuarioAutor`);

--
-- Índices de tabela `tb_manga`
--
ALTER TABLE `tb_manga`
  ADD PRIMARY KEY (`cd_manga`);

--
-- Índices de tabela `tb_obra`
--
ALTER TABLE `tb_obra`
  ADD PRIMARY KEY (`cd_obra`),
  ADD KEY `cd_autorObra` (`cd_autorObra`);

--
-- Índices de tabela `tb_paginas_manga`
--
ALTER TABLE `tb_paginas_manga`
  ADD PRIMARY KEY (`cd_pagina`),
  ADD KEY `cd_manga` (`cd_manga`);

--
-- Índices de tabela `tb_usuario`
--
ALTER TABLE `tb_usuario`
  ADD PRIMARY KEY (`cd_usuario`);

--
-- AUTO_INCREMENT para tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `tb_autor`
--
ALTER TABLE `tb_autor`
  MODIFY `cd_autor` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT de tabela `tb_manga`
--
ALTER TABLE `tb_manga`
  MODIFY `cd_manga` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT de tabela `tb_obra`
--
ALTER TABLE `tb_obra`
  MODIFY `cd_obra` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT de tabela `tb_paginas_manga`
--
ALTER TABLE `tb_paginas_manga`
  MODIFY `cd_pagina` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=351;

--
-- AUTO_INCREMENT de tabela `tb_usuario`
--
ALTER TABLE `tb_usuario`
  MODIFY `cd_usuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=52;

--
-- Restrições para tabelas despejadas
--

--
-- Restrições para tabelas `tb_autor`
--
ALTER TABLE `tb_autor`
  ADD CONSTRAINT `fk_autor_usuario` FOREIGN KEY (`cd_usuarioAutor`) REFERENCES `tb_usuario` (`cd_usuario`);

--
-- Restrições para tabelas `tb_obra`
--
ALTER TABLE `tb_obra`
  ADD CONSTRAINT `tb_obra_ibfk_1` FOREIGN KEY (`cd_autorObra`) REFERENCES `tb_autor` (`cd_autor`);

--
-- Restrições para tabelas `tb_paginas_manga`
--
ALTER TABLE `tb_paginas_manga`
  ADD CONSTRAINT `tb_paginas_manga_ibfk_1` FOREIGN KEY (`cd_manga`) REFERENCES `tb_manga` (`cd_manga`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
