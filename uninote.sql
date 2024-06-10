-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : ven. 07 juin 2024 à 17:09
-- Version du serveur : 10.4.28-MariaDB
-- Version de PHP : 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `uninote`
--

-- --------------------------------------------------------

--
-- Structure de la table `compte`
--

CREATE TABLE `compte` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nom` varchar(30) DEFAULT NULL,
  `prenom` varchar(30) DEFAULT NULL,
  `password` text DEFAULT NULL,
  `niv_perm` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `compte`
--

INSERT INTO `compte` (`id`, `nom`, `prenom`, `password`, `niv_perm`) VALUES
(1, 'AL SALTI', 'Nadia', '$2y$10$By240eFJbhk4CaeIwptnTukvdES/oA1YmXPhXAk2qXaKIC71mYWFG', 2);

-- --------------------------------------------------------

--
-- Structure de la table `enseignants`
--

CREATE TABLE `enseignants` (
  `id_ens` int(11) NOT NULL AUTO_INCREMENT,
  `num_tel` int(11) DEFAULT NULL,
  `mail` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id_ens`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `enseignants`
--

INSERT INTO `enseignants` (`id_ens`, `num_tel`, `mail`) VALUES
(1, 600000000, 'rien@gmail.com');

-- --------------------------------------------------------

--
-- Structure de la table `etudiant`
--

CREATE TABLE `etudiant` (
  `id_etud` int(11) NOT NULL AUTO_INCREMENT,
  `tp` varchar(1) DEFAULT NULL,
  PRIMARY KEY (`id_etud`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `eval`
--

CREATE TABLE `eval` (
  `id_eval` int(11) NOT NULL AUTO_INCREMENT,
  `id_ressource` int(11) DEFAULT NULL,
  `coeff` float DEFAULT NULL,
  `intitule` varchar(50) DEFAULT NULL,
  `date` datetime DEFAULT NULL,
  PRIMARY KEY (`id_eval`),
  KEY `id_ressource` (`id_ressource`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `eval`
--

INSERT INTO `eval` (`id_eval`, `id_ressource`, `coeff`, `intitule`, `date`) VALUES
(1, 1, 2, 'Compréhension orale', '2024-06-07 17:08:11');

-- --------------------------------------------------------

--
-- Structure de la table `matiereens`
--

CREATE TABLE `matiereens` (
  `id_ressource` int(11) NOT NULL,
  `id_ens` int(11) NOT NULL,
  PRIMARY KEY (`id_ressource`, `id_ens`),
  KEY `id_ens` (`id_ens`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `matiereens`
--

INSERT INTO `matiereens` (`id_ressource`, `id_ens`) VALUES
(1, 1);

-- --------------------------------------------------------

--
-- Structure de la table `notes`
--

CREATE TABLE `notes` (
  `id_eval` int(11) NOT NULL,
  `id_etud` int(11) NOT NULL,
  `note` float DEFAULT NULL,
  PRIMARY KEY (`id_eval`, `id_etud`),
  KEY `id_etud` (`id_etud`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `promotions`
--

CREATE TABLE `promotions` (
  `id_promo` int(11) NOT NULL AUTO_INCREMENT,
  `formation` varchar(50) DEFAULT NULL,
  `annee_forma` int(11) DEFAULT NULL,
  PRIMARY KEY (`id_promo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `promotions`
--

INSERT INTO `promotions` (`id_promo`, `formation`, `annee_forma`) VALUES
(1, 'BUT MMI 1ere Année', 1);

-- --------------------------------------------------------

--
-- Structure de la table `ressource`
--

CREATE TABLE `ressource` (
  `id_ressource` int(11) NOT NULL AUTO_INCREMENT,
  `intitule` varchar(50) DEFAULT NULL,
  `ue` int(11) DEFAULT NULL,
  PRIMARY KEY (`id_ressource`),
  KEY `ue` (`ue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `ressource`
--

INSERT INTO `ressource` (`id_ressource`, `intitule`, `ue`) VALUES
(1, 'R101 - Anglais', 1);

-- --------------------------------------------------------

--
-- Structure de la table `tp`
--

CREATE TABLE `tp` (
  `id_tp` int(11) NOT NULL AUTO_INCREMENT,
  `libelle` varchar(30) DEFAULT NULL,
  `promotion` int(11) DEFAULT NULL,
  PRIMARY KEY (`id_tp`),
  KEY `promotion` (`promotion`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `ue`
--

CREATE TABLE `ue` (
  `id_ue` int(11) NOT NULL AUTO_INCREMENT,
  `intitule` varchar(50) DEFAULT NULL,
  `id_promo` int(11) DEFAULT NULL,
  PRIMARY KEY (`id_ue`),
  KEY `id_promo` (`id_promo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `ue`
--

INSERT INTO `ue` (`id_ue`, `intitule`, `id_promo`) VALUES
(1, 'Comprendre', 1);

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `eval`
--
ALTER TABLE `eval`
  ADD CONSTRAINT `eval_ibfk_1` FOREIGN KEY (`id_ressource`) REFERENCES `ressource` (`id_ressource`);

--
-- Contraintes pour la table `matiereens`
--
ALTER TABLE `matiereens`
  ADD CONSTRAINT `matiereens_ibfk_1` FOREIGN KEY (`id_ressource`) REFERENCES `ressource` (`id_ressource`),
  ADD CONSTRAINT `matiereens_ibfk_2` FOREIGN KEY (`id_ens`) REFERENCES `enseignants` (`id_ens`);

--
-- Contraintes pour la table `notes`
--
ALTER TABLE `notes`
  ADD CONSTRAINT `notes_ibfk_1` FOREIGN KEY (`id_eval`) REFERENCES `eval` (`id_eval`),
  ADD CONSTRAINT `notes_ibfk_2` FOREIGN KEY (`id_etud`) REFERENCES `etudiant` (`id_etud`);

--
-- Contraintes pour la table `ressource`
--
ALTER TABLE `ressource`
  ADD CONSTRAINT `ressource_ibfk_1` FOREIGN KEY (`ue`) REFERENCES `ue` (`id_ue`);

--
-- Contraintes pour la table `tp`
--
ALTER TABLE `tp`
  ADD CONSTRAINT `tp_ibfk_1` FOREIGN KEY (`promotion`) REFERENCES `promotions` (`id_promo`);

--
-- Contraintes pour la table `ue`
--
ALTER TABLE `ue`
  ADD CONSTRAINT `ue_ibfk_1` FOREIGN KEY (`id_promo`) REFERENCES `promotions` (`id_promo`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
