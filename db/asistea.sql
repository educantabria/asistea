-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Versión del servidor:         8.0.30 - MySQL Community Server - GPL
-- SO del servidor:              Win64
-- HeidiSQL Versión:             12.1.0.6537
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

-- Crear la base de datos 'asistea' si no existe
CREATE DATABASE IF NOT EXISTS `asistea` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- Usar la base de datos 'asistea'
USE `asistea`;

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "SYSTEM";   -- Esto configura la zona horaria del servidor MySQL según la configuración del sistema operativo.

CREATE TABLE `categories` (
  `id` int(5) NOT NULL,
  `name` varchar(100) NOT NULL,
  `color` varchar(7) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Volcando datos para la tabla categories: ~9 rows (aproximadamente)
INSERT IGNORE INTO `categories` (`id`, `name`, `color`) VALUES
	(1, 'civil', '#DE1F59'),
	(2, 'inmobiliario', '#DE1FAA'),
	(3, 'familia', '#B01FDE'),
	(4, 'laboral', '#681FDE'),
	(5, 'penal', '#1FAADE'),
	(6, 'mercantil', '#6b747c'),
	(7, 'patentes', '#9293b9'),
	(8, 'sucesiones', '#715050'),
	(9, 'extranjeria', '#715059');
	
CREATE TABLE `expenses` (
  `id` int(20) NOT NULL,
  `title` varchar(150) NOT NULL,
  `category_id` int(5) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `date` date NOT NULL,
  `id_user` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;	

-- Volcando datos para la tabla expenses: ~25 rows (aproximadamente)
INSERT IGNORE INTO `expenses` (`id`, `title`, `category_id`, `amount`, `date`, `id_user`) VALUES
	(1, 'factura 13', 3, 12.40, '2020-03-21', 5),
	(2, 'factura 1', 1, 60.00, '2020-03-21', 5),
	(4, 'factura 9', 2, 1200.00, '2020-03-22', 5),
	(5, 'factura 16', 4, 4600.00, '2020-03-26', 5),
	(6, 'factura 18', 5, 20000.00, '2020-01-25', 5),
	(7, 'factura 2', 1, 140.00, '2020-03-27', 5),
	(10, 'factura 3', 1, 2323.00, '2020-04-03', 5),
	(11, 'factura 4', 1, 232.00, '2020-04-03', 5),
	(12, 'factura 14', 3, 11.00, '2020-04-03', 5),
	(13, 'factura 19', 5, 23.00, '2020-04-03', 5),
	(19, 'factura 5', 1, 300.00, '2020-01-01', 5),
	(20, 'factura 17', 4, 1100.00, '2020-04-04', 5),
	(21, 'factura 1', 3, 200.00, '2020-04-09', 6),
	(23, 'factura 10', 2, 21.00, '2020-06-06', 5),
	(24, 'factura 15', 3, 300.00, '2020-06-04', 5),
	(25, 'factura 11', 2, 200.00, '2020-07-12', 5),
	(26, 'factura 1', 5, 50.00, '2023-11-26', 9),
	(28, 'factura 4', 5, 20.00, '2023-12-04', 10),
	(29, 'factura 6', 1, 30.00, '2020-01-01', 10),
	(30, 'factura 3', 4, 15.00, '2023-12-09', 10),
	(38, 'factura 7', 1, 45.00, '2024-01-30', 5),
	(39, 'factura 12', 2, 100.00, '2024-01-30', 5),
	(40, 'factura 8', 1, 1299.00, '2024-01-30', 5),
	(41, 'factura 1', 1, 35.00, '2020-01-01', 10),
	(42, 'factura 2', 2, 89.00, '2020-01-01', 10);
	
CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(60) NOT NULL,
  `role` enum('user','admin') NOT NULL,
  `budget` float(10,2) NOT NULL,
  `photo` varchar(300) NOT NULL,
  `name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;	
	

-- Volcando datos para la tabla users: ~5 rows (aproximadamente)
INSERT IGNORE INTO `users` (`id`, `username`, `password`, `role`, `budget`, `photo`, `name`) VALUES
	(5, 'asistea', '$2y$10$0aOmd1LTFHtBLCEtDrJgy.xxs7FArnOlzHXLrviwP85LWS.XbxsNO', 'user', 20000.00, '570a1f4bd1ecbfa3c0c5025955c24d86.png', 'Asistea'),
	(6, 'lena', '$2y$10$C/MX.IRvzrNuMyo4pk5uU.bCD20hSWChoCM1bp4n3kEzO2TYamSI.', 'user', 16000.00, '303cdd76d86c8ee7407eb58dca30fb79.png', 'Elena'),
	(8, 'admin', '$2y$10$KCD0x.WyWC.tCc9LNUBP5ezzcSypgxwhPcdEtGWYUf0BC9Eihduk.', 'admin', 10000.00, '', ''),
	(9, 'gracobjo', '$2y$10$KCD0x.WyWC.tCc9LNUBP5ezzcSypgxwhPcdEtGWYUf0BC9Eihduk.', 'admin', 10000.00, 'd8eb8c58160f13143d4c6ef11c34b57a.png', ''),
	(10, 'laragon', '$2y$10$LrIZEpguveO09QUtQG2OZOuEJ7b7elyu6UHYqxgd.WwVTuPIUG0oe', 'user', 10000.00, '1008957e2da491de260e6a7bee49929c.png', '');
	
--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `expenses`
--
ALTER TABLE `expenses`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_user_expense` (`id_user`),
  ADD KEY `id_category_expense` (`category_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);
  
--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `expenses`
--
ALTER TABLE `expenses`
  MODIFY `id` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;
  
--
-- Constraints for table `expenses`
--
ALTER TABLE `expenses`
  ADD CONSTRAINT `id_category_expense` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`),
  ADD CONSTRAINT `id_user_expense` FOREIGN KEY (`id_user`) REFERENCES `users` (`id`);
COMMIT;  
  

  

  

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
