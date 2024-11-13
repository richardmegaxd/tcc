-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 23/10/2024 às 20:11
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
  `conta_ativa` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `tb_usuario`
--

INSERT INTO `tb_usuario` (`cd_usuario`, `ds_email`, `ds_senha`, `nm_user`, `nm_apelido`, `sg_sexoUser`, `qt_idadeUsuario`, `ds_foto_perfil`, `login_google`, `conta_ativa`) VALUES
(1, 'andre.silva@gmail.com', '123456', NULL, '', NULL, NULL, '', 0, 1),
(2, 'marcelo.augusto@gmail.com', '6789', NULL, '', NULL, NULL, '', 0, 1),
(3, 'lucas@hotmail.com', '121326', 'Lucas', '', NULL, NULL, '', 0, 1),
(5, 'andre.oliveira@gmail.com', '123456', 'André Luiz', 'Nareba', NULL, NULL, '', 0, 1),
(6, 'andreluizposilva@gmail.com', '', 'André Luiz', '', NULL, NULL, 'https://lh3.googleusercontent.com/a/ACg8ocLwqcR7wp_wyFJo7RfwcHEPMf3nPxtgiCglnLkzky-OXxNmHUkqhg=s96-c', 1, 1),
(7, 'lelecosilva1216@gmail.com', '', 'Leticia Silva', '', NULL, NULL, 'https://lh3.googleusercontent.com/a/ACg8ocIr9_0NKui8i52qOHvBuul4YZ2TfGbF7FAJoqJ2eMUoFioyQjkS=s96-c', 1, 1),
(8, 'isaaceuzebio10@gmail.com', '', 'Isaaczin FF', '', NULL, NULL, 'https://lh3.googleusercontent.com/a/ACg8ocIIDKdmhEY7_QwUzozhV39UJAI9mISOo2_9YYeG2hccnkLuC3I1=s96-c', 1, 1),
(9, 'felipenonato092@gmail.com', '', 'Felipe Nonato', '', NULL, NULL, 'https://lh3.googleusercontent.com/a/ACg8ocL74dOo08JJtbECfumWnPZZ2nUgNQIJTPCAEBQCjZt1f7XeaA=s96-c', 1, 1),
(10, 'maykon13102004@gmail.com', '', 'Maykon Oliveira', '', NULL, NULL, 'https://lh3.googleusercontent.com/a/ACg8ocJwN2AchqAzC9PMSLKgRCnCKW3jNf93EIEfRm61vxzoNdX8tUCk=s96-c', 1, 1);

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `tb_usuario`
--
ALTER TABLE `tb_usuario`
  ADD PRIMARY KEY (`cd_usuario`);

--
-- AUTO_INCREMENT para tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `tb_usuario`
--
ALTER TABLE `tb_usuario`
  MODIFY `cd_usuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;
COMMIT;

ALTER TABLE `tb_usuario`
  ADD `codigo_confirmacao` varchar(6);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
