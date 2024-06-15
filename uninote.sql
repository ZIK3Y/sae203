-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : sam. 15 juin 2024 à 21:11
-- Version du serveur : 10.4.32-MariaDB
-- Version de PHP : 8.2.12

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
  `id` int(11) NOT NULL,
  `nom` varchar(30) DEFAULT NULL,
  `prenom` varchar(30) DEFAULT NULL,
  `password` text DEFAULT NULL,
  `niv_perm` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `compte`
--

INSERT INTO `compte` (`id`, `nom`, `prenom`, `password`, `niv_perm`) VALUES
(1, 'AL SALTI', 'Nadia', '$2y$10$By240eFJbhk4CaeIwptnTukvdES/oA1YmXPhXAk2qXaKIC71mYWFG', 2),
(2, 'ROURE', 'Vincent', '$2y$10$5J5WhEWu0aOgh.jCcZZd0.uEy.2yoAT/2erlLOrGpVpm9ntbxyZOG', 1),
(3, 'ZAIDI', 'Fares', '$2y$10$FxocsQtL5g.rM30CXDjvGemDhvCZGYz2MYNVSLPQR9yVLJC0bYQMC', 3),
(4, 'CHEURFA', 'Liam', '$2y$10$ffLT72Kwt7UmybSgEA3Dle/PL91UeHishQCBeJGC5FPncJLLDMHnm', 1);

-- --------------------------------------------------------

--
-- Structure de la table `enseignants`
--

