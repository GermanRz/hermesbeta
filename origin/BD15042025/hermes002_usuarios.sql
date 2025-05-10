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
-- Table structure for table `usuarios`
--

DROP TABLE IF EXISTS `usuarios`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `usuarios` (
  `id_usuario` int NOT NULL AUTO_INCREMENT,
  `tipo_documento` varchar(5) NOT NULL,
  `numero_documento` varchar(30) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `apellido` varchar(50) NOT NULL,
  `correo_electronico` varchar(100) NOT NULL,
  `nombre_usuario` varchar(50) NOT NULL,
  `clave` varchar(255) NOT NULL,
  `telefono` varchar(20) DEFAULT NULL,
  `direccion` varchar(100) DEFAULT NULL,
  `genero` int DEFAULT '3',
  `estado` enum('activo','inactivo') DEFAULT 'activo',
  `fecha_registro` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_usuario`),
  UNIQUE KEY `nombre_usuario` (`nombre_usuario`),
  UNIQUE KEY `numero_documento_UNIQUE` (`numero_documento`)
) ENGINE=InnoDB AUTO_INCREMENT=73 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `usuarios`
--

LOCK TABLES `usuarios` WRITE;
/*!40000 ALTER TABLE `usuarios` DISABLE KEYS */;
INSERT INTO `usuarios` VALUES (1,'CC','1','Admin','Sistema','admin@sistema.com','admin','admin123',NULL,NULL,3,'activo','2025-03-22 16:35:28'),(42,'CC','1023456789','Juan','Pérez','juan.perez@email.com','juanperez1','clave123',NULL,NULL,3,'activo','2025-04-02 03:29:02'),(43,'TI','1234567','María','Gómez','maria.gomez@email.com','mariagomez2','clave123',NULL,NULL,3,'activo','2025-04-02 03:29:02'),(44,'CC','1098765432','Carlos','López','carlos.lopez@email.com','carloslopez3','clave123',NULL,NULL,3,'inactivo','2025-04-02 03:29:02'),(45,'TI','7654321','Ana','Martínez','ana.martinez@email.com','anamartinez4','clave123',NULL,NULL,3,'activo','2025-04-02 03:29:02'),(46,'CC','1002345678','Pedro','Sánchez','pedro.sanchez@email.com','pedrosanchez5','clave123',NULL,NULL,3,'activo','2025-04-02 03:29:02'),(47,'TI','1122334','Laura','Fernández','laura.fernandez@email.com','laurafernandez6','clave123',NULL,NULL,3,'activo','2025-04-02 03:29:02'),(48,'CC','1034567890','Luis','Torres','luis.torres@email.com','luistorres7','clave123',NULL,NULL,3,'activo','2025-04-02 03:29:02'),(49,'TI','2233445','Sofía','Ramírez','sofia.ramirez@email.com','sofiaramirez8','clave123',NULL,NULL,3,'inactivo','2025-04-02 03:29:02'),(50,'CC','1045678901','Andrés','Vargas','andres.vargas@email.com','andresvargas9','clave123',NULL,NULL,3,'activo','2025-04-02 03:29:02'),(51,'TI','3344556','Elena','Hernández','elena.hernandez@email.com','elenahernandez10','clave123',NULL,NULL,3,'activo','2025-04-02 03:29:02'),(52,'CC','1056789012','Gabriel','Castro','gabriel.castro@email.com','gabrielcastro11','clave123',NULL,NULL,3,'activo','2025-04-02 03:29:02'),(53,'TI','4455667','Paula','Ortega','paula.ortega@email.com','paulaortega12','clave123',NULL,NULL,3,'activo','2025-04-02 03:29:02'),(54,'CC','1067890123','Ricardo','Molina','ricardo.molina@email.com','ricardomolina13','clave123',NULL,NULL,3,'activo','2025-04-02 03:29:02'),(55,'TI','5566778','Fernanda','Ruiz','fernanda.ruiz@email.com','fernandaruiz14','clave123',NULL,NULL,3,'activo','2025-04-02 03:29:02'),(56,'CC','1078901234','Hugo','Silva','hugo.silva@email.com','hugosilva15','clave123',NULL,NULL,3,'activo','2025-04-02 03:29:02'),(57,'TI','6677889','Isabel','Jiménez','isabel.jimenez@email.com','isabeljimenez16','clave123',NULL,NULL,3,'activo','2025-04-02 03:29:02'),(58,'CC','1089012345','José','Morales','jose.morales@email.com','josemorales17','clave123',NULL,NULL,3,'activo','2025-04-02 03:29:02'),(59,'TI','7788990','Natalia','Paredes','natalia.paredes@email.com','nataliaparedes18','clave123',NULL,NULL,3,'activo','2025-04-02 03:29:02'),(60,'CC','1090123456','Emilio','Guzmán','emilio.guzman@email.com','emilioguzman19','clave123',NULL,NULL,3,'activo','2025-04-02 03:29:02'),(61,'TI','8899001','Valeria','Díaz','valeria.diaz@email.com','valeriadiaz20','clave123',NULL,NULL,3,'activo','2025-04-02 03:29:02'),(71,'CC','75','German','Ramirez','test@example.us','75','75','6019521325','calle 20 33a42',2,'activo','2025-04-04 16:00:40'),(72,'CC','1116','Alonso','Arboleda','teste@exemplo.us','1116','1116','315','calle 20 33a42',2,'activo','2025-04-04 16:16:32');
/*!40000 ALTER TABLE `usuarios` ENABLE KEYS */;
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
