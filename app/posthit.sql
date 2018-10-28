-- phpMyAdmin SQL Dump
-- version 4.6.4
-- https://www.phpmyadmin.net/
--
-- Client :  127.0.0.1
-- Généré le :  Dim 19 Novembre 2017 à 14:10
-- Version du serveur :  5.7.14
-- Version de PHP :  7.0.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données :  `posthit`
--

-- --------------------------------------------------------

--
-- Structure de la table `display_board`
--

CREATE TABLE `display_board` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description`varchar (255),
  `latitude` double NOT NULL,
  `longitude` double NOT NULL,
  `altitude` double,
  `status_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Contenu de la table `display_board`
--

INSERT INTO `display_board` (`id`, `name`, `description`, `latitude`, `longitude`, `altitude`, `status_id`) VALUES
(1, 'ETNA Restaurant',  "Restaurant communautaire, ramenez votre nourriture", 48.8134007, 2.3932467, 10.0, 5),
(2, 'Comme à la maison',  "Restau Asiat' reconnu partout dans Choisy le Roi", 48.768709, 2.4131314, 10.0, 5);

-- --------------------------------------------------------

--
-- Structure de la table `like_post_it`
--

CREATE TABLE `like_post_it` (
  `post_it_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `opinion_type_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `opinion_type`
--

CREATE TABLE `opinion_type` (
  `id` int(11) NOT NULL,
  `name` varchar(128) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Contenu de la table `opinion_type`
--

INSERT INTO `opinion_type` (`id`, `name`) VALUES
(1, 'LIKE'),
(2, 'DISLIKE'),
(3, 'REPORT');

-- --------------------------------------------------------

--
-- Structure de la table `post_hit`
--

CREATE TABLE `post_hit` (
  `id` int(11) NOT NULL,
  `latitude` double NOT NULL,
  `longitude` double NOT NULL,
  `axeXYZ` varchar(32) NOT NULL,
  `message` varchar(2048) NOT NULL,
  `reputation` int(11) NOT NULL DEFAULT '0',
  `display_board_id` int(11) NOT NULL,
  `status_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Contenu de la table `post_hit`
--

INSERT INTO `post_hit` (`id`, `latitude`, `longitude`, `axeXYZ`, `message`, `reputation`, `display_board_id`, `status_id`, `user_id`) VALUES
(1, 4848484.4848, 777.78484, '8408,8408,4804', 'zefefzegqrzgzrgzqrrzqg', 50, 1, 5, 1),
(2, 65999.94959, 595959.5448, '1515,8484,578', 'coucou les loulou', 50, 1, 5, 2),
(4, 10561.8448, 10561.8448, '840,84,84', 'HEllo my new post hit', 50, 1, 5, 3),
(3, 4848484.4848, 777.78484, '8408,8408,4804', 'zefefzegqrzgzrgzqrrzqg', 50, 2, 5, 1),
(5, 65999.94959, 595959.5448, '1515,8484,578', 'coucou les loulou', 50, 2, 5, 2),
(6, 10561.8448, 10561.8448, '840,84,84', 'HEllo my new post hit', 50, 2, 5, 3);

-- --------------------------------------------------------

--
-- Structure de la table `post_hit_tags`
--

CREATE TABLE `post_hit_tags` (
  `post_hit_id` int(11) NOT NULL,
  `tag_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Contenu de la table `post_hit_tags`
--

INSERT INTO `post_hit_tags` (`post_hit_id`, `tag_id`) VALUES
(1, 1),
(2, 2),
(2, 3),
(4, 1),
(4, 4),
(4, 5);

-- --------------------------------------------------------

--
-- Structure de la table `role`
--

CREATE TABLE `role` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Contenu de la table `role`
--

INSERT INTO `role` (`id`, `name`) VALUES
(1, 'ADMIN'),
(2, 'USER');

-- --------------------------------------------------------

--
-- Structure de la table `status`
--

CREATE TABLE `status` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Contenu de la table `status`
--

INSERT INTO `status` (`id`, `name`) VALUES
(1, 'WAITING VALIDATION REPORT'),
(2, 'REPORTED'),
(3, 'DELETED'),
(4, 'BANISHED'),
(5, 'VALIDATED'),
(6, 'PENDING VALIDATION');

-- --------------------------------------------------------

--
-- Structure de la table `tags`
--

CREATE TABLE `tags` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Contenu de la table `tags`
--

INSERT INTO `tags` (`id`, `name`) VALUES
(1, 'nanananaan'),
(2, 'pouetpouet'),
(3, 'bimbim'),
(4, 'machin'),
(5, 'new try post hit');

-- --------------------------------------------------------

--
-- Structure de la table `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `pseudo` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role_id` int(11) NOT NULL,
  `status_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Contenu de la table `user`
--

INSERT INTO `user` (`id`, `pseudo`, `email`, `password`, `role_id`, `status_id`) VALUES
(1, 'pliGroup', 'pli@posthit.com', '1234', 2, 5),
(2, 'notwak', 'notwak@etna.com', '1234', 2, 5),
(3, 'newUser', 'newUser@hotmail.com', '1234', 2, 5);

--
-- Index pour les tables exportées
--

--
-- Index pour la table `display_board`
--
ALTER TABLE `display_board`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_display_board_status_id` (`status_id`);

--
-- Index pour la table `like_post_it`
--
ALTER TABLE `like_post_it`
  ADD KEY `post_it_id` (`post_it_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `opinion_type_id` (`opinion_type_id`);

--
-- Index pour la table `opinion_type`
--
ALTER TABLE `opinion_type`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `post_hit`
--
ALTER TABLE `post_hit`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `display_board_id` (`display_board_id`),
  ADD KEY `post_it_ibfk_2` (`status_id`);

--
-- Index pour la table `post_hit_tags`
--
ALTER TABLE `post_hit_tags`
  ADD PRIMARY KEY (`tag_id`,`post_hit_id`),
  ADD KEY `fk_post_hit_tags_post_hit_id` (`post_hit_id`);

--
-- Index pour la table `role`
--
ALTER TABLE `role`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `status`
--
ALTER TABLE `status`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `tags`
--
ALTER TABLE `tags`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `pseudo` (`pseudo`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `status_id` (`status_id`),
  ADD KEY `role_id` (`role_id`);

--
-- AUTO_INCREMENT pour les tables exportées
--

--
-- AUTO_INCREMENT pour la table `display_board`
--
ALTER TABLE `display_board`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT pour la table `opinion_type`
--
ALTER TABLE `opinion_type`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT pour la table `post_hit`
--
ALTER TABLE `post_hit`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT pour la table `role`
--
ALTER TABLE `role`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT pour la table `status`
--
ALTER TABLE `status`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT pour la table `tags`
--
ALTER TABLE `tags`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT pour la table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- Contraintes pour les tables exportées
--

--
-- Contraintes pour la table `display_board`
--
ALTER TABLE `display_board`
  ADD CONSTRAINT `fk_display_board_status_id` FOREIGN KEY (`status_id`) REFERENCES `status` (`id`);

--
-- Contraintes pour la table `like_post_it`
--
ALTER TABLE `like_post_it`
  ADD CONSTRAINT `like_post_it_ibfk_1` FOREIGN KEY (`post_it_id`) REFERENCES `post_hit` (`id`),
  ADD CONSTRAINT `like_post_it_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`),
  ADD CONSTRAINT `like_post_it_ibfk_3` FOREIGN KEY (`opinion_type_id`) REFERENCES `opinion_type` (`id`);

--
-- Contraintes pour la table `post_hit`
--
ALTER TABLE `post_hit`
  ADD CONSTRAINT `post_hit_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`),
  ADD CONSTRAINT `post_hit_ibfk_2` FOREIGN KEY (`status_id`) REFERENCES `status` (`id`),
  ADD CONSTRAINT `post_hit_ibfk_3` FOREIGN KEY (`display_board_id`) REFERENCES `display_board` (`id`);

--
-- Contraintes pour la table `post_hit_tags`
--
ALTER TABLE `post_hit_tags`
  ADD CONSTRAINT `fk_post_hit_tags_post_hit_id` FOREIGN KEY (`post_hit_id`) REFERENCES `post_hit` (`id`),
  ADD CONSTRAINT `fk_post_hit_tags_tags_id` FOREIGN KEY (`tag_id`) REFERENCES `tags` (`id`);

--
-- Contraintes pour la table `user`
--
ALTER TABLE `user`
  ADD CONSTRAINT `user_ibfk_1` FOREIGN KEY (`status_id`) REFERENCES `status` (`id`),
  ADD CONSTRAINT `user_ibfk_10` FOREIGN KEY (`role_id`) REFERENCES `role` (`id`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
