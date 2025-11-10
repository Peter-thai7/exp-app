/*M!999999\- enable the sandbox mode */ 
-- MariaDB dump 10.19-11.8.3-MariaDB, for Linux (x86_64)
--
-- Host: localhost    Database: u943879768_expense
-- ------------------------------------------------------
-- Server version	11.8.3-MariaDB-log

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*M!100616 SET @OLD_NOTE_VERBOSITY=@@NOTE_VERBOSITY, NOTE_VERBOSITY=0 */;

--
-- Table structure for table `cache`
--

DROP TABLE IF EXISTS `cache`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cache`
--

LOCK TABLES `cache` WRITE;
/*!40000 ALTER TABLE `cache` DISABLE KEYS */;
set autocommit=0;
/*!40000 ALTER TABLE `cache` ENABLE KEYS */;
UNLOCK TABLES;
commit;

--
-- Table structure for table `cache_locks`
--

DROP TABLE IF EXISTS `cache_locks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cache_locks`
--

LOCK TABLES `cache_locks` WRITE;
/*!40000 ALTER TABLE `cache_locks` DISABLE KEYS */;
set autocommit=0;
/*!40000 ALTER TABLE `cache_locks` ENABLE KEYS */;
UNLOCK TABLES;
commit;

--
-- Table structure for table `categories`
--

DROP TABLE IF EXISTS `categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `categories` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name_th` varchar(255) NOT NULL,
  `name_en` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `categories`
--

LOCK TABLES `categories` WRITE;
/*!40000 ALTER TABLE `categories` DISABLE KEYS */;
set autocommit=0;
INSERT INTO `categories` VALUES
(1,'อาหาร',NULL,NULL,'2025-09-18 15:58:47','2025-09-18 15:58:47'),
(2,'การเดินทาง',NULL,NULL,'2025-09-18 15:58:47','2025-09-18 15:58:47'),
(3,'ค่าสาธารณูปโภค',NULL,NULL,'2025-09-18 15:58:47','2025-09-18 15:58:47'),
(4,'การช้อปปิ้ง',NULL,NULL,'2025-09-18 15:58:47','2025-09-18 15:58:47'),
(5,'ค่ารักษาพยาบาล','Medical Expenses','ค่าแพทย์ ค่ายา ค่ารพ.','2025-09-20 08:13:07','2025-09-20 08:13:07'),
(6,'ค่าการศึกษา','Educational fees',NULL,'2025-09-20 08:16:03','2025-09-20 08:16:03'),
(7,'ค่าวิจัยและพัฒนา','Research & Development',NULL,'2025-09-20 08:18:04','2025-09-20 08:18:04'),
(8,'ค่าของใช้','Consumer Goods',NULL,'2025-09-20 08:19:56','2025-09-20 08:19:56'),
(9,'ค่าประกัน','Insurance','ประกันรถ ประกันชีวิต ประกันอุบัติเหตุ','2025-09-20 08:21:52','2025-09-20 08:21:52'),
(10,'ค่าผลไม้','Fruit',NULL,'2025-09-20 08:23:14','2025-09-20 08:23:14'),
(11,'ขนมขบเคี้ยว','Snacks',NULL,'2025-09-20 08:23:50','2025-09-20 08:23:50'),
(12,'ค่าซ่อมบำรุง','Maintenance','ค่าซ่อมรถ ซ่อมบ้าน ซ่อมอุปกรณ์ไฟฟ้า','2025-09-20 08:25:37','2025-09-20 08:25:37');
/*!40000 ALTER TABLE `categories` ENABLE KEYS */;
UNLOCK TABLES;
commit;

--
-- Table structure for table `expenses`
--

DROP TABLE IF EXISTS `expenses`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `expenses` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned NOT NULL,
  `type_id` bigint(20) unsigned NOT NULL,
  `date` date NOT NULL,
  `amount` decimal(12,2) NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `type_id` (`type_id`),
  CONSTRAINT `expenses_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `expenses_ibfk_2` FOREIGN KEY (`type_id`) REFERENCES `types` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `expenses`
--

