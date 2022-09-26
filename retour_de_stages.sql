-- phpMyAdmin SQL Dump
-- version 4.9.5deb2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Sep 26, 2022 at 10:42 AM
-- Server version: 8.0.30-0ubuntu0.20.04.2
-- PHP Version: 7.4.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `dpp`
--

-- --------------------------------------------------------

--
-- Table structure for table `retour_de_stages`
--

DROP TABLE IF EXISTS `retour_de_stages`;
CREATE TABLE `retour_de_stages` (
  `id` int NOT NULL,
  `id_agent` int NOT NULL,
  `numero_decision_rs` varchar(255) NOT NULL,
  `date_signature` varchar(255) NOT NULL,
  `date_fin_formation` varchar(255) NOT NULL,
  `date_reprise_service` varchar(255) NOT NULL,
  `categorie_rs` varchar(255) NOT NULL,
  `annee_rs` varchar(255) NOT NULL,
  `incidence_bn` varchar(255) NOT NULL,
  `structure_rs` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `retour_de_stages`
--
ALTER TABLE `retour_de_stages`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `retour_de_stages`
--
ALTER TABLE `retour_de_stages`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;
  
  --
-- Constraints for dumped tables
--

--
-- Constraints for table `retour_de_stages`
--
ALTER TABLE `mise_en_stages`
  ADD CONSTRAINT `retour_de_stages_id_agent_foreign` FOREIGN KEY (`id_agent`) REFERENCES `agent_formations` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
