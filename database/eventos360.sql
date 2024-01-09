-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 09-Jan-2024 às 22:39
-- Versão do servidor: 11.3.0-MariaDB
-- versão do PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `eventos360`
--

-- --------------------------------------------------------

--
-- Estrutura da tabela `attendance`
--

CREATE TABLE `attendance` (
  `attendance_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `event_id` int(11) NOT NULL,
  `status` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `attendance`
--

INSERT INTO `attendance` (`attendance_id`, `user_id`, `event_id`, `status`) VALUES
(116, 17, 40, 'nao_vou'),
(117, 17, 39, 'vou');

-- --------------------------------------------------------

--
-- Estrutura da tabela `category`
--

CREATE TABLE `category` (
  `category_id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `description` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `category`
--

INSERT INTO `category` (`category_id`, `name`, `description`) VALUES
(1, '1', 'teste'),
(2, '2', 'desc'),
(3, 'nome 1', 'desc 1');

-- --------------------------------------------------------

--
-- Estrutura da tabela `event`
--

CREATE TABLE `event` (
  `event_id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `description` varchar(200) NOT NULL,
  `date` date NOT NULL,
  `location` varchar(50) NOT NULL,
  `user_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `image_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `event`
--

INSERT INTO `event` (`event_id`, `name`, `description`, `date`, `location`, `user_id`, `category_id`, `image_id`) VALUES
(38, 'a', 'a', '2222-02-22', '22', 17, 1, 13),
(39, 'a', 'a', '2222-02-22', '2', 17, 1, 14),
(40, 'teste', 'desc teste', '2024-02-12', 'Viana', 17, 3, 15),
(41, 'a', 'a', '2222-02-22', '2', 17, 1, NULL);

-- --------------------------------------------------------

--
-- Estrutura da tabela `event_users`
--

CREATE TABLE `event_users` (
  `event_user_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `event_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `images`
--

CREATE TABLE `images` (
  `image_id` int(11) NOT NULL,
  `url` varchar(500) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `images`
--

INSERT INTO `images` (`image_id`, `url`) VALUES
(13, 'event_image_659dba73a3300.jpg'),
(14, 'event_image_659dba87a5daf.jpg'),
(15, 'event_image_659dbb4cd1ad5.jpg');

-- --------------------------------------------------------

--
-- Estrutura da tabela `invitation`
--

CREATE TABLE `invitation` (
  `invitation_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `event_id` int(11) NOT NULL,
  `status` varchar(25) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `location`
--

CREATE TABLE `location` (
  `location_id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `city` varchar(50) NOT NULL,
  `country` varchar(50) NOT NULL,
  `street` varchar(120) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `user`
--

CREATE TABLE `user` (
  `user_id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(50) NOT NULL,
  `name` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `user`
--

INSERT INTO `user` (`user_id`, `username`, `password`, `name`, `email`) VALUES
(1, 'admin', '$2y$10$uMS0BLeVY50cBeHJm1241usLclEq5hVDOrTB.A.P9Jw', 'admin', 'admin@email.com'),
(17, 'a', '$2y$10$n35.u6SsXC0DxK9M6Fo54.z2x3cS18SHFUN8MFl77bz', 'a', 'a@a'),
(18, 'b', '$2y$10$3xMe7jBm0bmQYMGTgcd6VeJ2BMzVGjfOt7tr2CAkZq4', 'b', 'b@b'),
(19, 'c', '$2y$10$5Cf2HfsgJuYkIuxmwLArzusXeU2BAcoWla3DCsaR.Ca', 'c', 'c@c');

--
-- Índices para tabelas despejadas
--

--
-- Índices para tabela `attendance`
--
ALTER TABLE `attendance`
  ADD PRIMARY KEY (`attendance_id`),
  ADD KEY `event_id_fk` (`event_id`),
  ADD KEY `user_id_fk` (`user_id`);

--
-- Índices para tabela `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`category_id`);

--
-- Índices para tabela `event`
--
ALTER TABLE `event`
  ADD PRIMARY KEY (`event_id`),
  ADD KEY `user_id_fk_3` (`user_id`),
  ADD KEY `image_id_fk` (`image_id`);

--
-- Índices para tabela `event_users`
--
ALTER TABLE `event_users`
  ADD PRIMARY KEY (`event_user_id`),
  ADD KEY `event_id_fk_3` (`event_id`),
  ADD KEY `user_id_fk_4` (`user_id`);

--
-- Índices para tabela `images`
--
ALTER TABLE `images`
  ADD PRIMARY KEY (`image_id`);

--
-- Índices para tabela `invitation`
--
ALTER TABLE `invitation`
  ADD PRIMARY KEY (`invitation_id`),
  ADD KEY `event_id_fk_2` (`event_id`),
  ADD KEY `user_id_fk_2` (`user_id`);

--
-- Índices para tabela `location`
--
ALTER TABLE `location`
  ADD PRIMARY KEY (`location_id`);

--
-- Índices para tabela `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT de tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `attendance`
--
ALTER TABLE `attendance`
  MODIFY `attendance_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=118;

--
-- AUTO_INCREMENT de tabela `category`
--
ALTER TABLE `category`
  MODIFY `category_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de tabela `event`
--
ALTER TABLE `event`
  MODIFY `event_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;

--
-- AUTO_INCREMENT de tabela `event_users`
--
ALTER TABLE `event_users`
  MODIFY `event_user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT de tabela `images`
--
ALTER TABLE `images`
  MODIFY `image_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT de tabela `invitation`
--
ALTER TABLE `invitation`
  MODIFY `invitation_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `location`
--
ALTER TABLE `location`
  MODIFY `location_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `user`
--
ALTER TABLE `user`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- Restrições para despejos de tabelas
--

--
-- Limitadores para a tabela `attendance`
--
ALTER TABLE `attendance`
  ADD CONSTRAINT `attendance_ibfk_1` FOREIGN KEY (`event_id`) REFERENCES `event` (`event_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `user_id_fk` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`);

--
-- Limitadores para a tabela `event`
--
ALTER TABLE `event`
  ADD CONSTRAINT `image_id_fk` FOREIGN KEY (`image_id`) REFERENCES `images` (`image_id`);

--
-- Limitadores para a tabela `event_users`
--
ALTER TABLE `event_users`
  ADD CONSTRAINT `event_users_ibfk_1` FOREIGN KEY (`event_id`) REFERENCES `event` (`event_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `user_id_fk_4` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`);

--
-- Limitadores para a tabela `invitation`
--
ALTER TABLE `invitation`
  ADD CONSTRAINT `event_id_fk_2` FOREIGN KEY (`event_id`) REFERENCES `event` (`event_id`),
  ADD CONSTRAINT `user_id_fk_2` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