LOCK TABLES `expenses` WRITE;
/*!40000 ALTER TABLE `expenses` DISABLE KEYS */;
set autocommit=0;
INSERT INTO `expenses` VALUES
(1,5,1,'2025-09-20',125.00,NULL,'2025-09-20 05:23:30','2025-09-20 05:23:30'),
(2,5,5,'2025-09-20',1000.00,NULL,'2025-09-20 06:17:09','2025-09-20 06:17:09'),
(3,5,10,'2025-09-20',2500.00,NULL,'2025-09-20 06:17:39','2025-09-20 06:17:39'),
(4,5,13,'2025-09-20',223.00,'เสื้อเชิ้ต','2025-09-20 06:25:34','2025-09-20 06:25:34'),
(5,5,16,'2025-09-20',3500.00,'ซ่อมแอร์รถยนต์','2025-09-20 12:39:53','2025-09-20 12:39:53'),
(6,5,5,'2025-09-20',1500.00,'ดีเซล เอเวอร์เรส','2025-09-20 12:41:19','2025-09-20 12:41:19');
/*!40000 ALTER TABLE `expenses` ENABLE KEYS */;
UNLOCK TABLES;
commit;

--
-- Table structure for table `failed_jobs`
--

DROP TABLE IF EXISTS `failed_jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `failed_jobs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `failed_jobs`
--

LOCK TABLES `failed_jobs` WRITE;
/*!40000 ALTER TABLE `failed_jobs` DISABLE KEYS */;
set autocommit=0;
/*!40000 ALTER TABLE `failed_jobs` ENABLE KEYS */;
UNLOCK TABLES;
commit;

--
-- Table structure for table `job_batches`
--

DROP TABLE IF EXISTS `job_batches`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `job_batches`
--

LOCK TABLES `job_batches` WRITE;
/*!40000 ALTER TABLE `job_batches` DISABLE KEYS */;
set autocommit=0;
/*!40000 ALTER TABLE `job_batches` ENABLE KEYS */;
UNLOCK TABLES;
commit;

--
-- Table structure for table `jobs`
--

DROP TABLE IF EXISTS `jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `jobs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) unsigned NOT NULL,
  `reserved_at` int(10) unsigned DEFAULT NULL,
  `available_at` int(10) unsigned NOT NULL,
  `created_at` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jobs`
--

LOCK TABLES `jobs` WRITE;
/*!40000 ALTER TABLE `jobs` DISABLE KEYS */;
set autocommit=0;
/*!40000 ALTER TABLE `jobs` ENABLE KEYS */;
UNLOCK TABLES;
commit;

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `migrations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
set autocommit=0;
INSERT INTO `migrations` VALUES
(7,'0001_01_01_000000_create_users_table',1),
(8,'0001_01_01_000001_create_cache_table',1),
(9,'0001_01_01_000002_create_jobs_table',1),
(10,'2025_09_18_123642_create_categories_table',1),
(11,'2025_09_18_123643_add_role_to_users_table',1),
(12,'2025_09_18_123643_create_types_table',1);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;
commit;

--
-- Table structure for table `password_reset_tokens`
--

