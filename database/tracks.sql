-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : jeu. 08 oct. 2026 à 19:01
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
-- Base de données : `tracks`
--

-- --------------------------------------------------------

--
-- Structure de la table `anneeacademique2`
--

CREATE TABLE `anneeacademique2` (
  `ID2` int(10) NOT NULL,
  `Id` int(10) NOT NULL,
  `Libelle` varchar(255) DEFAULT NULL,
  `EstActive` bit(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `campus2`
--

CREATE TABLE `campus2` (
  `ID2` int(10) NOT NULL,
  `Id` int(10) NOT NULL,
  `Nom` varchar(255) DEFAULT NULL,
  `Localisation` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `chapitre2`
--

CREATE TABLE `chapitre2` (
  `ID2` int(10) NOT NULL,
  `UE2ID2` int(10) NOT NULL,
  `Id` int(10) NOT NULL,
  `Titre` varchar(255) DEFAULT NULL,
  `Ordre` int(10) NOT NULL,
  `EstTraite` bit(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `convocation2`
--

CREATE TABLE `convocation2` (
  `ID2` int(10) NOT NULL,
  `Utilisateur2ID22` int(10) NOT NULL,
  `Utilisateur2ID2` int(10) NOT NULL,
  `Id` int(10) NOT NULL,
  `Motif` varchar(255) DEFAULT NULL,
  `DateEmission` date DEFAULT NULL,
  `DateRendezVous` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `filiere2`
--

CREATE TABLE `filiere2` (
  `ID2` int(10) NOT NULL,
  `Semestre2ID2` int(10) NOT NULL,
  `Campus2ID2` int(10) NOT NULL,
  `Id` int(10) NOT NULL,
  `Nom` varchar(255) DEFAULT NULL,
  `SeuilAbsences` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `justification2`
--

CREATE TABLE `justification2` (
  `ID2` int(10) NOT NULL,
  `Presence2ID2` int(10) NOT NULL,
  `Id` int(10) NOT NULL,
  `Motif` varchar(255) DEFAULT NULL,
  `PieceJointePath` varchar(255) DEFAULT NULL,
  `EstValide` bit(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `logsaudit2`
--

CREATE TABLE `logsaudit2` (
  `ID2` int(10) NOT NULL,
  `Utilisateur2ID2` int(10) NOT NULL,
  `Id` int(10) NOT NULL,
  `Timestamp` date DEFAULT NULL,
  `Action` varchar(255) DEFAULT NULL,
  `Description` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `presence2`
--

CREATE TABLE `presence2` (
  `ID2` int(10) NOT NULL,
  `Utilisateur2ID2` int(10) NOT NULL,
  `Seance2ID2` int(10) NOT NULL,
  `Id` int(10) NOT NULL,
  `Statut` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `salle2`
--

CREATE TABLE `salle2` (
  `ID2` int(10) NOT NULL,
  `Campus2ID2` int(10) NOT NULL,
  `Id` int(10) NOT NULL,
  `Nom` varchar(255) DEFAULT NULL,
  `Capacite` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `seance2`
--

CREATE TABLE `seance2` (
  `ID2` int(10) NOT NULL,
  `Salle2ID2` int(10) NOT NULL,
  `UE2ID2` int(10) NOT NULL,
  `Id` int(10) NOT NULL,
  `DateSeance` date DEFAULT NULL,
  `HeureDebut` time DEFAULT NULL,
  `HeureFin` time DEFAULT NULL,
  `Type` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `semestre2`
--

CREATE TABLE `semestre2` (
  `ID2` int(10) NOT NULL,
  `AnneeAcademique2ID2` int(10) NOT NULL,
  `Id` int(10) NOT NULL,
  `Code` varchar(255) DEFAULT NULL,
  `DateDebut` date DEFAULT NULL,
  `DateFin` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `systracks2`
--

CREATE TABLE `systracks2` (
  `ID` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `ue2`
--

CREATE TABLE `ue2` (
  `ID2` int(10) NOT NULL,
  `Utilisateur2ID2` int(10) NOT NULL,
  `Filiere2ID2` int(10) NOT NULL,
  `Id` int(10) NOT NULL,
  `Code` varchar(255) DEFAULT NULL,
  `Intitule` varchar(255) DEFAULT NULL,
  `VolumeHorairePrevu` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `utilisateur2`
--

CREATE TABLE `utilisateur2` (
  `ID2` int(10) NOT NULL,
  `Filiere2ID2` int(10) DEFAULT NULL,
  `Id` int(10) NOT NULL,
  `Nom` varchar(255) DEFAULT NULL,
  `Prenom` varchar(255) DEFAULT NULL,
  `Email` varchar(255) DEFAULT NULL,
  `MotDePasse` varchar(255) DEFAULT NULL,
  `Role` varchar(255) DEFAULT NULL,
  `EstActif` bit(1) NOT NULL,
  `Matricule` varchar(255) DEFAULT NULL,
  `Specialite` varchar(255) DEFAULT NULL,
  `Discriminator` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `anneeacademique2`
--
ALTER TABLE `anneeacademique2`
  ADD PRIMARY KEY (`ID2`);

--
-- Index pour la table `campus2`
--
ALTER TABLE `campus2`
  ADD PRIMARY KEY (`ID2`);

--
-- Index pour la table `chapitre2`
--
ALTER TABLE `chapitre2`
  ADD PRIMARY KEY (`ID2`),
  ADD KEY `structure` (`UE2ID2`);

--
-- Index pour la table `convocation2`
--
ALTER TABLE `convocation2`
  ADD PRIMARY KEY (`ID2`),
  ADD KEY `emet` (`Utilisateur2ID2`),
  ADD KEY `recoit` (`Utilisateur2ID22`);

--
-- Index pour la table `filiere2`
--
ALTER TABLE `filiere2`
  ADD PRIMARY KEY (`ID2`),
  ADD KEY `heberge` (`Campus2ID2`),
  ADD KEY `planifie` (`Semestre2ID2`);

--
-- Index pour la table `justification2`
--
ALTER TABLE `justification2`
  ADD PRIMARY KEY (`ID2`),
  ADD KEY `justifie` (`Presence2ID2`);

--
-- Index pour la table `logsaudit2`
--
ALTER TABLE `logsaudit2`
  ADD PRIMARY KEY (`ID2`),
  ADD KEY `consulte` (`Utilisateur2ID2`);

--
-- Index pour la table `presence2`
--
ALTER TABLE `presence2`
  ADD PRIMARY KEY (`ID2`),
  ADD KEY `controle` (`Seance2ID2`),
  ADD KEY `pointe` (`Utilisateur2ID2`);

--
-- Index pour la table `salle2`
--
ALTER TABLE `salle2`
  ADD PRIMARY KEY (`ID2`),
  ADD KEY `appartient` (`Campus2ID2`);

--
-- Index pour la table `seance2`
--
ALTER TABLE `seance2`
  ADD PRIMARY KEY (`ID2`),
  ADD KEY `deroule` (`UE2ID2`),
  ADD KEY `occupe` (`Salle2ID2`);

--
-- Index pour la table `semestre2`
--
ALTER TABLE `semestre2`
  ADD PRIMARY KEY (`ID2`),
  ADD KEY `compose` (`AnneeAcademique2ID2`);

--
-- Index pour la table `systracks2`
--
ALTER TABLE `systracks2`
  ADD PRIMARY KEY (`ID`);

--
-- Index pour la table `ue2`
--
ALTER TABLE `ue2`
  ADD PRIMARY KEY (`ID2`),
  ADD KEY `contient` (`Filiere2ID2`),
  ADD KEY `enseigne` (`Utilisateur2ID2`);

--
-- Index pour la table `utilisateur2`
--
ALTER TABLE `utilisateur2`
  ADD PRIMARY KEY (`ID2`),
  ADD KEY `inscrit` (`Filiere2ID2`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `anneeacademique2`
--
ALTER TABLE `anneeacademique2`
  MODIFY `ID2` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `campus2`
--
ALTER TABLE `campus2`
  MODIFY `ID2` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `chapitre2`
--
ALTER TABLE `chapitre2`
  MODIFY `ID2` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `convocation2`
--
ALTER TABLE `convocation2`
  MODIFY `ID2` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `filiere2`
--
ALTER TABLE `filiere2`
  MODIFY `ID2` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `justification2`
--
ALTER TABLE `justification2`
  MODIFY `ID2` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `logsaudit2`
--
ALTER TABLE `logsaudit2`
  MODIFY `ID2` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `presence2`
--
ALTER TABLE `presence2`
  MODIFY `ID2` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `salle2`
--
ALTER TABLE `salle2`
  MODIFY `ID2` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `seance2`
--
ALTER TABLE `seance2`
  MODIFY `ID2` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `semestre2`
--
ALTER TABLE `semestre2`
  MODIFY `ID2` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `systracks2`
--
ALTER TABLE `systracks2`
  MODIFY `ID` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `ue2`
--
ALTER TABLE `ue2`
  MODIFY `ID2` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `utilisateur2`
--
ALTER TABLE `utilisateur2`
  MODIFY `ID2` int(10) NOT NULL AUTO_INCREMENT;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `chapitre2`
--
ALTER TABLE `chapitre2`
  ADD CONSTRAINT `structure` FOREIGN KEY (`UE2ID2`) REFERENCES `ue2` (`ID2`);

--
-- Contraintes pour la table `convocation2`
--
ALTER TABLE `convocation2`
  ADD CONSTRAINT `emet` FOREIGN KEY (`Utilisateur2ID2`) REFERENCES `utilisateur2` (`ID2`),
  ADD CONSTRAINT `recoit` FOREIGN KEY (`Utilisateur2ID22`) REFERENCES `utilisateur2` (`ID2`);

--
-- Contraintes pour la table `filiere2`
--
ALTER TABLE `filiere2`
  ADD CONSTRAINT `heberge` FOREIGN KEY (`Campus2ID2`) REFERENCES `campus2` (`ID2`),
  ADD CONSTRAINT `planifie` FOREIGN KEY (`Semestre2ID2`) REFERENCES `semestre2` (`ID2`);

--
-- Contraintes pour la table `justification2`
--
ALTER TABLE `justification2`
  ADD CONSTRAINT `justifie` FOREIGN KEY (`Presence2ID2`) REFERENCES `presence2` (`ID2`);

--
-- Contraintes pour la table `logsaudit2`
--
ALTER TABLE `logsaudit2`
  ADD CONSTRAINT `consulte` FOREIGN KEY (`Utilisateur2ID2`) REFERENCES `utilisateur2` (`ID2`);

--
-- Contraintes pour la table `presence2`
--
ALTER TABLE `presence2`
  ADD CONSTRAINT `controle` FOREIGN KEY (`Seance2ID2`) REFERENCES `seance2` (`ID2`),
  ADD CONSTRAINT `pointe` FOREIGN KEY (`Utilisateur2ID2`) REFERENCES `utilisateur2` (`ID2`);

--
-- Contraintes pour la table `salle2`
--
ALTER TABLE `salle2`
  ADD CONSTRAINT `appartient` FOREIGN KEY (`Campus2ID2`) REFERENCES `campus2` (`ID2`);

--
-- Contraintes pour la table `seance2`
--
ALTER TABLE `seance2`
  ADD CONSTRAINT `deroule` FOREIGN KEY (`UE2ID2`) REFERENCES `ue2` (`ID2`),
  ADD CONSTRAINT `occupe` FOREIGN KEY (`Salle2ID2`) REFERENCES `salle2` (`ID2`);

--
-- Contraintes pour la table `semestre2`
--
ALTER TABLE `semestre2`
  ADD CONSTRAINT `compose` FOREIGN KEY (`AnneeAcademique2ID2`) REFERENCES `anneeacademique2` (`ID2`);

--
-- Contraintes pour la table `ue2`
--
ALTER TABLE `ue2`
  ADD CONSTRAINT `contient` FOREIGN KEY (`Filiere2ID2`) REFERENCES `filiere2` (`ID2`),
  ADD CONSTRAINT `enseigne` FOREIGN KEY (`Utilisateur2ID2`) REFERENCES `utilisateur2` (`ID2`);

--
-- Contraintes pour la table `utilisateur2`
--
ALTER TABLE `utilisateur2`
  ADD CONSTRAINT `inscrit` FOREIGN KEY (`Filiere2ID2`) REFERENCES `filiere2` (`ID2`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
