-- MySQL dump 10.13  Distrib 5.5.57, for debian-linux-gnu (x86_64)
--
-- Host: localhost    Database: c9
-- ------------------------------------------------------
-- Server version	5.5.57-0ubuntu0.14.04.1

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `display_board`
--

DROP TABLE IF EXISTS `display_board`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `display_board` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `latitude` double NOT NULL,
  `longitude` double NOT NULL,
  `status_id` int(11) NOT NULL,
  `altitude` double DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_display_board_status_id` (`status_id`),
  CONSTRAINT `fk_display_board_status_id` FOREIGN KEY (`status_id`) REFERENCES `status` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `display_board`
--

LOCK TABLES `display_board` WRITE;
/*!40000 ALTER TABLE `display_board` DISABLE KEYS */;
INSERT INTO `display_board` VALUES (1,'ETNA Restaurant',48.7632914,2.4137477,5,10,'Restaurant communautaire, ramenez votre nourriture'),(2,'Comme à la maison',48.768709,2.4131314,5,10,'Restau Asiat\' reconnu partout dans Choisy le Roi');
/*!40000 ALTER TABLE `display_board` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `like_post_it`
--

DROP TABLE IF EXISTS `like_post_it`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `like_post_it` (
  `post_it_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `opinion_type_id` int(11) NOT NULL,
  KEY `post_it_id` (`post_it_id`),
  KEY `user_id` (`user_id`),
  KEY `opinion_type_id` (`opinion_type_id`),
  CONSTRAINT `like_post_it_ibfk_1` FOREIGN KEY (`post_it_id`) REFERENCES `post_hit` (`id`),
  CONSTRAINT `like_post_it_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`),
  CONSTRAINT `like_post_it_ibfk_3` FOREIGN KEY (`opinion_type_id`) REFERENCES `opinion_type` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `like_post_it`
--

LOCK TABLES `like_post_it` WRITE;
/*!40000 ALTER TABLE `like_post_it` DISABLE KEYS */;
/*!40000 ALTER TABLE `like_post_it` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `opinion_type`
--

DROP TABLE IF EXISTS `opinion_type`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `opinion_type` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(128) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `opinion_type`
--

LOCK TABLES `opinion_type` WRITE;
/*!40000 ALTER TABLE `opinion_type` DISABLE KEYS */;
INSERT INTO `opinion_type` VALUES (1,'LIKE'),(2,'DISLIKE'),(3,'REPORT');
/*!40000 ALTER TABLE `opinion_type` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `post_hit`
--

DROP TABLE IF EXISTS `post_hit`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `post_hit` (
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
  KEY `post_it_ibfk_2` (`status_id`),
  CONSTRAINT `post_hit_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`),
  CONSTRAINT `post_hit_ibfk_2` FOREIGN KEY (`status_id`) REFERENCES `status` (`id`),
  CONSTRAINT `post_hit_ibfk_3` FOREIGN KEY (`display_board_id`) REFERENCES `display_board` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `post_hit`
--

LOCK TABLES `post_hit` WRITE;
/*!40000 ALTER TABLE `post_hit` DISABLE KEYS */;
INSERT INTO `post_hit` VALUES (1,48.7632914,2.4137477,'8408,8408,4804','zefefzegqrzgzrgzqrrzqg',50,1,5,1),(2,65999.94959,595959.5448,'1515,8484,578','coucou les loulou',50,1,5,2),(3,4848484.4848,777.78484,'8408,8408,4804','zefefzegqrzgzrgzqrrzqg',50,2,5,1),(4,10561.8448,10561.8448,'840,84,84','HEllo my new post hit',50,1,5,3),(5,65999.94959,595959.5448,'1515,8484,578','coucou les loulou',50,2,5,2),(6,10561.8448,10561.8448,'840,84,84','HEllo my new post hit',50,2,5,3),(7,48.768709,2.4131314,'Post It test from dummy to displ','840,84,84',50,2,5,5),(9,10561.8448,10561.8448,'dummy message board 1','dummy message board 1 edited',50,1,5,5);
/*!40000 ALTER TABLE `post_hit` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `post_hit_tags`
--

DROP TABLE IF EXISTS `post_hit_tags`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `post_hit_tags` (
  `post_hit_id` int(11) NOT NULL,
  `tag_id` int(11) NOT NULL,
  PRIMARY KEY (`tag_id`,`post_hit_id`),
  KEY `fk_post_hit_tags_post_hit_id` (`post_hit_id`),
  CONSTRAINT `fk_post_hit_tags_post_hit_id` FOREIGN KEY (`post_hit_id`) REFERENCES `post_hit` (`id`),
  CONSTRAINT `fk_post_hit_tags_tags_id` FOREIGN KEY (`tag_id`) REFERENCES `tags` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `post_hit_tags`
--

LOCK TABLES `post_hit_tags` WRITE;
/*!40000 ALTER TABLE `post_hit_tags` DISABLE KEYS */;
INSERT INTO `post_hit_tags` VALUES (7,4),(7,5),(7,6),(9,9),(9,10),(9,11);
/*!40000 ALTER TABLE `post_hit_tags` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `role`
--

DROP TABLE IF EXISTS `role`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `role` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `role`
--

LOCK TABLES `role` WRITE;
/*!40000 ALTER TABLE `role` DISABLE KEYS */;
INSERT INTO `role` VALUES (1,'ADMIN'),(2,'USER');
/*!40000 ALTER TABLE `role` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `status`
--

DROP TABLE IF EXISTS `status`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `status` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `status`
--

LOCK TABLES `status` WRITE;
/*!40000 ALTER TABLE `status` DISABLE KEYS */;
INSERT INTO `status` VALUES (1,'WAITING VALIDATION REPORT'),(2,'REPORTED'),(3,'DELETED'),(4,'BANISHED'),(5,'VALIDATED'),(6,'PENDING VALIDATION');
/*!40000 ALTER TABLE `status` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tags`
--

DROP TABLE IF EXISTS `tags`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tags` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tags`
--

LOCK TABLES `tags` WRITE;
/*!40000 ALTER TABLE `tags` DISABLE KEYS */;
INSERT INTO `tags` VALUES (1,'nanananaan'),(2,'pouetpouet'),(3,'bimbim'),(4,'ltd and lgn are the same than display_board 2'),(5,'axeXYZ is unchanged'),(6,'20/11/17'),(7,'machin'),(8,'new try post hit'),(9,'machin edited'),(10,'nanananaan edited'),(11,'new try post hit edited');
/*!40000 ALTER TABLE `tags` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user`
--

DROP TABLE IF EXISTS `user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user` (
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
  KEY `role_id` (`role_id`),
  CONSTRAINT `user_ibfk_1` FOREIGN KEY (`status_id`) REFERENCES `status` (`id`),
  CONSTRAINT `user_ibfk_10` FOREIGN KEY (`role_id`) REFERENCES `role` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user`
--

LOCK TABLES `user` WRITE;
/*!40000 ALTER TABLE `user` DISABLE KEYS */;
INSERT INTO `user` VALUES (1,'pliGroup','pli@posthit.com','1234',2,5),(2,'notwak','notwak@etna.com','1234',2,5),(3,'Bill','castel_a@etna-alternance.net','1234',2,5),(5,'dummy','dummya@etna-alternace.net','dummy',2,5),(6,'dummy1','dummy@etna-alternance.net','dummy',2,5);
/*!40000 ALTER TABLE `user` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2017-11-23 12:19:34
