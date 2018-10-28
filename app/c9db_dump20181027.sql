-- phpMyAdmin SQL Dump
-- version 4.0.10deb1
-- http://www.phpmyadmin.net
--
-- Client: 127.0.0.1
-- Généré le: Sam 27 Octobre 2018 à 12:03
-- Version du serveur: 5.5.57-0ubuntu0.14.04.1
-- Version de PHP: 5.5.9-1ubuntu4.22

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de données: `c9`
--

-- --------------------------------------------------------

--
-- Structure de la table `display_board`
--

CREATE TABLE IF NOT EXISTS `display_board` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `latitude` double NOT NULL,
  `longitude` double NOT NULL,
  `status_id` int(11) NOT NULL,
  `altitude` double DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_display_board_status_id` (`status_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

--
-- Contenu de la table `display_board`
--

INSERT INTO `display_board` (`id`, `name`, `latitude`, `longitude`, `status_id`, `altitude`, `description`) VALUES
(1, 'L''arc de triomphe triomphe', 48.8048395, 2.4047431, 5, -50, 'Emplacement Arc de triomphe'),
(2, 'La mona lisa dans toute sa splendeur', 48.768709, 2.4131314, 5, 10, 'Le Louvre / Mona Lisa'),
(3, 'Another World displayboard', 48.7546995, 2.4607821, 5, 300, 'You don''t want to know'),
(4, 'sucy', 48.769355, 2.502658, 5, 50, 'sucy en brie test');

-- --------------------------------------------------------

--
-- Structure de la table `like_post_it`
--

