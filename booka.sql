-- MySQL dump 10.13  Distrib 5.7.26, for Linux (x86_64)
--
-- Host: localhost    Database: booka
-- ------------------------------------------------------
-- Server version	5.7.26-0ubuntu0.18.04.1

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
-- Table structure for table `book`
--

DROP TABLE IF EXISTS `book`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `book` (
  `book_id` int(11) NOT NULL AUTO_INCREMENT,
  `status_id` int(11) NOT NULL DEFAULT '1',
  `book_createdBy` int(11) NOT NULL,
  `statusLend_id` int(11) NOT NULL DEFAULT '2',
  `book_name` varchar(300) NOT NULL,
  `book_url` varchar(300) NOT NULL,
  `book_encrypted` varchar(300) NOT NULL,
  `book_media` text NOT NULL,
  `book_description` text NOT NULL,
  `book_created` year(4) NOT NULL,
  `book_autor` varchar(300) NOT NULL,
  `book_creationDate` datetime NOT NULL,
  `book_lastModification` datetime DEFAULT NULL,
  PRIMARY KEY (`book_id`),
  UNIQUE KEY `book_id_UNIQUE` (`book_id`),
  UNIQUE KEY `book_url_UNIQUE` (`book_url`),
  UNIQUE KEY `book_encrypted_UNIQUE` (`book_encrypted`),
  KEY `book_status_idx` (`status_id`),
  KEY `book_user_idx` (`book_createdBy`),
  KEY `book_statusLend_idx` (`statusLend_id`),
  CONSTRAINT `book_status` FOREIGN KEY (`status_id`) REFERENCES `status` (`status_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `book_statusLend` FOREIGN KEY (`statusLend_id`) REFERENCES `status_lend` (`statusLend_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `book_user` FOREIGN KEY (`book_createdBy`) REFERENCES `user` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `book`
--

LOCK TABLES `book` WRITE;
/*!40000 ALTER TABLE `book` DISABLE KEYS */;
INSERT INTO `book` VALUES (1,1,1,2,'Cien años de Soledad','cien-anio-soledad','dny73uedh','book/cien-anio-soledad.png','Cien a',1967,'Gabriel garcia Marquez','2019-05-13 00:00:00',NULL),(2,1,1,1,'libro test','libro-test','3ae7227ccd1f4c498fab5e66594cfe41','book/libro-test.png','hola libro descitpcion',2019,'lo que sea','2019-05-15 05:14:47','2019-05-16 02:32:13');
/*!40000 ALTER TABLE `book` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `book_attributes`
--

DROP TABLE IF EXISTS `book_attributes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `book_attributes` (
  `bookAttributes_id` int(11) NOT NULL AUTO_INCREMENT,
  `status_id` int(11) NOT NULL DEFAULT '1',
  `bookAttributes_createdBy` int(11) NOT NULL,
  `bookAttributes_name` varchar(300) NOT NULL,
  `bookAttributes_encrypted` varchar(300) NOT NULL,
  `bookAttributes_description` text,
  `bookAttributes_creationDate` datetime NOT NULL,
  `bookAttributes_lastModification` datetime DEFAULT NULL,
  PRIMARY KEY (`bookAttributes_id`),
  UNIQUE KEY `bookAttributes_id_UNIQUE` (`bookAttributes_id`),
  UNIQUE KEY `bookAttributes_name_UNIQUE` (`bookAttributes_name`),
  UNIQUE KEY `bookAttributes_encrypted_UNIQUE` (`bookAttributes_encrypted`),
  KEY `bookAttributes_status_idx` (`status_id`),
  KEY `bookAttributes_user_idx` (`bookAttributes_createdBy`),
  CONSTRAINT `bookAttributes_status` FOREIGN KEY (`status_id`) REFERENCES `status` (`status_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `bookAttributes_user` FOREIGN KEY (`bookAttributes_createdBy`) REFERENCES `user` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='atributos del libro que van a poder ser elegidos al crear el libro';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `book_attributes`
--

LOCK TABLES `book_attributes` WRITE;
/*!40000 ALTER TABLE `book_attributes` DISABLE KEYS */;
/*!40000 ALTER TABLE `book_attributes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `book_bookAttributes`
--

DROP TABLE IF EXISTS `book_bookAttributes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `book_bookAttributes` (
  `bookBookAttributes_id` int(11) NOT NULL AUTO_INCREMENT,
  `status_id` int(11) NOT NULL DEFAULT '1',
  `book_id` int(11) NOT NULL,
  `bookAttributes_id` int(11) NOT NULL,
  `bookBookAttributes_name` varchar(300) DEFAULT NULL,
  `bookBookAttributes_encrypted` varchar(300) NOT NULL,
  `bookBookAttributes_description` text,
  `bookBookAttributes_creationDate` datetime NOT NULL,
  `bookBookAttributes_lastModification` datetime DEFAULT NULL,
  PRIMARY KEY (`bookBookAttributes_id`),
  UNIQUE KEY `bookBookAttributes_id_UNIQUE` (`bookBookAttributes_id`),
  UNIQUE KEY `bookBookAttributes_encrypted_UNIQUE` (`bookBookAttributes_encrypted`),
  KEY `bookBookAttributes_status_idx` (`status_id`),
  KEY `bookBookAttributes_book_idx` (`book_id`),
  KEY `bookBookAttributes_BookAttributes_idx` (`bookAttributes_id`),
  CONSTRAINT `bookBookAttributes_BookAttributes` FOREIGN KEY (`bookAttributes_id`) REFERENCES `book_attributes` (`bookAttributes_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `bookBookAttributes_book` FOREIGN KEY (`book_id`) REFERENCES `book` (`book_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `bookBookAttributes_status` FOREIGN KEY (`status_id`) REFERENCES `status` (`status_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `book_bookAttributes`
--

LOCK TABLES `book_bookAttributes` WRITE;
/*!40000 ALTER TABLE `book_bookAttributes` DISABLE KEYS */;
/*!40000 ALTER TABLE `book_bookAttributes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `book_lend`
--

DROP TABLE IF EXISTS `book_lend`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `book_lend` (
  `bookLend_id` int(11) NOT NULL AUTO_INCREMENT,
  `status_id` int(11) NOT NULL DEFAULT '1',
  `user_id` int(11) NOT NULL,
  `book_id` int(11) NOT NULL,
  `bookLend_name` varchar(300) DEFAULT NULL,
  `bookLend_encrypted` varchar(300) NOT NULL,
  `bookLend_description` text,
  `bookLend_creationDate` datetime NOT NULL,
  `bookLend_lastModification` datetime DEFAULT NULL,
  PRIMARY KEY (`bookLend_id`),
  UNIQUE KEY `bookLend_id_UNIQUE` (`bookLend_id`),
  UNIQUE KEY `bookLend_encrypted_UNIQUE` (`bookLend_encrypted`),
  KEY `bookLend_status_idx` (`status_id`),
  KEY `bookLend_user_idx` (`user_id`),
  KEY `bookLend_book_idx` (`book_id`),
  CONSTRAINT `bookLend_book` FOREIGN KEY (`book_id`) REFERENCES `book` (`book_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `bookLend_status` FOREIGN KEY (`status_id`) REFERENCES `status` (`status_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `bookLend_user` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=latin1 COMMENT='prestando libro';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `book_lend`
--

LOCK TABLES `book_lend` WRITE;
/*!40000 ALTER TABLE `book_lend` DISABLE KEYS */;
INSERT INTO `book_lend` VALUES (7,1,5,1,NULL,'dft4e',NULL,'2019-05-13 12:18:25','2019-05-16 01:38:03'),(8,1,1,2,NULL,'571a42ea05971f82d14cdcba2a1b5a50',NULL,'2019-05-16 00:45:24','2019-05-16 01:38:03'),(9,1,1,2,NULL,'ad5906451d7c668fe77ca6e1bb230b5f',NULL,'2019-05-16 01:40:17','2019-05-16 01:38:05'),(10,1,1,2,NULL,'a524834867cf1afba4593c5b38b68370',NULL,'2019-05-16 02:27:58','2019-05-16 02:30:14'),(11,1,5,2,NULL,'8412fcff4b46697317d1e2f85e2ded5d',NULL,'2019-05-16 02:32:13',NULL);
/*!40000 ALTER TABLE `book_lend` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `rol`
--

DROP TABLE IF EXISTS `rol`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `rol` (
  `rol_id` int(11) NOT NULL AUTO_INCREMENT,
  `status_id` int(11) NOT NULL DEFAULT '1',
  `rol_name` varchar(300) NOT NULL,
  `rol_encrypted` varchar(300) NOT NULL,
  `rol_description` text,
  `rol_crationDate` datetime DEFAULT NULL,
  `rol_lastModification` datetime DEFAULT NULL,
  PRIMARY KEY (`rol_id`),
  UNIQUE KEY `rol_id_UNIQUE` (`rol_id`),
  UNIQUE KEY `rol_name_UNIQUE` (`rol_name`),
  UNIQUE KEY `rol_encrypted_UNIQUE` (`rol_encrypted`),
  KEY `rol_status_idx` (`status_id`),
  CONSTRAINT `rol_status` FOREIGN KEY (`status_id`) REFERENCES `status` (`status_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1 COMMENT='Roles del usuario';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `rol`
--

LOCK TABLES `rol` WRITE;
/*!40000 ALTER TABLE `rol` DISABLE KEYS */;
INSERT INTO `rol` VALUES (1,1,'administrador','sdy73uedj',NULL,NULL,NULL),(2,1,'cliente','fgy54edfg',NULL,NULL,NULL);
/*!40000 ALTER TABLE `rol` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `status`
--

DROP TABLE IF EXISTS `status`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `status` (
  `status_id` int(11) NOT NULL AUTO_INCREMENT,
  `status_name` varchar(300) NOT NULL,
  `status_encrypted` varchar(300) NOT NULL,
  `status_description` text,
  `status_crationDate` datetime DEFAULT NULL,
  `status_lastModification` datetime DEFAULT NULL,
  PRIMARY KEY (`status_id`),
  UNIQUE KEY `status_id_UNIQUE` (`status_id`),
  UNIQUE KEY `status_name_UNIQUE` (`status_name`),
  UNIQUE KEY `status_encrypted_UNIQUE` (`status_encrypted`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1 COMMENT='status del sistema';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `status`
--

LOCK TABLES `status` WRITE;
/*!40000 ALTER TABLE `status` DISABLE KEYS */;
INSERT INTO `status` VALUES (1,'active','ahah22373haaj',NULL,NULL,NULL),(2,'delete','ghy654e',NULL,NULL,NULL),(3,'suspend','4566redfg',NULL,NULL,NULL);
/*!40000 ALTER TABLE `status` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `status_lend`
--

DROP TABLE IF EXISTS `status_lend`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `status_lend` (
  `statusLend_id` int(11) NOT NULL AUTO_INCREMENT,
  `status_id` int(11) NOT NULL DEFAULT '1',
  `statusLend_name` varchar(300) NOT NULL,
  `statusLend_encrypted` varchar(300) NOT NULL,
  `statusLend_description` text,
  `statusLend_creationDate` datetime NOT NULL,
  `statusLend_lastModification` datetime DEFAULT NULL,
  PRIMARY KEY (`statusLend_id`),
  UNIQUE KEY `statusLend_id_UNIQUE` (`statusLend_id`),
  UNIQUE KEY `statusLend_name_UNIQUE` (`statusLend_name`),
  UNIQUE KEY `statusLend_encrypted_UNIQUE` (`statusLend_encrypted`),
  KEY `statusLend_status_idx` (`status_id`),
  CONSTRAINT `statusLend_status` FOREIGN KEY (`status_id`) REFERENCES `status` (`status_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1 COMMENT='status de un presto';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `status_lend`
--

LOCK TABLES `status_lend` WRITE;
/*!40000 ALTER TABLE `status_lend` DISABLE KEYS */;
INSERT INTO `status_lend` VALUES (1,1,'En Prestamo','gt4rf',NULL,'2019-05-13 12:00:00',NULL),(2,1,'Libre','fe345',NULL,'2019-05-13 12:00:00',NULL),(3,1,'Extraviado','hg45',NULL,'2019-05-13 12:00:00',NULL);
/*!40000 ALTER TABLE `status_lend` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user`
--

DROP TABLE IF EXISTS `user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT,
  `status_id` int(11) NOT NULL DEFAULT '1',
  `rol_id` int(11) NOT NULL DEFAULT '2',
  `user_createdBy` int(11) NOT NULL,
  `user_name` varchar(300) NOT NULL,
  `user_lastName` varchar(300) NOT NULL,
  `user_email` varchar(300) NOT NULL,
  `user_nick` varchar(15) DEFAULT NULL,
  `user_encrypted` varchar(300) NOT NULL,
  `user_password` varchar(300) NOT NULL,
  `user_description` text,
  `user_creationDate` datetime NOT NULL,
  `user_lastModification` datetime DEFAULT NULL,
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `user_id_UNIQUE` (`user_id`),
  UNIQUE KEY `user_email_UNIQUE` (`user_email`),
  UNIQUE KEY `user_encrypted_UNIQUE` (`user_encrypted`),
  KEY `user_status_idx` (`status_id`),
  KEY `user_rol_idx` (`rol_id`),
  KEY `user_user_idx` (`user_createdBy`),
  CONSTRAINT `user_rol` FOREIGN KEY (`rol_id`) REFERENCES `rol` (`rol_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `user_status` FOREIGN KEY (`status_id`) REFERENCES `status` (`status_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `user_user` FOREIGN KEY (`user_createdBy`) REFERENCES `user` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user`
--

LOCK TABLES `user` WRITE;
/*!40000 ALTER TABLE `user` DISABLE KEYS */;
INSERT INTO `user` VALUES (1,1,1,1,'juan','franco','juan@gmail.com','juantest','123edjdjdj33','827ccb0eea8a706c4c34a16891f84e7b',NULL,'2019-05-12 12:00:00','2019-05-13 03:25:51'),(5,1,2,1,'aws update','test','test@booka.loc',NULL,'ac1b6687ee0e184ee41087eebf6a7aaf','827ccb0eea8a706c4c34a16891f84e7b',NULL,'2019-05-13 17:04:26','2019-05-15 02:43:00');
/*!40000 ALTER TABLE `user` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user_recovery`
--

DROP TABLE IF EXISTS `user_recovery`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user_recovery` (
  `userRecovery_id` int(11) NOT NULL AUTO_INCREMENT,
  `status_id` int(11) NOT NULL DEFAULT '1',
  `user_id` int(11) NOT NULL,
  `userRecovery_name` varchar(300) DEFAULT NULL,
  `userRecovery_encrypted` varchar(300) NOT NULL,
  `userRecovery_description` text,
  `userRecovery_create` date NOT NULL,
  `userRecovery_expired` date NOT NULL,
  `userRecovery_creationDate` datetime NOT NULL,
  `userRecovery_lastModification` datetime DEFAULT NULL,
  PRIMARY KEY (`userRecovery_id`),
  UNIQUE KEY `userRecovery_id_UNIQUE` (`userRecovery_id`),
  UNIQUE KEY `userRecovery_encrypted_UNIQUE` (`userRecovery_encrypted`),
  KEY `userRecovery_status_idx` (`status_id`),
  KEY `userRecovery_user_idx` (`user_id`),
  CONSTRAINT `userRecovery_status` FOREIGN KEY (`status_id`) REFERENCES `status` (`status_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `userRecovery_user` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=latin1 COMMENT='Recuperación de contraseña';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user_recovery`
--

LOCK TABLES `user_recovery` WRITE;
/*!40000 ALTER TABLE `user_recovery` DISABLE KEYS */;
INSERT INTO `user_recovery` VALUES (6,2,1,NULL,'e4ff59e044f9053451f7b6dcb863518a',NULL,'2019-05-13','2019-05-14','2019-05-13 02:39:49','2019-05-13 03:25:51');
/*!40000 ALTER TABLE `user_recovery` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user_session`
--

DROP TABLE IF EXISTS `user_session`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user_session` (
  `userSession_id` int(11) NOT NULL AUTO_INCREMENT,
  `status_id` int(11) NOT NULL DEFAULT '1',
  `user_id` int(11) NOT NULL,
  `userSession_name` varchar(300) DEFAULT NULL,
  `userSession_encrypted` varchar(300) NOT NULL,
  `userSession_ip` varchar(45) DEFAULT NULL,
  `userSession_description` text,
  `userSession_creationDate` datetime NOT NULL,
  `userSession_lastModification` datetime DEFAULT NULL,
  PRIMARY KEY (`userSession_id`),
  UNIQUE KEY `userSession_id_UNIQUE` (`userSession_id`),
  UNIQUE KEY `userSession_encrypted_UNIQUE` (`userSession_encrypted`),
  KEY `userSession_status_idx` (`status_id`),
  KEY `userSession_user_idx` (`user_id`),
  CONSTRAINT `userSession_status` FOREIGN KEY (`status_id`) REFERENCES `status` (`status_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `userSession_user` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=latin1 COMMENT='Se guarda los inicio de session del usuario';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user_session`
--

LOCK TABLES `user_session` WRITE;
/*!40000 ALTER TABLE `user_session` DISABLE KEYS */;
INSERT INTO `user_session` VALUES (1,1,1,NULL,'VRj0wWhMzS7XsI3gGUS4jKcKHaDaPW66IEN4Wic0','127.0.0.1',NULL,'2019-05-12 18:07:04',NULL),(2,1,1,NULL,'zm4jNgohCHWeL4sEDvMV5YlxUN5BchHO25JwyGtp','127.0.0.1',NULL,'2019-05-12 18:46:14',NULL),(3,1,1,NULL,'K828CrI5RcztnTZHYxnQUEglXaogIt4m0Qt0C2IP','127.0.0.1',NULL,'2019-05-12 18:51:13',NULL),(4,1,1,NULL,'nU8Sn0jG4ELYVrhK16bj9ENAmgLfthzumVC0Qy8A','127.0.0.1',NULL,'2019-05-13 15:08:34',NULL),(5,1,1,NULL,'r1cegcy984W2vk0Ouu1Ku8BWrXI4qGTysBXLAnnu','127.0.0.1',NULL,'2019-05-13 17:11:11',NULL),(6,1,5,NULL,'RROcQazaopz4wLoi4NbHVfjUESyrk5OEwz1gQdaK','127.0.0.1',NULL,'2019-05-13 17:15:16',NULL),(7,1,1,NULL,'LfUdQpy1gpUc9OfLIpIRKQGIyKqoSHTPkdd51rFf','127.0.0.1',NULL,'2019-05-13 17:15:32',NULL),(8,1,1,NULL,'CoEgnOEx3jo5T7KyQd1jl7QkviGyrRVagnetzKJy','127.0.0.1',NULL,'2019-05-15 00:20:18',NULL),(9,1,1,NULL,'W62vv9pJckUNEL1rjhyi40yx0E9D1ayLNrXHcCqF','127.0.0.1',NULL,'2019-05-15 01:25:22',NULL),(10,1,1,NULL,'EHVj8iApFMMUFn65OCYQixV1QtxH5OvOH7421xfr','127.0.0.1',NULL,'2019-05-15 02:43:27',NULL),(11,1,1,NULL,'F72t5pYB1psC9FU8Y4V83ZrdNwf5mDMFl3bXjdBZ','127.0.0.1',NULL,'2019-05-15 16:10:38',NULL),(12,1,1,NULL,'k87Of6ighlFUH5P5Z4xpW4KDks5qECzWfFbJhX8N','127.0.0.1',NULL,'2019-05-16 00:28:41',NULL),(13,1,5,NULL,'YIWIStUd81Sp2P9jMG4iwuvqXFTKCqBJQ3SyKdsP','127.0.0.1',NULL,'2019-05-16 02:31:35',NULL);
/*!40000 ALTER TABLE `user_session` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2019-05-16  4:31:54