DROP TABLE IF EXISTS `password_reset_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `password_reset_tokens`
--

LOCK TABLES `password_reset_tokens` WRITE;
/*!40000 ALTER TABLE `password_reset_tokens` DISABLE KEYS */;
set autocommit=0;
/*!40000 ALTER TABLE `password_reset_tokens` ENABLE KEYS */;
UNLOCK TABLES;
commit;

--
-- Table structure for table `sessions`
--

DROP TABLE IF EXISTS `sessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL,
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
set autocommit=0;
INSERT INTO `sessions` VALUES
('1r9gM9lfQFFLxG5SZHps0N09U03yp80ZAYyrPCXw',5,'113.53.153.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36 OPR/120.0.0.0','YTo1OntzOjY6Il90b2tlbiI7czo0MDoiUWV6aEtGR0tjZ1pIRjdXb0Y3RE5HdlhOaVhpNG1nYU5USGpPMlM4SSI7czozOiJ1cmwiO2E6MDp7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fXM6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjMxOiJodHRwczovL2V4cC50aGFpdGVjaDUuY29tL2FkbWluIjt9czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6NTt9',1758356737),
('3oAjCZEOVfhOeYjW7Biih8YE24FtKEKDTZYvC60N',NULL,'18.246.10.36','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.110 Safari/537.3','YTozOntzOjY6Il90b2tlbiI7czo0MDoiTGVubWs0SUpBYzNRMjRuTzVZWHZMWmNlcUpkNmlrUldHY3hMZWZLTyI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MjU6Imh0dHBzOi8vZXhwLnRoYWl0ZWNoNS5jb20iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1758361297),
('ak2EIxkekufFZOW4dGJ5aRGTmjwfsKQtfDc82lB7',5,'113.53.153.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36 OPR/120.0.0.0','YTo0OntzOjY6Il90b2tlbiI7czo0MDoiNkxuVDBTUDNQR2wybFVpM0s1eFZpNlNJcFB4YTM1M2hSdVhjdnltMiI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6NDg6Imh0dHBzOi8vZXhwLnRoYWl0ZWNoNS5jb20vYXBpL2NhdGVnb3JpZXMvMS90eXBlcyI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fXM6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjU7fQ==',1758299267),
('COZTq8aVqqYFxOKuMIgbic7p5avLm56Wy88SWteu',NULL,'64.233.173.144','Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Mobile Safari/537.36 (compatible; Google-Read-Aloud; +https://support.google.com/webmasters/answer/1061943)','YTozOntzOjY6Il90b2tlbiI7czo0MDoieVVKZXV2SVIzN1B0YkpYSE9qMTV6cEJJaGp1blpyV1JBSXQ5TDJXYiI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MjU6Imh0dHBzOi8vZXhwLnRoYWl0ZWNoNS5jb20iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1758349371),
('cqUop8SgeyIJqZS3iWMsGzFUa5IP8N4iSAZReL9T',NULL,'2a02:4780:3:1::3','Go-http-client/2.0','YTozOntzOjY6Il90b2tlbiI7czo0MDoibVhzVTlUZmxqSzVZYk5DODI5MlYzQnlRcVR2SUVsR2xTM2ZmRkFlQSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MjU6Imh0dHBzOi8vZXhwLnRoYWl0ZWNoNS5jb20iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1758336803),
('FGTEe8rCMN3T0BHomnXQxgV5lyDIAHsm6VLlWJ9u',NULL,'149.57.180.176','Mozilla/5.0 (X11; Linux i686; rv:109.0) Gecko/20100101 Firefox/120.0','YTozOntzOjY6Il90b2tlbiI7czo0MDoiUHdkSzRrSzRzeDRCc0VFYzBscGZub3lrRHpXM3daVEs3UHZ5NnhxViI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MjU6Imh0dHBzOi8vZXhwLnRoYWl0ZWNoNS5jb20iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1758307612),
('H7HIAWy97U4BRv4JB1rllgPOFSu0OJrhvZ4iZzPQ',NULL,'149.57.180.183','Mozilla/5.0 (X11; Linux i686; rv:109.0) Gecko/20100101 Firefox/120.0','YTozOntzOjY6Il90b2tlbiI7czo0MDoibXp4QjVZbU9HQjJ4dWI3WGJhZ2NPZVdRVjU3aHlBMERiQ0xPck5IdiI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MjU6Imh0dHBzOi8vZXhwLnRoYWl0ZWNoNS5jb20iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1758359694),
('IubAC3rvTALomwknesfZhv3MhlySuYEojZ1WQ9yA',NULL,'64.233.173.146','Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Mobile Safari/537.36 (compatible; Google-Read-Aloud; +https://support.google.com/webmasters/answer/1061943)','YTozOntzOjY6Il90b2tlbiI7czo0MDoicTU1VkJzVFluZVo1SjF1MDVRTGVuWTA0bUlqS0ZVYW9JZlRKME9TayI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MjU6Imh0dHBzOi8vZXhwLnRoYWl0ZWNoNS5jb20iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1758349368),
('jXS7wM7yaxltoi5tUkTRy7tdEoyIYltr296jkosL',NULL,'149.57.180.121','Mozilla/5.0 (X11; Linux i686; rv:109.0) Gecko/20100101 Firefox/120.0','YTozOntzOjY6Il90b2tlbiI7czo0MDoiUDBMbWgxQ21KS3N6WGhWTTVDSXhDYnRwOVFFMFVhMFZaM01lZHJmQyI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MjU6Imh0dHBzOi8vZXhwLnRoYWl0ZWNoNS5jb20iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1758366311),
('ma64hqVQdeeizdBMsD6Qe8ssM3ZWuDNpv9LZK5Al',5,'113.53.153.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36 OPR/120.0.0.0','YTo1OntzOjY6Il90b2tlbiI7czo0MDoiZHBsVHJaOXY4d2kzM1NIQzZWbWwxaVhrd2hUOWxmNUFLaVdpVkRlMiI7czozOiJ1cmwiO2E6MDp7fXM6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjQxOiJodHRwczovL2V4cC50aGFpdGVjaDUuY29tL2V4cGVuc2VzL2NyZWF0ZSI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fXM6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjU7fQ==',1758372083),
('PTioP7ZzzW1YVX3QSgH17qSlXhQUKVkWOg4PBhbc',NULL,'18.246.10.36','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.110 Safari/537.3','YTozOntzOjY6Il90b2tlbiI7czo0MDoiMVJTVUcyN2RlTHRSRjBWSTd0eDB3a2xwNTF6TEkxN0hiSG51eHEydSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MjU6Imh0dHBzOi8vZXhwLnRoYWl0ZWNoNS5jb20iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1758361303),
('TKowpU3Te9UsTELmu3mYPefj9trhz5Vb9hTzyvh9',5,'113.53.153.1','Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Mobile Safari/537.36','YTo1OntzOjY6Il90b2tlbiI7czo0MDoieXVxNzRNM0Vqc0M1SkRBSUhuWEdOSDB5eUxuVHpnaHlPbkcxVE1uQiI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzQ6Imh0dHBzOi8vZXhwLnRoYWl0ZWNoNS5jb20vZXhwZW5zZXMiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX1zOjM6InVybCI7YTowOnt9czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6NTt9',1758349534),
('WGw0R56PoKG1zdq8mYu1YnqkWrZIKl6gSYrTY8bm',NULL,'64.233.173.132','Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Mobile Safari/537.36 (compatible; Google-Read-Aloud; +https://support.google.com/webmasters/answer/1061943)','YTozOntzOjY6Il90b2tlbiI7czo0MDoieVpWYlFpZXBpV3hHYml6M21sU3BLRGxrN05HeXZYQzZtdjh2dXdFOCI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MjU6Imh0dHBzOi8vZXhwLnRoYWl0ZWNoNS5jb20iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1758349370),
('ywHkAMELKhwqUkQ3nEeG73E2J5C2jpIBKAca20mV',NULL,'18.246.10.36','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.110 Safari/537.3','YTozOntzOjY6Il90b2tlbiI7czo0MDoiNnZ1MVNiY2JlcHZtVVg1ZmwxMk5tMHFQZVIxZlFSUmMwT284WHo1VSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6NDI6Imh0dHBzOi8vZXhwLnRoYWl0ZWNoNS5jb20vcHVibGljL2luZGV4LnBocCI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1758361305);
/*!40000 ALTER TABLE `sessions` ENABLE KEYS */;
UNLOCK TABLES;
commit;

--
-- Table structure for table `types`
--

DROP TABLE IF EXISTS `types`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `types` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `category_id` bigint(20) unsigned NOT NULL,
  `name_th` varchar(255) NOT NULL,
  `name_en` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `types_category_id_foreign` (`category_id`),
  CONSTRAINT `types_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `types`
--

LOCK TABLES `types` WRITE;
/*!40000 ALTER TABLE `types` DISABLE KEYS */;
set autocommit=0;
INSERT INTO `types` VALUES
(1,1,'ข้าวเช้า',NULL,'2025-09-18 15:58:47','2025-09-18 15:58:47'),
(2,1,'ข้าวเที่ยง',NULL,'2025-09-18 15:58:47','2025-09-18 15:58:47'),
(3,1,'ข้าวเย็น',NULL,'2025-09-18 15:58:47','2025-09-18 15:58:47'),
(4,1,'ของว่าง',NULL,'2025-09-18 15:58:47','2025-09-18 15:58:47'),
(5,2,'น้ำมัน',NULL,'2025-09-18 15:58:47','2025-09-18 15:58:47'),
(6,2,'ค่าที่จอดรถ',NULL,'2025-09-18 15:58:47','2025-09-18 15:58:47'),
(7,2,'บริการรถโดยสาร',NULL,'2025-09-18 15:58:47','2025-09-18 15:58:47'),
(8,2,'ค่าตั๋วเครื่องบิน',NULL,'2025-09-18 15:58:47','2025-09-18 15:58:47'),
(9,3,'ค่าน้ำ',NULL,'2025-09-18 15:58:47','2025-09-18 15:58:47'),
(10,3,'ค่าไฟฟ้า',NULL,'2025-09-18 15:58:47','2025-09-18 15:58:47'),
(11,3,'ค่าโทรศัพท์',NULL,'2025-09-18 15:58:47','2025-09-18 15:58:47'),
(12,3,'อินเทอร์เน็ต',NULL,'2025-09-18 15:58:47','2025-09-18 15:58:47'),
(13,4,'เสื้อผ้า',NULL,'2025-09-18 15:58:47','2025-09-18 15:58:47'),
(14,4,'เครื่องใช้ในบ้าน',NULL,'2025-09-18 15:58:47','2025-09-18 15:58:47'),
(15,4,'ของขวัญ',NULL,'2025-09-18 15:58:47','2025-09-18 15:58:47'),
(16,12,'รถ','Car','2025-09-20 12:03:11','2025-09-20 12:03:11'),
(17,12,'บ้าน','็Home','2025-09-20 12:03:53','2025-09-20 12:03:53');
/*!40000 ALTER TABLE `types` ENABLE KEYS */;
UNLOCK TABLES;
commit;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `role` enum('admin','user') NOT NULL DEFAULT 'user',
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
set autocommit=0;
INSERT INTO `users` VALUES
(1,'Admin','admin@expense.com',NULL,'$2y$12$JVaN8o5dADEtHOBzJEKlYuVTs5yPDUb2Y7PrpBh1lGhYCFjOxJFL.',NULL,'2025-09-18 15:58:47','2025-09-18 15:58:47','admin'),
(2,'Betty Denesik III','gregorio.kutch@example.com','2025-09-18 15:58:47','$2y$12$K1viRB.RKc8Q/5jfrdDO4.XY.53712jhyorRsKjA7I.BW7vmEOIBm','95QypZ174m','2025-09-18 15:58:47','2025-09-18 15:58:47','user'),
(3,'Dr. Clementina Sipes','dorris75@example.net','2025-09-18 15:58:47','$2y$12$K1viRB.RKc8Q/5jfrdDO4.XY.53712jhyorRsKjA7I.BW7vmEOIBm','sZBwbgU4J5','2025-09-18 15:58:47','2025-09-18 15:58:47','user'),
(4,'Justine Hackett','javonte.pouros@example.com','2025-09-18 15:58:47','$2y$12$K1viRB.RKc8Q/5jfrdDO4.XY.53712jhyorRsKjA7I.BW7vmEOIBm','MRTsQwaUWy','2025-09-18 15:58:47','2025-09-18 15:58:47','user'),
(5,'สมชาย','prapisth@gmail.com',NULL,'$2y$12$CvGQ6o.xAoTY6AkS3uw9vOcKXsNqQufpqTuK3KmLmmFFSJbj.Jmri','6fVnneTjBqT7GMqEWdcM0lKyS2dxL33w22s1kezV7xaCeZitevOhkW53eh6Q','2025-09-19 08:04:03','2025-09-19 12:09:44','user'),
(6,'วินัย ใจดี','neothai1015@gmail.com',NULL,'$2y$12$c00Sie2LSJQWvkQHxq6kyOxQ6/GMvaD1CXSfZ3XSCYo2z6k0lIaC6',NULL,'2025-09-19 08:52:16','2025-09-19 08:52:16','user');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;
commit;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*M!100616 SET NOTE_VERBOSITY=@OLD_NOTE_VERBOSITY */;

-- Dump completed on 2025-09-20 13:14:38