CREATE TABLE IF NOT EXISTS `like_post_it` (
  `post_it_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `opinion_type_id` int(11) NOT NULL,
  KEY `post_it_id` (`post_it_id`),
  KEY `user_id` (`user_id`),
  KEY `opinion_type_id` (`opinion_type_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `opinion_type`
--

CREATE TABLE IF NOT EXISTS `opinion_type` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(128) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Contenu de la table `opinion_type`
--

INSERT INTO `opinion_type` (`id`, `name`) VALUES
(1, 'LIKE'),
(2, 'DISLIKE');

-- --------------------------------------------------------

--
-- Structure de la table `post_hit`
--

CREATE TABLE IF NOT EXISTS `post_hit` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `latitude` double NOT NULL,
  `longitude` double NOT NULL,
  `axeXYZ` varchar(32) NOT NULL,
  `message` varchar(2048) NOT NULL,
  `reputation` int(11) NOT NULL DEFAULT '0',
  `display_board_id` int(11) NOT NULL,
  `status_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `display_board_id` (`display_board_id`),
  KEY `post_it_ibfk_2` (`status_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

--
-- Contenu de la table `post_hit`
--

INSERT INTO `post_hit` (`id`, `latitude`, `longitude`, `axeXYZ`, `message`, `reputation`, `display_board_id`, `status_id`, `user_id`) VALUES
(1, 48.7632914, 2.4137477, '8408,8408,4804', 'Information Officielle : \nL''arc de Constantin à Rome, ive siècle.\n\nL''arc de triomphe de l''Étoile à Paris, xixe siècle.\nUn arc de triomphe, et plus généralement un arc monumental, est une structure libre monumentale enjambant une voie et utilisant la forme architecturale de l''arc avec un ou plusieurs passages voûtés. Ce type d''ouvrages est un des éléments les plus caractéristiques de l''architecture romaine, utilisé pour commémorer les généraux victorieux ou les évènements importants comme le décès d''un membre de la famille impériale, l''accession au trône d''un nouvel empereur ou encore les fondations de nouvelles colonies, la construction d''une route ou d''un pont.', 50, 1, 5, 1),
(3, 4848484.4848, 777.78484, '8408,8408,4804', 'Information Officielle :La Joconde\nMona Lisa, by Leonardo da Vinci, from C2RMF retouched.jpg\nArtiste	\nLéonard de Vinci\nDate	\nEntre 1503 et 1506 ou entre 1513 et 1516, peut-être jusqu''à 1519\nCommanditaire	\nFrancesco del Giocondo\nType	\nHuile sur panneau de bois de peuplier\nLieu de création	\nFlorence\nDimensions (H × L)	\n77 × 53 cm\nMouvement	\nHaute Renaissance\nLocalisation	\nMusée du Louvre, Peintures italiennes, salle 6, Paris (France)\nPropriétaire	\nPropriété de l''État français, affectée à la collection du Département des peintures du Louvre. Protégée au titre de bien d''un musée de France.\nNuméro d’inventaire	\nINV. 779\nmodifier - modifier le code - modifier WikidataDocumentation du modèle\n\nLa Joconde, ou Portrait de Mona Lisa1 voire simplement Mona Lisa, est un tableau de l''artiste Léonard de Vinci, réalisé entre 1503 et 1506 ou entre 1513 et 15162,3, et peut-être jusqu''à 15194, qui représente un portrait mi-corps, probablement celui de la Florentine Lisa Gherardini, épouse de Francesco del Giocondo. Acquise par François Ier, cette peinture à l''huile sur panneau de bois de peuplier de 77 × 53 cm est exposée au musée du Louvre à Paris. La Joconde est l''un des rares tableaux attribués de façon certaine à Léonard de Vinci.', 50, 2, 5, 1),
(4, 10561.8448, 10561.8448, '840,84,84', 'Anecdote d''un fin connaisseur: L’Arc de Triomphe a bien failli être un éléphant! (je me rappelle plus des détails)', 50, 1, 5, 3);

-- --------------------------------------------------------

--
-- Structure de la table `post_hit_tags`
--

CREATE TABLE IF NOT EXISTS `post_hit_tags` (
  `post_hit_id` int(11) NOT NULL,
  `tag_id` int(11) NOT NULL,
  PRIMARY KEY (`tag_id`,`post_hit_id`),
  KEY `fk_post_hit_tags_post_hit_id` (`post_hit_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `reporting`
--

CREATE TABLE IF NOT EXISTS `reporting` (
  `post_hit_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `comment` varchar(1024) NOT NULL,
  PRIMARY KEY (`user_id`,`post_hit_id`),
  KEY `post_hit_id` (`post_hit_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `role`
--

CREATE TABLE IF NOT EXISTS `role` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

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

CREATE TABLE IF NOT EXISTS `status` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=7 ;

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

CREATE TABLE IF NOT EXISTS `tags` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=12 ;

--
-- Contenu de la table `tags`
--

INSERT INTO `tags` (`id`, `name`) VALUES
(1, 'nanananaan'),
(2, 'pouetpouet'),
(3, 'bimbim'),
(4, 'ltd and lgn are the same than display_board 2'),
(5, 'axeXYZ is unchanged'),
(6, '20/11/17'),
(7, 'machin'),
(8, 'new try post hit'),
(9, 'machin edited'),
(10, 'nanananaan edited'),
(11, 'new try post hit edited');

-- --------------------------------------------------------

--
-- Structure de la table `user`
--

CREATE TABLE IF NOT EXISTS `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pseudo` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role_id` int(11) NOT NULL,
  `status_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `pseudo` (`pseudo`),
  UNIQUE KEY `email` (`email`),
  KEY `status_id` (`status_id`),
  KEY `role_id` (`role_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=7 ;

--
-- Contenu de la table `user`
--

INSERT INTO `user` (`id`, `pseudo`, `email`, `password`, `role_id`, `status_id`) VALUES
(1, 'pliGroup', 'pli@posthit.com', '1234', 2, 5),
(2, 'notwak', 'notwak@etna.com', '1234', 2, 5),
(3, 'Bill', 'castel_a@etna-alternance.net', '1234', 2, 5),
(5, 'dummy', 'dummya@etna-alternace.net', 'dummy', 2, 5),
(6, 'dummy1', 'dummy@etna-alternance.net', 'dummy', 2, 5);

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
-- Contraintes pour la table `reporting`
--
ALTER TABLE `reporting`
  ADD CONSTRAINT `reporting_ibfk_1` FOREIGN KEY (`post_hit_id`) REFERENCES `post_hit` (`id`),
  ADD CONSTRAINT `reporting_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);

--
-- Contraintes pour la table `user`
--
ALTER TABLE `user`
  ADD CONSTRAINT `user_ibfk_1` FOREIGN KEY (`status_id`) REFERENCES `status` (`id`),
  ADD CONSTRAINT `user_ibfk_10` FOREIGN KEY (`role_id`) REFERENCES `role` (`id`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