CREATE TABLE `enseignants` (
  `id_ens` int(11) NOT NULL,
  `num_tel` int(11) DEFAULT NULL,
  `mail` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `enseignants`
--

INSERT INTO `enseignants` (`id_ens`, `num_tel`, `mail`) VALUES
(1, 600000000, 'rien@gmail.com'),
(2, 0, 'adefinir@adressemail.com'),
(3, 0, 'adefinir@adressemail.com'),
(4, 0, 'adefinir@adressemail.com');

-- --------------------------------------------------------

--
-- Structure de la table `etudiant`
--

CREATE TABLE `etudiant` (
  `id_etud` int(11) NOT NULL,
  `promo` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `etudiant`
--

INSERT INTO `etudiant` (`id_etud`, `promo`) VALUES
(2, 1),
(4, 1);

-- --------------------------------------------------------

--
-- Structure de la table `eval`
--

CREATE TABLE `eval` (
  `id_eval` int(11) NOT NULL,
  `id_ressource` int(11) DEFAULT NULL,
  `coeff` float DEFAULT NULL,
  `intitule` varchar(50) DEFAULT NULL,
  `date` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `eval`
--

INSERT INTO `eval` (`id_eval`, `id_ressource`, `coeff`, `intitule`, `date`) VALUES
(1, 1, 2, 'Compréhension orale', '2024-06-07 17:08:11'),
(3, 1, 2, 'Oral', '2024-06-09 22:56:33'),
(6, NULL, NULL, NULL, '2024-06-14 18:46:51'),
(7, 1, 2, 'Compréhension écrite', '2024-06-14 20:01:59'),
(8, 1, 3, 'Evaluation', '2024-06-14 20:02:23'),
(9, NULL, NULL, NULL, '2024-06-14 20:03:38'),
(10, NULL, NULL, NULL, '2024-06-14 20:05:42'),
(11, 3, 2, 'Javascript', '2024-06-15 18:34:26'),
(12, 4, 4, 'Table de jardin', '2024-06-15 18:34:43'),
(13, 5, 3, 'PHP', '2024-06-15 18:34:58'),
(14, 6, 2, 'Displate', '2024-06-15 18:35:10'),
(15, 7, 2, 'Verbes', '2024-06-15 18:35:23'),
(16, 7, 2, 'DST 1', '2024-06-15 21:06:46');

-- --------------------------------------------------------

--
-- Structure de la table `matiereens`
--

CREATE TABLE `matiereens` (
  `id_ressource` int(11) NOT NULL,
  `id_ens` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `matiereens`
--

INSERT INTO `matiereens` (`id_ressource`, `id_ens`) VALUES
(1, 1),
(2, 1),
(3, 1),
(4, 1),
(5, 1),
(6, 1),
(7, 1);

-- --------------------------------------------------------

--
-- Structure de la table `notes`
--

CREATE TABLE `notes` (
  `id_eval` int(11) NOT NULL,
  `id_etud` int(11) NOT NULL,
  `note` float DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `notes`
--

INSERT INTO `notes` (`id_eval`, `id_etud`, `note`) VALUES
(1, 2, 17),
(1, 4, 12);

-- --------------------------------------------------------

--
-- Structure de la table `promotions`
--

CREATE TABLE `promotions` (
  `id_promo` int(11) NOT NULL,
  `formation` varchar(50) DEFAULT NULL,
  `annee_forma` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `promotions`
--

INSERT INTO `promotions` (`id_promo`, `formation`, `annee_forma`) VALUES
(1, 'BUT MMI 1ere Année', 1),
(2, 'BUT GEA 1ere année', 1);

-- --------------------------------------------------------

--
-- Structure de la table `ressource`
--

CREATE TABLE `ressource` (
  `id_ressource` int(11) NOT NULL,
  `intitule` varchar(50) DEFAULT NULL,
  `ue` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `ressource`
--

INSERT INTO `ressource` (`id_ressource`, `intitule`, `ue`) VALUES
(1, 'R101 - Anglais', 1),
(2, 'Comptabilité', 2),
(3, 'Intégration', 1),
(4, 'Production 3D', 3),
(5, 'Développement Web', 4),
(6, 'Stratégie Marketing', 5),
(7, 'Expression communication et réthorique', 6);

-- --------------------------------------------------------

--
-- Structure de la table `ue`
--

CREATE TABLE `ue` (
  `id_ue` int(11) NOT NULL,
  `intitule` varchar(50) DEFAULT NULL,
  `id_promo` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `ue`
--

INSERT INTO `ue` (`id_ue`, `intitule`, `id_promo`) VALUES
(1, 'Comprendre', 1),
(2, 'Développer', 2),
(3, 'Concevoir', 1),
(4, 'Développer', 1),
(5, 'Entreprendre', 1),
(6, 'Exprimer', 1);

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `compte`
--
ALTER TABLE `compte`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `enseignants`
--
ALTER TABLE `enseignants`
  ADD PRIMARY KEY (`id_ens`);

--
-- Index pour la table `etudiant`
--
ALTER TABLE `etudiant`
  ADD PRIMARY KEY (`id_etud`),
  ADD KEY `fk_etudiant_promotions` (`promo`);

--
-- Index pour la table `eval`
--
ALTER TABLE `eval`
  ADD PRIMARY KEY (`id_eval`),
  ADD KEY `id_ressource` (`id_ressource`);

--
-- Index pour la table `matiereens`
--
ALTER TABLE `matiereens`
  ADD PRIMARY KEY (`id_ressource`,`id_ens`),
  ADD KEY `id_ens` (`id_ens`);

--
-- Index pour la table `notes`
--
ALTER TABLE `notes`
  ADD PRIMARY KEY (`id_eval`,`id_etud`),
  ADD KEY `id_etud` (`id_etud`);

--
-- Index pour la table `promotions`
--
ALTER TABLE `promotions`
  ADD PRIMARY KEY (`id_promo`);

--
-- Index pour la table `ressource`
--
ALTER TABLE `ressource`
  ADD PRIMARY KEY (`id_ressource`),
  ADD KEY `ue` (`ue`);

--
-- Index pour la table `ue`
--
ALTER TABLE `ue`
  ADD PRIMARY KEY (`id_ue`),
  ADD KEY `id_promo` (`id_promo`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `compte`
--
ALTER TABLE `compte`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT pour la table `enseignants`
--
ALTER TABLE `enseignants`
  MODIFY `id_ens` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT pour la table `etudiant`
--
ALTER TABLE `etudiant`
  MODIFY `id_etud` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT pour la table `eval`
--
ALTER TABLE `eval`
  MODIFY `id_eval` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT pour la table `promotions`
--
ALTER TABLE `promotions`
  MODIFY `id_promo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT pour la table `ressource`
--
ALTER TABLE `ressource`
  MODIFY `id_ressource` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT pour la table `ue`
--
ALTER TABLE `ue`
  MODIFY `id_ue` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `etudiant`
--
ALTER TABLE `etudiant`
  ADD CONSTRAINT `fk_etudiant_promotions` FOREIGN KEY (`promo`) REFERENCES `promotions` (`id_promo`);

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
-- Contraintes pour la table `ue`
--
ALTER TABLE `ue`
  ADD CONSTRAINT `ue_ibfk_1` FOREIGN KEY (`id_promo`) REFERENCES `promotions` (`id_promo`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
