-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : mysql:3306
-- Généré le : dim. 06 avr. 2025 à 09:21
-- Version du serveur : 5.7.44
-- Version de PHP : 8.2.8

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `LaCriee`
--

DROP DATABASE IF EXISTS LaCriee;
CREATE DATABASE IF NOT EXISTS LaCriee CHARACTER SET utf8;
USE LaCriee;

-- --------------------------------------------------------

--
-- Structure de la table `ACHETEUR`
--

CREATE TABLE `ACHETEUR` (
  `idCompte` varchar(30) NOT NULL,
  `raisonSocialeEntreprise` varchar(50) DEFAULT NULL,
  `locRue` varchar(50) DEFAULT NULL,
  `rue` varchar(50) DEFAULT NULL,
  `ville` varchar(50) DEFAULT NULL,
  `codePostal` varchar(50) DEFAULT NULL,
  `numHabilitation` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `ACHETEUR`
--

INSERT INTO `ACHETEUR` (`idCompte`, `raisonSocialeEntreprise`, `locRue`, `rue`, `ville`, `codePostal`, `numHabilitation`) VALUES
('BELLER', 'TLAD', '4', 'rue Schoch', 'STRASBOURG', '67000', '12345'),
('PFISTER', 'TLAD', '4', 'rue Schoch', 'STRASBOURG', '67000', '12345');

-- --------------------------------------------------------

--
-- Structure de la table `ADMIN`
--

CREATE TABLE `ADMIN` (
  `idCompte` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `ADMIN`
--

INSERT INTO `ADMIN` (`idCompte`) VALUES
('GEMINI');

-- --------------------------------------------------------

--
-- Structure de la table `ANNONCE`
--

CREATE TABLE `ANNONCE` (
  `idImage` varchar(255) NOT NULL,
  `idBateau` varchar(50) NOT NULL,
  `datePeche` date NOT NULL,
  `idLot` int(11) NOT NULL,
  `prixEnchere` decimal(15,2) NOT NULL,
  `heureEnchere` time NOT NULL,
  `titreAnnonce` varchar(50) NOT NULL,
  `idCompteV` varchar(30) NOT NULL,
  `idCompteA` varchar(30) DEFAULT NULL,
  `dateDerniereEnchere` datetime DEFAULT NULL,
  `dateFinEnchere` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `ANNONCE`
--

INSERT INTO `ANNONCE` (`idImage`,`idBateau`, `datePeche`, `idLot`, `prixEnchere`, `heureEnchere`, `titreAnnonce`, `idCompteV`, `idCompteA`, `dateDerniereEnchere`, `dateFinEnchere`) VALUES
('imgE/fsh-spin.gif','KORRI', '2025-04-05', 1, 12.34, '10:00:00', 'annonce 1', 'SOARES', 'BELLER', '2025-04-05 10:00:00', '2025-04-05 12:00:00'),
('imgE/fsh-spin.gif','TLAD', '2020-01-01', 2, 56.78, '15:30:00', 'annonce 2', 'CIOBOTARU', 'PFISTER', '2025-04-05 15:30:00', '2025-04-05 17:30:00');

-- --------------------------------------------------------

--
-- Structure de la table `BAC`
--

CREATE TABLE `BAC` (
  `idBac` varchar(50) NOT NULL,
  `tare` decimal(15,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `BAC`
--

INSERT INTO `BAC` (`idBac`, `tare`) VALUES
('B', 2.50),
('F', 4.00);

-- --------------------------------------------------------

--
-- Structure de la table `BATEAU`
--

CREATE TABLE `BATEAU` (
  `idBateau` varchar(50) NOT NULL,
  `immatriculation` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `BATEAU`
--

INSERT INTO `BATEAU` (`idBateau`, `immatriculation`) VALUES
('KORRI', 'AA123456'),
('TLAD', 'SIOSLAM');

-- --------------------------------------------------------

--
-- Structure de la table `COMPTE`
--

CREATE TABLE `COMPTE` (
  `idCompte` varchar(30) NOT NULL,
  `mdpCompte` varchar(255) DEFAULT NULL,
  `typeCompte` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `COMPTE`
--

INSERT INTO `COMPTE` (`idCompte`, `mdpCompte`, `typeCompte`) VALUES
('BELLER', 'bellerthierry', 'acheteur'),
('CIOBOTARU', 'ciobotarualexandru', 'vendeur'),
('GEMINI', 'BIRD', 'admin'),
('PFISTER', 'pfisterludovic', 'acheteur'),
('SOARES', 'soaresdaniels', 'vendeur');

-- --------------------------------------------------------

--
-- Structure de la table `ESPECE`
--

CREATE TABLE `ESPECE` (
  `idEspece` varchar(50) NOT NULL,
  `nomEspece` varchar(50) DEFAULT NULL,
  `nomCommun` varchar(50) DEFAULT NULL,
  `nomScientifique` varchar(200) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `ESPECE`
--

INSERT INTO `ESPECE` (`idEspece`, `nomEspece`, `nomCommun`, `nomScientifique`) VALUES
('LJL', 'LJAUL', 'lieu jaune ligne', 'polliachus polliachus');

-- --------------------------------------------------------

--
-- Structure de la table `FACTURE`
--

CREATE TABLE `FACTURE` (
  `idFacture` varchar(50) NOT NULL,
  `idCompte` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `FACTURE`
--

INSERT INTO `FACTURE` (`idFacture`, `idCompte`) VALUES
('001', 'BELLER'),
('002', 'PFISTER');

-- --------------------------------------------------------

--
-- Structure de la table `LOT`
--

CREATE TABLE `LOT` (
  `idBateau` varchar(50) NOT NULL,
  `datePeche` date NOT NULL,
  `idLot` int(11) NOT NULL,
  `idEspece` varchar(50) NOT NULL,
  `idTaille` int(11) NOT NULL,
  `idPresentation` varchar(50) NOT NULL,
  `idBac` varchar(50) NOT NULL,
  `idCompte` varchar(30) NOT NULL,
  `idQualite` varchar(50) NOT NULL,
  `poidsBrutLot` decimal(15,2) DEFAULT NULL,
  `prixPlancher` decimal(15,2) DEFAULT NULL,
  `prixDepart` decimal(15,2) DEFAULT NULL,
  `prixEncheresMax` decimal(15,2) DEFAULT NULL,
  `dateEnchere` date DEFAULT NULL,
  `heureDebutEnchere` time DEFAULT NULL,
  `codeEtat` varchar(50) DEFAULT NULL,
  `idFacture` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `LOT`
--

INSERT INTO `LOT` (`idBateau`, `datePeche`, `idLot`, `idEspece`, `idTaille`, `idPresentation`, `idBac`, `idCompte`, `idQualite`, `poidsBrutLot`, `prixPlancher`, `prixDepart`, `prixEncheresMax`, `dateEnchere`, `heureDebutEnchere`, `codeEtat`, `idFacture`) VALUES
('KORRI', '2025-04-05', 1, 'LJL', 10, 'ENT', 'B', 'BELLER', 'E', 22.50, 10.00, 11.00, 15.00, '2025-04-05', '09:00:00', 'ok', '001'),
('TLAD', '2020-01-01', 2, 'LJL', 20, 'VID', 'F', 'PFISTER', 'A', 44.00, 50.00, 53.00, 70.00, '2025-04-05', '09:00:00', 'ok', '002');

-- --------------------------------------------------------

--
-- Structure de la table `PECHE`
--

CREATE TABLE `PECHE` (
  `idBateau` varchar(50) NOT NULL,
  `datePeche` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `PECHE`
--

INSERT INTO `PECHE` (`idBateau`, `datePeche`) VALUES
('KORRI', '2025-04-05'),
('TLAD', '2020-01-01');

-- --------------------------------------------------------

--
-- Structure de la table `PRESENTATION`
--

CREATE TABLE `PRESENTATION` (
  `idPresentation` varchar(50) NOT NULL,
  `libelle` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `PRESENTATION`
--

INSERT INTO `PRESENTATION` (`idPresentation`, `libelle`) VALUES
('ENT', 'entier'),
('VID', 'vidé');

-- --------------------------------------------------------

--
-- Structure de la table `QUALITE`
--

CREATE TABLE `QUALITE` (
  `idQualite` varchar(50) NOT NULL,
  `libelle` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `QUALITE`
--

INSERT INTO `QUALITE` (`idQualite`, `libelle`) VALUES
('A', 'glacé'),
('B', 'déclassé'),
('E', 'extra');

-- --------------------------------------------------------

--
-- Structure de la table `TAILLE`
--

CREATE TABLE `TAILLE` (
  `idTaille` int(11) NOT NULL,
  `specification` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `TAILLE`
--

INSERT INTO `TAILLE` (`idTaille`, `specification`) VALUES
(10, 'taille 1'),
(20, 'taille 2'),
(30, 'taille 3'),
(40, 'taille 4'),
(50, 'taille 5');

-- --------------------------------------------------------

--
-- Structure de la table `VENDEUR`
--

CREATE TABLE `VENDEUR` (
  `idCompte` varchar(30) NOT NULL,
  `raisonSocialeEntreprise` varchar(50) DEFAULT NULL,
  `nom` varchar(50) DEFAULT NULL,
  `prenom` varchar(50) DEFAULT NULL,
  `locRue` varchar(50) DEFAULT NULL,
  `rue` varchar(50) DEFAULT NULL,
  `ville` varchar(50) DEFAULT NULL,
  `codePostal` varchar(50) DEFAULT NULL,
  `numHabilitation` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `VENDEUR`
--

INSERT INTO `VENDEUR` (`idCompte`, `raisonSocialeEntreprise`, `nom`, `prenom`, `locRue`, `rue`, `ville`, `codePostal`, `numHabilitation`) VALUES
('CIOBOTARU', 'TLAD', 'Ciobotaru', 'Alexandru', '4', 'rue Schoch', 'STRASBOURG', '67000', '12345'),
('SOARES', 'TLAD', 'Soares', 'Daniels', '4', 'rue Schoch', 'STRASBOURG', '67000', '12345');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `ACHETEUR`
--
ALTER TABLE `ACHETEUR`
  ADD PRIMARY KEY (`idCompte`);

--
-- Index pour la table `ADMIN`
--
ALTER TABLE `ADMIN`
  ADD PRIMARY KEY (`idCompte`);

--
-- Index pour la table `ANNONCE`
--
ALTER TABLE `ANNONCE`
  ADD PRIMARY KEY (`idBateau`,`datePeche`,`idLot`),
  ADD KEY `fk_annonce_vendeur` (`idCompteV`);

--
-- Index pour la table `BAC`
--
ALTER TABLE `BAC`
  ADD PRIMARY KEY (`idBac`);

--
-- Index pour la table `BATEAU`
--
ALTER TABLE `BATEAU`
  ADD PRIMARY KEY (`idBateau`);

--
-- Index pour la table `COMPTE`
--
ALTER TABLE `COMPTE`
  ADD PRIMARY KEY (`idCompte`);

--
-- Index pour la table `ESPECE`
--
ALTER TABLE `ESPECE`
  ADD PRIMARY KEY (`idEspece`);

--
-- Index pour la table `FACTURE`
--
ALTER TABLE `FACTURE`
  ADD PRIMARY KEY (`idFacture`),
  ADD KEY `fk_facture_acheteur` (`idCompte`);

--
-- Index pour la table `LOT`
--
ALTER TABLE `LOT`
  ADD PRIMARY KEY (`idBateau`,`datePeche`,`idLot`),
  ADD KEY `fk_lot_espece` (`idEspece`),
  ADD KEY `fk_lot_taille` (`idTaille`),
  ADD KEY `fk_lot_presentation` (`idPresentation`),
  ADD KEY `fk_lot_bac` (`idBac`),
  ADD KEY `fk_lot_acheteur` (`idCompte`),
  ADD KEY `fk_lot_qualite` (`idQualite`),
  ADD KEY `fk_lot_facture` (`idFacture`);

--
-- Index pour la table `PECHE`
--
ALTER TABLE `PECHE`
  ADD PRIMARY KEY (`idBateau`,`datePeche`);

--
-- Index pour la table `PRESENTATION`
--
ALTER TABLE `PRESENTATION`
  ADD PRIMARY KEY (`idPresentation`);

--
-- Index pour la table `QUALITE`
--
ALTER TABLE `QUALITE`
  ADD PRIMARY KEY (`idQualite`);

--
-- Index pour la table `TAILLE`
--
ALTER TABLE `TAILLE`
  ADD PRIMARY KEY (`idTaille`);

--
-- Index pour la table `VENDEUR`
--
ALTER TABLE `VENDEUR`
  ADD PRIMARY KEY (`idCompte`);

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `ACHETEUR`
--
ALTER TABLE `ACHETEUR`
  ADD CONSTRAINT `fk_acheteur_compte` FOREIGN KEY (`idCompte`) REFERENCES `COMPTE` (`idCompte`);

--
-- Contraintes pour la table `ADMIN`
--
ALTER TABLE `ADMIN`
  ADD CONSTRAINT `fk_admin_compte` FOREIGN KEY (`idCompte`) REFERENCES `COMPTE` (`idCompte`);

--
-- Contraintes pour la table `ANNONCE`
--
ALTER TABLE `ANNONCE`
  ADD CONSTRAINT `fk_annonce_lot` FOREIGN KEY (`idBateau`,`datePeche`,`idLot`) REFERENCES `LOT` (`idBateau`, `datePeche`, `idLot`),
  ADD CONSTRAINT `fk_annonce_vendeur` FOREIGN KEY (`idCompteV`) REFERENCES `VENDEUR` (`idCompte`);

--
-- Contraintes pour la table `FACTURE`
--
ALTER TABLE `FACTURE`
  ADD CONSTRAINT `fk_facture_acheteur` FOREIGN KEY (`idCompte`) REFERENCES `ACHETEUR` (`idCompte`);

--
-- Contraintes pour la table `LOT`
--
ALTER TABLE `LOT`
  ADD CONSTRAINT `fk_lot_acheteur` FOREIGN KEY (`idCompte`) REFERENCES `ACHETEUR` (`idCompte`),
  ADD CONSTRAINT `fk_lot_bac` FOREIGN KEY (`idBac`) REFERENCES `BAC` (`idBac`),
  ADD CONSTRAINT `fk_lot_espece` FOREIGN KEY (`idEspece`) REFERENCES `ESPECE` (`idEspece`),
  ADD CONSTRAINT `fk_lot_facture` FOREIGN KEY (`idFacture`) REFERENCES `FACTURE` (`idFacture`),
  ADD CONSTRAINT `fk_lot_peche` FOREIGN KEY (`idBateau`,`datePeche`) REFERENCES `PECHE` (`idBateau`, `datePeche`),
  ADD CONSTRAINT `fk_lot_presentation` FOREIGN KEY (`idPresentation`) REFERENCES `PRESENTATION` (`idPresentation`),
  ADD CONSTRAINT `fk_lot_qualite` FOREIGN KEY (`idQualite`) REFERENCES `QUALITE` (`idQualite`),
  ADD CONSTRAINT `fk_lot_taille` FOREIGN KEY (`idTaille`) REFERENCES `TAILLE` (`idTaille`);

--
-- Contraintes pour la table `PECHE`
--
ALTER TABLE `PECHE`
  ADD CONSTRAINT `fk_peche_bateau` FOREIGN KEY (`idBateau`) REFERENCES `BATEAU` (`idBateau`);

--
-- Contraintes pour la table `VENDEUR`
--
ALTER TABLE `VENDEUR`
  ADD CONSTRAINT `fk_vendeur_compte` FOREIGN KEY (`idCompte`) REFERENCES `COMPTE` (`idCompte`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;