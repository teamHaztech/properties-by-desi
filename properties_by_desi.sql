-- MySQL dump 10.13  Distrib 9.6.0, for macos26.3 (arm64)
--
-- Host: localhost    Database: properties_by_desi
-- ------------------------------------------------------
-- Server version	9.6.0
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `activity_logs`
--

DROP TABLE IF EXISTS `activity_logs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `activity_logs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned DEFAULT NULL,
  `subject_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `subject_id` bigint unsigned NOT NULL,
  `action` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `changes` json DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `activity_logs_user_id_foreign` (`user_id`),
  KEY `activity_logs_subject_type_subject_id_index` (`subject_type`,`subject_id`),
  KEY `activity_logs_created_at_index` (`created_at`),
  CONSTRAINT `activity_logs_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `activity_logs`
--

LOCK TABLES `activity_logs` WRITE;
/*!40000 ALTER TABLE `activity_logs` DISABLE KEYS */;
/*!40000 ALTER TABLE `activity_logs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cache`
--

DROP TABLE IF EXISTS `cache`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cache` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` bigint NOT NULL,
  PRIMARY KEY (`key`),
  KEY `cache_expiration_index` (`expiration`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cache`
--

LOCK TABLES `cache` WRITE;
/*!40000 ALTER TABLE `cache` DISABLE KEYS */;
/*!40000 ALTER TABLE `cache` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cache_locks`
--

DROP TABLE IF EXISTS `cache_locks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cache_locks` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` bigint NOT NULL,
  PRIMARY KEY (`key`),
  KEY `cache_locks_expiration_index` (`expiration`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cache_locks`
--

LOCK TABLES `cache_locks` WRITE;
/*!40000 ALTER TABLE `cache_locks` DISABLE KEYS */;
/*!40000 ALTER TABLE `cache_locks` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cities`
--

DROP TABLE IF EXISTS `cities`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cities` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `state` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Goa',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `cities_name_unique` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=116 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cities`
--

LOCK TABLES `cities` WRITE;
/*!40000 ALTER TABLE `cities` DISABLE KEYS */;
INSERT INTO `cities` VALUES (1,'Panjim','Goa',1,'2026-04-02 02:08:00','2026-04-02 02:08:00'),(2,'Mapusa','Goa',1,'2026-04-02 02:08:00','2026-04-02 02:08:00'),(3,'Calangute','Goa',1,'2026-04-02 02:08:00','2026-04-02 02:08:00'),(4,'Candolim','Goa',1,'2026-04-02 02:08:00','2026-04-02 02:08:00'),(5,'Anjuna','Goa',1,'2026-04-02 02:08:00','2026-04-02 02:08:00'),(6,'Vagator','Goa',1,'2026-04-02 02:08:00','2026-04-02 02:08:00'),(7,'Assagao','Goa',1,'2026-04-02 02:08:00','2026-04-02 02:08:00'),(8,'Siolim','Goa',1,'2026-04-02 02:08:00','2026-04-02 02:08:00'),(9,'Morjim','Goa',1,'2026-04-02 02:08:00','2026-04-02 02:08:00'),(10,'Arambol','Goa',1,'2026-04-02 02:08:00','2026-04-02 02:08:00'),(11,'Mandrem','Goa',1,'2026-04-02 02:08:00','2026-04-02 02:08:00'),(12,'Porvorim','Goa',1,'2026-04-02 02:08:00','2026-04-02 02:08:00'),(13,'Tivim','Goa',1,'2026-04-02 02:08:00','2026-04-02 02:08:00'),(14,'Aldona','Goa',1,'2026-04-02 02:08:00','2026-04-02 02:08:00'),(15,'Saligao','Goa',1,'2026-04-02 02:08:00','2026-04-02 02:08:00'),(16,'Sangolda','Goa',1,'2026-04-02 02:08:00','2026-04-02 02:08:00'),(17,'Guirim','Goa',1,'2026-04-02 02:08:00','2026-04-02 02:08:00'),(18,'Pilerne','Goa',1,'2026-04-02 02:08:00','2026-04-02 02:08:00'),(19,'Reis Magos','Goa',1,'2026-04-02 02:08:00','2026-04-02 02:08:00'),(20,'Nerul','Goa',1,'2026-04-02 02:08:00','2026-04-02 02:08:00'),(21,'Dona Paula','Goa',1,'2026-04-02 02:08:00','2026-04-02 02:08:00'),(22,'Bambolim','Goa',1,'2026-04-02 02:08:00','2026-04-02 02:08:00'),(23,'Margao','Goa',1,'2026-04-02 02:08:00','2026-04-02 02:08:00'),(24,'Vasco da Gama','Goa',1,'2026-04-02 02:08:00','2026-04-02 02:08:00'),(25,'Colva','Goa',1,'2026-04-02 02:08:00','2026-04-02 02:08:00'),(26,'Benaulim','Goa',1,'2026-04-02 02:08:00','2026-04-02 02:08:00'),(27,'Palolem','Goa',1,'2026-04-02 02:08:00','2026-04-02 02:08:00'),(28,'Canacona','Goa',1,'2026-04-02 02:08:00','2026-04-02 02:08:00'),(29,'Quepem','Goa',1,'2026-04-02 02:08:00','2026-04-02 02:08:00'),(30,'Cuncolim','Goa',1,'2026-04-02 02:08:00','2026-04-02 02:08:00'),(31,'Loutolim','Goa',1,'2026-04-02 02:08:00','2026-04-02 02:08:00'),(32,'Rachol','Goa',1,'2026-04-02 02:08:00','2026-04-02 02:08:00'),(33,'Cortalim','Goa',1,'2026-04-02 02:08:00','2026-04-02 02:08:00'),(34,'Sancoale','Goa',1,'2026-04-02 02:08:00','2026-04-02 02:08:00'),(35,'Dabolim','Goa',1,'2026-04-02 02:08:00','2026-04-02 02:08:00'),(36,'Old Goa','Goa',1,'2026-04-02 02:08:00','2026-04-02 02:08:00'),(37,'Ponda','Goa',1,'2026-04-02 02:08:00','2026-04-02 02:08:00'),(38,'Bicholim','Goa',1,'2026-04-02 02:08:00','2026-04-02 02:08:00'),(39,'Pernem','Goa',1,'2026-04-02 02:08:00','2026-04-02 02:08:00'),(40,'Sanguem','Goa',1,'2026-04-02 02:08:00','2026-04-02 02:08:00'),(41,'Sanquelim','Goa',1,'2026-04-02 02:08:00','2026-04-02 02:08:00'),(42,'Valpoi','Goa',1,'2026-04-02 02:08:00','2026-04-02 02:08:00'),(43,'Curchorem','Goa',1,'2026-04-02 02:08:00','2026-04-02 02:08:00'),(44,'Agonda','Goa',1,'2026-04-02 02:19:02','2026-04-02 02:19:02'),(45,'Arpora','Goa',1,'2026-04-02 02:19:02','2026-04-02 02:19:02'),(46,'Ashwem','Goa',1,'2026-04-02 02:19:02','2026-04-02 02:19:02'),(47,'Baga','Goa',1,'2026-04-02 02:19:02','2026-04-02 02:19:02'),(48,'Betim','Goa',1,'2026-04-02 02:19:02','2026-04-02 02:19:02'),(49,'Cavelossim','Goa',1,'2026-04-02 02:19:02','2026-04-02 02:19:02'),(50,'Chapora','Goa',1,'2026-04-02 02:19:02','2026-04-02 02:19:02'),(51,'Chopdem','Goa',1,'2026-04-02 02:19:02','2026-04-02 02:19:02'),(52,'Colvale','Goa',1,'2026-04-02 02:19:02','2026-04-02 02:19:02'),(53,'Divar Island','Goa',1,'2026-04-02 02:19:02','2026-04-02 02:19:02'),(54,'Goa Velha','Goa',1,'2026-04-02 02:19:02','2026-04-02 02:19:02'),(55,'Moira','Goa',1,'2026-04-02 02:19:02','2026-04-02 02:19:02'),(56,'Nachinola','Goa',1,'2026-04-02 02:19:02','2026-04-02 02:19:02'),(57,'Navelim','Goa',1,'2026-04-02 02:19:02','2026-04-02 02:19:02'),(58,'Oxel','Goa',1,'2026-04-02 02:19:02','2026-04-02 02:19:02'),(59,'Parra','Goa',1,'2026-04-02 02:19:02','2026-04-02 02:19:02'),(60,'Penha de Franca','Goa',1,'2026-04-02 02:19:02','2026-04-02 02:19:02'),(61,'Ribandar','Goa',1,'2026-04-02 02:19:02','2026-04-02 02:19:02'),(62,'Salvador do Mundo','Goa',1,'2026-04-02 02:19:02','2026-04-02 02:19:02'),(63,'Santa Cruz','Goa',1,'2026-04-02 02:19:02','2026-04-02 02:19:02'),(64,'Siridao','Goa',1,'2026-04-02 02:19:02','2026-04-02 02:19:02'),(65,'Socorro','Goa',1,'2026-04-02 02:19:02','2026-04-02 02:19:02'),(66,'Taleigao','Goa',1,'2026-04-02 02:19:02','2026-04-02 02:19:02'),(67,'Ucassaim','Goa',1,'2026-04-02 02:19:02','2026-04-02 02:19:02'),(68,'Verla Canca','Goa',1,'2026-04-02 02:19:02','2026-04-02 02:19:02'),(69,'Agassaim','Goa',1,'2026-04-02 02:19:02','2026-04-02 02:19:02'),(70,'Balli','Goa',1,'2026-04-02 02:19:02','2026-04-02 02:19:02'),(71,'Betalbatim','Goa',1,'2026-04-02 02:19:02','2026-04-02 02:19:02'),(72,'Bogmalo','Goa',1,'2026-04-02 02:19:02','2026-04-02 02:19:02'),(73,'Cabo de Rama','Goa',1,'2026-04-02 02:19:02','2026-04-02 02:19:02'),(74,'Carambolim','Goa',1,'2026-04-02 02:19:02','2026-04-02 02:19:02'),(75,'Chandor','Goa',1,'2026-04-02 02:19:02','2026-04-02 02:19:02'),(76,'Chinchinim','Goa',1,'2026-04-02 02:19:02','2026-04-02 02:19:02'),(77,'Consua','Goa',1,'2026-04-02 02:19:02','2026-04-02 02:19:02'),(78,'Curtorim','Goa',1,'2026-04-02 02:19:02','2026-04-02 02:19:02'),(79,'Dramapur','Goa',1,'2026-04-02 02:19:02','2026-04-02 02:19:02'),(80,'Fatorda','Goa',1,'2026-04-02 02:19:02','2026-04-02 02:19:02'),(81,'Loliem','Goa',1,'2026-04-02 02:19:02','2026-04-02 02:19:02'),(82,'Majorda','Goa',1,'2026-04-02 02:19:02','2026-04-02 02:19:02'),(83,'Mobor','Goa',1,'2026-04-02 02:19:02','2026-04-02 02:19:02'),(84,'Navelim South','Goa',1,'2026-04-02 02:19:02','2026-04-02 02:19:02'),(85,'Nuvem','Goa',1,'2026-04-02 02:19:02','2026-04-02 02:19:02'),(86,'Orlim','Goa',1,'2026-04-02 02:19:02','2026-04-02 02:19:02'),(87,'Paroda','Goa',1,'2026-04-02 02:19:02','2026-04-02 02:19:02'),(88,'Quelossim','Goa',1,'2026-04-02 02:19:02','2026-04-02 02:19:02'),(89,'Raia','Goa',1,'2026-04-02 02:19:02','2026-04-02 02:19:02'),(90,'Rivona','Goa',1,'2026-04-02 02:19:02','2026-04-02 02:19:02'),(91,'Salcete','Goa',1,'2026-04-02 02:19:02','2026-04-02 02:19:02'),(92,'Seraulim','Goa',1,'2026-04-02 02:19:02','2026-04-02 02:19:02'),(93,'Shiroda','Goa',1,'2026-04-02 02:19:02','2026-04-02 02:19:02'),(94,'Sirlim','Goa',1,'2026-04-02 02:19:02','2026-04-02 02:19:02'),(95,'Utorda','Goa',1,'2026-04-02 02:19:02','2026-04-02 02:19:02'),(96,'Varca','Goa',1,'2026-04-02 02:19:02','2026-04-02 02:19:02'),(97,'Verna','Goa',1,'2026-04-02 02:19:02','2026-04-02 02:19:02'),(98,'Zuarinagar','Goa',1,'2026-04-02 02:19:02','2026-04-02 02:19:02'),(99,'Bardez','Goa',1,'2026-04-02 02:19:02','2026-04-02 02:19:02'),(100,'Dharbandora','Goa',1,'2026-04-02 02:19:02','2026-04-02 02:19:02'),(101,'Mardol','Goa',1,'2026-04-02 02:19:02','2026-04-02 02:19:02'),(102,'Marcel','Goa',1,'2026-04-02 02:19:02','2026-04-02 02:19:02'),(103,'Priol','Goa',1,'2026-04-02 02:19:02','2026-04-02 02:19:02'),(104,'Savoi Verem','Goa',1,'2026-04-02 02:19:02','2026-04-02 02:19:02'),(105,'Tisk','Goa',1,'2026-04-02 02:19:02','2026-04-02 02:19:02'),(106,'Corlim','Goa',1,'2026-04-02 02:19:02','2026-04-02 02:19:02'),(107,'Chimbel','Goa',1,'2026-04-02 02:19:02','2026-04-02 02:19:02'),(108,'Merces','Goa',1,'2026-04-02 02:19:02','2026-04-02 02:19:02'),(109,'Miramar','Goa',1,'2026-04-02 02:19:02','2026-04-02 02:19:02'),(110,'Caranzalem','Goa',1,'2026-04-02 02:19:02','2026-04-02 02:19:02'),(111,'Tonca','Goa',1,'2026-04-02 02:19:02','2026-04-02 02:19:02'),(112,'Altinho','Goa',1,'2026-04-02 02:19:02','2026-04-02 02:19:02'),(113,'Campal','Goa',1,'2026-04-02 02:19:02','2026-04-02 02:19:02'),(114,'St Inez','Goa',1,'2026-04-02 02:19:02','2026-04-02 02:19:02'),(115,'Fontainhas','Goa',1,'2026-04-02 02:19:02','2026-04-02 02:19:02');
/*!40000 ALTER TABLE `cities` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `city_lead`
--

DROP TABLE IF EXISTS `city_lead`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `city_lead` (
  `city_id` bigint unsigned NOT NULL,
  `lead_id` bigint unsigned NOT NULL,
  PRIMARY KEY (`city_id`,`lead_id`),
  KEY `city_lead_lead_id_foreign` (`lead_id`),
  CONSTRAINT `city_lead_city_id_foreign` FOREIGN KEY (`city_id`) REFERENCES `cities` (`id`) ON DELETE CASCADE,
  CONSTRAINT `city_lead_lead_id_foreign` FOREIGN KEY (`lead_id`) REFERENCES `leads` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `city_lead`
--

LOCK TABLES `city_lead` WRITE;
/*!40000 ALTER TABLE `city_lead` DISABLE KEYS */;
/*!40000 ALTER TABLE `city_lead` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `clients`
--

DROP TABLE IF EXISTS `clients`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `clients` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `lead_id` bigint unsigned NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `buying_type` enum('loan','cash','black_white_mix') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `purpose` enum('investment','end_use') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `buyer_profile` enum('family','individual','company') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `urgency` enum('low','medium','high','immediate') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'medium',
  `address` text COLLATE utf8mb4_unicode_ci,
  `pan_number` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `aadhar_number` varchar(12) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `clients_lead_id_foreign` (`lead_id`),
  KEY `clients_phone_index` (`phone`),
  CONSTRAINT `clients_lead_id_foreign` FOREIGN KEY (`lead_id`) REFERENCES `leads` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `clients`
--

LOCK TABLES `clients` WRITE;
/*!40000 ALTER TABLE `clients` DISABLE KEYS */;
/*!40000 ALTER TABLE `clients` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `communications`
--

DROP TABLE IF EXISTS `communications`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `communications` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `lead_id` bigint unsigned NOT NULL,
  `user_id` bigint unsigned NOT NULL,
  `type` enum('call','whatsapp','email','sms','meeting','site_visit','other') COLLATE utf8mb4_unicode_ci NOT NULL,
  `direction` enum('inbound','outbound') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'outbound',
  `summary` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `duration_minutes` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `communications_lead_id_foreign` (`lead_id`),
  KEY `communications_user_id_foreign` (`user_id`),
  CONSTRAINT `communications_lead_id_foreign` FOREIGN KEY (`lead_id`) REFERENCES `leads` (`id`) ON DELETE CASCADE,
  CONSTRAINT `communications_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `communications`
--

LOCK TABLES `communications` WRITE;
/*!40000 ALTER TABLE `communications` DISABLE KEYS */;
/*!40000 ALTER TABLE `communications` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `documents`
--

DROP TABLE IF EXISTS `documents`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `documents` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `documentable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `documentable_id` bigint unsigned NOT NULL,
  `uploaded_by` bigint unsigned NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `file_path` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `file_type` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `file_size` int unsigned NOT NULL,
  `category` enum('kyc','agreement','loan_paper','id_proof','property_doc','other') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'other',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `documents_documentable_type_documentable_id_index` (`documentable_type`,`documentable_id`),
  KEY `documents_uploaded_by_foreign` (`uploaded_by`),
  CONSTRAINT `documents_uploaded_by_foreign` FOREIGN KEY (`uploaded_by`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `documents`
--

LOCK TABLES `documents` WRITE;
/*!40000 ALTER TABLE `documents` DISABLE KEYS */;
/*!40000 ALTER TABLE `documents` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `failed_jobs`
--

DROP TABLE IF EXISTS `failed_jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `failed_jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `failed_jobs`
--

LOCK TABLES `failed_jobs` WRITE;
/*!40000 ALTER TABLE `failed_jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `failed_jobs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `follow_ups`
--

DROP TABLE IF EXISTS `follow_ups`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `follow_ups` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `lead_id` bigint unsigned NOT NULL,
  `user_id` bigint unsigned NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `scheduled_at` datetime NOT NULL,
  `completed_at` datetime DEFAULT NULL,
  `status` enum('pending','completed','missed','cancelled') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `priority` enum('low','medium','high') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'medium',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `follow_ups_lead_id_foreign` (`lead_id`),
  KEY `follow_ups_user_id_foreign` (`user_id`),
  KEY `follow_ups_scheduled_at_status_index` (`scheduled_at`,`status`),
  CONSTRAINT `follow_ups_lead_id_foreign` FOREIGN KEY (`lead_id`) REFERENCES `leads` (`id`) ON DELETE CASCADE,
  CONSTRAINT `follow_ups_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `follow_ups`
--

LOCK TABLES `follow_ups` WRITE;
/*!40000 ALTER TABLE `follow_ups` DISABLE KEYS */;
/*!40000 ALTER TABLE `follow_ups` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `job_batches`
--

DROP TABLE IF EXISTS `job_batches`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `job_batches` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext COLLATE utf8mb4_unicode_ci,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `job_batches`
--

LOCK TABLES `job_batches` WRITE;
/*!40000 ALTER TABLE `job_batches` DISABLE KEYS */;
/*!40000 ALTER TABLE `job_batches` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jobs`
--

DROP TABLE IF EXISTS `jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint unsigned NOT NULL,
  `reserved_at` int unsigned DEFAULT NULL,
  `available_at` int unsigned NOT NULL,
  `created_at` int unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jobs`
--

LOCK TABLES `jobs` WRITE;
/*!40000 ALTER TABLE `jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `jobs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `lead_property`
--

DROP TABLE IF EXISTS `lead_property`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `lead_property` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `lead_id` bigint unsigned NOT NULL,
  `property_id` bigint unsigned NOT NULL,
  `status` enum('suggested','shown','visited','interested','rejected') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'suggested',
  `feedback` text COLLATE utf8mb4_unicode_ci,
  `shown_at` timestamp NULL DEFAULT NULL,
  `visited_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `lead_property_lead_id_property_id_unique` (`lead_id`,`property_id`),
  KEY `lead_property_property_id_foreign` (`property_id`),
  CONSTRAINT `lead_property_lead_id_foreign` FOREIGN KEY (`lead_id`) REFERENCES `leads` (`id`) ON DELETE CASCADE,
  CONSTRAINT `lead_property_property_id_foreign` FOREIGN KEY (`property_id`) REFERENCES `properties` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `lead_property`
--

LOCK TABLES `lead_property` WRITE;
/*!40000 ALTER TABLE `lead_property` DISABLE KEYS */;
/*!40000 ALTER TABLE `lead_property` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `leads`
--

DROP TABLE IF EXISTS `leads`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `leads` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `source` enum('call','whatsapp','instagram','facebook','referral','website','walk_in','other') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'call',
  `status` enum('new','contacted','spoken','interested','not_interested','visited_site','follow_up_required','loan_processing','closed_won','closed_lost') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'new',
  `assigned_agent_id` bigint unsigned DEFAULT NULL,
  `budget_min` decimal(12,2) DEFAULT NULL,
  `budget_max` decimal(12,2) DEFAULT NULL,
  `preferred_property_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `location_preference` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `urgency` enum('low','medium','high','immediate') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'medium',
  `is_converted` tinyint(1) NOT NULL DEFAULT '0',
  `last_contacted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `leads_phone_unique` (`phone`,`deleted_at`),
  KEY `leads_assigned_agent_id_foreign` (`assigned_agent_id`),
  KEY `leads_phone_index` (`phone`),
  CONSTRAINT `leads_assigned_agent_id_foreign` FOREIGN KEY (`assigned_agent_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `leads`
--

LOCK TABLES `leads` WRITE;
/*!40000 ALTER TABLE `leads` DISABLE KEYS */;
INSERT INTO `leads` VALUES (1,'Vikram Singh','9988776655',NULL,'call','new',3,3000000.00,5000000.00,'plot','North Goa','high',0,NULL,'2026-04-01 08:49:02','2026-04-01 08:49:02',NULL),(2,'Meera Joshi','9977665544',NULL,'whatsapp','interested',4,20000000.00,30000000.00,'villa','Vagator','medium',0,NULL,'2026-04-01 08:49:02','2026-04-01 08:49:02',NULL),(3,'Rajesh Kumar','9966554433',NULL,'facebook','contacted',5,5000000.00,8000000.00,'flat','Panjim','low',0,NULL,'2026-04-01 08:49:02','2026-04-01 08:49:02',NULL),(4,'Anita Deshpande','9955443322',NULL,'referral','visited_site',3,10000000.00,15000000.00,'plot','Anjuna','immediate',0,NULL,'2026-04-01 08:49:02','2026-04-01 08:49:02',NULL),(5,'Suresh Pai','9944332211',NULL,'instagram','follow_up_required',4,4000000.00,6000000.00,'plot','Siolim','medium',0,NULL,'2026-04-01 08:49:02','2026-04-01 08:49:02',NULL),(6,'Kavita Menon','9933221100',NULL,'website','spoken',5,6000000.00,9000000.00,'flat','Panjim','high',0,NULL,'2026-04-01 08:49:02','2026-04-01 08:49:02',NULL),(7,'Deepak Reddy','9922110099',NULL,'call','loan_processing',3,5000000.00,7000000.00,'flat','Margao','high',0,NULL,'2026-04-01 08:49:02','2026-04-01 08:49:02',NULL),(8,'Pooja Verma','9911009988',NULL,'whatsapp','closed_won',4,25000000.00,30000000.00,'villa','Vagator','medium',0,NULL,'2026-04-01 08:49:02','2026-04-01 08:49:02',NULL);
/*!40000 ALTER TABLE `leads` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES (1,'0001_01_01_000000_create_users_table',1),(2,'0001_01_01_000001_create_cache_table',1),(3,'0001_01_01_000002_create_jobs_table',1),(4,'2026_04_01_063837_create_permission_tables',1),(5,'2026_04_01_064336_create_personal_access_tokens_table',1),(6,'2026_04_01_093442_create_notifications_table',1),(7,'2026_04_01_100001_add_fields_to_users_table',1),(8,'2026_04_01_100002_create_leads_table',1),(9,'2026_04_01_100003_create_clients_table',1),(10,'2026_04_01_100004_create_properties_table',1),(11,'2026_04_01_100005_create_lead_property_table',1),(12,'2026_04_01_100006_create_notes_table',1),(13,'2026_04_01_100007_create_follow_ups_table',1),(14,'2026_04_01_100008_create_communications_table',1),(15,'2026_04_01_100009_create_documents_table',1),(16,'2026_04_01_100010_create_tags_table',1),(17,'2026_04_01_100011_create_activity_logs_table',1),(18,'2026_04_01_200001_add_username_to_users_table',1),(19,'2026_04_02_100001_create_cities_table',2),(20,'2026_04_04_100001_add_pricing_fields_to_properties_table',3),(21,'2026_04_04_100002_add_price_range_to_properties',4);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `model_has_permissions`
--

DROP TABLE IF EXISTS `model_has_permissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `model_has_permissions` (
  `permission_id` bigint unsigned NOT NULL,
  `model_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint unsigned NOT NULL,
  PRIMARY KEY (`permission_id`,`model_id`,`model_type`),
  KEY `model_has_permissions_model_id_model_type_index` (`model_id`,`model_type`),
  CONSTRAINT `model_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `model_has_permissions`
--

LOCK TABLES `model_has_permissions` WRITE;
/*!40000 ALTER TABLE `model_has_permissions` DISABLE KEYS */;
/*!40000 ALTER TABLE `model_has_permissions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `model_has_roles`
--

DROP TABLE IF EXISTS `model_has_roles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `model_has_roles` (
  `role_id` bigint unsigned NOT NULL,
  `model_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint unsigned NOT NULL,
  PRIMARY KEY (`role_id`,`model_id`,`model_type`),
  KEY `model_has_roles_model_id_model_type_index` (`model_id`,`model_type`),
  CONSTRAINT `model_has_roles_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `model_has_roles`
--

LOCK TABLES `model_has_roles` WRITE;
/*!40000 ALTER TABLE `model_has_roles` DISABLE KEYS */;
INSERT INTO `model_has_roles` VALUES (2,'App\\Models\\User',1),(3,'App\\Models\\User',2),(4,'App\\Models\\User',3),(4,'App\\Models\\User',4),(4,'App\\Models\\User',5),(2,'App\\Models\\User',6),(1,'App\\Models\\User',7),(1,'App\\Models\\User',8);
/*!40000 ALTER TABLE `model_has_roles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `notes`
--

DROP TABLE IF EXISTS `notes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `notes` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `notable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `notable_id` bigint unsigned NOT NULL,
  `user_id` bigint unsigned NOT NULL,
  `content` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_pinned` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `notes_notable_type_notable_id_index` (`notable_type`,`notable_id`),
  KEY `notes_user_id_foreign` (`user_id`),
  CONSTRAINT `notes_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `notes`
--

LOCK TABLES `notes` WRITE;
/*!40000 ALTER TABLE `notes` DISABLE KEYS */;
/*!40000 ALTER TABLE `notes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `notifications`
--

DROP TABLE IF EXISTS `notifications`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `notifications` (
  `id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `notifiable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `notifiable_id` bigint unsigned NOT NULL,
  `data` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `read_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `notifications_notifiable_type_notifiable_id_index` (`notifiable_type`,`notifiable_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `notifications`
--

LOCK TABLES `notifications` WRITE;
/*!40000 ALTER TABLE `notifications` DISABLE KEYS */;
/*!40000 ALTER TABLE `notifications` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `password_reset_tokens`
--

DROP TABLE IF EXISTS `password_reset_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `password_reset_tokens`
--

LOCK TABLES `password_reset_tokens` WRITE;
/*!40000 ALTER TABLE `password_reset_tokens` DISABLE KEYS */;
/*!40000 ALTER TABLE `password_reset_tokens` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `permissions`
--

DROP TABLE IF EXISTS `permissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `permissions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `permissions_name_guard_name_unique` (`name`,`guard_name`)
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `permissions`
--

LOCK TABLES `permissions` WRITE;
/*!40000 ALTER TABLE `permissions` DISABLE KEYS */;
INSERT INTO `permissions` VALUES (1,'leads.view','web','2026-04-01 08:49:01','2026-04-01 08:49:01'),(2,'leads.create','web','2026-04-01 08:49:01','2026-04-01 08:49:01'),(3,'leads.edit','web','2026-04-01 08:49:01','2026-04-01 08:49:01'),(4,'leads.delete','web','2026-04-01 08:49:01','2026-04-01 08:49:01'),(5,'leads.assign','web','2026-04-01 08:49:01','2026-04-01 08:49:01'),(6,'leads.export','web','2026-04-01 08:49:01','2026-04-01 08:49:01'),(7,'properties.view','web','2026-04-01 08:49:01','2026-04-01 08:49:01'),(8,'properties.create','web','2026-04-01 08:49:01','2026-04-01 08:49:01'),(9,'properties.edit','web','2026-04-01 08:49:01','2026-04-01 08:49:01'),(10,'properties.delete','web','2026-04-01 08:49:01','2026-04-01 08:49:01'),(11,'clients.view','web','2026-04-01 08:49:01','2026-04-01 08:49:01'),(12,'clients.create','web','2026-04-01 08:49:01','2026-04-01 08:49:01'),(13,'clients.edit','web','2026-04-01 08:49:01','2026-04-01 08:49:01'),(14,'follow_ups.view','web','2026-04-01 08:49:01','2026-04-01 08:49:01'),(15,'follow_ups.create','web','2026-04-01 08:49:01','2026-04-01 08:49:01'),(16,'follow_ups.edit','web','2026-04-01 08:49:01','2026-04-01 08:49:01'),(17,'dashboard.view','web','2026-04-01 08:49:01','2026-04-01 08:49:01'),(18,'reports.view','web','2026-04-01 08:49:01','2026-04-01 08:49:01'),(19,'users.view','web','2026-04-01 08:49:01','2026-04-01 08:49:01'),(20,'users.create','web','2026-04-01 08:49:01','2026-04-01 08:49:01'),(21,'users.edit','web','2026-04-01 08:49:01','2026-04-01 08:49:01'),(22,'users.delete','web','2026-04-01 08:49:01','2026-04-01 08:49:01');
/*!40000 ALTER TABLE `permissions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `personal_access_tokens`
--

DROP TABLE IF EXISTS `personal_access_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `personal_access_tokens` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `tokenable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint unsigned NOT NULL,
  `name` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text COLLATE utf8mb4_unicode_ci,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`),
  KEY `personal_access_tokens_expires_at_index` (`expires_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `personal_access_tokens`
--

LOCK TABLES `personal_access_tokens` WRITE;
/*!40000 ALTER TABLE `personal_access_tokens` DISABLE KEYS */;
/*!40000 ALTER TABLE `personal_access_tokens` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `properties`
--

DROP TABLE IF EXISTS `properties`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `properties` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` enum('plot','villa','flat') COLLATE utf8mb4_unicode_ci NOT NULL,
  `sub_type` enum('orchard','settlement','sanad','na') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `location` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `city_id` bigint unsigned DEFAULT NULL,
  `area` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `price` decimal(14,2) NOT NULL,
  `owner_expected_price` decimal(14,2) DEFAULT NULL,
  `quoted_price` decimal(14,2) DEFAULT NULL,
  `final_selling_price` decimal(14,2) DEFAULT NULL,
  `commission_percent` decimal(5,2) NOT NULL DEFAULT '2.00',
  `commission_amount` decimal(14,2) DEFAULT NULL,
  `is_negotiable` tinyint(1) NOT NULL DEFAULT '0',
  `negotiable_price` decimal(14,2) DEFAULT NULL,
  `total_plot_price` decimal(14,2) DEFAULT NULL,
  `price_per_sqm` decimal(10,2) DEFAULT NULL,
  `min_rate_sqm` decimal(10,2) DEFAULT NULL,
  `max_rate_sqm` decimal(10,2) DEFAULT NULL,
  `size_sqm` decimal(10,2) DEFAULT NULL,
  `size_label` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bedrooms` int DEFAULT NULL,
  `bathrooms` int DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `status` enum('available','reserved','sold') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'available',
  `tags` json DEFAULT NULL,
  `amenities` json DEFAULT NULL,
  `images` json DEFAULT NULL,
  `map_link` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `owner_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `owner_phone` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `added_by` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `properties_slug_unique` (`slug`),
  KEY `properties_added_by_foreign` (`added_by`),
  KEY `properties_type_index` (`type`),
  KEY `properties_city_id_foreign` (`city_id`),
  CONSTRAINT `properties_added_by_foreign` FOREIGN KEY (`added_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `properties_city_id_foreign` FOREIGN KEY (`city_id`) REFERENCES `cities` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `properties`
--

LOCK TABLES `properties` WRITE;
/*!40000 ALTER TABLE `properties` DISABLE KEYS */;
INSERT INTO `properties` VALUES (1,'Premium Orchard Plot - Assagao','premium-orchard-plot-assagao-jaa7y','plot','orchard','Assagao',NULL,'North Goa',4500000.00,NULL,NULL,NULL,2.00,NULL,0,NULL,NULL,NULL,NULL,NULL,500.00,'500 sq.m',NULL,NULL,'Lush green orchard plot in prime Assagao location with road access.','available','[\"investment\", \"premium\"]',NULL,NULL,NULL,NULL,NULL,1,'2026-04-01 08:49:02','2026-04-01 08:49:02',NULL),(2,'Settlement Plot - Calangute','settlement-plot-calangute-hnzhv','plot','settlement','Calangute',NULL,'North Goa',8000000.00,NULL,NULL,NULL,2.00,NULL,0,NULL,NULL,NULL,NULL,NULL,300.00,'300 sq.m',NULL,NULL,'Settlement plot 5 mins from Calangute beach, ideal for villa construction.','available','[\"beach-proximity\", \"settlement\"]',NULL,NULL,NULL,NULL,NULL,1,'2026-04-01 08:49:02','2026-04-01 08:49:02',NULL),(3,'Sanad Plot - Siolim','sanad-plot-siolim-y81or','plot','sanad','Siolim',NULL,'North Goa',3500000.00,NULL,NULL,NULL,2.00,NULL,0,NULL,NULL,NULL,NULL,NULL,400.00,'400 sq.m',NULL,NULL,'Sanad plot with river view in quiet Siolim neighborhood.','available','[\"budget\", \"sanad\"]',NULL,NULL,NULL,NULL,NULL,1,'2026-04-01 08:49:02','2026-04-01 08:49:02',NULL),(4,'Luxury Villa - Vagator','luxury-villa-vagator-pskmv','villa',NULL,'Vagator',NULL,'North Goa',25000000.00,NULL,NULL,NULL,2.00,NULL,0,NULL,NULL,NULL,NULL,NULL,250.00,'250 sq.m built-up',4,4,'4BHK luxury villa with private pool and sea view.','available','[\"luxury\", \"pool\", \"sea-view\"]',NULL,NULL,NULL,NULL,NULL,1,'2026-04-01 08:49:02','2026-04-01 08:49:02',NULL),(5,'2BHK Flat - Panjim','2bhk-flat-panjim-0gtoc','flat',NULL,'Panjim',NULL,'Central Goa',6500000.00,NULL,NULL,NULL,2.00,NULL,0,NULL,NULL,NULL,NULL,NULL,95.00,'95 sq.m',2,2,'Ready-to-move 2BHK flat in the heart of Panjim.','available','[\"city\", \"ready-to-move\"]',NULL,NULL,NULL,NULL,NULL,1,'2026-04-01 08:49:02','2026-04-01 08:49:02',NULL),(6,'NA Plot - Anjuna','na-plot-anjuna-8juta','plot','na','Anjuna',NULL,'North Goa',12000000.00,NULL,NULL,NULL,2.00,NULL,0,NULL,NULL,NULL,NULL,NULL,600.00,'600 sq.m',NULL,NULL,'NA converted plot in premium Anjuna location.','reserved','[\"premium\", \"na-converted\"]',NULL,NULL,NULL,NULL,NULL,1,'2026-04-01 08:49:02','2026-04-01 08:49:02',NULL);
/*!40000 ALTER TABLE `properties` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `role_has_permissions`
--

DROP TABLE IF EXISTS `role_has_permissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `role_has_permissions` (
  `permission_id` bigint unsigned NOT NULL,
  `role_id` bigint unsigned NOT NULL,
  PRIMARY KEY (`permission_id`,`role_id`),
  KEY `role_has_permissions_role_id_foreign` (`role_id`),
  CONSTRAINT `role_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  CONSTRAINT `role_has_permissions_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `role_has_permissions`
--

LOCK TABLES `role_has_permissions` WRITE;
/*!40000 ALTER TABLE `role_has_permissions` DISABLE KEYS */;
INSERT INTO `role_has_permissions` VALUES (1,1),(2,1),(3,1),(4,1),(5,1),(6,1),(7,1),(8,1),(9,1),(10,1),(11,1),(12,1),(13,1),(14,1),(15,1),(16,1),(17,1),(18,1),(19,1),(20,1),(21,1),(22,1),(1,2),(2,2),(3,2),(4,2),(5,2),(6,2),(7,2),(8,2),(9,2),(10,2),(11,2),(12,2),(13,2),(14,2),(15,2),(16,2),(17,2),(18,2),(19,2),(20,2),(21,2),(22,2),(1,3),(2,3),(3,3),(4,3),(5,3),(6,3),(7,3),(8,3),(9,3),(11,3),(12,3),(13,3),(14,3),(15,3),(16,3),(17,3),(18,3),(19,3),(1,4),(2,4),(3,4),(7,4),(11,4),(12,4),(13,4),(14,4),(15,4),(16,4),(17,4);
/*!40000 ALTER TABLE `role_has_permissions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `roles`
--

DROP TABLE IF EXISTS `roles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `roles` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `roles_name_guard_name_unique` (`name`,`guard_name`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `roles`
--

LOCK TABLES `roles` WRITE;
/*!40000 ALTER TABLE `roles` DISABLE KEYS */;
INSERT INTO `roles` VALUES (1,'super_admin','web','2026-04-01 08:49:01','2026-04-01 08:49:01'),(2,'admin','web','2026-04-01 08:49:01','2026-04-01 08:49:01'),(3,'manager','web','2026-04-01 08:49:01','2026-04-01 08:49:01'),(4,'sales_agent','web','2026-04-01 08:49:01','2026-04-01 08:49:01');
/*!40000 ALTER TABLE `roles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sessions`
--

DROP TABLE IF EXISTS `sessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sessions` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sessions`
--

LOCK TABLES `sessions` WRITE;
/*!40000 ALTER TABLE `sessions` DISABLE KEYS */;
INSERT INTO `sessions` VALUES ('4bAi3IOALHME6Y5y6WAo855x7d9j8CijKkTIH6Fa',NULL,'127.0.0.1','curl/8.7.1','eyJfdG9rZW4iOiJPZm1HY0JZRHh1OFppWXFhbnZUS2YzUlk4U0dqQ1dXclJUTVNuMnI4IiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cLzEyNy4wLjAuMTo4MDAyXC9sb2dpbiIsInJvdXRlIjoibG9naW4ifSwiX2ZsYXNoIjp7Im9sZCI6W10sIm5ldyI6W119fQ==',1775053153),('4jPsQzJ4WOEBPLA0QhErs2toxWUELC8l3u9xEH5b',6,'127.0.0.1','curl/8.7.1','eyJfdG9rZW4iOiIxQWpGYldBSnpMMThFUzNFWG9QTGV0SjBucE5vUlNtS3FtN09xMFBrIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cLzEyNy4wLjAuMTo4MDAyXC9sZWFkc1wvY3JlYXRlIiwicm91dGUiOiJsZWFkcy5jcmVhdGUifSwiX2ZsYXNoIjp7Im9sZCI6W10sIm5ldyI6W119LCJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI6Nn0=',1775117419),('fQxgTDrLDnrZsUBKC0YpAq6vRcfnkKGJ9i6zgEYw',NULL,'127.0.0.1','curl/8.7.1','eyJfdG9rZW4iOiJVUXpMb2tLSkV6dGVyYXBBRERheWVHUU1FMjBJWHlVUm5rWnBoQ2dKIiwidXJsIjp7ImludGVuZGVkIjoiaHR0cDpcL1wvMTI3LjAuMC4xOjgwMDJcL2xlYWRzIn0sIl9wcmV2aW91cyI6eyJ1cmwiOiJodHRwOlwvXC8xMjcuMC4wLjE6ODAwMlwvbG9naW4iLCJyb3V0ZSI6ImxvZ2luIn0sIl9mbGFzaCI6eyJvbGQiOltdLCJuZXciOltdfX0=',1775116064),('JoXuAdq2jWvThXpOAMBR6yWcXJqyaBZE3INWGUo5',7,'127.0.0.1','curl/8.7.1','eyJfdG9rZW4iOiJuMHEwN1lDVjF4SkxTVXVrRWtQbFBGS1pnWjVJQmd2Y0dpbU01cDVhIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cLzEyNy4wLjAuMTo4MDAyXC9wcm9wZXJ0aWVzXC8xIiwicm91dGUiOiJwcm9wZXJ0aWVzLnNob3cifSwiX2ZsYXNoIjp7Im9sZCI6W10sIm5ldyI6W119LCJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI6N30=',1775291146),('mkvKdflRFj0ssVErXt18WFVW17rwkgTM1F7WTzQB',NULL,'127.0.0.1','curl/8.7.1','eyJfdG9rZW4iOiJyNngyUzNHYXdjM1RvR2R0QzFDcW9vaU9uZERCRlNTS09WcnRTZWxJIiwidXJsIjp7ImludGVuZGVkIjoiaHR0cDpcL1wvMTI3LjAuMC4xOjgwMDJcL3Byb3BlcnRpZXNcLzEifSwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cLzEyNy4wLjAuMTo4MDAyXC9wcm9wZXJ0aWVzXC8xIiwicm91dGUiOiJwcm9wZXJ0aWVzLnNob3cifSwiX2ZsYXNoIjp7Im9sZCI6W10sIm5ldyI6W119fQ==',1775288134),('r2Dws0y95S47gCEVmAynBbKipRa230CHoIns1QjQ',NULL,'127.0.0.1','curl/8.7.1','eyJfdG9rZW4iOiJsNGIxOXNhQUYwRjVFYUhUcTZNTUJ5VzAxa3hwRDRHVXpqTGJzelBrIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cLzEyNy4wLjAuMTo4MDAyXC9lbnF1aXJ5Iiwicm91dGUiOiJwdWJsaWMubGVhZC1mb3JtIn0sIl9mbGFzaCI6eyJvbGQiOltdLCJuZXciOltdfX0=',1775116239),('snaiEGGhZkOGLuSgc1m9NETz10DTzDiweO96wyTJ',NULL,'127.0.0.1','curl/8.7.1','eyJfdG9rZW4iOiJrUzNiWGE1NjVGQzd2SFlPd1l4QXU5V29hMldhbk1Xa2Vqb1ZiWTFxIiwidXJsIjp7ImludGVuZGVkIjoiaHR0cDpcL1wvMTI3LjAuMC4xOjgwMDJcL3Byb3BlcnRpZXMifSwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cLzEyNy4wLjAuMTo4MDAyXC9wcm9wZXJ0aWVzIiwicm91dGUiOiJwcm9wZXJ0aWVzLmluZGV4In0sIl9mbGFzaCI6eyJvbGQiOltdLCJuZXciOltdfX0=',1775288134),('YjGTiLYRD6aiEenAIa6w5H9P1xQkInA34L3szyYe',6,'127.0.0.1','curl/8.7.1','eyJfdG9rZW4iOiJ6Ym1nS2tlQ0d5cVFFWEJLVkhaN1lkNDJ2Qlh2MXJHd2RueldtcUZwIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cLzEyNy4wLjAuMTo4MDAyXC9sZWFkcyIsInJvdXRlIjoibGVhZHMuaW5kZXgifSwiX2ZsYXNoIjp7Im9sZCI6W10sIm5ldyI6W119LCJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI6Nn0=',1775116074),('YNSKAL43ya8ZCtj4lKUL0kuz5RFBF9VcKBIp6Ffd',NULL,'127.0.0.1','curl/8.7.1','eyJfdG9rZW4iOiJ0T2hLSGtFV2pVVGtpRFRTM2VWRDhNTkRJTXZQRDJWT0N0cXZYWjhVIiwidXJsIjp7ImludGVuZGVkIjoiaHR0cDpcL1wvMTI3LjAuMC4xOjgwMDJcL3Byb3BlcnRpZXNcL2NyZWF0ZSJ9LCJfcHJldmlvdXMiOnsidXJsIjoiaHR0cDpcL1wvMTI3LjAuMC4xOjgwMDJcL3Byb3BlcnRpZXNcL2NyZWF0ZSIsInJvdXRlIjoicHJvcGVydGllcy5jcmVhdGUifSwiX2ZsYXNoIjp7Im9sZCI6W10sIm5ldyI6W119fQ==',1775288134),('zTAAjnAhOY91cdI379K9AopQX9WCJbFSQyky9Lfg',NULL,'127.0.0.1','curl/8.7.1','eyJfdG9rZW4iOiJWdUVxcVJKMjVzbERDaXV3anZqT1IwbFRKT1plQUttNFI2aWNkUlpOIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cLzEyNy4wLjAuMTo4MDAyXC9sb2dpbiIsInJvdXRlIjoibG9naW4ifSwiX2ZsYXNoIjp7Im9sZCI6W10sIm5ldyI6W119fQ==',1775116217);
/*!40000 ALTER TABLE `sessions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `taggables`
--

DROP TABLE IF EXISTS `taggables`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `taggables` (
  `tag_id` bigint unsigned NOT NULL,
  `taggable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `taggable_id` bigint unsigned NOT NULL,
  UNIQUE KEY `taggables_tag_id_taggable_id_taggable_type_unique` (`tag_id`,`taggable_id`,`taggable_type`),
  KEY `taggables_taggable_type_taggable_id_index` (`taggable_type`,`taggable_id`),
  CONSTRAINT `taggables_tag_id_foreign` FOREIGN KEY (`tag_id`) REFERENCES `tags` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `taggables`
--

LOCK TABLES `taggables` WRITE;
/*!40000 ALTER TABLE `taggables` DISABLE KEYS */;
/*!40000 ALTER TABLE `taggables` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tags`
--

DROP TABLE IF EXISTS `tags`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tags` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `color` varchar(7) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '#6B7280',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `tags_name_unique` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tags`
--

LOCK TABLES `tags` WRITE;
/*!40000 ALTER TABLE `tags` DISABLE KEYS */;
/*!40000 ALTER TABLE `tags` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `username` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `avatar` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`),
  UNIQUE KEY `users_username_unique` (`username`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'Admin','admin','admin@propertiesbydesi.com','9876543210',NULL,1,NULL,'$2y$12$Z45.BtKzipFgpz65RhZ6n.R8qMGHwGvZPdwOFkiiO9c38EjU3Wo8a',NULL,'2026-04-01 08:49:01','2026-04-01 08:49:22',NULL),(2,'Rahul Sharma','rahul','rahul@propertiesbydesi.com','9876543211',NULL,1,NULL,'$2y$12$xJCRgx4hY04j4sTUACxtPeImrEv3.LliG7AYQ86fivaJko/wIFy2K',NULL,'2026-04-01 08:49:01','2026-04-01 08:49:22',NULL),(3,'Pasad','pasad','priya@propertiesbydesi.com','9876543212',NULL,1,NULL,'$2y$12$arlMP0e1bswp34xrgXPHVusjdXqQTNUNvKb1W4LzEG0Pz6lCpIX26',NULL,'2026-04-01 08:49:01','2026-04-01 08:49:22',NULL),(4,'Amit Desai','amit','amit@propertiesbydesi.com','9876543213',NULL,1,NULL,'$2y$12$WCF8b/YBbiPhoKZErht.G.bwlkUKSwCvmRhaelaLpIFj4xj25bJ1K',NULL,'2026-04-01 08:49:02','2026-04-01 08:49:22',NULL),(5,'Sneha Patel','sneha','sneha@propertiesbydesi.com','9876543214',NULL,1,NULL,'$2y$12$rsFAyjKyPNQvkGsJb6Ib1eCGdKkornQPEwpCfDbOD4yzeZC80N6aa',NULL,'2026-04-01 08:49:02','2026-04-01 08:49:22',NULL),(6,'Fiza','fiza','fiza@propertiesbydesi.com',NULL,NULL,1,NULL,'$2y$12$1Dx8PH6SvRK8ziKcECs0I.JgCLbe5lMaBfVzWV36QK4a8mjQLA8x.',NULL,'2026-04-01 08:49:22','2026-04-01 08:49:22',NULL),(7,'Mohsin','mohsin','mohsin@propertiesbydesi.com',NULL,NULL,1,NULL,'$2y$12$z97PItQMZTE.r/oyCstcDOsMh9OemZVxnNVY/DGKtKCdu82YFxDxa',NULL,'2026-04-01 08:49:23','2026-04-01 08:49:23',NULL),(8,'Mufeez','mufeez','mufeez@propertiesbydesi.com',NULL,NULL,1,NULL,'$2y$12$ZYOaYfHmICKJPh.Yuk7guO5tM/riAy.iOECwuf/o2uL9jlZJR5Pg.',NULL,'2026-04-01 08:49:23','2026-04-01 08:49:23',NULL);
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-04-04 13:55:55
