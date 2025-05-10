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
-- Table structure for table `permisos`
--

DROP TABLE IF EXISTS `permisos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `permisos` (
  `id_permiso` int NOT NULL AUTO_INCREMENT,
  `id_modulo` int NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `descripcion` varchar(255) DEFAULT NULL,
  `fecha_creacion` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `estado` enum('activo','inactivo') DEFAULT 'activo',
  PRIMARY KEY (`id_permiso`),
  UNIQUE KEY `unique_permiso_modulo` (`id_modulo`,`nombre`),
  CONSTRAINT `permisos_ibfk_1` FOREIGN KEY (`id_modulo`) REFERENCES `modulos` (`id_modulo`)
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `permisos`
--

LOCK TABLES `permisos` WRITE;
/*!40000 ALTER TABLE `permisos` DISABLE KEYS */;
INSERT INTO `permisos` VALUES (1,1,'Registrar equipooooo','Crear nuevos equipos en el sistemas','2025-03-22 16:35:28','activo'),(2,1,'Consultar equipos','Consultar información de equipos','2025-03-22 16:35:28','activo'),(3,1,'Modificar equipos','Modificar información de equipos','2025-03-22 16:35:28','activo'),(4,1,'Inhabilitar equipos','Inhabilitar equipos','2025-03-22 16:35:28','activo'),(5,1,'Asignar equipos','Asignar equipos a cuentadantes','2025-03-22 16:35:28','activo'),(6,1,'Revisar equipos','Revisar el estado de los equipos','2025-03-22 16:35:28','activo'),(7,2,'Realizar solicitudes','Realizar solicitudes de préstamo','2025-03-22 16:35:28','activo'),(8,2,'Consultar equipos','Consultar información de equipos para solicitudes','2025-03-22 16:35:28','activo'),(9,2,'Cancelar solicitudes','Cancelar solicitudes','2025-03-22 16:35:28','activo'),(10,2,'Aprobar solicitudes','Aprobar solicitudes de préstamo','2025-03-22 16:35:28','activo'),(11,2,'Entregar equipos','Entregar equipos a los usuarios','2025-03-22 16:35:28','activo'),(12,3,'Recibir equipos','Registrar la devolución de equipos','2025-03-22 16:35:28','activo'),(13,3,'Consultar solicitudes','Consultar solicitudes relacionadas con devoluciones','2025-03-22 16:35:28','activo'),(14,3,'Revisar equipos','Revisar el estado de los equipos devueltos','2025-03-22 16:35:28','activo'),(15,3,'Actualizar estados','Actualizar el estado de los equipos','2025-03-22 16:35:28','activo'),(16,4,'Autorizar salidas','Autorizar salidas de equipos','2025-03-22 16:35:28','activo'),(17,4,'Verificar autorizaciones','Verificar autorizaciones de salida','2025-03-22 16:35:28','activo'),(18,4,'Consultar salidas','Consultar registros de salidas de equipos','2025-03-22 16:35:28','activo'),(19,5,'Configuraraciones ','Acceso a configuraciones del sistema','2025-03-22 16:35:28','activo'),(20,5,'Gestionar usuarios','Gestionar usuarios del sistema','2025-03-22 16:35:28','activo'),(21,5,'Gestionar roles','Gestionar roles y permisos','2025-03-22 16:35:28','activo'),(22,7,'Gestionar fichas','Gestionar las fichas del sistema','2025-04-14 22:57:21','activo');
/*!40000 ALTER TABLE `permisos` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2025-04-15 12:01:04
