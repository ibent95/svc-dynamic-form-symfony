-- MariaDB dump 10.17  Distrib 10.4.14-MariaDB, for Win64 (AMD64)
--
-- Host: localhost    Database: dynamic_form_app
-- ------------------------------------------------------
-- Server version	10.5.15-MariaDB-1:10.5.15+maria~focal

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `doctrine_migration_versions`
--

DROP TABLE IF EXISTS `doctrine_migration_versions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `doctrine_migration_versions` (
  `version` varchar(191) COLLATE utf8_unicode_ci NOT NULL,
  `executed_at` datetime DEFAULT NULL,
  `execution_time` int(11) DEFAULT NULL,
  PRIMARY KEY (`version`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `doctrine_migration_versions`
--

LOCK TABLES `doctrine_migration_versions` WRITE;
/*!40000 ALTER TABLE `doctrine_migration_versions` DISABLE KEYS */;
INSERT INTO `doctrine_migration_versions` VALUES ('DoctrineMigrations\\Version20220409134804','2022-04-09 20:48:58',365);
/*!40000 ALTER TABLE `doctrine_migration_versions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `form`
--

DROP TABLE IF EXISTS `form`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `form` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `publication_form_version_id` bigint(20) unsigned DEFAULT NULL,
  `id_form_version` bigint(20) NOT NULL,
  `id_form_parent` bigint(20) DEFAULT NULL,
  `field_label` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `field_type` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `field_name` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `field_id` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `field_class` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `field_placeholder` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `field_options` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `error_message` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `validation_config` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `dependency_child` longtext COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '(DC2Type:json)',
  `dependency_parent` longtext COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '(DC2Type:json)',
  `flag_required` tinyint(1) NOT NULL DEFAULT 0,
  `flag_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_user` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_user` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `updated_at` datetime NOT NULL,
  `uuid` char(36) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '(DC2Type:guid)',
  PRIMARY KEY (`id`),
  KEY `IDX_5288FD4F51C5150D` (`publication_form_version_id`),
  CONSTRAINT `FK_5288FD4F51C5150D` FOREIGN KEY (`publication_form_version_id`) REFERENCES `publication_form_version` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `form`
--

LOCK TABLES `form` WRITE;
/*!40000 ALTER TABLE `form` DISABLE KEYS */;
INSERT INTO `form` VALUES (1,1,1,NULL,'Try text','text','try_text','try_text','try_text',NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,1,'system','2022-04-09 21:43:00','system','2022-04-09 21:43:00','39357f6a-52bd-44f3-ba57-49cc143b941a'),(2,1,1,NULL,'Try select','select','try_select','try_select','try_select',NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,1,'system','2022-04-09 21:43:00','system','2022-04-09 21:43:00','18b1c813-d6a6-444e-a4d1-5848c750ef7a');
/*!40000 ALTER TABLE `form` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `publication`
--

DROP TABLE IF EXISTS `publication`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `publication` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `publication_general_type_id` bigint(20) unsigned NOT NULL,
  `publication_type_id` bigint(20) unsigned NOT NULL,
  `publication_form_version_id` bigint(20) unsigned NOT NULL,
  `publication_status_id` bigint(20) unsigned NOT NULL,
  `title` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `id_publication_general_type` bigint(20) NOT NULL,
  `id_publication_type` bigint(20) NOT NULL,
  `id_publication_form_version` bigint(20) NOT NULL,
  `id_publication_status` bigint(20) NOT NULL,
  `publication_date` datetime DEFAULT NULL,
  `flag_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_user` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_user` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `updated_at` datetime NOT NULL,
  `uuid` char(36) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '(DC2Type:guid)',
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_AF3C6779A7D25F7B` (`publication_general_type_id`),
  UNIQUE KEY `UNIQ_AF3C6779CDC55AAF` (`publication_type_id`),
  UNIQUE KEY `UNIQ_AF3C677951C5150D` (`publication_form_version_id`),
  UNIQUE KEY `UNIQ_AF3C677977B4062A` (`publication_status_id`),
  CONSTRAINT `FK_AF3C677951C5150D` FOREIGN KEY (`publication_form_version_id`) REFERENCES `publication_form_version` (`id`),
  CONSTRAINT `FK_AF3C677977B4062A` FOREIGN KEY (`publication_status_id`) REFERENCES `publication_status` (`id`),
  CONSTRAINT `FK_AF3C6779A7D25F7B` FOREIGN KEY (`publication_general_type_id`) REFERENCES `publication_general_type` (`id`),
  CONSTRAINT `FK_AF3C6779CDC55AAF` FOREIGN KEY (`publication_type_id`) REFERENCES `publication_type` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `publication`
--

LOCK TABLES `publication` WRITE;
/*!40000 ALTER TABLE `publication` DISABLE KEYS */;
/*!40000 ALTER TABLE `publication` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `publication_form_version`
--

DROP TABLE IF EXISTS `publication_form_version`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `publication_form_version` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `publication_type_id` bigint(20) unsigned DEFAULT NULL,
  `publication_form_version_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `publication_form_version_code` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `flag_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_user` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_user` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `updated_at` datetime NOT NULL,
  `uuid` char(36) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '(DC2Type:guid)',
  PRIMARY KEY (`id`),
  KEY `IDX_CFA3AA3DCDC55AAF` (`publication_type_id`),
  CONSTRAINT `FK_CFA3AA3DCDC55AAF` FOREIGN KEY (`publication_type_id`) REFERENCES `publication_type` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `publication_form_version`
--

LOCK TABLES `publication_form_version` WRITE;
/*!40000 ALTER TABLE `publication_form_version` DISABLE KEYS */;
INSERT INTO `publication_form_version` VALUES (1,1,'Book type v1','BOK-1-v1',1,'system','2022-04-09 21:43:00','system','2022-04-09 21:43:00','d152c092-1141-4640-846d-24a6ef91b646');
/*!40000 ALTER TABLE `publication_form_version` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `publication_general_type`
--

DROP TABLE IF EXISTS `publication_general_type`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `publication_general_type` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `publication_general_type_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `publication_general_type_code` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `flag_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_user` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_user` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `updated_at` datetime NOT NULL,
  `uuid` char(36) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '(DC2Type:guid)',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `publication_general_type`
--

LOCK TABLES `publication_general_type` WRITE;
/*!40000 ALTER TABLE `publication_general_type` DISABLE KEYS */;
INSERT INTO `publication_general_type` VALUES (1,'Book','BOK',1,'system','2022-04-09 21:43:00','system','2022-04-09 21:43:00','fed02ab7-1f41-472b-b451-a893f0485e33'),(2,'Journal','JUR',1,'system','2022-04-09 21:43:00','system','2022-04-09 21:43:00','91aa58b3-46fa-4ae2-bac8-b2eda520fd09'),(3,'Proceedings','PRO',1,'system','2022-04-09 21:43:00','system','2022-04-09 21:43:00','5bb3a2b0-d603-4bec-bc53-0e35d0034ae2');
/*!40000 ALTER TABLE `publication_general_type` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `publication_meta`
--

DROP TABLE IF EXISTS `publication_meta`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `publication_meta` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `publication_id` bigint(20) unsigned DEFAULT NULL,
  `id_publication` bigint(20) NOT NULL,
  `field_name` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `field_value` varchar(1000) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `flag_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_user` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_user` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `updated_at` datetime NOT NULL,
  `uuid` char(36) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '(DC2Type:guid)',
  PRIMARY KEY (`id`),
  KEY `IDX_DC0A95F838B217A7` (`publication_id`),
  CONSTRAINT `FK_DC0A95F838B217A7` FOREIGN KEY (`publication_id`) REFERENCES `publication` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `publication_meta`
