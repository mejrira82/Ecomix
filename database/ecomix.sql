-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : lun. 29 mai 2023 à 17:36
-- Version du serveur : 10.4.28-MariaDB
-- Version de PHP : 8.0.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `ecomix`
--

-- --------------------------------------------------------

--
-- Structure de la table `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `parent_id` int(11) DEFAULT NULL,
  `name` varchar(100) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `category_order` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `categories`
--

INSERT INTO `categories` (`id`, `parent_id`, `name`, `slug`, `category_order`) VALUES
(21, NULL, 'Computers', 'computers', 1),
(22, 21, 'Laptop', 'laptop', 2),
(23, 21, 'Gaming Laptop', 'gaming-laptop', 3),
(24, 21, 'Desktop Comupter', 'desktop-comupter', 4),
(25, 21, 'Gaming Desktop Computer', 'gaming-desktop-computer', 5),
(26, NULL, 'Accessories', 'accessories', 6),
(27, 26, 'Screen', 'screen', 7),
(28, 26, 'Keyboard', 'keyboard', 8),
(29, 26, 'Mouse', 'mouse', 10),
(30, 26, 'Headphones', 'headphones', 9);

-- --------------------------------------------------------

--
-- Structure de la table `coupons`
--

CREATE TABLE `coupons` (
  `id` int(11) NOT NULL,
  `coupons_types_id` int(11) NOT NULL,
  `code` varchar(10) NOT NULL,
  `description` longtext NOT NULL,
  `discount` int(11) NOT NULL,
  `max_usage` int(11) NOT NULL,
  `validity` datetime NOT NULL,
  `is_valid` tinyint(1) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp() COMMENT '(DC2Type:datetime_immutable)'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `coupons_types`
--

CREATE TABLE `coupons_types` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `images`
--

CREATE TABLE `images` (
  `id` int(11) NOT NULL,
  `products_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `images`
--

INSERT INTO `images` (`id`, `products_id`, `name`) VALUES
(1133, 214, 'd08433cacd8a43123b1459529158d120.png'),
(1134, 214, '810cc76a0c7656896d8f54e58fa3528c.png'),
(1135, 214, '474c85b0346b3102183f28993f9f18ad.png'),
(1136, 214, 'cc971145d27be68ab847b42303128e07.png'),
(1137, 214, 'ed9f9d73447dda11c9fd5f54e91f9ea7.png');

-- --------------------------------------------------------

--
-- Structure de la table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `coupons_id` int(11) DEFAULT NULL,
  `users_id` int(11) NOT NULL,
  `reference` varchar(20) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp() COMMENT '(DC2Type:datetime_immutable)'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `orders_details`
--

