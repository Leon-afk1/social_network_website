-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : jeu. 04 avr. 2024 à 16:49
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
-- Structure de la table `admin`
--

CREATE TABLE `admin` (
  `id_admin` int(11) NOT NULL,
  `id_utilisateur` bigint(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `commentaire`
--

CREATE TABLE `commentaire` (
  `id_commentaire` bigint(20) NOT NULL,
  `id_post` bigint(20) NOT NULL,
  `id_utilisateur` bigint(20) NOT NULL,
  `contenu` longtext NOT NULL,
  `date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `follower`
--

CREATE TABLE `follower` (
  `id_follower` bigint(20) NOT NULL,
  `id_utilisateur` bigint(20) NOT NULL,
  `id_utilisateur_suivi` bigint(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `message`
--

CREATE TABLE `message` (
  `id_message` bigint(20) NOT NULL,
  `id_expediteur` bigint(20) NOT NULL,
  `id_destinataire` bigint(20) NOT NULL,
  `contenu` longtext NOT NULL,
  `date` date NOT NULL,
  `statut_lecture` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `post`
--

CREATE TABLE `post` (
  `id_post` bigint(20) NOT NULL,
  `id_utilisateur` bigint(20) NOT NULL,
  `contenu` varchar(520) NOT NULL,
  `date` date NOT NULL DEFAULT current_timestamp(),
  `image_path` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `post`
--

INSERT INTO `post` (`id_post`, `id_utilisateur`, `contenu`, `date`, `image_path`) VALUES
(39, 27, 'test', '2024-04-02', NULL),
(40, 27, 'test2', '2024-04-02', NULL),
(41, 27, 'test3', '2024-04-02', NULL),
(43, 27, 'test avec image', '2024-04-02', './images/43.jpg'),
(44, 27, 'test avec image 2', '2024-04-02', './images/44.jpg'),
(45, 27, 'test', '2024-04-04', NULL),
(46, 27, 'test avec image', '2024-04-04', './images/46.jpg'),
(47, 30, 'test', '2024-04-04', NULL),
(48, 30, 'test', '2024-04-04', './images/48.jpg'),
(49, 31, 'test', '2024-04-04', NULL);

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
  `adresse` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `utilisateur`
--

INSERT INTO `utilisateur` (`id_utilisateur`, `nom`, `prenom`, `email`, `mdp`, `description`, `dateNaissance`, `username`, `avatar`, `adresse`) VALUES
(27, 'morales', 'Léon', 'leon.morales@utbm.fr', '$2y$10$EGrUAgKkF7qd/xwvouCfrexqMcfSHu8J3WE1eMKliir6AJ.v.b1v2', 'Je suis Léon test', '2003-08-19', 'Léon', './avatar/27.jpg', 'testt'),
(30, 'test', 'test', 'test@test.fr', '$2y$10$xwgwQA65kXeUlB4eya/zYOxaFch//wFwvj1OGE60WbXnrWu3e3FqW', 'pourquoi pas', '2024-03-14', 'test', './avatar/30.jpg', 'testt'),
(31, 'test', 'test', 'test2@test.fr', '$2y$10$.dfn.bTEPbsxyq2BZP9rN.7P6lDputsy.mrHLX09rWlc04/kbMzR6', '', '2024-03-13', 'test2', './avatar/defaultAvatar.jpg', 'testt'),
(32, 'test', 'test', 'test.morales@utbm.fr', '$2y$10$xAQH4UGrnPSnUJaczlBuCeUyLKDKA2QVVGmdUDnVQ7VPJbHIJSnDy', NULL, '2024-04-12', 'test3', './avatar/defaultAvatar.jpg', '36 rue du Neufeld');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id_admin`),
  ADD UNIQUE KEY `id_utilisateur` (`id_utilisateur`);

--
-- Index pour la table `commentaire`
--
ALTER TABLE `commentaire`
  ADD PRIMARY KEY (`id_commentaire`),
  ADD UNIQUE KEY `id_post` (`id_post`,`id_utilisateur`),
  ADD KEY `id_utilisateur` (`id_utilisateur`);

--
-- Index pour la table `follower`
--
ALTER TABLE `follower`
  ADD PRIMARY KEY (`id_follower`),
  ADD UNIQUE KEY `id_utilisateur` (`id_utilisateur`,`id_utilisateur_suivi`),
  ADD KEY `id_utilisateur_suivi` (`id_utilisateur_suivi`);

--
-- Index pour la table `message`
--
ALTER TABLE `message`
  ADD PRIMARY KEY (`id_message`),
  ADD UNIQUE KEY `id_expediteur` (`id_expediteur`,`id_destinataire`),
  ADD KEY `id_destinataire` (`id_destinataire`);

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
-- AUTO_INCREMENT pour la table `commentaire`
--
ALTER TABLE `commentaire`
  MODIFY `id_commentaire` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `follower`
--
ALTER TABLE `follower`
  MODIFY `id_follower` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `message`
--
ALTER TABLE `message`
  MODIFY `id_message` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `post`
--
ALTER TABLE `post`
  MODIFY `id_post` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=50;

--
-- AUTO_INCREMENT pour la table `utilisateur`
--
ALTER TABLE `utilisateur`
  MODIFY `id_utilisateur` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `admin`
--
ALTER TABLE `admin`
  ADD CONSTRAINT `admin_ibfk_1` FOREIGN KEY (`id_utilisateur`) REFERENCES `utilisateur` (`id_utilisateur`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `commentaire`
--
ALTER TABLE `commentaire`
  ADD CONSTRAINT `commentaire_ibfk_1` FOREIGN KEY (`id_post`) REFERENCES `post` (`id_post`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `commentaire_ibfk_2` FOREIGN KEY (`id_utilisateur`) REFERENCES `utilisateur` (`id_utilisateur`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `follower`
--
ALTER TABLE `follower`
  ADD CONSTRAINT `follower_ibfk_1` FOREIGN KEY (`id_utilisateur`) REFERENCES `utilisateur` (`id_utilisateur`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `follower_ibfk_2` FOREIGN KEY (`id_utilisateur_suivi`) REFERENCES `utilisateur` (`id_utilisateur`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `message`
--
ALTER TABLE `message`
  ADD CONSTRAINT `message_ibfk_1` FOREIGN KEY (`id_expediteur`) REFERENCES `utilisateur` (`id_utilisateur`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `message_ibfk_2` FOREIGN KEY (`id_destinataire`) REFERENCES `utilisateur` (`id_utilisateur`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Contraintes pour la table `post`
--
ALTER TABLE `post`
  ADD CONSTRAINT `fk_utilisateur_id` FOREIGN KEY (`id_utilisateur`) REFERENCES `utilisateur` (`id_utilisateur`) ON DELETE NO ACTION ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
