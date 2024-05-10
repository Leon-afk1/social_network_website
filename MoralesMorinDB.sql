-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : ven. 10 mai 2024 à 12:18
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
-- Base de données : `moralesmorindb`
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
(69, 49, 50),
(70, 49, 51),
(71, 49, 53),
(68, 50, 51),
(67, 51, 50);

-- --------------------------------------------------------

--
-- Structure de la table `likes`
--

CREATE TABLE `likes` (
  `id_likes` bigint(11) NOT NULL,
  `id_utilisateur` bigint(11) NOT NULL,
  `id_post` bigint(11) NOT NULL,
  `date` date NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `likes`
--

INSERT INTO `likes` (`id_likes`, `id_utilisateur`, `id_post`, `date`) VALUES
(31, 50, 207, '2024-05-10'),
(32, 48, 207, '2024-05-10'),
(35, 51, 212, '2024-05-10'),
(36, 51, 207, '2024-05-10'),
(37, 51, 214, '2024-05-10'),
(39, 49, 208, '2024-05-10');

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
  `id_utilisateur_cible` bigint(20) DEFAULT NULL,
  `message_notification` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `notification`
--

INSERT INTO `notification` (`id_notification`, `type`, `bool_lue`, `id_utilisateur`, `date_notification`, `id_post_cible`, `id_utilisateur_cible`, `message_notification`) VALUES
(161, 'like', 0, 50, '2024-05-10 10:05:04', 207, 50, NULL),
(162, 'like', 0, 50, '2024-05-10 10:07:10', 207, 48, NULL),
(165, 'like', 0, 51, '2024-05-10 10:15:51', 212, 51, NULL),
(166, 'follow', 0, 50, '2024-05-10 10:16:29', NULL, 51, NULL),
(167, 'like', 0, 50, '2024-05-10 10:18:16', 207, 51, NULL),
(168, 'follow', 0, 51, '2024-05-10 10:19:33', NULL, 50, NULL),
(169, 'signalement', 0, 48, '2024-05-10 10:22:20', 213, 52, 'Ce contenu a été signalé par un utilisateur'),
(170, 'signalement', 0, 49, '2024-05-10 10:22:20', 213, 52, 'Ce contenu a été signalé par un utilisateur'),
(171, 'retirerPost', 0, 52, '2024-05-10 10:22:39', 213, 51, 'Ce contenu a été retiré car il ne respecte pas les règles de la communauté'),
(172, 'post', 0, 51, '2024-05-10 10:25:46', 214, 50, NULL),
(173, 'sensible', 0, 50, '2024-05-10 10:30:52', 214, 51, 'Ce contenu a été marqué comme sensible car offensant'),
(174, 'like', 0, 50, '2024-05-10 10:32:53', 214, 51, NULL),
(175, 'ban', 0, 52, '2024-05-10 10:34:52', NULL, 49, NULL),
(176, 'ban', 0, 52, '2024-05-10 10:37:24', NULL, 49, NULL),
(178, 'post', 0, 51, '2024-05-10 11:58:12', 216, 50, NULL),
(179, 'like', 0, 51, '2024-05-10 12:15:22', 208, 49, NULL),
(180, 'follow', 0, 50, '2024-05-10 12:16:08', NULL, 49, NULL),
(181, 'follow', 0, 51, '2024-05-10 12:16:24', NULL, 49, NULL),
(182, 'follow', 0, 53, '2024-05-10 12:16:39', NULL, 49, NULL);

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
  `video_lien` varchar(200) DEFAULT NULL,
  `visibilite` varchar(255) DEFAULT 'public'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `post`
--

INSERT INTO `post` (`id_post`, `id_utilisateur`, `id_parent`, `contenu`, `date`, `image_path`, `video_lien`, `visibilite`) VALUES
(207, 50, NULL, 'Ceci est mon premier post, simple sans images et sans vidéo. Enjoy', '2024-05-10 10:04:43', NULL, NULL, 'public'),
(208, 51, 207, 'Sympa ce post remi! Ceci est un commentaire simple.', '2024-05-10 10:07:30', NULL, NULL, 'public'),
(212, 51, NULL, 'Regardez cette vidéo elle est plutôt sympa n&#039;est ce pas ?', '2024-05-10 10:15:43', NULL, 'https://www.youtube.com/watch?v=ApXoWvfEYVU', 'public'),
(213, 52, NULL, 'Je vous déteste tous !! Ceci est un message de haine !', '2024-05-10 10:18:38', NULL, NULL, 'offensant'),
(214, 50, NULL, 'Ceci est un post sensible faites attention :', '2024-05-10 10:25:46', NULL, NULL, 'sensible'),
(215, 50, NULL, 'Regardez cette jolie photo', '2024-05-10 11:00:22', NULL, NULL, 'public'),
(216, 50, NULL, 'Post avec image', '2024-05-10 11:58:11', './images/216.jpg', NULL, 'public'),
(217, 53, NULL, 'Post avec un gif', '2024-05-10 12:08:46', './images/217.gif', NULL, 'public'),
(218, 53, NULL, 'post avec une grosse image qui est remise à la bonne taille', '2024-05-10 12:09:24', './images/218.JPG', NULL, 'public'),
(219, 53, NULL, 'Les posts suivant vont etre pour montrer que les posts chargent 5 par 5 quand on arrive au bas de la page', '2024-05-10 12:09:57', NULL, NULL, 'public'),
(220, 53, NULL, 'post en plus\r\n\r\n\r\n(il en faut pleins)', '2024-05-10 12:10:18', NULL, NULL, 'public'),
(221, 53, NULL, 'et un de plus', '2024-05-10 12:10:30', NULL, NULL, 'public'),
(222, 53, NULL, 'toujours plus', '2024-05-10 12:10:40', NULL, NULL, 'public'),
(223, 53, NULL, 'il commence à en avoir pas mal', '2024-05-10 12:10:55', NULL, NULL, 'public'),
(224, 53, NULL, 'mais il en faut encore plus', '2024-05-10 12:11:07', NULL, NULL, 'public'),
(225, 49, NULL, 'Je suis Admin donc il y a l&#039;icone admin a cote de mon avatar sur chacun de mes posts', '2024-05-10 12:13:24', NULL, NULL, 'public'),
(226, 49, 207, 'Pas mal ce post, basique mais cool', '2024-05-10 12:15:14', NULL, NULL, 'public'),
(227, 49, 208, 'Ceci est une réponse à un commentaire avec en plus une image', '2024-05-10 12:15:51', './images/227.jpg', NULL, 'public');

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
(48, 'Alban', 'Morin', 'alban.morin@y.com', '$2y$10$/Y8u/AC.VhbdbQq/aqW11.EFGpwpM88ayL.EWOM4IcPkTjm/BqkbW', NULL, '2002-02-10', 'albanAdmin', './avatar/defaultAvatar.jpg', 'rue Edouard Baer Belfort', 1, 0, NULL, NULL),
(49, 'Leon', 'Morales', 'leon.morales@y.com', '$2y$10$Ex32lMY2Hb2Z8Cw7OBo06eLCtv2wnptVyR/v34LwuIxIuJABgicIW', '', '1998-06-10', 'leonAdmin', './avatar/49.jpg', 'faubourg', 1, 0, NULL, NULL),
(50, 'Remi', 'Bonaventure', 'remi.bon@gmail.com', '$2y$10$BWemJ3MOaJsjkXFiXWfOJO952Szbt/Z3Xjze2d9lWHEOtf7hoRbTS', NULL, '2000-09-01', 'remi74', './avatar/50.jpg', 'rue adolphe thiers belfort', 0, 0, NULL, NULL),
(51, 'Sans Gel', 'Noe', 'noe.athle@ffa.fr', '$2y$10$wirkftC2lPxIkFtSNsPPMeDa6yxzrxnXHozcYmE7W0lzbGon5SIni', NULL, '2002-04-14', 'noeCoureur', './avatar/51.jpg', 'rue de madagascar Belfort', 0, 0, NULL, NULL),
(52, 'Porcinet', 'Matteo', 'matteo@folie.com', '$2y$10$gwIswPJKOynDeyLiaJbT0eHr0/HjEez9XwgWN8ZNm701w8Rwt7pES', NULL, '2000-03-25', 'matteoLeFou', './avatar/defaultAvatar.jpg', 'rue de rivoli Paris', 0, 1, NULL, 'Il est fou'),
(53, 'Morales', 'Morgane', 'morgane@utbm.fr', '$2y$10$de4hUTlu8Kvo.oQ9LRcOA.5v26yuwz6y3ge/Wt21UH7.KYD7ASGjm', NULL, '2003-07-20', 'morgane_mls', './avatar/53.jpg', '35 rue du Neufeld', 0, 0, NULL, NULL);

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
  MODIFY `id_follower` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=72;

--
-- AUTO_INCREMENT pour la table `likes`
--
ALTER TABLE `likes`
  MODIFY `id_likes` bigint(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- AUTO_INCREMENT pour la table `notification`
--
ALTER TABLE `notification`
  MODIFY `id_notification` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=183;

--
-- AUTO_INCREMENT pour la table `post`
--
ALTER TABLE `post`
  MODIFY `id_post` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=228;

--
-- AUTO_INCREMENT pour la table `utilisateur`
--
ALTER TABLE `utilisateur`
  MODIFY `id_utilisateur` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=54;

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