--

LOCK TABLES `publication_meta` WRITE;
/*!40000 ALTER TABLE `publication_meta` DISABLE KEYS */;
/*!40000 ALTER TABLE `publication_meta` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `publication_status`
--

DROP TABLE IF EXISTS `publication_status`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `publication_status` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `publication_status_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `publication_status_code` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `flag_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_user` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_user` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `updated_at` datetime NOT NULL,
  `uuid` char(36) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '(DC2Type:guid)',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `publication_status`
--

LOCK TABLES `publication_status` WRITE;
/*!40000 ALTER TABLE `publication_status` DISABLE KEYS */;
/*!40000 ALTER TABLE `publication_status` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `publication_type`
--

DROP TABLE IF EXISTS `publication_type`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `publication_type` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `publication_general_type_id` bigint(20) unsigned DEFAULT NULL,
  `publication_type_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `publication_type_code` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `id_publication_general_type` bigint(20) NOT NULL,
  `flag_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_user` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_user` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `updated_at` datetime NOT NULL,
  `uuid` char(36) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '(DC2Type:guid)',
  PRIMARY KEY (`id`),
  KEY `IDX_8726D6E4A7D25F7B` (`publication_general_type_id`),
  CONSTRAINT `FK_8726D6E4A7D25F7B` FOREIGN KEY (`publication_general_type_id`) REFERENCES `publication_general_type` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `publication_type`
--

LOCK TABLES `publication_type` WRITE;
/*!40000 ALTER TABLE `publication_type` DISABLE KEYS */;
INSERT INTO `publication_type` VALUES (1,1,'Book type','BOK-1',1,1,'system','2022-04-09 21:43:00','system','2022-04-09 21:43:00','a23892cd-6811-44bd-a671-c85b87829887');
/*!40000 ALTER TABLE `publication_type` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping routines for database 'dynamic_form_app'
--
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2022-04-09 22:57:05
