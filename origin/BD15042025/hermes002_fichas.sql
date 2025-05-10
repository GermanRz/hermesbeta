-- MySQL dump 10.13  Distrib 8.0.40, for Win64 (x86_64)
--
-- Host: localhost    Database: hermes002
-- ------------------------------------------------------
-- Server version	9.1.0

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `fichas`
--

DROP TABLE IF EXISTS `fichas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `fichas` (
  `id_ficha` int NOT NULL AUTO_INCREMENT,
  `codigo` varchar(50) NOT NULL,
  `descripcion` varchar(255) NOT NULL,
  `id_sede` int NOT NULL,
  `fecha_inicio` date NOT NULL,
  `fecha_fin` date NOT NULL,
  `fecha_creacion` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `estado` enum('activa','inactiva') DEFAULT 'activa',
  PRIMARY KEY (`id_ficha`),
  UNIQUE KEY `codigo` (`codigo`),
  KEY `id_sede` (`id_sede`),
  CONSTRAINT `fichas_ibfk_1` FOREIGN KEY (`id_sede`) REFERENCES `sedes` (`id_sede`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `fichas`
--

LOCK TABLES `fichas` WRITE;
/*!40000 ALTER TABLE `fichas` DISABLE KEYS */;
INSERT INTO `fichas` VALUES (1,'1000001','Tecnología en Análisis y Desarrollo de Software',1,'2024-01-15','2024-12-15','2025-03-24 23:26:50','inactiva'),(2,'1000002','Técnico en Sistemas',28,'2024-02-01','2024-11-30','2025-03-24 23:26:50','activa'),(3,'1000003','Gestión Administrativa',3,'2024-03-10','2024-12-10','2025-03-24 23:26:50','activa'),(4,'1000004','Diseño e Integración de Multimedia',27,'2024-04-05','2025-03-05','2025-03-24 23:26:50','activa'),(5,'1000005','Producción Agropecuaria',28,'2024-05-20','2025-04-20','2025-03-24 23:26:50','inactiva'),(6,'1000006','Electricidad Industrial',1,'2024-06-01','2025-05-01','2025-03-24 23:26:50','activa'),(7,'1000007','Mecánica Automotriz',2,'2024-07-15','2025-06-15','2025-03-24 23:26:50','activa'),(8,'1000008','Contabilidad y Finanzas',28,'2024-08-10','2025-07-10','2025-03-24 23:26:50','activa'),(9,'1000009','Seguridad y Salud en el Trabajo',27,'2024-09-05','2025-08-05','2025-03-24 23:26:50','inactiva'),(10,'1000010','Redes y Telecomunicaciones',28,'2024-10-20','2025-09-20','2025-03-24 23:26:50','activa'),(11,'2847523','ADSO',1,'2025-03-01','2025-03-31','2025-03-25 01:00:56','activa'),(12,'1111','ADSO',28,'2025-03-01','2025-03-29','2025-03-26 17:44:36','activa');
/*!40000 ALTER TABLE `fichas` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2025-04-15 12:01:05
