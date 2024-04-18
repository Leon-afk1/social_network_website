-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : jeu. 18 avr. 2024 à 13:00
-- Version du serveur : 10.4.32-MariaDB
-- Version de PHP : 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `twitterlike`
--

-- --------------------------------------------------------

--
-- Structure de la table `follower`
--

CREATE TABLE `follower` (
  `id_follower` bigint(20) NOT NULL,
  `id_utilisateur` bigint(20) NOT NULL,
  `id_utilisateur_suivi` bigint(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `follower`
--

INSERT INTO `follower` (`id_follower`, `id_utilisateur`, `id_utilisateur_suivi`) VALUES
(61, 30, 31),
(60, 31, 27),
(62, 31, 32),
(63, 32, 27);

-- --------------------------------------------------------

--
-- Structure de la table `likes`
--

CREATE TABLE `likes` (
  `id_likes` bigint(11) NOT NULL,
  `id_utilisateur` bigint(11) NOT NULL,
  `id_post` bigint(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `notification`
--

CREATE TABLE `notification` (
  `id_notification` bigint(20) NOT NULL,
  `type` varchar(20) NOT NULL,
  `bool_lue` tinyint(1) DEFAULT 0,
  `id_utilisateur` bigint(20) NOT NULL,
  `date_notification` datetime NOT NULL,
  `id_post_cible` bigint(20) DEFAULT NULL,
  `id_utilisateur_cible` bigint(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `notification`
--

INSERT INTO `notification` (`id_notification`, `type`, `bool_lue`, `id_utilisateur`, `date_notification`, `id_post_cible`, `id_utilisateur_cible`) VALUES
(46, 'follow', 0, 32, '2024-04-17 01:18:27', NULL, 27),
(56, 'unban', 1, 30, '2024-04-17 19:18:20', NULL, 27),
(57, 'ban', 1, 30, '2024-04-17 19:18:30', NULL, 27),
(64, 'unban', 0, 31, '2024-04-17 21:08:39', NULL, 27),
(65, 'ban', 0, 31, '2024-04-17 21:08:52', NULL, 27),
(66, 'ban', 0, 31, '2024-04-18 11:58:42', NULL, 27);

-- --------------------------------------------------------

--
-- Structure de la table `post`
--

CREATE TABLE `post` (
  `id_post` bigint(20) NOT NULL,
  `id_utilisateur` bigint(20) NOT NULL,
  `id_parent` int(11) DEFAULT NULL,
  `contenu` varchar(520) NOT NULL,
  `date` datetime NOT NULL DEFAULT current_timestamp(),
  `image_path` varchar(255) DEFAULT NULL,
  `video_lien` varchar(200) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `post`
--

INSERT INTO `post` (`id_post`, `id_utilisateur`, `id_parent`, `contenu`, `date`, `image_path`, `video_lien`) VALUES
(47, 30, NULL, 'test', '2024-04-04 00:00:00', NULL, NULL),
(48, 30, NULL, 'test', '2024-04-04 00:00:00', './images/48.jpg', NULL),
(52, 32, NULL, 'test', '2024-04-04 00:00:00', NULL, NULL),
(53, 32, NULL, 'test heure', '2024-04-05 16:14:58', NULL, NULL),
(54, 32, NULL, 'test min', '2024-04-05 16:25:21', NULL, NULL),
(115, 27, 54, 'jrgjrbht', '2024-04-11 19:35:11', './images/115.gif', NULL),
(116, 27, 54, 'tredhtydddjf', '2024-04-12 19:05:38', NULL, NULL),
(120, 27, NULL, 'test', '2024-04-12 19:56:19', NULL, NULL),
(124, 27, NULL, 'test', '2024-04-13 18:10:41', './images/124.png', NULL),
(125, 27, NULL, 'test', '2024-04-13 18:10:58', NULL, NULL),
(126, 27, NULL, 'test', '2024-04-13 18:18:30', NULL, 'https://www.youtube.com/watch?v=L9ZyryHc304'),
(128, 27, NULL, 'test sans rien', '2024-04-13 18:42:17', NULL, NULL),
(129, 27, NULL, 'test image', '2024-04-13 18:42:38', './images/129.gif', NULL),
(130, 27, NULL, 'test video', '2024-04-13 18:42:51', NULL, 'https://www.youtube.com/watch?v=L9ZyryHc304'),
(151, 30, NULL, 'test1', '2024-04-14 18:01:16', NULL, NULL),
(152, 30, NULL, 'test2', '2024-04-14 18:01:22', NULL, NULL),
(153, 30, NULL, 'test3', '2024-04-14 18:01:30', NULL, NULL),
(154, 30, NULL, 'test4', '2024-04-14 18:01:40', NULL, NULL),
(157, 30, NULL, 'test notifications', '2024-04-15 17:23:56', NULL, NULL),
(158, 30, NULL, 'test notifications', '2024-04-15 17:24:36', NULL, NULL),
(159, 30, NULL, 'test notifications', '2024-04-15 17:25:12', NULL, NULL),
(161, 31, NULL, 'test notif 3', '2024-04-15 17:26:39', NULL, NULL);

-- --------------------------------------------------------

--
-- Structure de la table `utilisateur`
--

CREATE TABLE `utilisateur` (
  `id_utilisateur` bigint(20) NOT NULL,
  `nom` varchar(50) NOT NULL,
  `prenom` varchar(50) NOT NULL,
  `email` varchar(75) NOT NULL,
  `mdp` varchar(255) NOT NULL,
  `description` varchar(400) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL,
  `dateNaissance` date NOT NULL,
  `username` varchar(75) NOT NULL,
  `avatar` varchar(255) DEFAULT './avatar/defaultAvatar.jpg',
  `adresse` varchar(100) DEFAULT NULL,
  `admin` tinyint(1) DEFAULT 0,
  `ban` tinyint(1) DEFAULT 0,
  `date_fin_ban` datetime DEFAULT NULL,
  `justification_ban` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `utilisateur`
--

INSERT INTO `utilisateur` (`id_utilisateur`, `nom`, `prenom`, `email`, `mdp`, `description`, `dateNaissance`, `username`, `avatar`, `adresse`, `admin`, `ban`, `date_fin_ban`, `justification_ban`) VALUES
(27, 'morales', 'Léon', 'leon.morales@utbm.fr', '$2y$10$EGrUAgKkF7qd/xwvouCfrexqMcfSHu8J3WE1eMKliir6AJ.v.b1v2', 'Je suis Léon', '2003-08-19', 'Léon', './avatar/27.jpg', 'testt', 1, 0, NULL, NULL),
(30, 'test', 'test', 'test@test.fr', '$2y$10$xwgwQA65kXeUlB4eya/zYOxaFch//wFwvj1OGE60WbXnrWu3e3FqW', 'pourquoi pas', '2014-03-14', 'test', './avatar/30.jpg', 'testt', 0, 1, '2222-01-01 00:00:00', 'Contenue inapproprié'),
(31, 'test', 'test', 'test2@test.fr', '$2y$10$.dfn.bTEPbsxyq2BZP9rN.7P6lDputsy.mrHLX09rWlc04/kbMzR6', '', '2016-03-13', 'test2', './avatar/defaultAvatar.jpg', 'testt', 0, 1, NULL, 'Contenue inapproprié'),
(32, 'test', 'test', 'test.morales@utbm.fr', '$2y$10$xAQH4UGrnPSnUJaczlBuCeUyLKDKA2QVVGmdUDnVQ7VPJbHIJSnDy', NULL, '2024-04-12', 'test3', './avatar/32.jpg', '36 rue du Neufeld', 0, 0, NULL, NULL),
(40, 'Morales', 'Léon', 'sgefffs.morales@utbm.fr', '$2y$10$xquP3E.LqPEbKzFp6SK1POf.gTjkHcufpUMwfa/2FTIVs33quxVD2', NULL, '2024-04-19', 'wrg', './avatar/defaultAvatar.jpg', '35 rue du Neufeld', 0, 0, NULL, NULL),
(41, 'Morales', 'Léon', 'afzezzd.morales@utbm.fr', '$2y$10$BEIKLRfv1dyFaQS2DKs9LueCBBdSSyFtpCGO1RGHya8KYWjxs.Zna', NULL, '2024-04-11', 'sefe', './avatar/defaultAvatar.jpg', '35 rue du Neufeld', 0, 0, NULL, NULL),
(42, 'Morales', 'Léon', 'dfgrr.morales@utbm.fr', '$2y$10$orhNN154nYOoRDeNDKYCDeK1BXosm0oC1ldVDh.gWlJ/hG3F/1zua', NULL, '2024-04-17', 'feez', './avatar/defaultAvatar.jpg', '35 rue du Neufeld', 0, 0, NULL, NULL),
(43, 'Morales', 'Léon', 'rsece.morales@utbm.fr', '$2y$10$C3PuwMAMkc3.LXbs/lv0oegWD99M.tz3NZjD2267SvW4O/Q8VG/LS', NULL, '2024-04-20', 'zevc', './avatar/defaultAvatar.jpg', '35 rue du Neufeld', 0, 0, NULL, NULL),
(44, 'Morales', 'Léon', 'fezez.morales@utbm.fr', '$2y$10$Vbj8iXlHKzaf74dcmMzH7uzWa1o0y6J4IQIyhMBRQFP8QSNDJQweW', NULL, '2024-04-19', 'fzaeaz', './avatar/defaultAvatar.jpg', '35 rue du Neufeld', 0, 0, NULL, NULL),
(45, 'Morales', 'Léon', 'zefezfd.morales@utbm.fr', '$2y$10$n2XAMXVHzohjxMQzt2KGfOrK6GPURpXZl.rE54ClLsNr6gCA3Fsi6', NULL, '2024-04-27', 'ezez', './avatar/defaultAvatar.jpg', '35 rue du Neufeld', 0, 0, NULL, NULL),
(46, 'morales', 'leon', 'leon.neo.18@gmail.com', '$2y$10$atEUw0F8yOCmJ2n1O35jV.dL0GCDKrFHA8oKLpDQzkkH.j.olTP/y', NULL, '2000-02-18', 'zef', './avatar/defaultAvatar.jpg', '35 rue du Neufeld', 0, 0, NULL, NULL);

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `follower`
--
ALTER TABLE `follower`
  ADD PRIMARY KEY (`id_follower`),
  ADD UNIQUE KEY `id_utilisateur` (`id_utilisateur`,`id_utilisateur_suivi`) USING BTREE,
  ADD KEY `id_utilisateur_suivi` (`id_utilisateur_suivi`) USING BTREE;

--
-- Index pour la table `likes`
--
ALTER TABLE `likes`
  ADD PRIMARY KEY (`id_likes`) USING BTREE,
  ADD KEY `id_utilisateur` (`id_utilisateur`),
  ADD KEY `id_post` (`id_post`);

--
-- Index pour la table `notification`
--
ALTER TABLE `notification`
  ADD PRIMARY KEY (`id_notification`),
  ADD KEY `notif_fk_id_utilisateur_cible` (`id_utilisateur_cible`),
  ADD KEY `notif_fk_id_utilisateur` (`id_utilisateur`),
  ADD KEY `notif_fk_id_post_cible` (`id_post_cible`);

--
-- Index pour la table `post`
--
ALTER TABLE `post`
  ADD PRIMARY KEY (`id_post`),
  ADD KEY `fk_utilisateur_id` (`id_utilisateur`);

--
-- Index pour la table `utilisateur`
--
ALTER TABLE `utilisateur`
  ADD PRIMARY KEY (`id_utilisateur`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `follower`
--
ALTER TABLE `follower`
  MODIFY `id_follower` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=65;

--
-- AUTO_INCREMENT pour la table `likes`
--
ALTER TABLE `likes`
  MODIFY `id_likes` bigint(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT pour la table `notification`
--
ALTER TABLE `notification`
  MODIFY `id_notification` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=67;

--
-- AUTO_INCREMENT pour la table `post`
--
ALTER TABLE `post`
  MODIFY `id_post` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=179;

--
-- AUTO_INCREMENT pour la table `utilisateur`
--
ALTER TABLE `utilisateur`
  MODIFY `id_utilisateur` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=47;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `follower`
--
ALTER TABLE `follower`
  ADD CONSTRAINT `follower_ibfk_1` FOREIGN KEY (`id_utilisateur`) REFERENCES `utilisateur` (`id_utilisateur`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `follower_ibfk_2` FOREIGN KEY (`id_utilisateur_suivi`) REFERENCES `utilisateur` (`id_utilisateur`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `likes`
--
ALTER TABLE `likes`
  ADD CONSTRAINT `likes_ibfk_1` FOREIGN KEY (`id_utilisateur`) REFERENCES `utilisateur` (`id_utilisateur`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `likes_ibfk_2` FOREIGN KEY (`id_post`) REFERENCES `post` (`id_post`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `notification`
--
ALTER TABLE `notification`
  ADD CONSTRAINT `notif_fk_id_post_cible` FOREIGN KEY (`id_post_cible`) REFERENCES `post` (`id_post`) ON DELETE CASCADE,
  ADD CONSTRAINT `notif_fk_id_utilisateur` FOREIGN KEY (`id_utilisateur`) REFERENCES `utilisateur` (`id_utilisateur`) ON DELETE NO ACTION ON UPDATE CASCADE,
  ADD CONSTRAINT `notif_fk_id_utilisateur_cible` FOREIGN KEY (`id_utilisateur_cible`) REFERENCES `utilisateur` (`id_utilisateur`) ON DELETE NO ACTION ON UPDATE CASCADE;

--
-- Contraintes pour la table `post`
--
ALTER TABLE `post`
  ADD CONSTRAINT `fk_utilisateur_id` FOREIGN KEY (`id_utilisateur`) REFERENCES `utilisateur` (`id_utilisateur`) ON DELETE NO ACTION ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
