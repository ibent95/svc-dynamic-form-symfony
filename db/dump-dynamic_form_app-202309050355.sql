-- MySQL dump 10.13  Distrib 8.0.19, for Win64 (x86_64)
--
-- Host: localhost    Database: dynamic_form_app
-- ------------------------------------------------------
-- Server version	5.5.5-10.6.7-MariaDB-1:10.6.7+maria~focal

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8mb4 */;
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
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `doctrine_migration_versions` (
  `version` varchar(191) COLLATE utf8mb3_unicode_ci NOT NULL,
  `executed_at` datetime DEFAULT NULL,
  `execution_time` int(11) DEFAULT NULL,
  PRIMARY KEY (`version`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `doctrine_migration_versions`
--

LOCK TABLES `doctrine_migration_versions` WRITE;
/*!40000 ALTER TABLE `doctrine_migration_versions` DISABLE KEYS */;
INSERT INTO `doctrine_migration_versions` VALUES ('DoctrineMigrations\\Version20220409134804','2022-04-09 20:48:58',365),('DoctrineMigrations\\Version20220509052720','2022-05-09 12:27:32',1482);
/*!40000 ALTER TABLE `doctrine_migration_versions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `publication`
--

DROP TABLE IF EXISTS `publication`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
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
  KEY `IDX_AF3C6779A7D25F7B` (`publication_general_type_id`),
  KEY `IDX_AF3C6779CDC55AAF` (`publication_type_id`),
  KEY `IDX_AF3C677951C5150D` (`publication_form_version_id`),
  KEY `IDX_AF3C677977B4062A` (`publication_status_id`),
  CONSTRAINT `FK_AF3C677951C5150D` FOREIGN KEY (`publication_form_version_id`) REFERENCES `publication_form_version` (`id`),
  CONSTRAINT `FK_AF3C677977B4062A` FOREIGN KEY (`publication_status_id`) REFERENCES `publication_status` (`id`),
  CONSTRAINT `FK_AF3C6779A7D25F7B` FOREIGN KEY (`publication_general_type_id`) REFERENCES `publication_general_type` (`id`),
  CONSTRAINT `FK_AF3C6779CDC55AAF` FOREIGN KEY (`publication_type_id`) REFERENCES `publication_type` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `publication`
--

LOCK TABLES `publication` WRITE;
/*!40000 ALTER TABLE `publication` DISABLE KEYS */;
INSERT INTO `publication` VALUES (1,1,1,1,1,'A 001',1,1,1,1,'2022-04-09 21:43:00',1,'system','2022-04-09 21:43:00','system','2022-04-09 21:43:00','0b8d43cf-f030-4129-85ce-b64cc193bfb6'),(2,1,1,1,2,'A 002',1,1,1,2,'2022-04-09 21:43:00',1,'system','2022-04-09 21:43:00','system','2022-04-09 21:43:00','5161618d-e25a-4e58-bd8f-c1b7d3fc085b'),(3,1,1,1,3,'B 001',1,1,1,3,'2022-04-09 21:43:00',1,'system','2022-04-09 21:43:00','system','2022-04-09 21:43:00','de6e6332-c2cf-413e-b796-8542c2a65ff7'),(4,1,1,1,4,'A 003',1,1,1,4,'2022-04-09 21:43:00',1,'system','2022-04-09 21:43:00','system','2022-04-09 21:43:00','8e72019c-8d69-4f4f-a2e2-01ffec3b0139');
/*!40000 ALTER TABLE `publication` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `publication_form`
--

DROP TABLE IF EXISTS `publication_form`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `publication_form` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `publication_form_version_id` bigint(20) unsigned DEFAULT NULL,
  `form_version_id` bigint(20) NOT NULL,
  `form_parent_id` bigint(20) DEFAULT NULL,
  `field_label` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `field_type` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `field_name` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `field_id` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `field_class` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `field_placeholder` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `field_options` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `field_configs` longtext COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '(DC2Type:json)',
  `description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `order_position` int(11) DEFAULT NULL,
  `validation_configs` longtext COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '(DC2Type:json)',
  `error_message` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `dependency_child` longtext COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '(DC2Type:json)',
  `dependency_parent` longtext COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '(DC2Type:json)',
  `flag_required` tinyint(1) NOT NULL DEFAULT 0,
  `flag_field_publication_type` tinyint(1) NOT NULL DEFAULT 0,
  `flag_field_title` tinyint(1) NOT NULL DEFAULT 0,
  `flag_field_publication_date` tinyint(1) NOT NULL DEFAULT 0,
  `flag_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_user` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_user` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `updated_at` datetime NOT NULL,
  `uuid` char(36) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '(DC2Type:guid)',
  PRIMARY KEY (`id`),
  KEY `IDX_59707C8251C5150D` (`publication_form_version_id`),
  CONSTRAINT `FK_59707C8251C5150D` FOREIGN KEY (`publication_form_version_id`) REFERENCES `publication_form_version` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=44 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `publication_form`
--

LOCK TABLES `publication_form` WRITE;
/*!40000 ALTER TABLE `publication_form` DISABLE KEYS */;
INSERT INTO `publication_form` VALUES (1,1,1,NULL,'Try text','text','text_1','text_1','text_1','Try text placeholder',NULL,NULL,'Try text description',1,NULL,NULL,NULL,NULL,1,0,0,0,1,'system','2022-04-09 21:43:00','system','2022-04-09 21:43:00','39357f6a-52bd-44f3-ba57-49cc143b941a'),(2,1,1,NULL,'Try select','select','select_1','select_1','select_1','Try select placeholder','master-publication_status',NULL,'Try select description',2,NULL,NULL,NULL,NULL,0,0,0,0,1,'system','2022-04-09 21:43:00','system','2022-04-09 21:43:00','18b1c813-d6a6-444e-a4d1-5848c750ef7a'),(3,1,1,NULL,'Try stepper','stepper',NULL,'try_stepper_1','try_stepper_1',NULL,NULL,'{\"orientation\":\"horizontal\",\"linear\":true}',NULL,3,NULL,NULL,NULL,NULL,0,0,0,0,1,'system','2022-04-13 08:30:00','system','2022-04-13 08:30:00','950ad242-10d3-4ba7-bbd9-09037575c588'),(4,1,1,3,'Try step 1','step',NULL,'try_stepper_1_step_1','try_stepper_1_step_1',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,0,0,1,'system','2022-04-13 08:30:00','system','2022-04-13 08:30:00','b3b89437-a19f-48af-84fa-c77736ef73b6'),(5,1,1,3,'Try step 2','step',NULL,'try_stepper_1_step_2','try_stepper_1_step_2',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,0,0,1,'system','2022-04-13 08:30:00','system','2022-04-13 08:30:00','f19b54a8-94e7-474f-aa76-545996336b01'),(6,1,1,3,'Try step 3','step',NULL,'try_stepper_1_step_3','try_stepper_1_step_3',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,0,0,1,'system','2022-04-13 08:30:00','system','2022-04-13 08:30:00','6ab05f52-1028-487f-b86c-05b51c2ce0ab'),(7,1,1,5,'Try text in stepper','text','text_2','text_2','text_2','Try text in stepper placeholder',NULL,NULL,'Try text in stepper description',NULL,NULL,NULL,NULL,NULL,1,0,0,0,1,'system','2022-04-09 21:43:00','system','2022-04-09 21:43:00','45e94335-5ceb-48e5-80e1-cf71a01c86a7'),(8,1,1,5,'Try url in stepper 2','url','url_1','url_1','url_1','Try url in stepper placeholder',NULL,NULL,'Try url in stepper description',NULL,'{\"pattern\":\"^(https?:\\\\/\\\\/(?:www\\\\.|(?!www))[a-zA-Z0-9][a-zA-Z0-9-]+[a-zA-Z0-9]\\\\.[^\\\\s]{2,}|www\\\\.[a-zA-Z0-9][a-zA-Z0-9-]+[a-zA-Z0-9]\\\\.[^\\\\s]{2,}|https?:\\\\/\\\\/(?:www\\\\.|(?!www))[a-zA-Z0-9]+\\\\.[^\\\\s]{2,}|www\\\\.[a-zA-Z0-9]+\\\\.[^\\\\s]{2,})\"}','Fill in the field with valid url (Example: https://www.dynamic-form.local)',NULL,NULL,1,0,0,0,1,'system','2022-04-09 21:43:00','system','2022-04-09 21:43:00','bf0a8b79-fc5e-41bd-86e7-5738a4250923'),(9,1,1,5,'Try select in stepper 2','select','select_2','select_2','select_2','Try select in stepper placeholder','master-publication_status',NULL,'Try select in stepper description',NULL,NULL,NULL,NULL,NULL,1,0,0,0,1,'system','2022-04-09 21:43:00','system','2022-04-09 21:43:00','665b4ed0-98b5-41a9-9e48-8e749d23746c'),(10,1,1,6,'Try select in stepper 3','select','select_3','select_3','select_3','Try select in stepper placeholder','master-publication_status',NULL,'Try select in stepper description',NULL,NULL,NULL,NULL,NULL,1,0,0,0,1,'system','2022-04-09 21:43:00','system','2022-04-09 21:43:00','91752469-462f-46ae-99ed-4e5d10e1a8cb'),(11,1,1,6,'Try select in stepper 4','select','select_4','select_4','select_4','Try select in stepper placeholder','master-publication_status',NULL,'Try select in stepper description',NULL,NULL,NULL,NULL,NULL,1,0,0,0,1,'system','2022-04-09 21:43:00','system','2022-04-09 21:43:00','01103034-b518-4331-85de-39a34909a4e2'),(12,1,1,6,'Try select in stepper 5','select','select_5','select_5','select_5','Try select in stepper placeholder','master-publication_status',NULL,'Try select in stepper description',NULL,NULL,NULL,NULL,NULL,1,0,0,0,1,'system','2022-04-09 21:43:00','system','2022-04-09 21:43:00','00296c1d-8a43-488f-b2ee-168a6bb35f5c'),(13,1,1,6,'Try number','number','number_1','number_1','number_1','Try number placeholder',NULL,NULL,'Try number description',NULL,'{\"min\":1,\"pattern\":\"^[0-9]*$\"}','Fill in the field with numbers greater than 0',NULL,NULL,1,0,0,0,1,'system','2022-04-09 21:43:00','system','2022-04-09 21:43:00','6bfc3e0e-5fb0-482d-9521-5cc0132fd877'),(14,1,1,NULL,'URL of dynamic-form blog','url','url_2','url_2','url_2','Try url placeholder',NULL,NULL,'Try url description',6,'{\"pattern\":\"^(https?:\\\\/\\\\/(?:www\\\\.|(?!www))[a-zA-Z0-9][a-zA-Z0-9-]+[a-zA-Z0-9]\\\\.[^\\\\s]{2,}|www\\\\.[a-zA-Z0-9][a-zA-Z0-9-]+[a-zA-Z0-9]\\\\.[^\\\\s]{2,}|https?:\\\\/\\\\/(?:www\\\\.|(?!www))[a-zA-Z0-9]+\\\\.[^\\\\s]{2,}|www\\\\.[a-zA-Z0-9]+\\\\.[^\\\\s]{2,})\"}','Fill in the field with valid url (Example: https://www.dynamic-form.local)',NULL,NULL,1,0,0,0,1,'system','2022-04-13 08:30:00','system','2022-04-13 08:30:00','88d8ffef-aa51-4337-b9fe-986249994710'),(15,1,1,NULL,'Number of position','number','number_2','number_2','number_2','Try number placeholder',NULL,NULL,'Try number description',7,'{\"min\":0,\"pattern\":\"^[0-9]*$\"}','Fill in the field with numbers minimal 0',NULL,NULL,1,0,0,0,1,'system','2022-04-13 08:30:00','system','2022-04-13 08:30:00','650fde7c-c52e-4d16-8c1b-6fea9e50dc4e'),(16,1,1,NULL,'Try accordion','accordion',NULL,'try_accordion_1','try_accordion_1',NULL,NULL,'{\"orientation\":\"horizontal\",\"linear\":true}','',8,NULL,NULL,NULL,NULL,0,0,0,0,1,'system','2022-04-13 08:30:00','system','2022-04-13 08:30:00','6b7dba25-0beb-4733-a835-b3898d6bb26e'),(17,1,1,16,'Try panel 1','panel',NULL,'try_accordion_1_panel_1','try_accordion_1_panel_1',NULL,NULL,'{\"panel_description\":{\"mat_icon\":\"signal_cellular_alt\"}}','Panel 1 description',NULL,NULL,NULL,NULL,NULL,0,0,0,0,1,'system','2022-04-13 08:30:00','system','2022-04-13 08:30:00','1bd26b53-2423-4196-915a-e07d598a400c'),(18,1,1,16,'Try panel 2','panel',NULL,'try_accordion_1_panel_2','try_accordion_1_panel_2',NULL,NULL,'{\"panel_description\":{\"mat_icon\":\"add_comment\"}}','Panel 2 description',NULL,NULL,NULL,NULL,NULL,0,0,0,0,1,'system','2022-04-13 08:30:00','system','2022-04-13 08:30:00','5c3d1fba-07e9-4f62-b40a-6f0cf3ca3c47'),(19,1,1,16,'Try panel 3','panel',NULL,'try_accordion_1_panel_3','try_accordion_1_panel_3',NULL,NULL,'{\"panel_description\":{\"mat_icon\":\"bedtime\"}}','Panel 3 description',NULL,NULL,NULL,NULL,NULL,0,0,0,0,1,'system','2022-04-13 08:30:00','system','2022-04-13 08:30:00','0bf8eaea-0b41-421f-90df-7cab6820bb03'),(20,1,1,17,'Try text in accordion','text','text_3','text_3','text_3','Try text in stepper placeholder',NULL,NULL,'Try text in stepper description',NULL,NULL,NULL,NULL,NULL,1,0,0,0,1,'system','2022-04-09 21:43:00','system','2022-04-09 21:43:00','97f24305-32fd-4c5c-9b06-3e06685b9e9d'),(21,1,1,17,'Try url in accordion','url','url_3','url_3','url_3','Try url in stepper placeholder',NULL,NULL,'Try url in stepper description',NULL,'{\"pattern\":\"^(https?:\\\\/\\\\/(?:www\\\\.|(?!www))[a-zA-Z0-9][a-zA-Z0-9-]+[a-zA-Z0-9]\\\\.[^\\\\s]{2,}|www\\\\.[a-zA-Z0-9][a-zA-Z0-9-]+[a-zA-Z0-9]\\\\.[^\\\\s]{2,}|https?:\\\\/\\\\/(?:www\\\\.|(?!www))[a-zA-Z0-9]+\\\\.[^\\\\s]{2,}|www\\\\.[a-zA-Z0-9]+\\\\.[^\\\\s]{2,})\"}','Fill in the field with valid url (Example: https://www.dynamic-form.local)',NULL,NULL,1,0,0,0,1,'system','2022-04-09 21:43:00','system','2022-04-09 21:43:00','c6fd9952-a579-4b77-80af-e77dd243df6d'),(22,1,1,18,'Try select 6 in accordion','select','select_6','select_6','select_6','Try select in stepper placeholder','master-publication_status',NULL,'Try select in stepper description',NULL,NULL,NULL,NULL,NULL,1,0,0,0,1,'system','2022-04-09 21:43:00','system','2022-04-09 21:43:00','ba098daa-b583-4585-be64-75aecedebebd'),(23,1,1,18,'Try select 7 in accordion','select','select_7','select_7','select_7','Try select in stepper placeholder','master-publication_status',NULL,'Try select in stepper description',NULL,NULL,NULL,NULL,NULL,1,0,0,0,1,'system','2022-04-09 21:43:00','system','2022-04-09 21:43:00','ac582053-7102-4d38-9e4e-4f2e147ea10c'),(24,1,1,19,'Try select 8 in accordion','select','select_8','select_8','select_8','Try select in stepper placeholder','master-publication_status',NULL,'Try select in stepper description',NULL,NULL,NULL,NULL,NULL,1,0,0,0,1,'system','2022-04-09 21:43:00','system','2022-04-09 21:43:00','e5d4d83e-7f44-4b27-b5eb-ed6a8606221f'),(25,1,1,19,'Try select 9 in accordion','select','select_9','select_9','select_9','Try select in stepper placeholder','master-publication_status',NULL,'Try select in stepper description',NULL,NULL,NULL,NULL,NULL,1,0,0,0,1,'system','2022-04-09 21:43:00','system','2022-04-09 21:43:00','e3353704-c52f-4fd2-a98f-f9de41256575'),(26,1,1,17,'Try number 3 in accordion','number','number_3','number_3','number_3','Try number placeholder',NULL,NULL,'Try number description',NULL,'{\"min\":1,\"pattern\":\"^[0-9]*$\"}','Fill in the field with numbers greater than 0',NULL,NULL,1,0,0,0,1,'system','2022-04-09 21:43:00','system','2022-04-09 21:43:00','fdd068c9-8358-4138-8ad3-11ad46928487'),(27,1,1,5,'Try date in stepper','date','date_1','date_1','date_1','Try date in stepper placeholder',NULL,NULL,'Try date in stepper description',NULL,NULL,NULL,NULL,NULL,1,0,0,0,1,'system','2022-04-09 21:43:00','system','2022-04-09 21:43:00','0c88833c-e6ed-4173-8218-610edaf00f64'),(28,1,1,5,'Try month in stepper','month','month_1','month_1','month_1','Try month in stepper placeholder',NULL,NULL,'Try month in stepper description',NULL,NULL,NULL,NULL,NULL,1,0,0,0,1,'system','2022-04-09 21:43:00','system','2022-04-09 21:43:00','824e276c-1329-4287-b481-7fa764aebd65'),(29,1,1,5,'Try year in stepper','year','year_1','year_1','year_1','Try year in stepper placeholder',NULL,NULL,'Try year in stepper description',NULL,NULL,NULL,NULL,NULL,1,0,0,0,1,'system','2022-04-09 21:43:00','system','2022-04-09 21:43:00','2cace5a0-039f-44cf-9b03-3ee572e874df'),(30,1,1,4,'Try owl date in stepper','owl-date','date_2','date_2','date_2','Try owl date in stepper placeholder',NULL,NULL,'Try owl date in stepper description',NULL,NULL,NULL,NULL,NULL,1,0,0,0,1,'system','2022-04-09 21:43:00','system','2022-04-09 21:43:00','ba9091e4-0c1f-4305-bf43-c1f269efc173'),(31,1,1,4,'Try owl month in stepper','owl-month','month_2','month_2','month_2','Try owl month in stepper placeholder',NULL,NULL,'Try owl month in stepper description',NULL,NULL,NULL,NULL,NULL,1,0,0,0,1,'system','2022-04-09 21:43:00','system','2022-04-09 21:43:00','53a9e993-6cbc-4c79-a7ba-a71d90750d0a'),(32,1,1,4,'Try owl year in stepper','owl-year','year_2','year_2','year_2','Try owl year in stepper placeholder',NULL,NULL,'Try owl year in stepper description',NULL,NULL,NULL,NULL,NULL,1,0,0,0,1,'system','2022-04-09 21:43:00','system','2022-04-09 21:43:00','11a72636-718f-47e3-b0b7-03fd7a89e684'),(33,1,1,4,'Try owl time in stepper','owl-time','time_1','time_1','time_1','Try owl time in stepper placeholder',NULL,NULL,'Try owl time in stepper description',NULL,NULL,NULL,NULL,NULL,1,0,0,0,1,'system','2022-04-09 21:43:00','system','2022-04-09 21:43:00','14514578-58b8-4afb-b15b-9b91a57f1732'),(34,1,1,4,'Try owl date time in stepper','owl-datetime','datetime_1','datetime_1','datetime_1','Try owl date time in stepper placeholder',NULL,NULL,'Try owl date time in stepper description',NULL,NULL,NULL,NULL,NULL,1,0,0,0,1,'system','2022-04-09 21:43:00','system','2022-04-09 21:43:00','191067ab-71e0-4826-a342-c3ef7f49c0e5'),(35,1,1,4,'Try owl date range in stepper','owl-daterange','daterange_1','daterange_1','daterange_1','Try owl date range in stepper placeholder','',NULL,'Try owl date range in stepper description',NULL,NULL,NULL,NULL,NULL,1,0,0,0,1,'system','2022-04-09 21:43:00','system','2022-04-09 21:43:00','ac86e1ea-aaa7-4998-a959-ea681c8aa9c2'),(36,1,1,4,'Try owl time range in stepper','owl-timerange','timerange_1','timerange_1','timerange_1','Try owl time range in stepper placeholder','',NULL,'Try owl time range in stepper description',NULL,NULL,NULL,NULL,NULL,1,0,0,0,1,'system','2022-04-09 21:43:00','system','2022-04-09 21:43:00','b2a39665-5d93-4fa6-b343-bbbf0773cd48'),(37,1,1,4,'Try owl date time range in stepper','owl-datetimerange','datetimerange_1','datetimerange_1','datetimerange_1','Try owl date time range in stepper placeholder','',NULL,'Try owl date time range in stepper description',NULL,NULL,NULL,NULL,NULL,1,0,0,0,1,'system','2022-04-09 21:43:00','system','2022-04-09 21:43:00','7afb5ab5-6e39-4c5e-a2f4-75949ff511b3'),(38,1,1,NULL,'Try file','file','file_1','file_1','file_1','Try file placeholder',NULL,NULL,'Try file description',4,NULL,NULL,NULL,NULL,1,0,0,0,1,'system','2022-04-09 21:43:00','system','2022-04-09 21:43:00','3a43bf7f-892d-4848-a282-5557da659882'),(39,1,1,NULL,'Try image','image','image_1','image_1','image_1','Try image placeholder',NULL,NULL,'Try image description',5,NULL,NULL,NULL,NULL,1,0,0,0,1,'system','2022-04-09 21:43:00','system','2022-04-09 21:43:00','c869f0b9-8223-4f2d-afb6-6832c855a19b'),(40,1,1,NULL,'Try date 3','date','date_3','date_3','date_3','Try date 3 placeholder',NULL,NULL,'Try date 3 description',6,NULL,NULL,NULL,NULL,1,0,0,0,1,'system','2022-04-09 21:43:00','system','2022-04-09 21:43:00','e63062ee-e414-4c1f-a242-20272d4891e3'),(41,1,1,NULL,'Try month 3','month','month_3','month_3','month_3','Try month 3 placeholder',NULL,NULL,'Try month 3 description',7,NULL,NULL,NULL,NULL,1,0,0,0,1,'system','2022-04-09 21:43:00','system','2022-04-09 21:43:00','5988a0b7-3526-4396-9ec6-98534f9b280f'),(42,1,1,NULL,'Try year 3','year','year_3','year_3','year_3','Try year 3 placeholder',NULL,NULL,'Try year 3 description',8,NULL,NULL,NULL,NULL,1,0,0,0,1,'system','2022-04-09 21:43:00','system','2022-04-09 21:43:00','7bd71861-2e84-463b-baf9-4336d692c648'),(43,1,1,NULL,'Try time 3','time','time_3','time_3','time_3','Try time 3 placeholder',NULL,NULL,'Try time 3 description',9,NULL,NULL,NULL,NULL,1,0,0,0,1,'system','2022-04-09 21:43:00','system','2022-04-09 21:43:00','1e1869e2-0fc4-4587-8882-4ee667ff053f');
/*!40000 ALTER TABLE `publication_form` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `publication_form_version`
--

DROP TABLE IF EXISTS `publication_form_version`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `publication_form_version` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `publication_type_id` bigint(20) NOT NULL,
  `publication_form_version_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `publication_form_version_code` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `grid_system` longtext COLLATE utf8mb4_unicode_ci DEFAULT '{"type":"no_grid_system","cols":12,"config":{}}' COMMENT '(DC2Type:json)',
  `flag_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_user` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_user` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `updated_at` datetime NOT NULL,
  `uuid` char(36) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '(DC2Type:guid)',
  PRIMARY KEY (`id`),
  KEY `IDX_CFA3AA3DCDC55AAF` (`publication_type_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `publication_form_version`
--

LOCK TABLES `publication_form_version` WRITE;
/*!40000 ALTER TABLE `publication_form_version` DISABLE KEYS */;
INSERT INTO `publication_form_version` VALUES (1,1,'Book 1 v1','BOK1-V1','{\"type\":\"tailwind\",\"cols\":12,\"config\":{\"text_1\":{\"colspan\":3},\"select_1\":{\"colspan\":3},\"file_1\":{\"colspan\":6},\"image_1\":{\"colspan\":6},\"try_stepper_1\":{\"colspan\":6,\"rowspan\":6},\"try_stepper_1_step_1\":{\"colspan\":12},\"date_2\":{\"colspan\":6},\"month_2\":{\"colspan\":6},\"year_2\":{\"colspan\":6},\"time_1\":{\"colspan\":6},\"datetime_1\":{\"colspan\":6},\"daterange_1\":{\"colspan\":6},\"timerange_1\":{\"colspan\":6},\"datetimerange_1\":{\"colspan\":6},\"try_stepper_1_step_2\":{\"colspan\":12},\"text_2\":{\"colspan\":12},\"url_1\":{\"colspan\":12},\"select_2\":{\"colspan\":12},\"date_1\":{\"colspan\":12},\"month_1\":{\"colspan\":12},\"year_1\":{\"colspan\":12},\"try_stepper_1_step_3\":{\"colspan\":12},\"select_3\":{\"colspan\":6},\"select_4\":{\"colspan\":6},\"select_5\":{\"colspan\":6},\"number_1\":{\"colspan\":6},\"url_2\":{\"colspan\":6},\"number_2\":{\"colspan\":6},\"try_accordion_1\":{\"colspan\":6,\"rowspan\":5},\"try_accordion_1_panel_1\":{\"colspan\":12},\"text_3\":{\"colspan\":4},\"url_3\":{\"colspan\":4},\"number_3\":{\"colspan\":4},\"try_accordion_1_panel_2\":{\"colspan\":12},\"select_6\":{\"colspan\":6},\"select_7\":{\"colspan\":6},\"try_accordion_1_panel_3\":{\"colspan\":12},\"select_8\":{\"colspan\":6},\"select_9\":{\"colspan\":6},\"date_3\":{\"colspan\":6},\"month_3\":{\"colspan\":6},\"year_3\":{\"colspan\":3},\"time_3\":{\"colspan\":3}}}',1,'system','2022-04-09 21:43:00','system','2022-04-09 21:43:00','d152c092-1141-4640-846d-24a6ef91b646'),(2,2,'Book 2 v1','BOK1-V2','{\"type\":\"no_grid_system\",\"cols\":12,\"config\":{}}',1,'system','2022-04-10 20:18:00','system','2022-04-10 20:18:00','4798da8b-c6a3-4619-b1f1-1770799918c3'),(3,3,'Journal 1 v1','JUR1-V2','{\"type\":\"no_grid_system\",\"cols\":12,\"config\":{}}',1,'system','2022-04-10 20:18:00','system','2022-04-10 20:18:00','b34ca836-0b7a-420d-88b9-e463cf6572da');
/*!40000 ALTER TABLE `publication_form_version` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `publication_general_type`
--

DROP TABLE IF EXISTS `publication_general_type`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
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
/*!50503 SET character_set_client = utf8mb4 */;
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
/*!50503 SET character_set_client = utf8mb4 */;
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
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `publication_status`
--

LOCK TABLES `publication_status` WRITE;
/*!40000 ALTER TABLE `publication_status` DISABLE KEYS */;
INSERT INTO `publication_status` VALUES (1,'Draft','DRF',1,'system','2022-04-09 21:43:00','system','2022-04-09 21:43:00','4bc22d41-4386-4f57-bf4d-dd51e993aa1e'),(2,'Processed','PRO',1,'system','2022-04-09 21:43:00','system','2022-04-09 21:43:00','2c5f13d8-cfbd-4d53-9311-356ca2f33ffd'),(3,'Verified','VER',1,'system','2022-04-09 21:43:00','system','2022-04-09 21:43:00','d3192561-8703-4801-8f34-06283b61fd1c'),(4,'Rejected','RJT',1,'system','2022-04-09 21:43:00','system','2022-04-09 21:43:00','c2cb0ee7-a02e-43a6-8fbe-3a5d17254e47');
/*!40000 ALTER TABLE `publication_status` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `publication_type`
--

DROP TABLE IF EXISTS `publication_type`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
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
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `publication_type`
--

LOCK TABLES `publication_type` WRITE;
/*!40000 ALTER TABLE `publication_type` DISABLE KEYS */;
INSERT INTO `publication_type` VALUES (1,1,'Book 1','BOK-1',1,1,'system','2022-04-09 21:43:00','system','2022-04-09 21:43:00','a23892cd-6811-44bd-a671-c85b87829887'),(2,1,'Book 2','BOK-2',1,1,'system','2022-04-10 20:18:00','system','2022-04-10 20:18:00','4376fe79-b2bd-4178-bfd4-1ed1664a8f61'),(3,2,'Journal 1','JUR-1',1,1,'system','2022-04-10 20:18:00','system','2022-04-10 20:18:00','4a12ddec-3183-4e12-ba34-7cd8c9771933');
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

-- Dump completed on 2023-09-05  3:55:38