CREATE TABLE `orders_details` (
  `orders_id` int(11) NOT NULL,
  `products_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `price` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `categories_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` longtext NOT NULL,
  `price` int(11) NOT NULL,
  `stock` int(11) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp() COMMENT '(DC2Type:datetime_immutable)',
  `slug` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `products`
--

INSERT INTO `products` (`id`, `categories_id`, `name`, `description`, `price`, `stock`, `created_at`, `slug`) VALUES
(214, 23, 'HP VICTUS 15', 'Écran 15.6 \" Full HD IPS- Processeur AMD Ryzen 5 5600H, up to 4.2 Ghz, 16 Mo de mémoire cache - Mémoire 12 Go - Disque 512 Go SSD M.2 - Carte graphique Nvidia GeForce GTX 1650, 4 Go de mémoire dédiée - Wifi 6 - Bluetooth 5.2 - Clavier rétroéclairé - SuperSpeed USB Type-C - SuperSpeed USB Type-A - HDMI 2.1 - RJ45 - Lecteur de cartes - Caméra HP Wide Vision HD 720p - Windows 11 - Garantie 1 an', 2200, 10, '2023-05-26 13:56:01', 'HP-VICTUS-15');

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `email` varchar(180) NOT NULL,
  `roles` longtext NOT NULL COMMENT '(DC2Type:json)',
  `password` varchar(255) NOT NULL,
  `lastname` varchar(100) NOT NULL,
  `firstname` varchar(100) NOT NULL,
  `adress` varchar(255) NOT NULL,
  `zipcode` varchar(5) NOT NULL,
  `city` varchar(150) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp() COMMENT '(DC2Type:datetime_immutable)',
  `is_verified` tinyint(1) NOT NULL,
  `reset_token` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `users`
--

INSERT INTO `users` (`id`, `email`, `roles`, `password`, `lastname`, `firstname`, `adress`, `zipcode`, `city`, `created_at`, `is_verified`, `reset_token`) VALUES
(18, 'mejrira82@gmail.com', '[\"ROLE_ADMIN\"]', '$2y$13$Ya5W7k.wBAhC.TATcTYOmOeoEchhFWtT3oJRe8SxMnQEQlD3emaRO', 'Mejri', 'Ramzi', '13 Rue Ibn Moetaz', '2082', 'Fouchana', '2023-05-25 13:47:24', 1, NULL),
(19, 'renee71@pereira.fr', '[]', '$2y$13$lQYRvsP3zfFCAXbQFKv66eMjDP6nSRXp8IORMJE.ONX6rwoVMcmua', 'Becker', 'Océane', '4, boulevard de Maillet\n93 582 Gregoire', '76912', 'Louisdan', '2023-05-25 13:47:24', 0, NULL),
(20, 'luc.martins@remy.org', '[]', '$2y$13$Hc1R1vMqL8D.A511qpO/6ecOXSClh4EHsKJ/k.iuXU9wqxUhCds.6', 'Tanguy', 'François', '727, boulevard Eugène Levy\n43601 Clementboeuf', '23504', 'Bazin', '2023-05-25 13:47:25', 0, NULL),
(21, 'jean.juliette@faivre.fr', '[]', '$2y$13$O7iJsjRMrl2F4JhxDmqYpeyuQphniqFtynHwlYc57ZpFvAVea3IY2', 'Legrand', 'Sabine', '491, avenue Frédéric Lecomte\n19 832 LevequeBourg', '35215', 'Pires', '2023-05-25 13:47:25', 0, NULL),
(22, 'monnier.matthieu@free.fr', '[]', '$2y$13$TZwkGyeH4xD00Qf5NnJGAOlv0VNUFnrJsaai/XB66.hs9K9PTlKuu', 'Regnier', 'Isaac', 'place Alix Lopes\n34826 Marie', '30673', 'Bousquet', '2023-05-25 13:47:26', 0, NULL),
(23, 'olivie89@bertrand.net', '[]', '$2y$13$BQYt9S8EdDQJ5h8K6ZEcOO5cE/EP3XjbYlOmN6M29gUEkdRKtuppy', 'Roussel', 'Tristan', '896, place Stéphane Valette\n31182 Gonzalez-la-Forêt', '10863', 'Hebert', '2023-05-25 13:47:26', 0, NULL),
(24, 'user@gmail.com', '[\"ROLE_PRODUCT_ADMIN\"]', '$2y$13$VMGOhbBwwFPhw/re4I9HM.2PkgY/7cSySTXnixzVV00C/208SHwTC', 'Mejri', 'Ramzi', 'qsdqsdsq', '2082', 'Fouchana', '2023-05-25 14:57:32', 1, NULL);

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_3AF34668727ACA70` (`parent_id`);

--
-- Index pour la table `coupons`
--
ALTER TABLE `coupons`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_F56411183DDD47B7` (`coupons_types_id`);

--
-- Index pour la table `coupons_types`
--
ALTER TABLE `coupons_types`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `images`
--
ALTER TABLE `images`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_E01FBE6A6C8A81A9` (`products_id`);

--
-- Index pour la table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQ_E52FFDEEAEA34913` (`reference`),
  ADD KEY `IDX_E52FFDEE6D72B15C` (`coupons_id`),
  ADD KEY `IDX_E52FFDEE67B3B43D` (`users_id`);

--
-- Index pour la table `orders_details`
--
ALTER TABLE `orders_details`
  ADD PRIMARY KEY (`orders_id`,`products_id`),
  ADD KEY `IDX_835379F1CFFE9AD6` (`orders_id`),
  ADD KEY `IDX_835379F16C8A81A9` (`products_id`);

--
-- Index pour la table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_B3BA5A5AA21214B7` (`categories_id`);

--
-- Index pour la table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQ_1483A5E9E7927C74` (`email`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT pour la table `coupons`
--
ALTER TABLE `coupons`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `coupons_types`
--
ALTER TABLE `coupons_types`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `images`
--
ALTER TABLE `images`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1138;

--
-- AUTO_INCREMENT pour la table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=215;

--
-- AUTO_INCREMENT pour la table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `categories`
--
ALTER TABLE `categories`
  ADD CONSTRAINT `FK_3AF34668727ACA70` FOREIGN KEY (`parent_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `coupons`
--
ALTER TABLE `coupons`
  ADD CONSTRAINT `FK_F56411183DDD47B7` FOREIGN KEY (`coupons_types_id`) REFERENCES `coupons_types` (`id`);

--
-- Contraintes pour la table `images`
--
ALTER TABLE `images`
  ADD CONSTRAINT `FK_E01FBE6A6C8A81A9` FOREIGN KEY (`products_id`) REFERENCES `products` (`id`);

--
-- Contraintes pour la table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `FK_E52FFDEE67B3B43D` FOREIGN KEY (`users_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `FK_E52FFDEE6D72B15C` FOREIGN KEY (`coupons_id`) REFERENCES `coupons` (`id`);

--
-- Contraintes pour la table `orders_details`
--
ALTER TABLE `orders_details`
  ADD CONSTRAINT `FK_835379F16C8A81A9` FOREIGN KEY (`products_id`) REFERENCES `products` (`id`),
  ADD CONSTRAINT `FK_835379F1CFFE9AD6` FOREIGN KEY (`orders_id`) REFERENCES `orders` (`id`);

--
-- Contraintes pour la table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `FK_B3BA5A5AA21214B7` FOREIGN KEY (`categories_id`) REFERENCES `categories` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
