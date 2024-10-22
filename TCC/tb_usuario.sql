-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 22/10/2024 às 20:13
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
  `ds_foto_perfil` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `tb_usuario`
--

INSERT INTO `tb_usuario` (`cd_usuario`, `ds_email`, `ds_senha`, `nm_user`, `nm_apelido`, `sg_sexoUser`, `qt_idadeUsuario`, `ds_foto_perfil`) VALUES
(1, 'andre.silva@gmail.com', '123456', NULL, '', NULL, NULL, ''),
(2, 'marcelo.augusto@gmail.com', '6789', NULL, '', NULL, NULL, ''),
(3, 'lucas@hotmail.com', '121326', 'Lucas', '', NULL, NULL, ''),
(4, 'andreluizposilva@gmail.com', '123456', 'André', 'Deco', NULL, NULL, 'https://lh3.googleusercontent.com/a/ACg8ocLwqcR7wp_wyFJo7RfwcHEPMf3nPxtgiCglnLkzky-OXxNmHUkqhg=s96-c'),
(5, 'andre.oliveira@gmail.com', '123456', 'André Luiz', 'Nareba', NULL, NULL, '');

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
  MODIFY `cd_usuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
