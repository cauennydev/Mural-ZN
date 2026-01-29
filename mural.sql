-- phpMyAdmin SQL Dump
-- version 4.0.4.1
-- http://www.phpmyadmin.net
--
-- Máquina: 127.0.0.1
-- Data de Criação: 27-Maio-2015 às 21:59
-- Versão do servidor: 5.5.32
-- versão do PHP: 5.4.19

-- Base de Dados: `mural`
CREATE TABLE IF NOT EXISTS `usuarios` (
  `email` varchar(100) COLLATE utf8_bin NOT NULL,
  `cod_usuario` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(100) COLLATE utf8_bin NOT NULL,
  `login` varchar(50) COLLATE utf8_bin NOT NULL,
  `Senha` varchar(50) COLLATE utf8_bin NOT NULL,
  `arquivo` varchar(255) COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`cod_usuario`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=2 ;
/* A TABELA USUARIOS É PARA ARMAZENAR INFORMAÇÕES DOS USUÁRIOS DO SISTEMA */
-- --------------------------------------------------------

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de Dados: `mural`
--
CREATE DATABASE IF NOT EXISTS `mural` DEFAULT CHARACTER SET utf8 COLLATE utf8_bin;
USE `mural`;

-- --------------------------------------------------------

--
-- Estrutura da tabela `midias`
--

CREATE TABLE IF NOT EXISTS `midias` (
  `cod_midia` int(11) NOT NULL AUTO_INCREMENT,
  `descricao` varchar(100) COLLATE utf8_bin NOT NULL,
  `arquivo` varchar(255) COLLATE utf8_bin NOT NULL,
  `data_inicio` date NOT NULL,
  `data_final` date NOT NULL,
  `ativo` varchar(3) COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`cod_midia`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=168 ;


-- Extraindo dados da tabela `usuarios`
--

INSERT INTO `usuarios` (`email`, `cod_usuario`, `nome`, `login`, `Senha`, `arquivo`) VALUES
('robson.pires.borges@gmail.com', 1, 'Administrador', 'admin', 'admin', '');

INSERT INTO `usuarios` (`email`, `cod_usuario`, `nome`, `login`, `Senha`, `arquivo`) VALUES
('', 3, 'Cauenny', 'cauenny', 'cauenny', '');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
