-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : jeu. 27 mars 2025 à 19:56
-- Version du serveur : 8.3.0
-- Version de PHP : 8.2.18

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `chicandchill`
--
CREATE DATABASE IF NOT EXISTS `chicandchill` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `chicandchill`;

-- --------------------------------------------------------

--
-- Structure de la table `admin_actions`
--

DROP TABLE IF EXISTS `admin_actions`;
CREATE TABLE IF NOT EXISTS `admin_actions` (
  `id` int NOT NULL AUTO_INCREMENT,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `categories`
--

DROP TABLE IF EXISTS `categories`;
CREATE TABLE IF NOT EXISTS `categories` (
  `id_categories` int NOT NULL AUTO_INCREMENT,
  `name_categories` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `desc_categories` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `gender` enum('femmes','enfants') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_categories`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `categories`
--

INSERT INTO `categories` (`id_categories`, `name_categories`, `desc_categories`, `gender`, `created_at`) VALUES
(1, 'Vestes et Manteaux', 'Manteaux, vestes et blousons pour femmes', 'femmes', '2025-02-16 15:51:12'),
(2, 'Tops et T-Shirts', 'T-shirts, tops et débardeurs', 'femmes', '2025-02-16 15:51:12'),
(3, 'Pantalons et Shorts', 'Jeans, leggings, pantalons habillés, shorts', 'femmes', '2025-02-16 15:51:12'),
(4, 'Jupes et Robes', 'Robes longues, courtes, jupes de toutes formes', 'femmes', '2025-02-16 15:51:12'),
(5, 'Accessoires', 'Bijoux, écharpes, ceintures et autres accessoires', 'femmes', '2025-02-16 15:51:12'),
(6, 'Pulls et Gilets', 'Gilets, pulls, cardigans', 'femmes', '2025-02-16 15:51:12'),
(7, 'Chaussures', 'Escarpins, baskets, bottines, sandales...', 'femmes', '2025-02-16 15:51:12'),
(8, 'Lingerie & Sous-vêtements', 'Soutiens-gorge, culottes, pyjamas', 'femmes', '2025-02-16 15:51:12'),
(9, 'Sportswear', 'Vêtements de sport et tenues confortables', 'femmes', '2025-02-16 15:51:12'),
(10, 'Vestes et Manteaux', 'Manteaux, vestes et blousons pour enfants', 'enfants', '2025-02-16 16:03:31'),
(11, 'Tops et T-Shirts', 'T-shirts, polos, débardeurs et tops', 'enfants', '2025-02-16 16:03:31'),
(12, 'Pantalons et shorts', 'Jeans, joggings, leggings, pantalons et shorts', 'enfants', '2025-02-16 16:03:31'),
(13, 'Jupes et Robes', 'Robes longues, courtes, jupes pour enfants', 'enfants', '2025-02-16 16:03:31'),
(14, 'Accessoires', 'Chapeaux, bonnets, écharpes et autres accessoires', 'enfants', '2025-02-16 16:03:31'),
(15, 'Pulls et Gilets', 'Gilets, sweats, pulls', 'enfants', '2025-02-16 16:03:31'),
(16, 'Chaussures', 'Baskets, bottes, sandales, chaussures de sport', 'enfants', '2025-02-16 16:03:31'),
(17, 'Sportswear', 'Vêtements de sport et tenues confortables', 'enfants', '2025-02-16 16:03:31');

-- --------------------------------------------------------

--
-- Structure de la table `contact_messages`
--

DROP TABLE IF EXISTS `contact_messages`;
CREATE TABLE IF NOT EXISTS `contact_messages` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `message` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `reply_body` text COLLATE utf8mb4_unicode_ci,
  `source` enum('magasin','location','evenements') COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `status` enum('unread','read','replied') COLLATE utf8mb4_unicode_ci DEFAULT 'unread',
  `replied_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `contact_messages`
--

INSERT INTO `contact_messages` (`id`, `name`, `email`, `message`, `reply_body`, `source`, `created_at`, `status`, `replied_at`) VALUES
(1, 'Mel', 'meltest@example.com', 'message test', NULL, 'evenements', '2025-02-13 21:58:22', 'unread', NULL),
(3, 'Test1', 'test1@example.com', 'Message 1', NULL, 'magasin', '2025-03-20 19:58:19', 'unread', NULL),
(4, 'Test2', 'test2@example.com', 'Message 2', NULL, 'location', '2025-03-20 19:58:19', 'unread', NULL),
(5, 'Mélanie Bethermat', 'melaniebethermat@gmail.com', 'test', NULL, 'evenements', '2025-03-21 17:40:03', 'unread', NULL),
(6, 'Mélanie Bethermat', 'melaniebethermat@gmail.com', 'message test encore', NULL, 'evenements', '2025-03-21 18:11:09', 'unread', NULL),
(7, 'Mélanie Bethermat', 'melaniebethermat@gmail.com', 'message retest', 'Réponse test', 'evenements', '2025-03-21 18:20:14', 'replied', '2025-03-23 17:19:14'),
(8, 'test mel', 'melaniebethermat@testmel.com', 'test', NULL, 'evenements', '2025-03-26 13:39:20', 'unread', NULL);

-- --------------------------------------------------------

--
-- Structure de la table `events`
--

DROP TABLE IF EXISTS `events`;
CREATE TABLE IF NOT EXISTS `events` (
  `id` int NOT NULL AUTO_INCREMENT,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `date_event` date NOT NULL,
  `location` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT 'placeholder.jpg',
  `status` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'inactive',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=36 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `events`
--

INSERT INTO `events` (`id`, `title`, `description`, `date_event`, `location`, `created_at`, `image`, `status`) VALUES
(1, 'Gala de boxe 2024', 'Habillage des Miss présentant les rounds', '2024-10-21', 'Charleville-Mézières', '2025-02-06 14:34:19', 'Gala de boxe 2024/galaBoxe5.jpg', 'inactive'),
(2, 'Soirée CHIC&CHILL à la Guinguette', 'Soirée Chic&Chill à la Guiguette', '2024-07-14', 'Charleville-Mézières', '2025-02-10 12:11:59', 'Soirée CHIC&CHILL à la Guinguette/soireeCCGuinguette5.jpg', 'inactive'),
(3, 'Shooting photos', 'Un shooting photo dans notre showroom privée', '2024-06-01', 'Charleville-Mézières', '2025-02-10 12:11:59', 'Shooting photos/shootingPhotos13.jpg', 'inactive'),
(4, 'Collab\' avec la Brasserie', 'Collaboration avec le restaurant la Brasserie ', '2024-09-14', 'Charleville-Mézières', '2025-02-10 12:13:15', 'Collab\' avec la Brasserie/collabeBrasserie1.jpg', 'inactive'),
(5, 'Fashion Champ 2024', 'Un événement exceptionnel mettant à l’honneur les commerçants et créateurs Ardennais', '2024-12-06', 'Charleville-Mézières', '2025-02-11 19:22:19', 'Fashion Champ 2024/fashionChamp2.jpg', 'inactive'),
(34, 'Encore un test', 'test', '2025-04-03', 'Charleville-Mézières', '2025-03-19 15:02:54', '67dd646d10b43_haut_printemps.jpg', 'active'),
(35, 'test', 'test', '2025-03-31', 'Charleville-Mézières', '2025-03-21 13:24:32', '67dd6890e29e3_robe_soiree.jpg', 'active');

-- --------------------------------------------------------

--
-- Structure de la table `event_images`
--

DROP TABLE IF EXISTS `event_images`;
CREATE TABLE IF NOT EXISTS `event_images` (
  `id` int NOT NULL AUTO_INCREMENT,
  `event_id` int NOT NULL,
  `image_url` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `type` enum('image','video') COLLATE utf8mb4_unicode_ci DEFAULT 'image',
  PRIMARY KEY (`id`),
  KEY `event_id` (`event_id`)
) ENGINE=InnoDB AUTO_INCREMENT=45 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `event_images`
--

INSERT INTO `event_images` (`id`, `event_id`, `image_url`, `created_at`, `type`) VALUES
(1, 1, 'Gala de boxe 2024/galaBoxe1.jpg', '2025-02-12 16:58:40', 'image'),
(2, 1, 'Gala de boxe 2024/galaBoxe2.jpg', '2025-02-12 16:58:40', 'image'),
(3, 1, 'Gala de boxe 2024/galaBoxe3.jpg', '2025-02-12 16:58:40', 'image'),
(4, 1, 'Gala de boxe 2024/galaBoxe4.jpg', '2025-02-12 16:58:40', 'image'),
(5, 1, 'Gala de boxe 2024/galaBoxe5.jpg', '2025-02-12 16:58:40', 'image'),
(6, 1, 'Gala de boxe 2024/galaBoxe6.jpg', '2025-02-12 16:58:40', 'image'),
(7, 1, 'Gala de boxe 2024/galaBoxe7.jpg', '2025-02-12 16:58:40', 'image'),
(8, 2, 'Soirée CHIC&CHILL à la Guinguette/soireeCCGuinguette5.jpg', '2025-02-12 16:58:40', 'image'),
(9, 3, 'Shooting photos/shootingPhotos1.jpg', '2025-02-12 16:58:40', 'image'),
(10, 3, 'Shooting photos/shootingPhotos10.jpg', '2025-02-12 16:58:40', 'image'),
(11, 3, 'Shooting photos/shootingPhotos11.jpg', '2025-02-12 16:58:40', 'image'),
(12, 3, 'Shooting photos/shootingPhotos12.jpg', '2025-02-12 16:58:40', 'image'),
(13, 3, 'Shooting photos/shootingPhotos13.jpg', '2025-02-12 16:58:40', 'image'),
(14, 3, 'Shooting photos/shootingPhotos14.jpg', '2025-02-12 16:58:40', 'image'),
(15, 3, 'Shooting photos/shootingPhotos15.jpg', '2025-02-12 16:58:40', 'image'),
(16, 3, 'Shooting photos/shootingPhotos16.jpg', '2025-02-12 16:58:40', 'image'),
(17, 3, 'Shooting photos/shootingPhotos17.jpg', '2025-02-12 16:58:40', 'image'),
(18, 4, 'Collab\' avec la Brasserie/collabeBrasserie1.jpg', '2025-02-12 16:58:40', 'image'),
(19, 4, 'Collab\' avec la Brasserie/collabeBrasserie11.jpg', '2025-02-12 16:58:40', 'image'),
(20, 4, 'Collab\' avec la Brasserie/collabeBrasserie2.jpg', '2025-02-12 16:58:40', 'image'),
(21, 4, 'Collab\' avec la Brasserie/collabeBrasserie3.jpg', '2025-02-12 16:58:40', 'image'),
(22, 4, 'Collab\' avec la Brasserie/collabeBrasserie4.jpg', '2025-02-12 16:58:40', 'image'),
(23, 4, 'Collab\' avec la Brasserie/collabeBrasserie5.jpg', '2025-02-12 16:58:40', 'image'),
(24, 4, 'Collab\' avec la Brasserie/collabeBrasserie6.jpg', '2025-02-12 16:58:40', 'image'),
(25, 4, 'Collab\' avec la Brasserie/collabeBrasserie7.jpg', '2025-02-12 16:58:40', 'image'),
(26, 4, 'Collab\' avec la Brasserie/collabeBrasserie8.jpg', '2025-02-12 16:58:41', 'image'),
(27, 4, 'Collab\' avec la Brasserie/collabeBrasserie9.jpg', '2025-02-12 16:58:41', 'image'),
(28, 5, 'Fashion Champ 2024/fashionChamp2.jpg', '2025-02-12 16:58:41', 'image'),
(29, 5, 'Fashion Champ 2024/fashionChamp7.jpg', '2025-02-12 16:58:41', 'image'),
(30, 4, 'Collab avec la Brasserie/collabeBrasserie10.mp4', '2025-02-17 01:02:57', 'video'),
(31, 5, 'Fashion Champ 2024/fashionChamp1.mp4', '2025-02-17 01:02:57', 'video'),
(32, 5, 'Fashion Champ 2024/fashionChamp3.mp4', '2025-02-17 01:02:57', 'video'),
(33, 5, 'Fashion Champ 2024/fashionChamp4.mp4', '2025-02-17 01:02:57', 'video'),
(34, 5, 'Fashion Champ 2024/fashionChamp5.mp4', '2025-02-17 01:02:57', 'video'),
(35, 5, 'Fashion Champ 2024/fashionChamp6.mp4', '2025-02-17 01:02:57', 'video'),
(36, 2, 'Soirée CHIC&CHILL à la Guinguette/soireeCCGuinguette1.mp4', '2025-02-17 01:02:57', 'video'),
(37, 2, 'Soirée CHIC&CHILL à la Guinguette/soireeCCGuinguette2.mp4', '2025-02-17 01:02:57', 'video'),
(38, 2, 'Soirée CHIC&CHILL à la Guinguette/soireeCCGuinguette3.mp4', '2025-02-17 01:02:57', 'video'),
(39, 2, 'Soirée CHIC&CHILL à la Guinguette/soireeCCGuinguette4.mp4', '2025-02-17 01:02:57', 'video'),
(40, 2, 'Soirée CHIC&CHILL à la Guinguette/soireeCCGuinguette6.mp4', '2025-02-17 01:02:57', 'video'),
(41, 3, 'Shooting photos/shootingPhotos21.mp4', '2025-02-24 13:49:22', 'video'),
(42, 3, 'Shooting photos/shootingPhotos19.jpg', '2025-02-24 13:49:22', 'image'),
(43, 3, 'Shooting photos/shootingPhotos20.jpg', '2025-02-24 13:49:22', 'image'),
(44, 3, 'Shooting photos/shootingPhotos1.jpg', '2025-02-24 13:49:22', 'image');

-- --------------------------------------------------------

--
-- Structure de la table `event_orders`
--

DROP TABLE IF EXISTS `event_orders`;
CREATE TABLE IF NOT EXISTS `event_orders` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `total_price` decimal(10,2) NOT NULL,
  `status` enum('pending','confirmed','shipped','cancelled') COLLATE utf8mb4_unicode_ci DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `event_orders`
--

INSERT INTO `event_orders` (`id`, `user_id`, `total_price`, `status`, `created_at`) VALUES
(1, 2, 150.00, 'confirmed', '2025-02-10 18:00:00'),
(2, 3, 120.00, 'pending', '2025-02-10 18:00:00');

-- --------------------------------------------------------

--
-- Structure de la table `event_packs`
--

DROP TABLE IF EXISTS `event_packs`;
CREATE TABLE IF NOT EXISTS `event_packs` (
  `id` int NOT NULL AUTO_INCREMENT,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `price` decimal(10,2) NOT NULL DEFAULT '0.00',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `duration` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `included` text COLLATE utf8mb4_unicode_ci,
  `status` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'inactive',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `event_packs`
--

INSERT INTO `event_packs` (`id`, `title`, `description`, `price`, `created_at`, `duration`, `included`, `status`) VALUES
(1, 'Pack VIP Gala', 'Accès VIP avec places aux premiers rangs et cocktail inclus.', 150.00, '2025-02-10 17:59:13', '4', 'Accès VIP + Cocktail de bienvenue', 'active'),
(2, 'Pack Standard Gala', 'Accès standard avec place numérotée.', 80.00, '2025-02-10 17:59:13', '3', 'Accès standard avec place numérotée', 'active'),
(3, 'Pack Premium Soirée', 'Entrée + Cocktail de bienvenue + Accès à l’espace lounge.', 120.00, '2025-02-10 17:59:13', '5', 'Entrée + Cocktail de bienvenue + Accès à l’espace VIP', 'active'),
(4, 'Pack Standard Soirée', 'Entrée simple à la soirée Chic & Chill.', 60.00, '2025-02-10 17:59:13', '3', 'Entrée simple à la soirée Chic & Chill', 'active'),
(5, 'Pack Shooting Pro', 'Shooting avec photographe pro + 5 photos retouchées.', 200.00, '2025-02-10 17:59:13', '2', 'Shooting avec photographe pro + 5 photos retouchées', 'active'),
(6, 'Pack Shooting Basic', 'Shooting simple avec 2 photos incluses.', 100.00, '2025-02-10 17:59:13', '1', 'Shooting simple avec 2 photos incluses', 'active'),
(7, 'Dîner à la Brasserie', 'Menu gastronomique spécial en collaboration avec la Brasserie.', 90.00, '2025-02-10 17:59:13', '3', 'Menu gastronomique spécial en collaboration avec la Brasserie', 'active');

-- --------------------------------------------------------

--
-- Structure de la table `event_reservations`
--

DROP TABLE IF EXISTS `event_reservations`;
CREATE TABLE IF NOT EXISTS `event_reservations` (
  `id` int NOT NULL AUTO_INCREMENT,
  `customer_type` enum('particulier','entreprise') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'particulier',
  `company_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `siret` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` text COLLATE utf8mb4_unicode_ci,
  `customer_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `event_type` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `participants` int DEFAULT '1',
  `services` text COLLATE utf8mb4_unicode_ci,
  `comments` text COLLATE utf8mb4_unicode_ci,
  `event_id` int DEFAULT NULL,
  `status` enum('pending','confirmed','cancelled') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `event_id` (`event_id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `event_reservations`
--

INSERT INTO `event_reservations` (`id`, `customer_type`, `company_name`, `siret`, `address`, `customer_name`, `email`, `phone`, `event_type`, `participants`, `services`, `comments`, `event_id`, `status`, `created_at`) VALUES
(2, 'particulier', NULL, NULL, NULL, 'Mélanie Bethermat', 'melaniebethermat@gmail.com', '0669991945', 'Anniversaire', 10, 'Restauration, Animation', '', 5, 'cancelled', '2025-02-14 19:03:25'),
(3, 'particulier', NULL, NULL, NULL, 'Mélanie Bethermat', 'melaniebethermat@gmail.com', '0669991945', 'Mariage', 150, 'Restauration, Animation, Décoration, Photographe', '', NULL, 'confirmed', '2025-03-21 14:47:20'),
(4, 'entreprise', 'Entreprise fictive', '123456001', '23 Rue fictive', 'Mélanie Bethermat', 'melaniebethermat@gmail.com', '0669991945', 'Conférence', 20, '', '', NULL, 'pending', '2025-03-21 14:58:56'),
(5, 'particulier', NULL, NULL, NULL, 'Mélanie Bethermat', 'melaniebethermat@gmail.com', '0669991945', 'Conférence', 10, '', '', NULL, 'pending', '2025-03-21 17:27:38'),
(6, 'particulier', NULL, NULL, NULL, 'Bethermat Mélanie', 'melaniebethermat@gmail.com', '0669991945', 'Mariage', 30, '', '', NULL, 'pending', '2025-03-21 17:29:11'),
(7, 'particulier', NULL, NULL, NULL, 'test', 'test@chicandchill.com', '0213598672', 'Mariage', 20, '', '', NULL, 'pending', '2025-03-21 17:34:56'),
(8, 'particulier', NULL, NULL, NULL, 'Mélanie Bethermat', 'melaniebethermat@gmail.com', '0669991945', 'Mariage', 90, '', '', NULL, 'pending', '2025-03-21 17:35:40'),
(9, 'particulier', NULL, NULL, NULL, 'Mélanie Bethermat', 'melaniebethermat@gmail.com', '0669991945', 'Soirée d&#039;entreprise', 30, '', '', NULL, 'cancelled', '2025-03-21 18:20:47'),
(10, 'entreprise', 'entreprise test', '12345600145590', '10 rue inventé', 'Mélanie Bethermat', 'melaniebethermat@gmail.com', '0669991945', 'Soirée d&#039;entreprise', 30, 'Restauration, Animation, Décoration, Photographe', '', NULL, 'pending', '2025-03-27 18:19:28');

-- --------------------------------------------------------

--
-- Structure de la table `newsletter_subscribers`
--

DROP TABLE IF EXISTS `newsletter_subscribers`;
CREATE TABLE IF NOT EXISTS `newsletter_subscribers` (
  `id` int NOT NULL AUTO_INCREMENT,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `newsletter_subscribers`
--

INSERT INTO `newsletter_subscribers` (`id`, `email`, `created_at`) VALUES
(1, 'mailtest@example.com', '2025-02-13 23:19:28'),
(2, 'meltest@example.com', '2025-02-14 14:48:50'),
(4, 'nouveau_test@example.com', '2025-03-21 18:35:42'),
(9, 'test3@example.com', '2025-03-21 19:04:13');

-- --------------------------------------------------------

--
-- Structure de la table `notifications`
--

DROP TABLE IF EXISTS `notifications`;
CREATE TABLE IF NOT EXISTS `notifications` (
  `id` int NOT NULL AUTO_INCREMENT,
  `message` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('unread','read') COLLATE utf8mb4_unicode_ci DEFAULT 'unread',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `notifications`
--

INSERT INTO `notifications` (`id`, `message`, `status`, `created_at`) VALUES
(1, 'Exemple de notification', 'read', '2025-03-28 22:31:46'),
(2, 'Nouveau message de Mélanie Bethermat via evenements', 'unread', '2025-03-21 18:20:14'),
(3, 'Nouvelle réservation d\'événement par Mélanie Bethermat (Soirée d&#039;entreprise)', 'unread', '2025-03-21 18:20:47'),
(4, 'Nouvelle réservation de pack par Mélanie Bethermat (ID: 5)', 'unread', '2025-03-21 18:21:10'),
(5, 'Nouvel abonné à la newsletter : test3@example.com', 'unread', '2025-03-21 19:04:13'),
(6, 'Réponse envoyée à Mélanie Bethermat', 'unread', '2025-03-23 17:05:17'),
(7, 'Réponse envoyée à Mélanie Bethermat', 'read', '2025-03-23 17:10:57'),
(8, 'Réponse envoyée à Mélanie Bethermat', 'read', '2025-03-23 17:19:14'),
(9, 'Nouveau message de test mel via evenements', 'unread', '2025-03-26 13:39:20'),
(10, 'Nouvelle réservation d\'événement par Mélanie Bethermat (Soirée d&#039;entreprise)', 'unread', '2025-03-27 18:19:28');

-- --------------------------------------------------------

--
-- Structure de la table `order_items`
--

DROP TABLE IF EXISTS `order_items`;
CREATE TABLE IF NOT EXISTS `order_items` (
  `id` int NOT NULL AUTO_INCREMENT,
  `order_id` int NOT NULL,
  `product_id` int NOT NULL,
  `quantity` int NOT NULL,
  `price` decimal(10,2) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `order_id` (`order_id`),
  KEY `product_id` (`product_id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `outfits_suggestions`
--

DROP TABLE IF EXISTS `outfits_suggestions`;
CREATE TABLE IF NOT EXISTS `outfits_suggestions` (
  `id` int NOT NULL AUTO_INCREMENT,
  `product_id` int NOT NULL,
  `outfit_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `accessories` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `status` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'inactive',
  PRIMARY KEY (`id`),
  KEY `outfits_suggestions_ibfk_1` (`product_id`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `outfits_suggestions`
--

INSERT INTO `outfits_suggestions` (`id`, `product_id`, `outfit_name`, `accessories`, `created_at`, `status`) VALUES
(1, 14, 'Robe pull élégante', 'Écharpe en laine, bottines, ceinture', '2025-03-19 14:18:02', 'active'),
(2, 15, 'Robe pull chic', 'Ceinture en cuir, collier en argent', '2025-03-19 14:18:29', 'active'),
(3, 12, 'Robe noire tendance', 'Bracelet en perles, sac à main', '2025-03-19 14:18:53', 'active'),
(4, 8, 'Robe plissée', 'Boucles d’oreilles dorées, pochette', '2025-02-17 00:50:34', 'active'),
(5, 9, 'Robe marron', 'Chaussures assorties, chapeau', '2025-02-17 00:50:34', 'active'),
(6, 10, 'Robe mi-longue', 'Collier en or, escarpins noirs', '2025-02-17 00:50:34', 'active'),
(7, 11, 'Robe d\'été fleurie', 'Chapeau de paille, sandales', '2025-02-17 00:50:34', 'active'),
(8, 12, 'Robe en velours', 'Collants en laine, bottes', '2025-02-17 00:50:34', 'active');

-- --------------------------------------------------------

--
-- Structure de la table `pack_reservations`
--

DROP TABLE IF EXISTS `pack_reservations`;
CREATE TABLE IF NOT EXISTS `pack_reservations` (
  `id` int NOT NULL AUTO_INCREMENT,
  `customer_type` enum('particulier','entreprise') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'particulier',
  `company_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `siret` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` text COLLATE utf8mb4_unicode_ci,
  `customer_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `pack_id` int NOT NULL,
  `services` text COLLATE utf8mb4_unicode_ci,
  `comments` text COLLATE utf8mb4_unicode_ci,
  `status` enum('pending','confirmed','cancelled') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `pack_id` (`pack_id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `pack_reservations`
--

INSERT INTO `pack_reservations` (`id`, `customer_type`, `company_name`, `siret`, `address`, `customer_name`, `email`, `phone`, `pack_id`, `services`, `comments`, `status`, `created_at`) VALUES
(1, 'particulier', NULL, NULL, NULL, 'mel', 'melaniebethermat@gmail.com', '0650147562', 4, 'Animation', '', 'confirmed', '2025-03-20 22:44:28'),
(2, 'particulier', NULL, NULL, NULL, 'Mélanie Bethermat', 'melaniebethermat@gmail.com', '0669991945', 6, '', '', 'pending', '2025-03-10 13:42:00'),
(3, 'entreprise', 'entreprise test', '12345600145590', '10 Rue test 08000 Charleville_Mézières ', 'Mélanie Bethermat', 'melaniebethermat@gmail.com', '0669991945', 1, 'Photographe', '', 'confirmed', '2025-03-21 14:57:42'),
(4, 'particulier', NULL, NULL, NULL, 'Mélanie Bethermat', 'melaniebethermat@gmail.com', '0669991945', 3, '', '', 'confirmed', '2025-03-21 18:13:31'),
(5, 'particulier', NULL, NULL, NULL, 'Mélanie Bethermat', 'melaniebethermat@gmail.com', '0669991945', 5, 'Animation, Décoration', '', 'confirmed', '2025-03-21 18:21:10');

-- --------------------------------------------------------

--
-- Structure de la table `password_resets`
--

DROP TABLE IF EXISTS `password_resets`;
CREATE TABLE IF NOT EXISTS `password_resets` (
  `id` int NOT NULL AUTO_INCREMENT,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `payments`
--

DROP TABLE IF EXISTS `payments`;
CREATE TABLE IF NOT EXISTS `payments` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `order_id` int DEFAULT NULL,
  `rental_id` int DEFAULT NULL,
  `amount` decimal(10,2) NOT NULL,
  `status` enum('pending','paid','failed') COLLATE utf8mb4_unicode_ci DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `order_id` (`order_id`),
  KEY `rental_id` (`rental_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `products`
--

DROP TABLE IF EXISTS `products`;
CREATE TABLE IF NOT EXISTS `products` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `price` decimal(10,2) NOT NULL,
  `discount_price` decimal(10,2) DEFAULT NULL,
  `stock` int NOT NULL DEFAULT '10',
  `category` enum('vente','location') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `brand` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `id_categories` int DEFAULT NULL,
  `id_ss_categories` int DEFAULT NULL,
  `gender` enum('femmes','enfants') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `gender_child` enum('fille','garçon','mixte') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `code_ena` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `size` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `image` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'placeholder.jpg',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `is_rentable` enum('oui','non','','') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'non',
  PRIMARY KEY (`id`),
  KEY `fk_products_categories` (`id_categories`),
  KEY `fk_products_ss_categories` (`id_ss_categories`)
) ENGINE=InnoDB AUTO_INCREMENT=31 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `products`
--

INSERT INTO `products` (`id`, `name`, `description`, `price`, `discount_price`, `stock`, `category`, `brand`, `id_categories`, `id_ss_categories`, `gender`, `gender_child`, `code_ena`, `size`, `image`, `created_at`, `updated_at`, `is_rentable`) VALUES
(5, 'Robe pull', 'Robe blanche à pompoms, col roulé, motif triangle', 15.00, NULL, 1, '', 'EMI MAJÖLY PARIS', 4, 67, 'femmes', '', '0436902747100', 'TU', 'uploads/produits/robe_blanche_pompom.jpg', '2025-02-17 00:50:34', '2025-02-17 00:50:34', 'non'),
(6, 'Robe pull', 'Robe grise à pompoms, col roulé, motif triangle', 15.00, NULL, 1, '', 'EMI MAJÖLY PARIS', 4, 67, 'femmes', '', '0436902747097', 'TU', 'uploads/produits/robe_pompom_grise.jpg', '2025-02-17 00:50:34', '2025-02-17 00:50:34', 'non'),
(7, 'Robe pull', 'Robe noire à pompoms, col roulé, motif triangle', 15.00, NULL, 1, '', 'EMI MAJÖLY PARIS', 4, 67, 'femmes', '', '0436902747109', 'TU', 'uploads/produits/robe_pompom_noire.jpg', '2025-02-17 00:50:34', '2025-02-17 00:50:34', 'non'),
(8, 'Robe', 'Robe noir sans manches, cintrée et effet plissé sur le côté, dos légèrement échancré, zip dans le dos', 15.00, NULL, 1, '', '', 4, 66, 'femmes', '', '0436902747091', 'T 36/38', 'uploads/produits/robe_plissee_noire.jpg', '2025-02-17 00:50:34', '2025-02-17 00:50:34', 'non'),
(9, 'Robe', 'Robe sans manches, col rond, couleur marron, imprimé cœur, doublé', 15.00, NULL, 1, '', 'AX PARIS12', 4, 66, 'femmes', '', '0436902747094', 'T 36/38', 'uploads/produits/robe_coeur_marron.jpg', '2025-02-17 00:50:34', '2025-02-17 00:50:34', 'non'),
(10, 'Robe', 'Robe mi-longue, col en V, manche longue, doublure, fermeture sur le côté', 15.00, NULL, 1, '', 'MANGO', 4, 66, 'femmes', '', '0436902747106', 'T M', 'uploads/robe_mi_longue_noire.jpg', '2025-02-17 00:50:34', '2025-02-17 00:50:34', 'non'),
(11, 'Robe', 'Robe d\'été, fleurale, centré à la taille, encolure en V', 15.00, NULL, 10, '', 'SHEIN', 4, 66, 'femmes', '', '0436902747103', 'T38', 'uploads/robe_ete_fleurie.jpg', '2025-02-17 00:50:34', '2025-02-17 00:50:34', 'non'),
(12, 'Robe', 'Robe en velours, échancré, manche longue', 15.00, NULL, 1, '', 'SBETRO', 4, 66, 'femmes', '', '0436902747067', 'T S', 'uploads/robe_velours.jpg', '2025-02-17 00:50:34', '2025-02-17 00:50:34', 'non'),
(13, 'Robe', 'robe imprimé zébré marron noir, doublure noir', 15.00, NULL, 1, '', 'TALLY WEIJL', 4, 66, 'femmes', '', '0436902747070', 'T M', 'uploads/robe_zebre.jpg', '2025-02-17 00:50:34', '2025-02-17 00:50:34', 'non'),
(14, 'Robe', 'robe sur-col rayé, droite, manche courte', 15.00, NULL, 1, '', 'CKH CLOCK HOUSE', 4, 66, 'femmes', '', '0436902747088', 'T L', 'uploads/robe_ecoliere.jpg', '2025-02-17 00:50:34', '2025-02-17 00:50:34', 'non'),
(15, 'Robe pull', 'Robe pull, couleur cappucino à pompoms,col roulé, motif triangle', 15.00, NULL, 1, '', 'EMI MAJÖLY PARIS', 4, 66, 'femmes', '', '0436902747076', 'TU', 'uploads/cappuccino_pompoms.jpg', '2025-02-17 00:50:34', '2025-02-17 00:50:34', 'non'),
(16, 'Robe', 'Robe sans manche, col rond, à froufrou, fermeture sur le côté', 15.00, NULL, 10, '', 'CAROLL', 4, 13, 'femmes', '', '0436902747064', 'T 38', 'uploads/robe_froufrou.jpg', '2025-02-17 00:50:34', '2025-02-17 00:50:34', 'non'),
(17, 'Robe', 'Robe noir, bustier, dentelle, avec ou sans bretelle, doublée, centrée a la taille, bas evasée', 15.00, NULL, 1, '', 'H&M', 4, 66, 'femmes', '', '0436902747073', 'T 38', 'uploads/corset_dentelle.jpg', '2025-02-17 00:50:34', '2025-02-17 00:50:34', 'non'),
(18, 'Gilet', 'Gilet long, manche longue, col drapé, poche', 10.00, NULL, 10, '', 'MISS CHARM PARIS', 6, 15, 'femmes', '', '0436902747419', 'TU', 'uploads/gilet_long_creme.jpg', '2025-02-17 00:50:34', '2025-02-17 00:50:34', 'non'),
(19, 'Haut', 'Sans manche, court, noir, texturé, petite fermeture à l\'arrière', 5.00, NULL, 10, '', 'CACHE CACHE', 2, 11, 'femmes', '', '0436902747307', 'T 38', 'uploads/haut_noir.jpg', '2025-02-17 00:50:34', '2025-02-17 00:50:34', 'non'),
(20, 'Gilet', 'Gilet manche longue, court, grosse maille, 3 boutons, col V échancré', 8.00, NULL, 10, '', '', 6, 15, 'femmes', '', '0436902747340', 'T M', 'uploads/gilet_court_bleu.jpg', '2025-02-17 00:50:34', '2025-02-17 00:50:34', 'non'),
(21, 'Pull', 'Pull long, manche longue, col rond, grosse maille', 10.00, NULL, 10, '', 'VERO MODA', 6, 15, 'femmes', '', '0436902747376', 'T XS', 'uploads/pull_long.jpg', '2025-02-17 00:50:34', '2025-02-17 00:50:34', 'non'),
(22, 'Chemisier', 'Bleu marine, col et manchette blanc, poche', 10.00, NULL, 10, '', 'ZARA BASIQUE', 2, 11, 'femmes', '', '0436902747370', 'T S', 'uploads/chemisier_bleu.jpg', '2025-02-17 00:50:34', '2025-02-17 00:50:34', 'non'),
(23, 'Chemisier', 'Chemise pêche, long à l\'arrière, effet oversize', 15.00, NULL, 10, '', 'AKOZ', 2, 11, 'femmes', '', '0436902747403', 'T S', 'uploads/chemisier_peche.jpg', '2025-02-17 00:50:34', '2025-02-17 00:50:34', 'non'),
(24, 'Chemisier', 'Chemise blanche, long à l\'arrière, effet oversize', 15.00, NULL, 10, '', 'AKOZ', 2, 11, 'femmes', '', '0436902747400', 'T XL', 'uploads/chemisier_blanc.jpg', '2025-02-17 00:50:34', '2025-02-17 00:50:34', 'non'),
(25, 'Veste', 'Blazer, 2 boutons, fausses poches, mi-court, manche longue', 8.00, NULL, 10, '', 'FRENCH CONNEXION', 1, 10, 'femmes', '', '0436902747407', 'T 36', 'uploads/blazer_noir.jpg', '2025-02-17 00:50:34', '2025-02-17 00:50:34', 'non'),
(26, 'Veste', 'Noir rayé à bouton, courte, manche longue, petite ceinture à l\'arrière', 8.00, NULL, 10, '', 'KIABI WOMAN', 1, 10, 'femmes', '', '0436902747410', 'T 38', 'uploads/veste_rayee.jpg', '2025-02-17 00:50:34', '2025-02-17 00:50:34', 'non'),
(27, 'Jupe en simili cuir', 'Simili cuir, courte, évasée', 10.00, NULL, 10, '', 'SOFTY', 4, 12, 'femmes', '', '0436902747458', 'T S', 'uploads/jupe_simili.jpg', '2025-02-17 00:50:34', '2025-02-17 00:50:34', 'non'),
(28, 'Jean', 'Effet coupé', 10.00, NULL, 10, '', '', 3, 13, 'femmes', '', '0436902747013', 'T 36', 'uploads/jean_bleu.jpg', '2025-02-17 00:50:34', '2025-02-17 00:50:34', 'non'),
(29, 'Manteau', 'Hiver mi-long, manche longue, capuche, élastique à l\'intérieur', 40.00, NULL, 10, '', 'ONLY', 1, 14, 'femmes', '', '0436902747449', 'T XS', 'uploads/manteau_hiver.jpg', '2025-02-17 00:50:34', '2025-02-17 00:50:34', 'non'),
(30, 'Manteau', 'Hiver long, manche longue, doux, ceinture', 40.00, NULL, 10, '', 'CC FASHION PARIS', 1, 14, 'femmes', '', '0436902747452', 'T XL', 'uploads/manteau_long.jpg', '2025-02-17 00:50:34', '2025-02-17 00:50:34', 'non');

-- --------------------------------------------------------

--
-- Structure de la table `rentals`
--

DROP TABLE IF EXISTS `rentals`;
CREATE TABLE IF NOT EXISTS `rentals` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `product_id` int NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `status` enum('pending','confirmed','returned') COLLATE utf8mb4_unicode_ci DEFAULT 'pending',
  `total_price` decimal(10,2) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `product_id` (`product_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `reviews`
--

DROP TABLE IF EXISTS `reviews`;
CREATE TABLE IF NOT EXISTS `reviews` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `event_id` int DEFAULT NULL,
  `product_id` int DEFAULT NULL,
  `rating` int DEFAULT NULL,
  `comment` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `event_id` (`event_id`),
  KEY `product_id` (`product_id`)
) ;

-- --------------------------------------------------------

--
-- Structure de la table `ss_categories`
--

DROP TABLE IF EXISTS `ss_categories`;
CREATE TABLE IF NOT EXISTS `ss_categories` (
  `id_ss_categories` int NOT NULL AUTO_INCREMENT,
  `name_ss_categories` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `id_categories` int NOT NULL,
  `gender` enum('femmes','enfants') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_ss_categories`),
  KEY `fk_ss_categories_categorie` (`id_categories`)
) ENGINE=InnoDB AUTO_INCREMENT=68 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `ss_categories`
--

INSERT INTO `ss_categories` (`id_ss_categories`, `name_ss_categories`, `id_categories`, `gender`, `created_at`) VALUES
(1, 'Vestes', 1, 'femmes', '2025-02-16 22:01:33'),
(2, 'Manteaux', 1, 'femmes', '2025-02-16 22:01:33'),
(3, 'T-shirts', 2, 'femmes', '2025-02-16 22:01:33'),
(4, 'Chemises', 2, 'femmes', '2025-02-16 22:01:33'),
(5, 'Chemisiers', 2, 'femmes', '2025-02-16 22:01:33'),
(6, 'Blouses', 2, 'femmes', '2025-02-16 22:01:33'),
(7, 'Débardeurs', 2, 'femmes', '2025-02-16 22:01:33'),
(8, 'Crop-tops', 2, 'femmes', '2025-02-16 22:01:33'),
(9, 'Body', 2, 'femmes', '2025-02-16 22:01:33'),
(10, 'Baskets', 7, 'femmes', '2025-02-16 22:01:33'),
(11, 'Escarpins', 7, 'femmes', '2025-02-16 22:01:33'),
(12, 'Boots', 7, 'femmes', '2025-02-16 22:01:33'),
(13, 'Sandales', 7, 'femmes', '2025-02-16 22:01:33'),
(14, 'Mules', 7, 'femmes', '2025-02-16 22:01:33'),
(15, 'Bottes', 7, 'femmes', '2025-02-16 22:01:33'),
(16, 'Pantalons', 3, 'femmes', '2025-02-16 22:01:33'),
(17, 'Pantacourts', 3, 'femmes', '2025-02-16 22:01:33'),
(18, 'Shorts', 3, 'femmes', '2025-02-16 22:01:33'),
(19, 'Bermudas', 3, 'femmes', '2025-02-16 22:01:33'),
(20, 'Jeans', 3, 'femmes', '2025-02-16 22:01:33'),
(21, 'Chinos', 3, 'femmes', '2025-02-16 22:01:33'),
(22, 'Leggings', 3, 'femmes', '2025-02-16 22:01:33'),
(23, 'Collants', 3, 'femmes', '2025-02-16 22:01:33'),
(24, 'Pulls', 6, 'femmes', '2025-02-16 22:01:33'),
(25, 'Gilets', 6, 'femmes', '2025-02-16 22:01:33'),
(26, 'Sweats', 6, 'femmes', '2025-02-16 22:01:33'),
(27, 'Sous-pulls', 6, 'femmes', '2025-02-16 22:01:33'),
(28, 'Bonnets', 5, 'femmes', '2025-02-16 22:01:33'),
(29, 'Écharpes', 5, 'femmes', '2025-02-16 22:01:33'),
(30, 'Gants', 5, 'femmes', '2025-02-16 22:01:33'),
(31, 'Joggings', 8, 'femmes', '2025-02-16 22:01:33'),
(32, 'Vestes de sport', 8, 'femmes', '2025-02-16 22:01:33'),
(33, 'Shorts de sport', 8, 'femmes', '2025-02-16 22:01:33'),
(34, 'Jupes', 4, 'femmes', '2025-02-16 22:01:33'),
(35, 'Robes', 4, 'femmes', '2025-02-16 22:01:33'),
(36, 'Robes de soirée', 4, 'femmes', '2025-02-16 22:01:33'),
(37, 'Robes longues', 4, 'femmes', '2025-02-16 22:01:33'),
(38, 'Robes courtes', 4, 'femmes', '2025-02-16 22:01:33'),
(39, 'Vestes', 10, 'enfants', '2025-02-16 22:01:33'),
(40, 'Manteaux', 10, 'enfants', '2025-02-16 22:01:33'),
(41, 'T-shirts', 11, 'enfants', '2025-02-16 22:01:33'),
(42, 'Chemises', 11, 'enfants', '2025-02-16 22:01:33'),
(43, 'Blouses', 11, 'enfants', '2025-02-16 22:01:33'),
(44, 'Débardeurs', 11, 'enfants', '2025-02-16 22:01:33'),
(45, 'Baskets', 16, 'enfants', '2025-02-16 22:01:33'),
(46, 'Bottes', 16, 'enfants', '2025-02-16 22:01:33'),
(47, 'Sandales', 16, 'enfants', '2025-02-16 22:01:33'),
(48, 'Mules', 16, 'enfants', '2025-02-16 22:01:33'),
(49, 'Pantalons', 12, 'enfants', '2025-02-16 22:01:33'),
(50, 'Shorts', 12, 'enfants', '2025-02-16 22:01:33'),
(51, 'Bermudas', 12, 'enfants', '2025-02-16 22:01:33'),
(52, 'Jeans', 12, 'enfants', '2025-02-16 22:01:33'),
(53, 'Leggings', 12, 'enfants', '2025-02-16 22:01:33'),
(54, 'Pulls', 15, 'enfants', '2025-02-16 22:01:33'),
(55, 'Gilets', 15, 'enfants', '2025-02-16 22:01:33'),
(56, 'Sweats', 15, 'enfants', '2025-02-16 22:01:33'),
(57, 'Bonnets', 14, 'enfants', '2025-02-16 22:01:33'),
(58, 'Écharpes', 14, 'enfants', '2025-02-16 22:01:33'),
(59, 'Gants', 14, 'enfants', '2025-02-16 22:01:33'),
(60, 'Joggings', 17, 'enfants', '2025-02-16 22:01:33'),
(61, 'Vestes de sport', 17, 'enfants', '2025-02-16 22:01:33'),
(62, 'Shorts de sport', 17, 'enfants', '2025-02-16 22:01:33'),
(63, 'Jupes', 13, 'enfants', '2025-02-16 22:01:33'),
(64, 'Robes', 13, 'enfants', '2025-02-16 22:01:33'),
(65, 'Robes longues', 13, 'enfants', '2025-02-16 22:01:33'),
(66, 'Robes courtes', 13, 'enfants', '2025-02-16 22:01:33'),
(67, 'Robe pull', 4, 'femmes', '2025-02-17 00:21:47');

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `identifiant` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `surname` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `adresse` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `number_phone` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` enum('client','admin') COLLATE utf8mb4_unicode_ci DEFAULT 'client',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `status` enum('active','inactive') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `email_verified` tinyint DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `users`
--

INSERT INTO `users` (`id`, `identifiant`, `name`, `surname`, `adresse`, `number_phone`, `email`, `password`, `role`, `created_at`, `status`, `email_verified`) VALUES
(1, 'Admin', 'Admin', '', '', '', 'admin@chicandchill.com', '$2y$10$iZxj5UKGoHIZYrnAtx9tAO.PE88D.OMMSZlQDybmRn9WHpYaXKA/C', 'admin', '2025-02-10 17:59:52', 'active', 0),
(2, 'Lucas Bernard', 'Bernard', 'Lucas', '', '', 'lucas.bernard@example.com', 'mdpClient1', 'client', '2025-02-10 17:59:52', 'active', 0),
(3, 'Emma Rousseau', 'Rousseau', 'Emma', '', '', 'emma.rousseau@example.com', 'mdpClient2', 'client', '2025-02-10 17:59:52', 'active', 0),
(19, 'user test1', 'Test1', 'User', '', '', 'user.test1@example.com', 'user1234', 'client', '2025-02-19 15:23:10', 'active', 0),
(20, 'totoro', 'toto', 'to', 'ruduto', '0102030405', 'to@gmail.com', '$2y$10$ldzreBYz81RgzGt5FPzamurkn/UjOkUrS9yPo2so1QULdYFBEHRsW', 'client', '2025-02-19 23:22:54', 'active', 0);

-- --------------------------------------------------------

--
-- Structure de la table `user_history`
--

DROP TABLE IF EXISTS `user_history`;
CREATE TABLE IF NOT EXISTS `user_history` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `username` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `action` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci NOT NULL,
  `action_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `user_history`
--

INSERT INTO `user_history` (`id`, `user_id`, `username`, `action`, `ip_address`, `action_date`) VALUES
(1, 1, 'Admin1', 'Mise à jour compte', '::1', '2025-03-19 21:40:30'),
(2, 1, 'Admin', 'Mise à jour compte', '::1', '2025-03-19 21:40:48'),
(3, 1, 'Utilisateur inconnu', 'Mise à jour apparence', '::1', '2025-03-19 23:12:01'),
(4, 1, 'Utilisateur inconnu', 'Mise à jour apparence', '::1', '2025-03-19 23:13:31'),
(5, 1, 'Utilisateur inconnu', 'Mise à jour notifications', '::1', '2025-03-19 23:14:41'),
(6, 1, 'Utilisateur inconnu', 'Mise à jour mot de passe', '::1', '2025-03-21 11:50:27'),
(7, 1, 'Utilisateur inconnu', 'Mise à jour mot de passe', '::1', '2025-03-21 11:50:52');

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `event_images`
--
ALTER TABLE `event_images`
  ADD CONSTRAINT `event_images_ibfk_1` FOREIGN KEY (`event_id`) REFERENCES `events` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `event_orders`
--
ALTER TABLE `event_orders`
  ADD CONSTRAINT `event_orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `event_reservations`
--
ALTER TABLE `event_reservations`
  ADD CONSTRAINT `event_reservations_ibfk_1` FOREIGN KEY (`event_id`) REFERENCES `events` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `event_orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `outfits_suggestions`
--
ALTER TABLE `outfits_suggestions`
  ADD CONSTRAINT `outfits_suggestions_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT;

--
-- Contraintes pour la table `pack_reservations`
--
ALTER TABLE `pack_reservations`
  ADD CONSTRAINT `pack_reservations_ibfk_1` FOREIGN KEY (`pack_id`) REFERENCES `event_packs` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `payments`
--
ALTER TABLE `payments`
  ADD CONSTRAINT `payments_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `payments_ibfk_2` FOREIGN KEY (`order_id`) REFERENCES `event_orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `payments_ibfk_3` FOREIGN KEY (`rental_id`) REFERENCES `rentals` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `rentals`
--
ALTER TABLE `rentals`
  ADD CONSTRAINT `rentals_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `rentals_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `reviews`
--
ALTER TABLE `reviews`
  ADD CONSTRAINT `reviews_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `reviews_ibfk_2` FOREIGN KEY (`event_id`) REFERENCES `events` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `reviews_ibfk_3` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `ss_categories`
--
ALTER TABLE `ss_categories`
  ADD CONSTRAINT `fk_ss_categories_categorie` FOREIGN KEY (`id_categories`) REFERENCES `categories` (`id_categories`) ON DELETE RESTRICT ON UPDATE CASCADE;

--
-- Contraintes pour la table `user_history`
--
ALTER TABLE `user_history`
  ADD CONSTRAINT `user_history_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
