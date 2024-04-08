-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Apr 04, 2024 at 04:17 PM
-- Server version: 10.4.21-MariaDB
-- PHP Version: 8.1.2
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
-- Database: `twitterlike`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id_admin` int(11) NOT NULL,
  `id_utilisateur` bigint(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `commentaire`
--

CREATE TABLE `commentaire` (
  `id_commentaire` bigint(20) NOT NULL,
  `id_post` bigint(20) NOT NULL,
  `id_utilisateur` bigint(20) NOT NULL,
  `contenu` longtext NOT NULL,
  `date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `follower`
--

CREATE TABLE `follower` (
  `id_follower` bigint(20) NOT NULL,
  `id_utilisateur` bigint(20) NOT NULL,
  `id_utilisateur_suivi` bigint(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `likes`
--

CREATE TABLE `likes` (
  `id_like` bigint(20) NOT NULL,
  `id_post` bigint(20) NOT NULL,
  `id_utilisateur` bigint(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `likes`
--

INSERT INTO `likes` (`id_like`, `id_post`, `id_utilisateur`) VALUES
(3, 45, 32);

-- --------------------------------------------------------

--
-- Table structure for table `message`
--

CREATE TABLE `message` (
  `id_message` bigint(20) NOT NULL,
  `id_expediteur` bigint(20) NOT NULL,
  `id_destinataire` bigint(20) NOT NULL,
  `contenu` longtext NOT NULL,
  `date` date NOT NULL,
  `statut_lecture` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `post`
--

CREATE TABLE `post` (
  `id_post` bigint(20) NOT NULL,
  `id_utilisateur` bigint(20) NOT NULL,
  `contenu` varchar(520) NOT NULL,
  `date` date NOT NULL DEFAULT current_timestamp(),
  `image_path` varchar(255) DEFAULT NULL,
  `video_link` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `post`
--

INSERT INTO `post` (`id_post`, `id_utilisateur`, `contenu`, `date`, `image_path`, `video_link`) VALUES
(39, 27, 'test', '2024-04-02', NULL, NULL),
(40, 27, 'test2', '2024-04-02', NULL, NULL),
(41, 27, 'test3', '2024-04-02', NULL, NULL),
(43, 27, 'test avec image', '2024-04-02', './images/43.jpg', NULL),
(44, 27, 'test avec image 2', '2024-04-02', './images/44.jpg', NULL),
(45, 32, 'premier post', '2024-04-04', NULL, 'https://www.youtube.com/watch?v=dQw4w9WgXcQ');
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
-- Table structure for table `utilisateur`
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `utilisateur`
--

INSERT INTO `utilisateur` (`id_utilisateur`, `nom`, `prenom`, `email`, `mdp`, `description`, `dateNaissance`, `username`, `avatar`, `adresse`) VALUES
(27, 'morales', 'Léon', 'leon.morales@utbm.fr', '$2y$10$EGrUAgKkF7qd/xwvouCfrexqMcfSHu8J3WE1eMKliir6AJ.v.b1v2', 'Je suis Léon test', '2003-08-19', 'Léon', './avatar/27.jpg', 'testt'),
(30, 'test', 'test', 'test@test.fr', '$2y$10$xwgwQA65kXeUlB4eya/zYOxaFch//wFwvj1OGE60WbXnrWu3e3FqW', 'pourquoi pas', '2024-03-14', 'test', './avatar/30.jpg', 'testt'),
(31, 'test', 'test', 'test2@test.fr', '$2y$10$.dfn.bTEPbsxyq2BZP9rN.7P6lDputsy.mrHLX09rWlc04/kbMzR6', '', '2024-03-13', 'test2', NULL, 'testt'),
(32, 'Morin', 'alban', 'alban.morin@utbm.fr', '$2y$10$SQwAu3/HSWTuCeUT3cm0jOK29spn73i9.o6PlT2DY/kNZyf0f/rkG', NULL, '2002-01-01', 'alban78', NULL, '12 rue du caca cacaville');
(31, 'test', 'test', 'test2@test.fr', '$2y$10$.dfn.bTEPbsxyq2BZP9rN.7P6lDputsy.mrHLX09rWlc04/kbMzR6', '', '2024-03-13', 'test2', './avatar/defaultAvatar.jpg', 'testt'),
(32, 'test', 'test', 'test.morales@utbm.fr', '$2y$10$xAQH4UGrnPSnUJaczlBuCeUyLKDKA2QVVGmdUDnVQ7VPJbHIJSnDy', NULL, '2024-04-12', 'test3', './avatar/defaultAvatar.jpg', '36 rue du Neufeld');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id_admin`),
  ADD UNIQUE KEY `id_utilisateur` (`id_utilisateur`);

--
-- Indexes for table `commentaire`
--
ALTER TABLE `commentaire`
  ADD PRIMARY KEY (`id_commentaire`),
  ADD UNIQUE KEY `id_post` (`id_post`,`id_utilisateur`),
  ADD KEY `id_utilisateur` (`id_utilisateur`);

--
-- Indexes for table `follower`
--
ALTER TABLE `follower`
  ADD PRIMARY KEY (`id_follower`),
  ADD UNIQUE KEY `id_utilisateur` (`id_utilisateur`,`id_utilisateur_suivi`),
  ADD KEY `id_utilisateur_suivi` (`id_utilisateur_suivi`);

--
-- Indexes for table `likes`
--
ALTER TABLE `likes`
  ADD PRIMARY KEY (`id_like`),
  ADD KEY `like_ibfk_utilisateur` (`id_utilisateur`),
  ADD KEY `like_ibfk_post` (`id_post`);

--
-- Indexes for table `message`
--
ALTER TABLE `message`
  ADD PRIMARY KEY (`id_message`),
  ADD UNIQUE KEY `id_expediteur` (`id_expediteur`,`id_destinataire`),
  ADD KEY `id_destinataire` (`id_destinataire`);

--
-- Indexes for table `post`
--
ALTER TABLE `post`
  ADD PRIMARY KEY (`id_post`),
  ADD KEY `fk_utilisateur_id` (`id_utilisateur`);

--
-- Indexes for table `utilisateur`
--
ALTER TABLE `utilisateur`
  ADD PRIMARY KEY (`id_utilisateur`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `commentaire`
--
ALTER TABLE `commentaire`
  MODIFY `id_commentaire` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `follower`
--
ALTER TABLE `follower`
  MODIFY `id_follower` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `likes`
--
ALTER TABLE `likes`
  MODIFY `id_like` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `message`
--
ALTER TABLE `message`
  MODIFY `id_message` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `post`
--
ALTER TABLE `post`
  MODIFY `id_post` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=46;
  MODIFY `id_post` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=50;

--
-- AUTO_INCREMENT for table `utilisateur`
--
ALTER TABLE `utilisateur`
  MODIFY `id_utilisateur` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;
  MODIFY `id_utilisateur` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `admin`
--
ALTER TABLE `admin`
  ADD CONSTRAINT `admin_ibfk_1` FOREIGN KEY (`id_utilisateur`) REFERENCES `utilisateur` (`id_utilisateur`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `commentaire`
--
ALTER TABLE `commentaire`
  ADD CONSTRAINT `commentaire_ibfk_1` FOREIGN KEY (`id_post`) REFERENCES `post` (`id_post`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `commentaire_ibfk_2` FOREIGN KEY (`id_utilisateur`) REFERENCES `utilisateur` (`id_utilisateur`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `follower`
--
ALTER TABLE `follower`
  ADD CONSTRAINT `follower_ibfk_1` FOREIGN KEY (`id_utilisateur`) REFERENCES `utilisateur` (`id_utilisateur`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `follower_ibfk_2` FOREIGN KEY (`id_utilisateur_suivi`) REFERENCES `utilisateur` (`id_utilisateur`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `likes`
--
ALTER TABLE `likes`
  ADD CONSTRAINT `like_ibfk_post` FOREIGN KEY (`id_post`) REFERENCES `post` (`id_post`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `like_ibfk_utilisateur` FOREIGN KEY (`id_utilisateur`) REFERENCES `utilisateur` (`id_utilisateur`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `message`
--
ALTER TABLE `message`
  ADD CONSTRAINT `message_ibfk_1` FOREIGN KEY (`id_expediteur`) REFERENCES `utilisateur` (`id_utilisateur`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `message_ibfk_2` FOREIGN KEY (`id_destinataire`) REFERENCES `utilisateur` (`id_utilisateur`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `post`
--
ALTER TABLE `post`
  ADD CONSTRAINT `fk_utilisateur_id` FOREIGN KEY (`id_utilisateur`) REFERENCES `utilisateur` (`id_utilisateur`) ON DELETE NO ACTION ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
