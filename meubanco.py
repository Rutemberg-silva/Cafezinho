-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 05/11/2024 às 14:48
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
-- Banco de dados: `cafezinho`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `avaliacoes`
--

CREATE TABLE `avaliacoes` (
  `id` int(11) NOT NULL,
  `usuario_id` int(11) DEFAULT NULL,
  `comentario` text NOT NULL,
  `avaliacao` int(11) NOT NULL CHECK (`avaliacao` between 1 and 5),
  `data_avaliacao` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `avaliacoes`
--

INSERT INTO `avaliacoes` (`id`, `usuario_id`, `comentario`, `avaliacao`, `data_avaliacao`) VALUES
(3, 22, 'otimo', 5, '2024-11-04 09:37:34'),
(4, 2, 'blz', 5, '2024-11-04 12:32:57'),
(5, 2, 'otimo', 5, '2024-11-04 17:45:31');

-- --------------------------------------------------------

--
-- Estrutura para tabela `carrinho`
--

CREATE TABLE `carrinho` (
  `id` int(11) NOT NULL,
  `usuario_id` int(11) NOT NULL,
  `produto_id` int(11) NOT NULL,
  `nome_produto` varchar(255) NOT NULL,
  `preco` decimal(10,2) NOT NULL,
  `imagem` varchar(255) NOT NULL,
  `quantidade` int(11) DEFAULT 1,
  `data_adicao` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `lista_desejos`
--

CREATE TABLE `lista_desejos` (
  `id` int(11) NOT NULL,
  `usuario_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `data_adicao` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `pedidos`
--

CREATE TABLE `pedidos` (
  `id` int(11) NOT NULL,
  `usuario_id` int(11) DEFAULT NULL,
  `endereco_entrega` varchar(255) DEFAULT NULL,
  `total` decimal(10,2) DEFAULT NULL,
  `metodo_pagamento` enum('pix','debito','credito') DEFAULT NULL,
  `status` enum('concluido','pendente','cancelado','avaliado') DEFAULT 'pendente',
  `data_pedido` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `pedidos`
--

INSERT INTO `pedidos` (`id`, `usuario_id`, `endereco_entrega`, `total`, `metodo_pagamento`, `status`, `data_pedido`) VALUES
(19, 22, 'Endereço cadastrado: Rua Exemplo, 123', 24.00, 'debito', 'avaliado', '2024-11-04 12:11:23'),
(20, 2, 'Endereço cadastrado: Rua Exemplo, 123', 22.49, 'debito', 'avaliado', '2024-11-04 12:24:59'),
(21, 2, 'Endereço cadastrado: Rua Exemplo, 123', 24.00, 'debito', 'avaliado', '2024-11-04 20:44:58');

-- --------------------------------------------------------

--
-- Estrutura para tabela `pedido_itens`
--

CREATE TABLE `pedido_itens` (
  `id` int(11) NOT NULL,
  `pedido_id` int(11) NOT NULL,
  `produto_id` int(11) NOT NULL,
  `quantidade` int(11) NOT NULL,
  `preco` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `produtos`
--

CREATE TABLE `produtos` (
  `id` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `descricao` text NOT NULL,
  `preco` decimal(10,2) NOT NULL,
  `imagem` varchar(255) NOT NULL,
  `data_cadastro` datetime DEFAULT current_timestamp(),
  `sugestoes` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `produtos`
--

INSERT INTO `produtos` (`id`, `nome`, `descricao`, `preco`, `imagem`, `data_cadastro`, `sugestoes`) VALUES
(11, 'Café Bartier', 'CAFÉ GOURMET, catuaí amarelo, com sabor achocolatado, da região da alta mogiana.', 42.00, 'uploads/Café Bartier.jpg', '2024-11-03 22:38:15', 'Ideal para quem gosta de saborear um bom café sem adição de adoçantes.'),
(12, 'Café Ilha do Ar Grãos', 'ILHA DO AR CAFES ESPECIAIS EM GRÃOS', 30.00, 'uploads/Café Ilha do Ar Grãos.jpg', '2024-11-03 22:44:14', 'Grãos selecionados artesanalmente em família, fino café arábica, rara variedade catiguá e torra média.'),
(13, 'Premium Café Minas Torrado', 'Suas principais características são o sabor rico e encorpado, proveniente dos grãos arábica torrados, e o formato em grãos, que preserva o frescor e aroma do café por mais tempo.', 45.00, 'uploads/Cafe minas torrado.jpg', '2024-11-03 22:51:01', 'Com esse produto, os consumidores podem desfrutar de uma experiência gourmet e apreciar um café de alta qualidade em casa.'),
(14, 'Café Gourmet  Santa Mônica', 'Café Gourmet Torra Italiana 500g - Santa Mônica', 37.84, 'uploads/Café Gourmet  500g  Santa Mônica.jpg', '2024-11-03 22:54:33', 'Apreciados por aqueles que preferem um café mais encorpado, intenso, forte e com nuances tostadas. Recomendação de preparo: 1/10 de grãos para água, ou seja, 10g de café para 100ml de água.'),
(15, '3 Corações Kit Café Gourmet Torrado e moído', '3 Corações Kit Café Gourmet Torrado e moído - Cerrado Mineiro e Mogiana Paulista, 2x250g ', 21.99, 'uploads/kit 3 coracoes.jpg', '2024-11-03 23:01:00', 'Uma linha de cafés *100% Arábica* de diversas regiões unindo a arte de fazer café com o memorável legado humanista. Além disso, em cada embalagem você encontrará notas com experiências sensoriais únicas e grãos de altíssima qualidade. '),
(16, 'L\'OR Café L\'Or Cápsula Papua-Nova Guiné - 10 Cápsulas', 'L\'OR Café L\'Or Cápsula Papua-Nova Guiné - 10 Cápsulas - 52 Gramas', 24.00, 'uploads/L\'OR Café L\'Or Cápsula Papua-Nova Guiné - 10 Cápsulas.jpg', '2024-11-03 23:08:37', ' uma espetacular mistura com um acentuado aroma que faz lembrar o clima frio e úmido das férteis encostas vulcânicas nas quais estes grãos de café amadurecem'),
(17, '3 Corações Café Torrado e Moído Gourmet Cerrado Mineiro, 250G', 'Ele é diferenciado, dá pra perceber o toque de chocolate e frutas amarelas, muito cheiroso!', 22.49, 'uploads/3 Corações Café Torrado e Moído Gourmet Cerrado Mineiro, 250G.jpg', '2024-11-03 23:15:32', 'na');

-- --------------------------------------------------------

--
-- Estrutura para tabela `suporte`
--

CREATE TABLE `suporte` (
  `id` int(11) NOT NULL,
  `usuario_id` int(11) DEFAULT NULL,
  `mensagem` text NOT NULL,
  `data_solicitacao` datetime DEFAULT current_timestamp(),
  `status` varchar(20) NOT NULL DEFAULT 'Aberto'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `senha` varchar(255) NOT NULL,
  `tipo_usuario` enum('admin','cliente') NOT NULL DEFAULT 'cliente',
  `endereco` varchar(255) NOT NULL,
  `telefone` varchar(15) NOT NULL,
  `data_criacao` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `usuarios`
--

INSERT INTO `usuarios` (`id`, `nome`, `email`, `senha`, `tipo_usuario`, `endereco`, `telefone`, `data_criacao`) VALUES
(2, 'admin', 'admin@cafezinho.com', '$2y$10$IbGh/Apow7NnSrxAPkeyxO.R.3eLHAwp8hMYqalYYhxICihRbvzI6', 'admin', 'Rua do adm', '83988888888', '2024-10-19 16:14:47'),
(20, 'Rutemberg', 'rutemberg@cafezinho.com', '$2y$10$.vqRaZw2.h8LQTIc97xLwegWF.Qq2CkyRaQfYV/D9m/mABqh/CYXi', 'admin', 'rua 9', '83988888888', '2024-10-27 00:45:03'),
(21, 'coffee', 'yuri242011@gmail.com', '$2y$10$7qs1aN303RYOHddtAEFKI.5rUAEs5tRwE8XoznaCEKblkH/WQUYCi', 'cliente', 'rua café com pão', '83', '2024-10-28 20:32:26'),
(22, 'Cliente teste 1', 'clienteteste1@cafezinho.com', '$2y$10$a48oPUUWrO6LYp.pvV2ZrezXn2BMOCE4Q1yLrp2.EbdU3S.oPJ4KO', 'cliente', 'rua nova', '83999999999', '2024-11-04 09:07:39');

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `avaliacoes`
--
ALTER TABLE `avaliacoes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `usuario_id` (`usuario_id`);

--
-- Índices de tabela `carrinho`
--
ALTER TABLE `carrinho`
  ADD PRIMARY KEY (`id`),
  ADD KEY `usuario_id` (`usuario_id`),
  ADD KEY `produto_id` (`produto_id`);

--
-- Índices de tabela `lista_desejos`
--
ALTER TABLE `lista_desejos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`usuario_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Índices de tabela `pedidos`
--
ALTER TABLE `pedidos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `usuario_id` (`usuario_id`);

--
-- Índices de tabela `pedido_itens`
--
ALTER TABLE `pedido_itens`
  ADD PRIMARY KEY (`id`),
  ADD KEY `pedido_id` (`pedido_id`),
  ADD KEY `produto_id` (`produto_id`);

--
-- Índices de tabela `produtos`
--
ALTER TABLE `produtos`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `suporte`
--
ALTER TABLE `suporte`
  ADD PRIMARY KEY (`id`),
  ADD KEY `usuario_id` (`usuario_id`);

--
-- Índices de tabela `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT para tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `avaliacoes`
--
ALTER TABLE `avaliacoes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de tabela `carrinho`
--
ALTER TABLE `carrinho`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- AUTO_INCREMENT de tabela `lista_desejos`
--
ALTER TABLE `lista_desejos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT de tabela `pedidos`
--
ALTER TABLE `pedidos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT de tabela `pedido_itens`
--
ALTER TABLE `pedido_itens`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `produtos`
--
ALTER TABLE `produtos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT de tabela `suporte`
--
ALTER TABLE `suporte`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- Restrições para tabelas despejadas
--

--
-- Restrições para tabelas `avaliacoes`
--
ALTER TABLE `avaliacoes`
  ADD CONSTRAINT `avaliacoes_ibfk_2` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`);

--
-- Restrições para tabelas `carrinho`
--
ALTER TABLE `carrinho`
  ADD CONSTRAINT `carrinho_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`),
  ADD CONSTRAINT `carrinho_ibfk_2` FOREIGN KEY (`produto_id`) REFERENCES `produtos` (`id`);

--
-- Restrições para tabelas `lista_desejos`
--
ALTER TABLE `lista_desejos`
  ADD CONSTRAINT `lista_desejos_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`),
  ADD CONSTRAINT `lista_desejos_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `produtos` (`id`);

--
-- Restrições para tabelas `pedidos`
--
ALTER TABLE `pedidos`
  ADD CONSTRAINT `pedidos_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`);

--
-- Restrições para tabelas `pedido_itens`
--
ALTER TABLE `pedido_itens`
  ADD CONSTRAINT `pedido_itens_ibfk_1` FOREIGN KEY (`pedido_id`) REFERENCES `pedidos` (`id`),
  ADD CONSTRAINT `pedido_itens_ibfk_2` FOREIGN KEY (`produto_id`) REFERENCES `carrinho` (`produto_id`);

--
-- Restrições para tabelas `suporte`
--
ALTER TABLE `suporte`
  ADD CONSTRAINT `suporte_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
