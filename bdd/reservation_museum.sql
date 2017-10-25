-- phpMyAdmin SQL Dump
-- version 4.5.1
-- http://www.phpmyadmin.net
--
-- Client :  127.0.0.1
-- Généré le :  Mer 27 Septembre 2017 à 11:42
-- Version du serveur :  10.1.16-MariaDB
-- Version de PHP :  7.0.9

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données :  `reservation_museum`
--
-- --------------------------------------------------------

--
-- Table structure for table `billet`
--

CREATE TABLE IF NOT EXISTS `billet` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tarrifs_id` int(11) NOT NULL,
  `code_de_reservation` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `type_billet` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `nom` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `prenom` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `pays` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `date_de_naissance` date NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_1F034AF61BB140B3` (`tarrifs_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=37 ;

--
-- Dumping data for table `billet`
--

INSERT INTO `billet` (`id`, `tarrifs_id`, `code_de_reservation`, `type_billet`, `nom`, `prenom`, `pays`, `date_de_naissance`) VALUES
(24, 2, 'WCk2sOHMtdh2a3yjrM2GEoZON9srR7zN', 'journee', 'Marco', 'DIAZ', 'France', '2013-10-04'),
(25, 1, 'AanvGnbyubl1kq6JkgNOHdb7Upx8fPyx', 'journee', 'Pasquet', 'Thomas', 'France', '1990-07-19'),
(26, 4, 'UPwiU8aUMSlTkOxEIJwBNfvSHqClOzDb', 'journee', 'Donjou', 'Julie', 'France', '1995-10-11'),
(27, 4, 'UOP0wUS51S6Y7sCBWhCCpRNJt13wRlDQ', 'journee', 'Faro', 'Louis', 'France', '1990-06-06'),
(28, 1, 'fH8Ya8YDA67fmbVONccZUwwVdv1U7rvi', 'journee', 'julie', 'desk', 'france', '1995-07-13'),
(29, 1, 'BiJ5UcQ5h6tw00saNi4QuTgiuHZgRYi4', 'journee', 'thomas', 'balgder', 'franece', '1994-06-07'),
(30, 2, 'r8clnvls3MOZwUuwTUUhBdcmmvCfNopN', 'demi-journee', 'Faro', 'Louis', 'France', '2013-10-08'),
(31, 2, '5h1M5MmxKGGz0zxAxjl2YtiGlMhShCV1', 'demi-journee', 'TAULEMESSE', 'Louis', 'France', '2013-10-08'),
(32, 4, 'lo3mSkxm9iBxx0ZrnOpB9SmF4vKpl36S', 'journee', 'Bangalder', 'Thomas', 'France ', '1990-02-14'),
(33, 2, 'JdSWAG7HCD0l9kS1KX3BIXeuufOVdgNY', 'journee', 'Desk', 'Julie', 'France', '2010-06-16'),
(34, 4, 'o7655A8Crxrptl2QqPUDXDVYFvgkIBAo', 'journee', 'Bangalder', 'Thomas', 'France', '1994-07-06'),
(35, 2, 'EYWwsSGzMGJZ89k5wFpqGlAvV7nPCY4X', 'journee', 'Desk', 'Julie', 'France', '2010-03-11'),
(36, 1, 'ANaFJfcPusnuJt80qatCSLzZmNvY2bzl', 'journee', 'Collon', 'Jean', 'France', '1990-03-01');

-- --------------------------------------------------------

--
-- Table structure for table `reservation`
--

CREATE TABLE IF NOT EXISTS `reservation` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `billets_id` int(11) NOT NULL,
  `jour` date NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_42C84955A76ED395` (`user_id`),
  KEY `IDX_42C84955B9EBD317` (`billets_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=37 ;

--
-- Dumping data for table `reservation`
--

INSERT INTO `reservation` (`id`, `user_id`, `billets_id`, `jour`) VALUES
(25, 19, 25, '2017-11-17'),
(26, 19, 26, '2017-11-17'),
(27, 20, 27, '2017-10-18'),
(28, 20, 28, '2017-10-18'),
(29, 20, 29, '2017-10-18'),
(30, 21, 30, '2017-10-18'),
(31, 21, 31, '2017-10-18'),
(32, 22, 32, '2017-10-20'),
(33, 22, 33, '2017-10-20'),
(34, 23, 34, '2017-10-20'),
(35, 23, 35, '2017-10-20'),
(36, 23, 36, '2017-10-20');

-- --------------------------------------------------------

--
-- Table structure for table `statistique`
--

CREATE TABLE IF NOT EXISTS `statistique` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nb_billet_vendu` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `date` date NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=9 ;

--
-- Dumping data for table `statistique`
--

INSERT INTO `statistique` (`id`, `nb_billet_vendu`, `date`) VALUES
(1, '1', '2017-09-21'),
(2, '2', '2017-09-28'),
(3, '1', '2017-10-01'),
(4, '1', '2017-10-13'),
(5, '5', '2017-10-14'),
(6, '2', '2017-11-17'),
(7, '5', '2017-10-18'),
(8, '5', '2017-10-20');

-- --------------------------------------------------------

--
-- Table structure for table `stripe`
--

CREATE TABLE IF NOT EXISTS `stripe` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `num_carte` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `annee` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `date_expiration` date NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `tarrif`
--

CREATE TABLE IF NOT EXISTS `tarrif` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `montant` int(11) NOT NULL,
  `libelle` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=5 ;

--
-- Dumping data for table `tarrif`
--

INSERT INTO `tarrif` (`id`, `montant`, `libelle`) VALUES
(1, 16, 'normal'),
(2, 8, 'enfant'),
(3, 12, 'senior'),
(4, 10, 'réduit');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE IF NOT EXISTS `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nom` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=24 ;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `nom`, `email`) VALUES
(19, 'Pasquet', 'doohfanatic@gmail.com'),
(20, 'Louis Lo Faro', 'louis.lofaro@gmail.com'),
(21, 'Louis LO FARO', 'louis.lofaro@gmail.com'),
(22, 'Bagalder', 'louis.lofaro@gmail.com'),
(23, 'Bangalder', 'louis.lofaro@gmail.com');

--
-- Constraints for dumped tables
--

--
-- Constraints for table `billet`
--
ALTER TABLE `billet`
  ADD CONSTRAINT `FK_1F034AF61BB140B3` FOREIGN KEY (`tarrifs_id`) REFERENCES `tarrif` (`id`);

--
-- Constraints for table `reservation`
--
ALTER TABLE `reservation`
  ADD CONSTRAINT `FK_42C84955A76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`),
  ADD CONSTRAINT `FK_42C84955B9EBD317` FOREIGN KEY (`billets_id`) REFERENCES `billet` (`id`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
