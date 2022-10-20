-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 09-Out-2022 às 21:57
-- Versão do servidor: 10.4.25-MariaDB
-- versão do PHP: 7.4.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `ordem`
--

-- --------------------------------------------------------

--
-- Estrutura da tabela `grupos`
--

CREATE TABLE `grupos` (
  `id` int(5) UNSIGNED NOT NULL,
  `nome` varchar(128) NOT NULL,
  `descricao` varchar(240) NOT NULL,
  `exibir` tinyint(1) NOT NULL,
  `criado_em` datetime DEFAULT NULL,
  `atualizado_em` datetime DEFAULT NULL,
  `deletado_em` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Extraindo dados da tabela `grupos`
--

INSERT INTO `grupos` (`id`, `nome`, `descricao`, `exibir`, `criado_em`, `atualizado_em`, `deletado_em`) VALUES
(1, 'Administrador', 'Grupo com acesso total ao sistema', 0, '2022-09-27 22:11:10', '2022-09-27 22:11:10', NULL),
(2, 'Clientes', 'Esse grupo é destinado para atribuição de clientes, pois os mesmos poderão logar no sistema para acessar suas ordens de serviços', 0, '2022-09-27 22:11:10', '2022-09-27 22:11:10', NULL),
(3, 'Atendentes', 'Esse grupo acessa o sistema para realizar atendimento aos clientes.', 1, '2022-09-27 22:11:10', '2022-10-01 22:43:04', NULL),
(4, 'Especialista em Microcomponentes', 'Grupo que será escolhido como opção na hora de definir um responsável técnico pela ordem de serviço.', 1, '2022-10-01 22:42:13', '2022-10-01 23:19:39', NULL),
(5, 'Gerente', 'Acessa o sistema como gerente.', 1, '2022-10-02 18:55:29', '2022-10-02 18:56:25', NULL),
(6, 'Financeiro', 'Acessa o sistema para trabalhar como financeiro', 1, '2022-10-02 18:55:59', '2022-10-02 18:56:18', NULL);

-- --------------------------------------------------------

--
-- Estrutura da tabela `grupos_permissoes`
--

CREATE TABLE `grupos_permissoes` (
  `id` int(5) UNSIGNED NOT NULL,
  `grupo_id` int(5) UNSIGNED NOT NULL,
  `permissao_id` int(5) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Extraindo dados da tabela `grupos_permissoes`
--

INSERT INTO `grupos_permissoes` (`id`, `grupo_id`, `permissao_id`) VALUES
(1, 6, 2),
(2, 6, 3),
(3, 4, 2),
(4, 4, 3),
(5, 3, 4),
(6, 6, 4),
(7, 6, 1);

-- --------------------------------------------------------

--
-- Estrutura da tabela `grupos_usuarios`
--

CREATE TABLE `grupos_usuarios` (
  `id` int(5) UNSIGNED NOT NULL,
  `grupo_id` int(5) UNSIGNED NOT NULL,
  `usuario_id` int(5) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Extraindo dados da tabela `grupos_usuarios`
--

INSERT INTO `grupos_usuarios` (`id`, `grupo_id`, `usuario_id`) VALUES
(1, 1, 1);

-- --------------------------------------------------------

--
-- Estrutura da tabela `migrations`
--

CREATE TABLE `migrations` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `version` varchar(255) NOT NULL,
  `class` varchar(255) NOT NULL,
  `group` varchar(255) NOT NULL,
  `namespace` varchar(255) NOT NULL,
  `time` int(11) NOT NULL,
  `batch` int(11) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Extraindo dados da tabela `migrations`
--

INSERT INTO `migrations` (`id`, `version`, `class`, `group`, `namespace`, `time`, `batch`) VALUES
(1, '2022-07-15-002610', 'App\\Database\\Migrations\\CriarTabelaUsuarios', 'default', 'App', 1665345010, 1),
(2, '2022-09-25-193600', 'App\\Database\\Migrations\\CriaTabelaGrupos', 'default', 'App', 1665345010, 1),
(3, '2022-10-02-022314', 'App\\Database\\Migrations\\CriaTabelaPermissoes', 'default', 'App', 1665345011, 1),
(4, '2022-10-02-180039', 'App\\Database\\Migrations\\CriaTabelaGruposPermissoes', 'default', 'App', 1665345011, 1),
(5, '2022-10-04-025926', 'App\\Database\\Migrations\\CriaTabelaGruposUsuarios', 'default', 'App', 1665345011, 1);

-- --------------------------------------------------------

--
-- Estrutura da tabela `permissoes`
--

CREATE TABLE `permissoes` (
  `id` int(5) UNSIGNED NOT NULL,
  `nome` varchar(128) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Extraindo dados da tabela `permissoes`
--

INSERT INTO `permissoes` (`id`, `nome`) VALUES
(2, 'criar-usuarios'),
(3, 'editar-usuarios'),
(4, 'excluir-usuarios'),
(1, 'listar-usuarios');

-- --------------------------------------------------------

--
-- Estrutura da tabela `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(5) UNSIGNED NOT NULL,
  `nome` varchar(128) NOT NULL,
  `email` varchar(240) NOT NULL,
  `password_hash` varchar(240) NOT NULL,
  `reset_hash` varchar(80) DEFAULT NULL,
  `reset_expira_em` datetime DEFAULT NULL,
  `imagem` varchar(240) DEFAULT NULL,
  `ativo` tinyint(1) NOT NULL,
  `criado_em` datetime DEFAULT NULL,
  `atualizado_em` datetime DEFAULT NULL,
  `deletado_em` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Extraindo dados da tabela `usuarios`
--

INSERT INTO `usuarios` (`id`, `nome`, `email`, `password_hash`, `reset_hash`, `reset_expira_em`, `imagem`, `ativo`, `criado_em`, `atualizado_em`, `deletado_em`) VALUES
(1, 'Contato', 'contato@summercomunicacao.com.br', '$2y$10$Cm7q6UycZl26K.nBYj9sA./1t4YiOF564nfn0h0eU7oP1M6T/nIy.', NULL, NULL, NULL, 1, '2022-10-09 16:55:25', '2022-10-09 16:55:25', NULL),
(2, 'Leonardo Rosa da Silva', 'leonardo.rosa@summercomunicacao.com.br', '$2y$10$G13R57zgAKBk19n3iXfzbO1FeSWFGhH742SG.DbhawQgUOwBVWAZu', NULL, NULL, NULL, 1, '2022-10-09 16:55:59', '2022-10-09 16:55:59', NULL);

--
-- Índices para tabelas despejadas
--

--
-- Índices para tabela `grupos`
--
ALTER TABLE `grupos`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nome` (`nome`);

--
-- Índices para tabela `grupos_permissoes`
--
ALTER TABLE `grupos_permissoes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `grupos_permissoes_grupo_id_foreign` (`grupo_id`),
  ADD KEY `grupos_permissoes_permissao_id_foreign` (`permissao_id`);

--
-- Índices para tabela `grupos_usuarios`
--
ALTER TABLE `grupos_usuarios`
  ADD PRIMARY KEY (`id`),
  ADD KEY `grupos_usuarios_grupo_id_foreign` (`grupo_id`),
  ADD KEY `grupos_usuarios_usuario_id_foreign` (`usuario_id`);

--
-- Índices para tabela `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `permissoes`
--
ALTER TABLE `permissoes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nome` (`nome`);

--
-- Índices para tabela `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT de tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `grupos`
--
ALTER TABLE `grupos`
  MODIFY `id` int(5) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de tabela `grupos_permissoes`
--
ALTER TABLE `grupos_permissoes`
  MODIFY `id` int(5) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de tabela `grupos_usuarios`
--
ALTER TABLE `grupos_usuarios`
  MODIFY `id` int(5) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de tabela `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de tabela `permissoes`
--
ALTER TABLE `permissoes`
  MODIFY `id` int(5) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de tabela `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(5) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Restrições para despejos de tabelas
--

--
-- Limitadores para a tabela `grupos_permissoes`
--
ALTER TABLE `grupos_permissoes`
  ADD CONSTRAINT `grupos_permissoes_grupo_id_foreign` FOREIGN KEY (`grupo_id`) REFERENCES `grupos` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `grupos_permissoes_permissao_id_foreign` FOREIGN KEY (`permissao_id`) REFERENCES `permissoes` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Limitadores para a tabela `grupos_usuarios`
--
ALTER TABLE `grupos_usuarios`
  ADD CONSTRAINT `grupos_usuarios_grupo_id_foreign` FOREIGN KEY (`grupo_id`) REFERENCES `grupos` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `grupos_usuarios_usuario_id_foreign` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
