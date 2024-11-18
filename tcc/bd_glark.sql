-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 18/11/2024 às 15:09
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
  `checkbox_status` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
  ADD PRIMARY KEY (`cd_autor`);

--
-- Índices de tabela `tb_obra`
--
ALTER TABLE `tb_obra`
  ADD PRIMARY KEY (`cd_obra`),
  ADD KEY `cd_autorObra` (`cd_autorObra`);

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
  MODIFY `cd_autor` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `tb_obra`
--
ALTER TABLE `tb_obra`
  MODIFY `cd_obra` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `tb_usuario`
--
ALTER TABLE `tb_usuario`
  MODIFY `cd_usuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=52;

--
-- Restrições para tabelas despejadas
--

--
-- Restrições para tabelas `tb_obra`
--
ALTER TABLE `tb_obra`
  ADD CONSTRAINT `tb_obra_ibfk_1` FOREIGN KEY (`cd_autorObra`) REFERENCES `tb_autor` (`cd_autor`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
