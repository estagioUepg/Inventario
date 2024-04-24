-- MariaDB dump 10.19  Distrib 10.6.16-MariaDB, for debian-linux-gnu (x86_64)
--
-- Host: localhost    Database: inventario
-- ------------------------------------------------------
-- Server version	10.6.16-MariaDB-0ubuntu0.22.04.1

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
-- Table structure for table `armazenamento`
--

DROP TABLE IF EXISTS `armazenamento`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `armazenamento` (
  `id_maquina` int(11) DEFAULT NULL,
  `nome` text DEFAULT NULL,
  `capacidade` int(11) DEFAULT NULL,
  `id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_maquina` (`id_maquina`),
  CONSTRAINT `armazenamento_ibfk_1` FOREIGN KEY (`id_maquina`) REFERENCES `maquina` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `armazenamento_o`
--

DROP TABLE IF EXISTS `armazenamento_o`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `armazenamento_o` (
  `id_maquina` int(11) DEFAULT NULL,
  `nome` text DEFAULT NULL,
  `capacidade` int(11) DEFAULT NULL,
  `id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_maquina` (`id_maquina`),
  CONSTRAINT `armazenamento_o_ibfk_1` FOREIGN KEY (`id_maquina`) REFERENCES `maquina` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `maquina`
--

DROP TABLE IF EXISTS `maquina`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `maquina` (
  `id` int(11) NOT NULL,
  `nome_n` varchar(20) DEFAULT NULL,
  `nome_o` varchar(20) DEFAULT NULL,
  `ip_n` varchar(20) DEFAULT NULL,
  `ip_o` varchar(20) DEFAULT NULL,
  `processador_n` text DEFAULT NULL,
  `processador_o` text DEFAULT NULL,
  `os` text DEFAULT NULL,
  `mouse` int(11) DEFAULT NULL,
  `teclado` int(11) DEFAULT NULL,
  `alerta` int(11) DEFAULT 0,
  `nome_sala` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `nome_sala` (`nome_sala`),
  CONSTRAINT `maquina_ibfk_1` FOREIGN KEY (`nome_sala`) REFERENCES `sala` (`nome`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb3 */ ;
/*!50003 SET character_set_results = utf8mb3 */ ;
/*!50003 SET collation_connection  = utf8mb3_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,ERROR_FOR_DIVISION_BY_ZERO,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER insere_maquina
BEFORE INSERT
ON maquina
FOR EACH ROW
BEGIN
    SET NEW.nome_o = NEW.nome_n;
    SET NEW.ip_o = NEW.ip_n;
    SET NEW.processador_o = NEW.processador_n;
    IF NEW.nome_n LIKE "%HO7%"
    THEN SET NEW.nome_sala = "sala 1";
    END IF;
    IF NEW.nome_n LIKE "%QPV%"
    THEN SET NEW.nome_sala = "sala note";
    END IF;
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;

--
-- Table structure for table `memoria`
--

DROP TABLE IF EXISTS `memoria`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `memoria` (
  `id_maquina` int(11) DEFAULT NULL,
  `capacidade` int(11) DEFAULT NULL,
  `velocidade` varchar(255) DEFAULT NULL,
  `id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_maquina` (`id_maquina`),
  CONSTRAINT `memoria_ibfk_1` FOREIGN KEY (`id_maquina`) REFERENCES `maquina` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `memoria_o`
--

DROP TABLE IF EXISTS `memoria_o`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `memoria_o` (
  `id_maquina` int(11) DEFAULT NULL,
  `capacidade` int(11) DEFAULT NULL,
  `velocidade` varchar(255) DEFAULT NULL,
  `id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_maquina` (`id_maquina`),
  CONSTRAINT `memoria_o_ibfk_1` FOREIGN KEY (`id_maquina`) REFERENCES `maquina` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `sala`
--

DROP TABLE IF EXISTS `sala`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sala` (
  `nome` varchar(20) NOT NULL,
  `qtd_maquinas` int(11) DEFAULT NULL,
  PRIMARY KEY (`nome`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `software`
--

DROP TABLE IF EXISTS `software`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `software` (
  `id_maquina` int(11) DEFAULT NULL,
  `data_instalacao` datetime DEFAULT NULL,
  `nome` text DEFAULT NULL,
  `proprietario` text DEFAULT NULL,
  `pasta` text DEFAULT NULL,
  `name_id` int(11) DEFAULT NULL,
  `pub_id` int(11) DEFAULT NULL,
  `id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_maquina` (`id_maquina`),
  CONSTRAINT `software_ibfk_1` FOREIGN KEY (`id_maquina`) REFERENCES `maquina` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb3 */ ;
/*!50003 SET character_set_results = utf8mb3 */ ;
/*!50003 SET collation_connection  = utf8mb3_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,ERROR_FOR_DIVISION_BY_ZERO,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER insere_software
BEFORE INSERT
ON software
FOR EACH ROW

BEGIN

    DECLARE nome TEXT;
    DECLARE proprietario TEXT;
    
    SELECT name
    INTO nome
    FROM ocsweb.software_name
    WHERE id = NEW.name_id; 
    
    SET NEW.nome = nome;
    
    SELECT publisher
    INTO proprietario
    FROM ocsweb.software_publisher
    WHERE id = NEW.pub_id; 
    
    SET NEW.proprietario = proprietario;
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;

--
-- Table structure for table `software_o`
--

DROP TABLE IF EXISTS `software_o`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `software_o` (
  `id_maquina` int(11) DEFAULT NULL,
  `data_instalacao` datetime DEFAULT NULL,
  `nome` text DEFAULT NULL,
  `proprietario` text DEFAULT NULL,
  `pasta` text DEFAULT NULL,
  `name_id` int(11) DEFAULT NULL,
  `pub_id` int(11) DEFAULT NULL,
  `id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_maquina` (`id_maquina`),
  CONSTRAINT `software_o_ibfk_1` FOREIGN KEY (`id_maquina`) REFERENCES `maquina` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `video`
--

DROP TABLE IF EXISTS `video`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `video` (
  `id_maquina` int(11) DEFAULT NULL,
  `nome` text DEFAULT NULL,
  `id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_maquina` (`id_maquina`),
  CONSTRAINT `video_ibfk_1` FOREIGN KEY (`id_maquina`) REFERENCES `maquina` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `video_o`
--

DROP TABLE IF EXISTS `video_o`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `video_o` (
  `id_maquina` int(11) DEFAULT NULL,
  `nome` text DEFAULT NULL,
  `id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_maquina` (`id_maquina`),
  CONSTRAINT `video_o_ibfk_1` FOREIGN KEY (`id_maquina`) REFERENCES `maquina` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2024-03-24  4:50:35
