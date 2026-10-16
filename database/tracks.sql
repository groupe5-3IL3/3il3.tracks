-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : ven. 16 oct. 2026 à 06:47
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
CREATE DATABASE IF NOT EXISTS `tracks` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `tracks`;

-- --------------------------------------------------------

--
-- Structure de la table `anneeacademique`
--

CREATE TABLE `anneeacademique` (
  `ID` int(10) NOT NULL,
  `Id_acad` int(10) NOT NULL,
  `Libelle_acad` varchar(255) DEFAULT NULL,
  `EstActive_acad` bit(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `campus`
--

CREATE TABLE `campus` (
  `ID` int(10) NOT NULL,
  `Id_campus` int(10) NOT NULL,
  `Nom_campus` varchar(255) DEFAULT NULL,
  `Localisation_campus` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `chapitre`
--

CREATE TABLE `chapitre` (
  `ID` int(10) NOT NULL,
  `UEID` int(10) NOT NULL,
  `Id_chap` int(10) NOT NULL,
  `Titre` varchar(255) DEFAULT NULL,
  `Ordre` int(10) NOT NULL,
  `EstTraite` bit(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `convocation`
--

CREATE TABLE `convocation` (
  `ID` int(10) NOT NULL,
  `UtilisateurID2` int(10) NOT NULL,
  `UtilisateurID` int(10) NOT NULL,
  `Id_con` int(10) NOT NULL,
  `Motif_con` varchar(255) DEFAULT NULL,
  `DateEmission_con` date DEFAULT NULL,
  `DateRendezVous_con` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `filiere`
--

CREATE TABLE `filiere` (
  `ID` int(10) NOT NULL,
  `SemestreID` int(10) NOT NULL,
  `CampusID` int(10) NOT NULL,
  `Id_filiere` int(10) NOT NULL,
  `Nom_filiere` varchar(255) DEFAULT NULL,
  `SeuilAbsences_filiere` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `justification`
--

CREATE TABLE `justification` (
  `ID` int(10) NOT NULL,
  `PresenceID` int(10) NOT NULL,
  `Id_jus` int(10) NOT NULL,
  `Motif_jus` varchar(255) DEFAULT NULL,
  `PieceJointePath_jus` varchar(255) DEFAULT NULL,
  `EstValide_jus` bit(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `logsaudit`
--

CREATE TABLE `logsaudit` (
  `ID` int(10) NOT NULL,
  `UtilisateurID` int(10) NOT NULL,
  `Id_log` int(10) NOT NULL,
  `Timestamp_log` date DEFAULT NULL,
  `Action_log` varchar(255) DEFAULT NULL,
  `Description_log` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déclencheurs `justification`
--
CREATE TRIGGER `after_justification_insert` AFTER INSERT ON `justification` FOR EACH ROW
    INSERT INTO logsaudit (UtilisateurID, Id_log, Timestamp_log, Action_log, Description_log)
    VALUES (1, 6, CURDATE(), 'INSERT', CONCAT('Nouveau justificatif d''absence téléversé pour le pointage de présence ID : ', NEW.PresenceID));

CREATE TRIGGER `after_justification_update` AFTER UPDATE ON `justification` FOR EACH ROW
    INSERT INTO logsaudit (UtilisateurID, Id_log, Timestamp_log, Action_log, Description_log)
    VALUES (1, 7, CURDATE(), 'UPDATE', CONCAT('Statut du justificatif ID ', NEW.ID, ' mis à jour (Validé = ', NEW.EstValide_jus, ')'));

--
-- Déchargement des données de la table `logsaudit`
--

INSERT INTO `logsaudit` (`ID`, `UtilisateurID`, `Id_log`, `Timestamp_log`, `Action_log`, `Description_log`) VALUES
(1, 1, 2, '2026-10-15', 'UPDATE', 'Modification de l''utilisateur ID 1 (Changement d''email ou mise à jour de sécurité de mot de passe)'),
(2, 2, 1, '2026-10-15', 'INSERT', 'Inscription de l''utilisateur : TCHOUTA Alain Serge (Rôle : chef_departement)'),
(3, 2, 2, '2026-10-15', 'UPDATE', 'Modification de l''utilisateur ID 2 (Changement d''email ou mise à jour de sécurité de mot de passe)');

-- --------------------------------------------------------

--
-- Structure de la table `parametre`
--

CREATE TABLE `parametre` (
  `ID` int(10) NOT NULL,
  `UtilisateurID` int(10) NOT NULL,
  `AnneeAcademique_set` varchar(255) DEFAULT '2026-2027',
  `RenduAppel_set` varchar(255) DEFAULT 'liste',
  `FormatExport_set` varchar(255) DEFAULT 'pdf',
  `NotifyAbsence_set` bit(1) NOT NULL DEFAULT b'1',
  `NotifySyllabus_set` bit(1) NOT NULL DEFAULT b'0',
  `NotifyJustif_set` bit(1) NOT NULL DEFAULT b'1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `parametre`
--

INSERT INTO `parametre` (`ID`, `UtilisateurID`, `AnneeAcademique_set`, `RenduAppel_set`, `FormatExport_set`, `NotifyAbsence_set`, `NotifySyllabus_set`, `NotifyJustif_set`) VALUES
(1, 1, '2026-2027', 'trombi', 'pdf', b'1', b'0', b'1'),
(22, 2, '2026-2027', 'liste', 'pdf', b'1', b'0', b'1');

-- --------------------------------------------------------

--
-- Structure de la table `presence`
--

CREATE TABLE `presence` (
  `ID` int(10) NOT NULL,
  `UtilisateurID` int(10) NOT NULL,
  `SeanceID` int(10) NOT NULL,
  `Id_pre` int(10) NOT NULL,
  `Statut_pre` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déclencheurs `presence`
--
CREATE TRIGGER `after_presence_insert` AFTER INSERT ON `presence` FOR EACH ROW
    INSERT INTO logsaudit (UtilisateurID, Id_log, Timestamp_log, Action_log, Description_log)
    VALUES (NEW.UtilisateurID, 4, CURDATE(), 'INSERT', CONCAT('Marquage initial de présence sur la séance ID ', NEW.SeanceID, ' - Statut : ', NEW.Statut_pre));

CREATE TRIGGER `after_presence_update` AFTER UPDATE ON `presence` FOR EACH ROW
    INSERT INTO logsaudit (UtilisateurID, Id_log, Timestamp_log, Action_log, Description_log)
    VALUES (NEW.UtilisateurID, 5, CURDATE(), 'UPDATE', CONCAT('Modification de présence pour l''étudiant ID ', NEW.UtilisateurID, ' (Ancien statut: ', OLD.Statut_pre, ' -> Nouveau statut: ', NEW.Statut_pre, ')'));

-- --------------------------------------------------------

--
-- Structure de la table `salle`
--

CREATE TABLE `salle` (
  `ID` int(10) NOT NULL,
  `CampusID` int(10) NOT NULL,
  `Id_salle` int(10) NOT NULL,
  `Nom_salle` varchar(255) DEFAULT NULL,
  `Capacite_salle` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `seance`
--

CREATE TABLE `seance` (
  `ID` int(10) NOT NULL,
  `SalleID` int(10) NOT NULL,
  `UEID` int(10) NOT NULL,
  `Id_sea` int(10) NOT NULL,
  `DateSeance_sea` date DEFAULT NULL,
  `HeureDebut_sea` time DEFAULT NULL,
  `HeureFin_sea` time DEFAULT NULL,
  `Type_sea` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `semestre`
--

CREATE TABLE `semestre` (
  `ID` int(10) NOT NULL,
  `AnneeAcademiqueID` int(10) NOT NULL,
  `Id_sem` int(10) NOT NULL,
  `Code_sem` varchar(255) DEFAULT NULL,
  `DateDebut_sem` date DEFAULT NULL,
  `DateFin_sem` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `systracks`
--

CREATE TABLE `systracks` (
  `ID` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `ue`
--

CREATE TABLE `ue` (
  `ID` int(10) NOT NULL,
  `UtilisateurID` int(10) NOT NULL,
  `FiliereID` int(10) NOT NULL,
  `Id_ue` int(10) NOT NULL,
  `Code_ue` varchar(255) DEFAULT NULL,
  `Intitule_ue` varchar(255) DEFAULT NULL,
  `VolumeHorairePrevu_ue` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `utilisateur`
--

CREATE TABLE `utilisateur` (
  `ID` int(10) NOT NULL,
  `FiliereID` int(10) DEFAULT NULL,
  `Id_user` int(10) NOT NULL,
  `Nom_user` varchar(255) DEFAULT NULL,
  `Prenom_user` varchar(255) DEFAULT NULL,
  `Email_user` varchar(255) DEFAULT NULL,
  `MotDePasse_user` varchar(255) DEFAULT NULL,
  `Role_user` varchar(255) DEFAULT NULL,
  `EstActif_user` bit(1) NOT NULL,
  `Matricule_etu` varchar(255) DEFAULT NULL,
  `Specialite_ens` varchar(255) DEFAULT NULL,
  `Discriminator` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `utilisateur`
--

INSERT INTO `utilisateur` (`ID`, `FiliereID`, `Id_user`, `Nom_user`, `Prenom_user`, `Email_user`, `MotDePasse_user`, `Role_user`, `EstActif_user`, `Matricule_etu`, `Specialite_ens`, `Discriminator`) VALUES
(1, NULL, 1, 'ADMIN', 'Scolarite', 'admin.tracks@etablissement.fr', '$2y$10$K6TaRN1OZAFyqj2.WaecUO7.8l9jr6KwnDXRukeygKsXTkJyzEeqW', 'scolarite', b'1', 'ADM202601', NULL, 'Utilisateur'),
(2, NULL, 2, 'TCHOUTA', 'Alain Serge', 'jean.eboa@etablissement.fr', '$2y$10$K6TaRN1OZAFyqj2.WaecUO7.8l9jr6KwnDXRukeygKsXTkJyzEeqW', 'chef_departement', b'1', NULL, 'Modelisation/Programmation systeme', 'Utilisateur');

--
-- Déclencheurs `utilisateur`
--
CREATE TRIGGER `after_utilisateur_insert` AFTER INSERT ON `utilisateur` FOR EACH ROW
    INSERT INTO logsaudit (UtilisateurID, Id_log, Timestamp_log, Action_log, Description_log)
    VALUES (NEW.ID, 1, CURDATE(), 'INSERT', CONCAT('Inscription de l''utilisateur : ', NEW.Nom_user, ' ', NEW.Prenom_user, ' (Rôle : ', NEW.Role_user, ')'));

CREATE TRIGGER `after_utilisateur_update` AFTER UPDATE ON `utilisateur` FOR EACH ROW
    INSERT INTO logsaudit (UtilisateurID, Id_log, Timestamp_log, Action_log, Description_log)
    VALUES (NEW.ID, 2, CURDATE(), 'UPDATE', CONCAT('Modification de l''utilisateur ID ', NEW.ID, ' (Changement d''email ou mise à jour de sécurité de mot de passe)'));

CREATE TRIGGER `before_utilisateur_delete` BEFORE DELETE ON `utilisateur` FOR EACH ROW
    INSERT INTO logsaudit (UtilisateurID, Id_log, Timestamp_log, Action_log, Description_log)
    VALUES (OLD.ID, 3, CURDATE(), 'DELETE', CONCAT('Suppression de l''utilisateur : ', OLD.Nom_user, ' ', OLD.Prenom_user, ' (Ancien Rôle : ', OLD.Role_user, ')'));

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `anneeacademique`
--
ALTER TABLE `anneeacademique`
  ADD PRIMARY KEY (`ID`);

--
-- Index pour la table `campus`
--
ALTER TABLE `campus`
  ADD PRIMARY KEY (`ID`);

--
-- Index pour la table `chapitre`
--
ALTER TABLE `chapitre`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `structure2` (`UEID`);

--
-- Index pour la table `convocation`
--
ALTER TABLE `convocation`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `emet2` (`UtilisateurID2`),
  ADD KEY `recoit2` (`UtilisateurID`);

--
-- Index pour la table `filiere`
--
ALTER TABLE `filiere`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `planifie2` (`SemestreID`),
  ADD KEY `heberge2` (`CampusID`);

--
-- Index pour la table `justification`
--
ALTER TABLE `justification`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `justifie2` (`PresenceID`);

--
-- Index pour la table `logsaudit`
--
ALTER TABLE `logsaudit`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `consulte2` (`UtilisateurID`);

--
-- Index pour la table `parametre`
--
ALTER TABLE `parametre`
  ADD PRIMARY KEY (`ID`),
  ADD UNIQUE KEY `unique_user` (`UtilisateurID`);

--
-- Index pour la table `presence`
--
ALTER TABLE `presence`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `pointe2` (`UtilisateurID`),
  ADD KEY `controle2` (`SeanceID`);

--
-- Index pour la table `salle`
--
ALTER TABLE `salle`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `appartient2` (`CampusID`);

--
-- Index pour la table `seance`
--
ALTER TABLE `seance`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `occupe2` (`SalleID`),
  ADD KEY `deroule2` (`UEID`);

--
-- Index pour la table `semestre`
--
ALTER TABLE `semestre`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `compose2` (`AnneeAcademiqueID`);

--
-- Index pour la table `systracks`
--
ALTER TABLE `systracks`
  ADD PRIMARY KEY (`ID`);

--
-- Index pour la table `ue`
--
ALTER TABLE `ue`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `enseigne2` (`UtilisateurID`),
  ADD KEY `contient2` (`FiliereID`);

--
-- Index pour la table `utilisateur`
--
ALTER TABLE `utilisateur`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `inscrit2` (`FiliereID`),
  ADD KEY `idx_search_identity` (`Nom_user`,`Prenom_user`,`Matricule_etu`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `anneeacademique`
--
ALTER TABLE `anneeacademique`
  MODIFY `ID` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `campus`
--
ALTER TABLE `campus`
  MODIFY `ID` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `chapitre`
--
ALTER TABLE `chapitre`
  MODIFY `ID` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `convocation`
--
ALTER TABLE `convocation`
  MODIFY `ID` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `filiere`
--
ALTER TABLE `filiere`
  MODIFY `ID` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `justification`
--
ALTER TABLE `justification`
  MODIFY `ID` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `logsaudit`
--
ALTER TABLE `logsaudit`
  MODIFY `ID` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT pour la table `parametre`
--
ALTER TABLE `parametre`
  MODIFY `ID` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT pour la table `presence`
--
ALTER TABLE `presence`
  MODIFY `ID` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `salle`
--
ALTER TABLE `salle`
  MODIFY `ID` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `seance`
--
ALTER TABLE `seance`
  MODIFY `ID` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `semestre`
--
ALTER TABLE `semestre`
  MODIFY `ID` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `systracks`
--
ALTER TABLE `systracks`
  MODIFY `ID` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `ue`
--
ALTER TABLE `ue`
  MODIFY `ID` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `utilisateur`
--
ALTER TABLE `utilisateur`
  MODIFY `ID` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `chapitre`
--
ALTER TABLE `chapitre`
  ADD CONSTRAINT `structure2` FOREIGN KEY (`UEID`) REFERENCES `ue` (`ID`);

--
-- Contraintes pour la table `convocation`
--
ALTER TABLE `convocation`
  ADD CONSTRAINT `emet2` FOREIGN KEY (`UtilisateurID2`) REFERENCES `utilisateur` (`ID`),
  ADD CONSTRAINT `recoit2` FOREIGN KEY (`UtilisateurID`) REFERENCES `utilisateur` (`ID`);

--
-- Contraintes pour la table `filiere`
--
ALTER TABLE `filiere`
  ADD CONSTRAINT `heberge2` FOREIGN KEY (`CampusID`) REFERENCES `campus` (`ID`),
  ADD CONSTRAINT `planifie2` FOREIGN KEY (`SemestreID`) REFERENCES `semestre` (`ID`);

--
-- Contraintes pour la table `justification`
--
ALTER TABLE `justification`
  ADD CONSTRAINT `justifie2` FOREIGN KEY (`PresenceID`) REFERENCES `presence` (`ID`);

--
-- Contraintes pour la table `logsaudit`
--
ALTER TABLE `logsaudit`
  ADD CONSTRAINT `consulte2` FOREIGN KEY (`UtilisateurID`) REFERENCES `utilisateur` (`ID`);

--
-- Contraintes pour la table `parametre`
--
ALTER TABLE `parametre`
  ADD CONSTRAINT `fk_setting_utilisateur` FOREIGN KEY (`UtilisateurID`) REFERENCES `utilisateur` (`ID`) ON DELETE CASCADE;

--
-- Contraintes pour la table `presence`
--
ALTER TABLE `presence`
  ADD CONSTRAINT `controle2` FOREIGN KEY (`SeanceID`) REFERENCES `seance` (`ID`),
  ADD CONSTRAINT `pointe2` FOREIGN KEY (`UtilisateurID`) REFERENCES `utilisateur` (`ID`);

--
-- Contraintes pour la table `salle`
--
ALTER TABLE `salle`
  ADD CONSTRAINT `appartient2` FOREIGN KEY (`CampusID`) REFERENCES `campus` (`ID`);

--
-- Contraintes pour la table `seance`
--
ALTER TABLE `seance`
  ADD CONSTRAINT `deroule2` FOREIGN KEY (`UEID`) REFERENCES `ue` (`ID`),
  ADD CONSTRAINT `occupe2` FOREIGN KEY (`SalleID`) REFERENCES `salle` (`ID`);

--
-- Contraintes pour la table `semestre`
--
ALTER TABLE `semestre`
  ADD CONSTRAINT `compose2` FOREIGN KEY (`AnneeAcademiqueID`) REFERENCES `anneeacademique` (`ID`);

--
-- Contraintes pour la table `ue`
--
ALTER TABLE `ue`
  ADD CONSTRAINT `contient2` FOREIGN KEY (`FiliereID`) REFERENCES `filiere` (`ID`),
  ADD CONSTRAINT `enseigne2` FOREIGN KEY (`UtilisateurID`) REFERENCES `utilisateur` (`ID`);

--
-- Contraintes pour la table `utilisateur`
--
ALTER TABLE `utilisateur`
  ADD CONSTRAINT `inscrit2` FOREIGN KEY (`FiliereID`) REFERENCES `filiere` (`ID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
