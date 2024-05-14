-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : localhost
-- Généré le : jeu. 09 mai 2024 à 22:26
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
-- Base de données : `stageDatabase2`
--

-- --------------------------------------------------------

--
-- Structure de la table `annee_universitaire`
--

CREATE TABLE `annee_universitaire` (
  `annee` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `annee_universitaire`
--

INSERT INTO `annee_universitaire` (`annee`) VALUES
(2018),
(2019),
(2020),
(2021),
(2022),
(2023),
(2024);

-- --------------------------------------------------------

--
-- Structure de la table `bac`
--

CREATE TABLE `bac` (
  `idbac` int(11) NOT NULL,
  `typebac` varchar(20) NOT NULL,
  `libele` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `bloc`
--

CREATE TABLE `bloc` (
  `codebloc` varchar(20) NOT NULL,
  `filiere` varchar(20) DEFAULT NULL,
  `element` varchar(20) DEFAULT NULL,
  `nombloc` varchar(60) NOT NULL,
  `noteplancher` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `bloc`
--

INSERT INTO `bloc` (`codebloc`, `filiere`, `element`, `nombloc`, `noteplancher`) VALUES
('TINL2B1', 'TINL2', 'TINL2B1', 'Transversaux', NULL),
('TINL2B2', 'TINL2', 'TINL2B2', 'Algorithmique et programmation', 6),
('TINL2B3', 'TINL2', 'TINL2B3', 'Fondements et théorie de l\'informatique', 6),
('TINL2B4', 'TINL2', 'TINL2B4', 'Technologie de l\'informatique', 6);

-- --------------------------------------------------------

--
-- Structure de la table `choix`
--

CREATE TABLE `choix` (
  `specialite` varchar(10) NOT NULL,
  `etudiant` varchar(8) NOT NULL,
  `enterminale` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `codes`
--

CREATE TABLE `codes` (
  `id` int(11) NOT NULL,
  `nom` varchar(255) DEFAULT NULL,
  `nature` varchar(10) DEFAULT NULL,
  `code` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `codes`
--

INSERT INTO `codes` (`id`, `nom`, `nature`, `code`) VALUES
(490, 'Parcours MI - Mathématiques', 'PAR', 'TMI1Q1'),
(491, 'BLOC Mathématiques 1', 'BLCT', 'TMI1B1MB'),
(492, 'BLOC Mathématiques 2', 'BLCT', 'TMI1B2MB'),
(493, 'BLOC Informatique', 'BLCT', 'TMI1B3MB'),
(494, 'Transversaux', 'BLOC', 'TMI1TIMB'),
(495, 'Parcours MI - Informatique', 'PAR', 'TMI1Q2'),
(496, 'BLOC Mathématiques', 'BLCT', 'TMI1B1IB'),
(497, 'BLOC Fondements et algorithmique', 'BLCT', 'TMI1B2IB'),
(498, 'BLOC Développement', 'BLCT', 'TMI1B3IB'),
(499, 'Transversaux', 'BLOC', 'TMI1TIIB'),
(500, 'Analyse élémentaire', 'UE', 'TMX1212'),
(501, 'analyse élémentaire P1', 'CC', 'TMX1212C1'),
(502, 'analyse élémentaire P1', 'MAT', 'TMX1212M1'),
(503, 'analyse élémentaire P2', 'CC', 'TMX1212C2'),
(504, 'analyse élémentaire P2', 'MAT', 'TMX1212M2'),
(505, 'analyse élémentaire P1', 'ECR', 'TMX1212E'),
(506, 'Algèbre élémentaire', 'UE', 'TMX1213'),
(507, 'algèbre élémentaire P1', 'CC', 'TMX1213C1'),
(508, 'algèbre élémentaire P1', 'MAT', 'TMX1213M1'),
(509, 'algèbre élémentaire P2', 'CC', 'TMX1213C2'),
(510, 'algèbre élémentaire P2', 'MAT', 'TMX1213M2'),
(511, 'algèbre élémentaire P1', 'ECR', 'TMX1213E'),
(512, 'Arithmétique dans Z - P3', 'UE', 'TMX1303'),
(513, 'arithmétique dans Z - P3', 'CC', 'TMX1303C'),
(514, 'arithmétique dans Z - P3', 'MAT', 'TMX130M1'),
(515, 'arithmétique dans Z -', 'ECR', 'TMX1303E'),
(516, 'Arithmétique des polynômes - P4', 'UE', 'TMX1403'),
(517, 'arithmétique des polynômes - P4', 'CC', 'TMX1403C'),
(518, 'arithmétique des polynômes - P4', 'MAT', 'TMX140M1'),
(519, 'arithmétique des polynômes -', 'ECR', 'TMX1403E'),
(520, 'Fondements d\'analyse - P3-P4', 'UE', 'TMX1413'),
(521, 'fondements d\'analyse - P3', 'CC', 'TMX1413C1'),
(522, 'fondements d\'analyse - P3', 'MAT', 'TMX1413M1'),
(523, 'fondements d\'analyse - P4', 'CC', 'TMX1413C2'),
(524, 'fondements d\'analyse - P4', 'MAT', 'TMX1413M2'),
(525, 'fondements d\'analyse - P3', 'ECR', 'TMX1413E'),
(526, 'Géométrie - P3-P4', 'UE', 'TMX1414'),
(527, 'géométrie - P3', 'CC', 'TMX1414C1'),
(528, 'géométrie - P3', 'MAT', 'TMX1414M1'),
(529, 'géométrie - P4', 'CC', 'TMX1414C2'),
(530, 'géométrie - P4', 'MAT', 'TMX1414M2'),
(531, 'géométrie - P3', 'ECR', 'TMX1414E'),
(532, 'Bases d\'informatique - P1', 'UE', 'TMX1111'),
(533, 'bases d\'informatique - P1', 'CC', 'TMX1111C'),
(534, 'bases d\'informatique - P1', 'MAT', 'TMX111M1'),
(535, 'bases d\'informatique -', 'ECR', 'TMX1111E'),
(536, 'Algorithmique 1 - P1-P2', 'UE', 'TMX1216'),
(537, 'algorithmique 1 - P1', 'CC', 'TMX1216C1'),
(538, 'algorithmique 1 - P1', 'MAT', 'TMX1216M1'),
(539, 'algorithmique 1 - P2', 'CC', 'TMX1216C2'),
(540, 'algorithmique 1 - P2', 'MAT', 'TMX1216M2'),
(541, 'algorithmique 1 - P1', 'ECR', 'TMX1216E'),
(542, 'Linux - P2', 'UE', 'TMX1217'),
(543, 'linux - P2', 'TP', 'TMX1217T'),
(544, 'linux - P2', 'MAT', 'TMX121M1'),
(545, 'linux -', 'ECR', 'TMX1217E'),
(546, 'Algorithmique 2 - P3 - pour MI-M', 'UE', 'TMX1312'),
(547, 'algorithmique 2 - MI-M - P3', 'CC', 'TMX1312C'),
(548, 'algorithmique 2 - MI-M - P3', 'MAT', 'TMX131M1'),
(549, 'algorithmique 2 - MI-M -', 'ECR', 'TMX1312E'),
(550, 'Développement Python - P4', 'UE', 'TMX1407'),
(551, 'développement Python - P4', 'TP', 'TMX1407T'),
(552, 'développement Python - P4', 'MAT', 'TMX140M1'),
(553, 'développement Python -', 'ECR', 'TMX1407E'),
(554, 'Culture numérique (PIX) - P1 - pour MI, DL MI, DL ME …', 'UE', 'TMX1154'),
(555, 'culture numérique (PIX) - P1 - MI ...', 'TP', 'TMX1154T1'),
(556, 'culture numérique (PIX) - P1 - MI ...', 'MAT', 'TMX1154M1'),
(557, 'culture numérique (PIX) - P1 - MI ...', 'TP', 'TMX1154T2'),
(558, 'culture numérique (PIX) - P1 - MI ...', 'MAT', 'TMX1154M2'),
(559, 'Anglais 1 - P1-P2', 'UE', 'TMX1257'),
(560, 'anglais 1 - P1-P2', 'CC', 'TMX1257C'),
(561, 'anglais 1 - P1-P2', 'MAT', 'TMX125M1'),
(562, 'anglais 1 - P1', 'ECR', 'TMX1257E'),
(563, 'Expression écrite et orale - P1-P2', 'UE', 'TMX1259'),
(564, 'expression écrite ét orale - P1-P2', 'CC', 'TMX1259C'),
(565, 'expression écrite ét orale - P1-P2', 'MAT', 'TMX125M1'),
(566, 'expression écrite ét orale - P1', 'ECR', 'TMX1259E'),
(567, 'Anglais 2 - P3-P4', 'UE', 'TMX1457'),
(568, 'anglais 2 - P3-P4', 'CC', 'TMX1457C'),
(569, 'anglais 2 - P3-P4', 'MAT', 'TMX145M1'),
(570, 'anglais 2 - P3', 'ECR', 'TMX1457E'),
(571, 'Projet personnel et professionnel - P3-P4', 'UE', 'TMX1458'),
(572, 'projet personnel et profes. - P3-P4', 'CC', 'TMX1458C'),
(573, 'projet personnel et profes. - P3-P4', 'MAT', 'TMX145M1'),
(574, 'projet personnel et profes. - P3', 'ECR', 'TMX1458E'),
(575, 'Algorithmique 2 - P3-P4 - pour MI-I', 'UE', 'TMX1416'),
(576, 'algorithmique 2 - MI-I - P3', 'CC', 'TMX1416C1'),
(577, 'algorithmique 2 - MI-I - P3', 'MAT', 'TMX1416M1'),
(578, 'algorithmique 2 - MI-I - P4', 'CC', 'TMX1416C2'),
(579, 'algorithmique 2 - MI-I - P4', 'MAT', 'TMX1416M2'),
(580, 'algorithmique 2 - MI-I - P3', 'ECR', 'TMX1416E'),
(581, 'Fondements de l\'informatique 1 - P3-P4', 'UE', 'TMX1417'),
(582, 'fondements de l\'informatique 1 - P3', 'CC', 'TMX1417C1'),
(583, 'fondements de l\'informatique 1 - P3', 'MAT', 'TMX1417M1'),
(584, 'fondements de l\'informatique 1 - P4', 'CC', 'TMX1417C2'),
(585, 'fondements de l\'informatique 1 - P4', 'MAT', 'TMX1417M2'),
(586, 'fondements de l\'informati. 1 - P3', 'ECR', 'TMX1417E'),
(587, 'Développement web 1 - P3', 'UE', 'TMX1307'),
(588, 'développement web 1 - P3', 'TP', 'TMX1307T'),
(589, 'développement web 1 - P3', 'MAT', 'TMX130M1'),
(590, 'développement web 1 -', 'ECR', 'TMX1307E'),
(591, 'Bases de données 1 - P3-P4', 'UE', 'TMX1408'),
(592, 'bases de données 1 - P3', 'CC', 'TMX1408C1'),
(593, 'bases de données 1 - P3', 'MAT', 'TMX1408M1'),
(594, 'bases de données 1 - P4', 'CC', 'TMX1408C2'),
(595, 'bases de données 1 - P4', 'MAT', 'TMX1408M2'),
(596, 'bases de données 1 - P3', 'ECR', 'TMX1408E');

-- --------------------------------------------------------

--
-- Structure de la table `doctrine_migration_versions`
--

CREATE TABLE `doctrine_migration_versions` (
  `version` varchar(191) NOT NULL,
  `executed_at` datetime DEFAULT NULL,
  `execution_time` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Déchargement des données de la table `doctrine_migration_versions`
--

INSERT INTO `doctrine_migration_versions` (`version`, `executed_at`, `execution_time`) VALUES
('DoctrineMigrations\\Version20240423105033', '2024-04-23 12:04:45', 750),
('DoctrineMigrations\\Version20240505191250', '2024-05-05 19:13:06', 9),
('DoctrineMigrations\\Version20240508151716', '2024-05-08 15:17:35', 14),
('DoctrineMigrations\\Version20240509194741', '2024-05-09 19:47:44', 398);

-- --------------------------------------------------------

--
-- Structure de la table `element`
--

CREATE TABLE `element` (
  `codeelt` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `element`
--

INSERT INTO `element` (`codeelt`) VALUES
('TINL2'),
('TINL2B1'),
('TINL2B1U1'),
('TINL2B1U1M1'),
('TINL2B1U1M1E1'),
('TINL2B1U1M1E2'),
('TINL2B1U1M2'),
('TINL2B1U2'),
('TINL2B1U2M1'),
('TINL2B1U2M1E1'),
('TINL2B1U2M1E10'),
('TINL2B1U2M1E11'),
('TINL2B1U2M1E12'),
('TINL2B1U2M1E13'),
('TINL2B1U2M1E14'),
('TINL2B1U2M1E15'),
('TINL2B1U2M1E16'),
('TINL2B1U2M1E17'),
('TINL2B1U2M1E18'),
('TINL2B1U2M1E19'),
('TINL2B1U2M1E2'),
('TINL2B1U2M1E20'),
('TINL2B1U2M1E3'),
('TINL2B1U2M1E4'),
('TINL2B1U2M1E5'),
('TINL2B1U2M1E6'),
('TINL2B1U2M1E7'),
('TINL2B1U2M1E8'),
('TINL2B1U2M1E9'),
('TINL2B1U2M2'),
('TINL2B1U3'),
('TINL2B1U3M1'),
('TINL2B1U3M1E1'),
('TINL2B1U3M2'),
('TINL2B1U3M2E1'),
('TINL2B1U3M3'),
('TINL2B1U3M3E1'),
('TINL2B1U3M4'),
('TINL2B1U3M4E1'),
('TINL2B1U4'),
('TINL2B1U4M1'),
('TINL2B1U4M1E1'),
('TINL2B1U4M1E2'),
('TINL2B1U4M2'),
('TINL2B1U4M2E1'),
('TINL2B2'),
('TINL2B2U1'),
('TINL2B2U1M1'),
('TINL2B2U1M1E1'),
('TINL2B2U1M1E2'),
('TINL2B2U1M2'),
('TINL2B2U1M2E1'),
('TINL2B2U2'),
('TINL2B2U2M1'),
('TINL2B2U2M1E1'),
('TINL2B2U2M1E2'),
('TINL2B2U2M2'),
('TINL2B2U2M2E1'),
('TINL2B2U2M3'),
('TINL2B2U2M3E1'),
('TINL2B3'),
('TINL2B3U1'),
('TINL2B3U1M1'),
('TINL2B3U1M1E1'),
('TINL2B3U1M1E2'),
('TINL2B3U1M2'),
('TINL2B3U1M2E1'),
('TINL2B3U2'),
('TINL2B3U2M1'),
('TINL2B3U2M1E1'),
('TINL2B3U2M1E2'),
('TINL2B3U2M2'),
('TINL2B3U2M2E1'),
('TINL2B3U3'),
('TINL2B3U3M1'),
('TINL2B3U3M1E1'),
('TINL2B3U3M1E2'),
('TINL2B4'),
('TINL2B4U1'),
('TINL2B4U1M1'),
('TINL2B4U1M1E1'),
('TINL2B4U1M1E2'),
('TINL2B4U1M2'),
('TINL2B4U1M2E1'),
('TINL2B4U2'),
('TINL2B4U2M1'),
('TINL2B4U2M1E1'),
('TINL2B4U2M1E2'),
('TINL2B4U2M2'),
('TINL2B4U2M2E1'),
('TINL2B4U2M3'),
('TINL2B4U2M3E1'),
('TINL2B4U3'),
('TINL2B4U3M1'),
('TINL2B4U3M1E1'),
('TINL2B4U3M1E2'),
('TINL2B4U4'),
('TINL2B4U4M1'),
('TINL2B4U4M1E1'),
('TINL2B4U4M1E2'),
('TMX1212C1');

-- --------------------------------------------------------

--
-- Structure de la table `epreuve`
--

CREATE TABLE `epreuve` (
  `codeepreuve` varchar(20) NOT NULL,
  `matiere` varchar(20) DEFAULT NULL,
  `element` varchar(20) DEFAULT NULL,
  `numchance` int(11) NOT NULL,
  `annee` int(11) DEFAULT NULL,
  `typeepreuve` varchar(20) NOT NULL,
  `salle` varchar(20) DEFAULT NULL,
  `duree` varchar(255) DEFAULT NULL,
  `pourcentage` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `epreuve`
--

INSERT INTO `epreuve` (`codeepreuve`, `matiere`, `element`, `numchance`, `annee`, `typeepreuve`, `salle`, `duree`, `pourcentage`) VALUES
('TINL2B1U1M1E1', 'TINL2B1U1M1', 'TINL2B1U1M1E1', 1, NULL, 'CC', NULL, '1h20', 100),
('TINL2B1U1M1E2', 'TINL2B1U1M1', 'TINL2B1U1M1E2', 2, NULL, 'CT', NULL, '1h', 100),
('TINL2B1U2M1E1', 'TINL2B1U2M1', 'TINL2B1U2M1E1', 1, NULL, 'CC', NULL, '1h20', 100),
('TINL2B1U2M1E2', 'TINL2B1U2M1', 'TINL2B1U2M1E2', 2, NULL, 'CT', NULL, '1h', 100),
('TINL2B1U3M1E1', 'TINL2B1U3M1', 'TINL2B1U3M1E1', 1, NULL, 'ASSIDUITE', NULL, '-', 10),
('TINL2B1U3M2E1', 'TINL2B1U3M2', 'TINL2B1U3M2E1', 1, NULL, 'CC', NULL, '-', 30),
('TINL2B1U3M3E1', 'TINL2B1U3M3', 'TINL2B1U3M3E1', 1, NULL, 'RAP', NULL, '-', 30),
('TINL2B1U3M4E1', 'TINL2B1U3M4', 'TINL2B1U3M4E1', 1, NULL, 'RAP', NULL, '-', 30),
('TINL2B1U4M1E1', 'TINL2B1U4M1', 'TINL2B1U4M1E1', 1, NULL, 'CC', NULL, '2h', 50),
('TINL2B1U4M1E2', 'TINL2B1U4M1', 'TINL2B1U4M1E2', 2, NULL, 'CT', NULL, '2h30', 100),
('TINL2B1U4M2E1', 'TINL2B1U4M2', 'TINL2B1U4M2E1', 1, NULL, 'CC', NULL, '2h30', 50),
('TINL2B2U1M1E1', 'TINL2B2U1M1', 'TINL2B2U1M1E1', 1, NULL, 'CC', NULL, '1h30', 50),
('TINL2B2U1M1E2', 'TINL2B2U1M1', 'TINL2B2U1M1E2', 2, NULL, 'CT', NULL, '1h30', 100),
('TINL2B2U1M2E1', 'TINL2B2U1M2', 'TINL2B2U1M2E1', 1, NULL, 'CC', NULL, '1h30', 50),
('TINL2B2U2M1E1', 'TINL2B2U2M1', 'TINL2B2U2M1E1', 1, NULL, 'CC', NULL, '1h', 20),
('TINL2B2U2M1E2', 'TINL2B2U2M1', 'TINL2B2U2M1E2', 2, NULL, 'CT', NULL, '2h', 601040),
('TINL2B2U2M2E1', 'TINL2B2U2M2', 'TINL2B2U2M2E1', 1, NULL, 'CC', NULL, '1h', 40),
('TINL2B2U2M3E1', 'TINL2B2U2M3', 'TINL2B2U2M3E1', 1, NULL, 'TP', NULL, '2h', 40),
('TINL2B3U1M1E1', 'TINL2B3U1M1', 'TINL2B3U1M1E1', 1, NULL, 'CC', NULL, '1h30', 30),
('TINL2B3U1M1E2', 'TINL2B3U1M1', 'TINL2B3U1M1E2', 2, NULL, 'CT', NULL, '1h30', 100),
('TINL2B3U1M2E1', 'TINL2B3U1M2', 'TINL2B3U1M2E1', 1, NULL, 'CC', NULL, '1h30', 70),
('TINL2B3U2M1E1', 'TINL2B3U2M1', 'TINL2B3U2M1E1', 1, NULL, 'CC', NULL, '1h30', 50),
('TINL2B3U2M1E2', 'TINL2B3U2M1', 'TINL2B3U2M1E2', 2, NULL, 'CT', NULL, '1h30', 100),
('TINL2B3U2M2E1', 'TINL2B3U2M2', 'TINL2B3U2M2E1', 1, NULL, 'CC', NULL, '1h30', 50),
('TINL2B3U3M1E1', 'TINL2B3U3M1', 'TINL2B3U3M1E1', 1, NULL, 'CC', NULL, '0h45', 100),
('TINL2B3U3M1E2', 'TINL2B3U3M1', 'TINL2B3U3M1E2', 2, NULL, 'CT', NULL, '0h45', 100),
('TINL2B4U1M1E1', 'TINL2B4U1M1', 'TINL2B4U1M1E1', 1, NULL, 'CC', NULL, '1h', 50),
('TINL2B4U1M1E2', 'TINL2B4U1M1', 'TINL2B4U1M1E2', 2, NULL, 'CT', NULL, '1h30', 100),
('TINL2B4U1M2E1', 'TINL2B4U1M2', 'TINL2B4U1M2E1', 1, NULL, 'CC', NULL, '1h', 50),
('TINL2B4U2M1E1', 'TINL2B4U2M1', 'TINL2B4U2M1E1', 1, NULL, 'CC', NULL, '1h30', 30),
('TINL2B4U2M1E2', 'TINL2B4U2M1', 'TINL2B4U2M1E2', 2, NULL, 'CT', NULL, '2h', 100),
('TINL2B4U2M2E1', 'TINL2B4U2M2', 'TINL2B4U2M2E1', 1, NULL, 'CC', NULL, '1h30', 30),
('TINL2B4U2M3E1', 'TINL2B4U2M3', 'TINL2B4U2M3E1', 1, NULL, 'CC', NULL, '1h30', 40),
('TINL2B4U3M1E1', 'TINL2B4U3M1', 'TINL2B4U3M1E1', 1, NULL, 'CC', NULL, '2h', 100),
('TINL2B4U3M1E2', 'TINL2B4U3M1', 'TINL2B4U3M1E2', 2, NULL, 'CT', NULL, '2h', 100),
('TINL2B4U4M1E1', 'TINL2B4U4M1', 'TINL2B4U4M1E1', 1, NULL, 'CC', NULL, '1h30', 100),
('TINL2B4U4M1E2', 'TINL2B4U4M1', 'TINL2B4U4M1E2', 2, NULL, 'CT', NULL, '1h30', 100);

-- --------------------------------------------------------

--
-- Structure de la table `etudiant`
--

CREATE TABLE `etudiant` (
  `numetd` varchar(8) NOT NULL,
  `nom` varchar(50) NOT NULL,
  `prenom` varchar(50) NOT NULL,
  `email` varchar(60) NOT NULL,
  `sexe` varchar(1) NOT NULL,
  `adresse` varchar(70) DEFAULT NULL,
  `tel` varchar(40) DEFAULT NULL,
  `filiere` varchar(40) DEFAULT NULL,
  `datnaiss` date DEFAULT NULL,
  `depnaiss` varchar(40) DEFAULT NULL,
  `villnaiss` varchar(40) DEFAULT NULL,
  `paysnaiss` varchar(40) DEFAULT NULL,
  `nationalite` varchar(50) DEFAULT NULL,
  `sports` varchar(80) DEFAULT NULL,
  `handicape` varchar(80) DEFAULT NULL,
  `derdiplome` varchar(50) DEFAULT NULL,
  `dateinsc` date DEFAULT NULL,
  `registre` varchar(30) DEFAULT NULL,
  `statut` varchar(30) DEFAULT NULL,
  `hide` tinyint(1) DEFAULT 0,
  `codegrp` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `etudiant`
--

INSERT INTO `etudiant` (`numetd`, `nom`, `prenom`, `email`, `sexe`, `adresse`, `tel`, `filiere`, `datnaiss`, `depnaiss`, `villnaiss`, `paysnaiss`, `nationalite`, `sports`, `handicape`, `derdiplome`, `dateinsc`, `registre`, `statut`, `hide`, `codegrp`) VALUES
('01234567', 'Dupont', 'Jean', 'jean.dupont@example.com', 'M', NULL, '0123456789', NULL, NULL, '75', 'Paris', NULL, 'Française', 'Football', 'Non', 'Baccalauréat', NULL, 'Registre 1', 'Actif', 0, NULL),
('12345678', 'Girard', 'Emilie', 'emilie.girard@example.com', 'F', NULL, '0987654321', NULL, NULL, '59', 'Lille', NULL, 'Française', 'Equitation', 'Oui', 'Doctorat', NULL, 'Registre 10', 'Actif', 0, NULL),
('23456789', 'Martin', 'Marie', 'marie.martin@example.com', 'F', NULL, '0987654321', NULL, NULL, '69', 'Lyon', NULL, 'Française', 'Basketball', 'Oui', 'BTS', NULL, 'Registre 2', 'Actif', 0, NULL),
('34567890', 'Durand', 'Pierre', 'pierre.durand@example.com', 'M', NULL, '0123456789', NULL, NULL, '33', 'Bordeaux', NULL, 'Française', 'Natation', 'Non', 'Licence', NULL, 'Registre 3', 'Actif', 0, NULL),
('45678901', 'Leclerc', 'Sophie', 'sophie.leclerc@example.com', 'F', NULL, '0987654321', NULL, NULL, '29', 'Brest', NULL, 'Française', 'Tennis', 'Oui', 'Master', NULL, 'Registre 4', 'Actif', 0, NULL),
('56789012', 'Petit', 'Luc', 'luc.petit@example.com', 'M', NULL, '0123456789', NULL, NULL, '78', 'Versailles', NULL, 'Française', 'Rugby', 'Non', 'Doctorat', NULL, 'Registre 5', 'Actif', 0, NULL),
('67890123', 'Robert', 'Anne', 'anne.robert@example.com', 'F', NULL, '0987654321', NULL, NULL, '31', 'Toulouse', NULL, 'Française', 'Volleyball', 'Oui', 'Baccalauréat', NULL, 'Registre 6', 'Actif', 0, NULL),
('78901234', 'Lafont', 'Marc', 'marc.lafont@example.com', 'M', NULL, '0123456789', NULL, NULL, '67', 'Strasbourg', NULL, 'Française', 'Course à pied', 'Non', 'BTS', NULL, 'Registre 7', 'Actif', 0, NULL),
('89012345', 'Moreau', 'Laura', 'laura.moreau@example.com', 'F', NULL, '0987654321', NULL, NULL, '13', 'Marseille', NULL, 'Française', 'Echecs', 'Oui', 'Licence', NULL, 'Registre 8', 'Actif', 0, NULL),
('90123456', 'Dubois', 'François', 'francois.dubois@example.com', 'M', NULL, '0123456789', NULL, NULL, '38', 'Grenoble', NULL, 'Française', 'Golf', 'Non', 'Master', NULL, 'Registre 9', 'Actif', 0, NULL);

-- --------------------------------------------------------

--
-- Structure de la table `etudsup`
--

CREATE TABLE `etudsup` (
  `formation` varchar(20) NOT NULL,
  `etudiant` varchar(8) NOT NULL,
  `anneedeb` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `filiere`
--

CREATE TABLE `filiere` (
  `codefiliere` varchar(20) NOT NULL,
  `element` varchar(20) DEFAULT NULL,
  `nomfiliere` varchar(30) NOT NULL,
  `respfiliere` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `filiere`
--

INSERT INTO `filiere` (`codefiliere`, `element`, `nomfiliere`, `respfiliere`) VALUES
('TINL2', 'TINL2', 'L2 Informatique', NULL);

-- --------------------------------------------------------

--
-- Structure de la table `formation_ant`
--

CREATE TABLE `formation_ant` (
  `codef` varchar(20) NOT NULL,
  `nomf` varchar(50) NOT NULL,
  `etablissement` varchar(80) DEFAULT NULL,
  `diplome` varchar(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `groupe`
--

CREATE TABLE `groupe` (
  `codegrp` varchar(50) NOT NULL,
  `nomgrp` varchar(50) NOT NULL,
  `nbetds` int(11) NOT NULL,
  `capacite` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `matiere`
--

CREATE TABLE `matiere` (
  `codemat` varchar(20) NOT NULL,
  `unite` varchar(20) DEFAULT NULL,
  `element` varchar(20) DEFAULT NULL,
  `nommat` varchar(40) NOT NULL,
  `periode` varchar(3) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `matiere`
--

INSERT INTO `matiere` (`codemat`, `unite`, `element`, `nommat`, `periode`) VALUES
('TINL2B1U1M1', 'TINL2B1U1', 'TINL2B1U1M1', 'Anglais 1', 'P6'),
('TINL2B1U1M2', 'TINL2B1U1', 'TINL2B1U1M2', 'Anglais 1', 'P7'),
('TINL2B1U2M1', 'TINL2B1U2', 'TINL2B1U2M1', 'Anglais 2', 'P8'),
('TINL2B1U2M2', 'TINL2B1U2', 'TINL2B1U2M2', 'Anglais 2', 'P9'),
('TINL2B1U3M1', 'TINL2B1U3', 'TINL2B1U3M1', 'Projet personnel et professionnel', 'P6'),
('TINL2B1U3M2', 'TINL2B1U3', 'TINL2B1U3M2', 'Projet personnel et professionnel', 'P7'),
('TINL2B1U3M3', 'TINL2B1U3', 'TINL2B1U3M3', 'Projet personnel et professionnel', 'P9'),
('TINL2B1U3M4', 'TINL2B1U3', 'TINL2B1U3M4', 'Projet personnel et professionnel', 'P10'),
('TINL2B1U4M1', 'TINL2B1U4', 'TINL2B1U4M1', 'Algèbre linéraire', 'P6'),
('TINL2B1U4M2', 'TINL2B1U4', 'TINL2B1U4M2', 'Algèbre linéaire', 'P7'),
('TINL2B2U1M1', 'TINL2B2U1', 'TINL2B2U1M1', 'Algorithmique 3', 'P6'),
('TINL2B2U1M2', 'TINL2B2U1', 'TINL2B2U1M2', 'Algorithmique 3', 'P7'),
('TINL2B2U2M1', 'TINL2B2U2', 'TINL2B2U2M1', 'Programmation orientée objet 1', 'P8'),
('TINL2B2U2M2', 'TINL2B2U2', 'TINL2B2U2M2', 'Programmation orientée objet 1', 'P9'),
('TINL2B2U2M3', 'TINL2B2U2', 'TINL2B2U2M3', 'Programmation orientée objet 1', 'P10'),
('TINL2B3U1M1', 'TINL2B3U1', 'TINL2B3U1M1', 'Fondements de l\'informatique 2', 'P6'),
('TINL2B3U1M2', 'TINL2B3U1', 'TINL2B3U1M2', 'Fondements de l\'informatique 2', 'P7'),
('TINL2B3U2M1', 'TINL2B3U2', 'TINL2B3U2M1', 'Théorie des langages 1', 'P8'),
('TINL2B3U2M2', 'TINL2B3U2', 'TINL2B3U2M2', 'Théorie des langages 1', 'P9'),
('TINL2B3U3M1', 'TINL2B3U3', 'TINL2B3U3M1', 'Fondements de l\'informatique 3', 'P1'),
('TINL2B4U1M1', 'TINL2B4U1', 'TINL2B4U1M1', 'Bases de données 2', 'P6'),
('TINL2B4U1M2', 'TINL2B4U1', 'TINL2B4U1M2', 'Bases de données 2', 'P7'),
('TINL2B4U2M1', 'TINL2B4U2', 'TINL2B4U2M1', 'Développement web 2', 'P8'),
('TINL2B4U2M2', 'TINL2B4U2', 'TINL2B4U2M2', 'Développement web 2', 'P9'),
('TINL2B4U2M3', 'TINL2B4U2', 'TINL2B4U2M3', 'Développement web 2', 'P10'),
('TINL2B4U3M1', 'TINL2B4U3', 'TINL2B4U3M1', 'Systèmes GNU/Linux et Bash', 'P1'),
('TINL2B4U4M1', 'TINL2B4U4', 'TINL2B4U4M1', 'Systèmes', 'P1');

-- --------------------------------------------------------

--
-- Structure de la table `messenger_messages`
--

CREATE TABLE `messenger_messages` (
  `id` bigint(20) NOT NULL,
  `body` longtext NOT NULL,
  `headers` longtext NOT NULL,
  `queue_name` varchar(190) NOT NULL,
  `created_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `available_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `delivered_at` datetime DEFAULT NULL COMMENT '(DC2Type:datetime_immutable)'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `note`
--

CREATE TABLE `note` (
  `anneeuniversitaire` int(11) NOT NULL,
  `etudiant` varchar(8) NOT NULL,
  `element` varchar(20) NOT NULL,
  `note` double DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `note`
--

INSERT INTO `note` (`anneeuniversitaire`, `etudiant`, `element`, `note`) VALUES
(2022, '01234567', 'TINL2', 15.944444444444),
(2022, '01234567', 'TINL2B1', 11.5),
(2022, '01234567', 'TINL2B1U1', 12.5),
(2022, '01234567', 'TINL2B1U1M1', 12.5),
(2022, '01234567', 'TINL2B1U1M1E1', 12.5),
(2022, '01234567', 'TINL2B1U2', 10.5),
(2022, '01234567', 'TINL2B1U2M1', 10.5),
(2022, '01234567', 'TINL2B1U2M1E1', 10.5),
(2022, '01234567', 'TINL2B2', 19.5),
(2022, '01234567', 'TINL2B2U1', 19.5),
(2022, '01234567', 'TINL2B2U1M2', 19.5),
(2022, '01234567', 'TINL2B2U1M2E1', 19.5),
(2022, '01234567', 'TINL2B3', 19.5),
(2022, '01234567', 'TINL2B3U1', 19.5),
(2022, '01234567', 'TINL2B3U1M1', 19.5),
(2022, '01234567', 'TINL2B3U1M1E1', 19.5),
(2022, '12345678', 'TINL2', 15.555555555556),
(2022, '12345678', 'TINL2B1', 13),
(2022, '12345678', 'TINL2B1U1', 13),
(2022, '12345678', 'TINL2B1U1M1', 13),
(2022, '12345678', 'TINL2B1U1M1E1', 13),
(2022, '12345678', 'TINL2B1U2', 13),
(2022, '12345678', 'TINL2B1U2M1', 13),
(2022, '12345678', 'TINL2B1U2M1E1', 13),
(2022, '12345678', 'TINL2B2', 20),
(2022, '12345678', 'TINL2B2U1', 20),
(2022, '12345678', 'TINL2B2U1M2', 20),
(2022, '12345678', 'TINL2B2U1M2E1', 20),
(2022, '12345678', 'TINL2B3', 16),
(2022, '12345678', 'TINL2B3U1', 16),
(2022, '12345678', 'TINL2B3U1M1', 16),
(2022, '12345678', 'TINL2B3U1M1E1', 16),
(2022, '23456789', 'TINL2', 16.444444444444),
(2022, '23456789', 'TINL2B1', 13),
(2022, '23456789', 'TINL2B1U1', 8),
(2022, '23456789', 'TINL2B1U1M1', 8),
(2022, '23456789', 'TINL2B1U1M1E1', 8),
(2022, '23456789', 'TINL2B1U2', 18),
(2022, '23456789', 'TINL2B1U2M1', 18),
(2022, '23456789', 'TINL2B1U2M1E1', 18),
(2022, '23456789', 'TINL2B2', 18),
(2022, '23456789', 'TINL2B2U1', 18),
(2022, '23456789', 'TINL2B2U1M2', 18),
(2022, '23456789', 'TINL2B2U1M2E1', 18),
(2022, '23456789', 'TINL2B3', 20),
(2022, '23456789', 'TINL2B3U1', 20),
(2022, '23456789', 'TINL2B3U1M1', 20),
(2022, '23456789', 'TINL2B3U1M1E1', 20),
(2022, '34567890', 'TINL2', 12.055555555556),
(2022, '34567890', 'TINL2B1', 11.5),
(2022, '34567890', 'TINL2B1U1', 16.5),
(2022, '34567890', 'TINL2B1U1M1', 16.5),
(2022, '34567890', 'TINL2B1U1M1E1', 16.5),
(2022, '34567890', 'TINL2B1U2', 6.5),
(2022, '34567890', 'TINL2B1U2M1', 6.5),
(2022, '34567890', 'TINL2B1U2M1E1', 6.5),
(2022, '34567890', 'TINL2B2', 6.5),
(2022, '34567890', 'TINL2B2U1', 6.5),
(2022, '34567890', 'TINL2B2U1M2', 6.5),
(2022, '34567890', 'TINL2B2U1M2E1', 6.5),
(2022, '34567890', 'TINL2B3', 16.5),
(2022, '34567890', 'TINL2B3U1', 16.5),
(2022, '34567890', 'TINL2B3U1M1', 16.5),
(2022, '34567890', 'TINL2B3U1M1E1', 16.5),
(2022, '45678901', 'TINL2', 13.444444444444),
(2022, '45678901', 'TINL2B1', 14),
(2022, '45678901', 'TINL2B1U1', 9),
(2022, '45678901', 'TINL2B1U1M1', 9),
(2022, '45678901', 'TINL2B1U1M1E1', 9),
(2022, '45678901', 'TINL2B1U2', 19),
(2022, '45678901', 'TINL2B1U2M1', 19),
(2022, '45678901', 'TINL2B1U2M1E1', 19),
(2022, '45678901', 'TINL2B2', 19),
(2022, '45678901', 'TINL2B2U1', 19),
(2022, '45678901', 'TINL2B2U1M2', 19),
(2022, '45678901', 'TINL2B2U1M2E1', 19),
(2022, '45678901', 'TINL2B3', 9),
(2022, '45678901', 'TINL2B3U1', 9),
(2022, '45678901', 'TINL2B3U1M1', 9),
(2022, '45678901', 'TINL2B3U1M1E1', 9),
(2022, '56789012', 'TINL2', 14.277777777778),
(2022, '56789012', 'TINL2B1', 15.5),
(2022, '56789012', 'TINL2B1U1', 15.5),
(2022, '56789012', 'TINL2B1U1M1', 15.5),
(2022, '56789012', 'TINL2B1U1M1E1', 15.5),
(2022, '56789012', 'TINL2B1U2', 15.5),
(2022, '56789012', 'TINL2B1U2M1', 15.5),
(2022, '56789012', 'TINL2B1U2M1E1', 15.5),
(2022, '56789012', 'TINL2B2', 5.5),
(2022, '56789012', 'TINL2B2U1', 5.5),
(2022, '56789012', 'TINL2B2U1M2', 5.5),
(2022, '56789012', 'TINL2B2U1M2E1', 5.5),
(2022, '56789012', 'TINL2B3', 18.5),
(2022, '56789012', 'TINL2B3U1', 18.5),
(2022, '56789012', 'TINL2B3U1M1', 18.5),
(2022, '56789012', 'TINL2B3U1M1E1', 18.5),
(2022, '67890123', 'TINL2', 11.277777777778),
(2022, '67890123', 'TINL2B1', 13.5),
(2022, '67890123', 'TINL2B1U1', 13.5),
(2022, '67890123', 'TINL2B1U1M1', 13.5),
(2022, '67890123', 'TINL2B1U1M1E1', 13.5),
(2022, '67890123', 'TINL2B1U2', 13.5),
(2022, '67890123', 'TINL2B1U2M1', 13.5),
(2022, '67890123', 'TINL2B1U2M1E1', 13.5),
(2022, '67890123', 'TINL2B2', 3.5),
(2022, '67890123', 'TINL2B2U1', 3.5),
(2022, '67890123', 'TINL2B2U1M2', 3.5),
(2022, '67890123', 'TINL2B2U1M2E1', 3.5),
(2022, '67890123', 'TINL2B3', 13.5),
(2022, '67890123', 'TINL2B3U1', 13.5),
(2022, '67890123', 'TINL2B3U1M1', 13.5),
(2022, '67890123', 'TINL2B3U1M1E1', 13.5),
(2022, '78901234', 'TINL2', 10.444444444444),
(2022, '78901234', 'TINL2B1', 1),
(2022, '78901234', 'TINL2B1U1', 1),
(2022, '78901234', 'TINL2B1U1M1', 1),
(2022, '78901234', 'TINL2B1U1M1E1', 1),
(2022, '78901234', 'TINL2B1U2', 1),
(2022, '78901234', 'TINL2B1U2M1', 1),
(2022, '78901234', 'TINL2B1U2M1E1', 1),
(2022, '78901234', 'TINL2B2', 18),
(2022, '78901234', 'TINL2B2U1', 18),
(2022, '78901234', 'TINL2B2U1M2', 18),
(2022, '78901234', 'TINL2B2U1M2E1', 18),
(2022, '78901234', 'TINL2B3', 18),
(2022, '78901234', 'TINL2B3U1', 18),
(2022, '78901234', 'TINL2B3U1M1', 18),
(2022, '78901234', 'TINL2B3U1M1E1', 18),
(2022, '89012345', 'TINL2', 14.888888888889),
(2022, '89012345', 'TINL2B1', 16),
(2022, '89012345', 'TINL2B1U1', 18),
(2022, '89012345', 'TINL2B1U1M1', 18),
(2022, '89012345', 'TINL2B1U1M1E1', 18),
(2022, '89012345', 'TINL2B1U2', 14),
(2022, '89012345', 'TINL2B1U2M1', 14),
(2022, '89012345', 'TINL2B1U2M1E1', 14),
(2022, '89012345', 'TINL2B2', 8),
(2022, '89012345', 'TINL2B2U1', 8),
(2022, '89012345', 'TINL2B2U1M2', 8),
(2022, '89012345', 'TINL2B2U1M2E1', 8),
(2022, '89012345', 'TINL2B3', 18),
(2022, '89012345', 'TINL2B3U1', 18),
(2022, '89012345', 'TINL2B3U1M1', 18),
(2022, '89012345', 'TINL2B3U1M1E1', 18),
(2022, '90123456', 'TINL2', 13.222222222222),
(2022, '90123456', 'TINL2B1', 16),
(2022, '90123456', 'TINL2B1U1', 19),
(2022, '90123456', 'TINL2B1U1M1', 19),
(2022, '90123456', 'TINL2B1U1M1E1', 19),
(2022, '90123456', 'TINL2B1U2', 13),
(2022, '90123456', 'TINL2B1U2M1', 13),
(2022, '90123456', 'TINL2B1U2M1E1', 13),
(2022, '90123456', 'TINL2B2', 5),
(2022, '90123456', 'TINL2B2U1', 5),
(2022, '90123456', 'TINL2B2U1M2', 5),
(2022, '90123456', 'TINL2B2U1M2E1', 5),
(2022, '90123456', 'TINL2B3', 15),
(2022, '90123456', 'TINL2B3U1', 15),
(2022, '90123456', 'TINL2B3U1M1', 15),
(2022, '90123456', 'TINL2B3U1M1E1', 15);

-- --------------------------------------------------------

--
-- Structure de la table `resultatbac`
--

CREATE TABLE `resultatbac` (
  `bac` int(11) NOT NULL,
  `etudiant` varchar(8) NOT NULL,
  `anneebac` int(11) NOT NULL,
  `mention` varchar(20) DEFAULT NULL,
  `moyennebac` double DEFAULT NULL,
  `etabbac` varchar(50) DEFAULT NULL,
  `depbac` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `specialite`
--

CREATE TABLE `specialite` (
  `codespe` varchar(10) NOT NULL,
  `nomspe` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `unite`
--

CREATE TABLE `unite` (
  `codeunite` varchar(20) NOT NULL,
  `bloc` varchar(20) DEFAULT NULL,
  `element` varchar(20) DEFAULT NULL,
  `nomunite` varchar(60) NOT NULL,
  `coeficient` int(11) DEFAULT NULL,
  `respunite` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `unite`
--

INSERT INTO `unite` (`codeunite`, `bloc`, `element`, `nomunite`, `coeficient`, `respunite`) VALUES
('TINL2B1U1', 'TINL2B1', 'TINL2B1U1', 'Anglais 1', 2, NULL),
('TINL2B1U2', 'TINL2B1', 'TINL2B1U2', 'Anglais 2', 2, NULL),
('TINL2B1U3', 'TINL2B1', 'TINL2B1U3', 'Projet personnel et professionnel', 3, NULL),
('TINL2B1U4', 'TINL2B1', 'TINL2B1U4', 'Algèbre linéaire', 7, NULL),
('TINL2B2U1', 'TINL2B2', 'TINL2B2U1', 'Algorithmique 3', 8, NULL),
('TINL2B2U2', 'TINL2B2', 'TINL2B2U2', 'Programmation orientée objet 1', 8, NULL),
('TINL2B3U1', 'TINL2B3', 'TINL2B3U1', 'Fondements de l\'informatique 2', 6, NULL),
('TINL2B3U2', 'TINL2B3', 'TINL2B3U2', 'Théorie des langages 1', 6, NULL),
('TINL2B3U3', 'TINL2B3', 'TINL2B3U3', 'Fondements de l\'informatique 3', 2, NULL),
('TINL2B4U1', 'TINL2B4', 'TINL2B4U1', 'Bases de données 2', 4, NULL),
('TINL2B4U2', 'TINL2B4', 'TINL2B4U2', 'Développement web 2', 6, NULL),
('TINL2B4U3', 'TINL2B4', 'TINL2B4U3', 'Systèmes GNU/Linux et Bash', 3, NULL),
('TINL2B4U4', 'TINL2B4', 'TINL2B4U4', 'Systèmes', 3, NULL);

-- --------------------------------------------------------

--
-- Structure de la table `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `email` varchar(180) NOT NULL,
  `roles` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL COMMENT '(DC2Type:json)' CHECK (json_valid(`roles`)),
  `password` varchar(255) NOT NULL,
  `firstname` varchar(255) NOT NULL,
  `lastname` varchar(255) NOT NULL,
  `validation` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `user`
--

INSERT INTO `user` (`id`, `email`, `roles`, `password`, `firstname`, `lastname`, `validation`) VALUES
(12, 'bouba@demo.fr', '[\"ROLE_ADMIN\"]', 'Bouba2345#', 'BARRY', 'Boubacar', 1),
(14, 'fanta@demo.fr', '[\"ROLE_USER\"]', '$2y$13$a2JEg26BXwy/nlEYfViLAer/hgymAYyicjAPA69PG4K.oPW3Wgumq', 'Fanta', 'BARRY', 0),
(15, 'habib@demo.fr', '[\"ROLE_ADMIN\"]', 'Habib2345#', 'KHTEIRA', 'Habib', 1),
(16, 'angers@demo.fr', '[\"ROLE_USER\"]', '$2y$13$1eRkFpm1rDbHFAt55oh2JedFHTSmZVp4UaSGPQ/2yB92wdIJXKFJS', 'angers', 'couffon', 0),
(17, 'habib15@demo.fr', '[\"ROLE_ADMIN\"]', '$2y$13$gPX7XsBupSU4PJwRE7K4K.9KLESclOMXLQVXNruOpnWGVSKJpoyzi', 'habib', 'KHTEIRA', 0),
(18, 'habib@gmail.com', '[\"ROLE_USER\"]', '$2y$13$cBTFznvdbnoP1norDNoqreuh71TccBLQimMca6td.o6BtETJSyXRK', 'habib', 'khteira', 0);

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `annee_universitaire`
--
ALTER TABLE `annee_universitaire`
  ADD PRIMARY KEY (`annee`);

--
-- Index pour la table `bac`
--
ALTER TABLE `bac`
  ADD PRIMARY KEY (`idbac`);

--
-- Index pour la table `bloc`
--
ALTER TABLE `bloc`
  ADD PRIMARY KEY (`codebloc`),
  ADD UNIQUE KEY `UNIQ_C778955A41405E39` (`element`),
  ADD KEY `IDX_C778955A2ED05D9E` (`filiere`);

--
-- Index pour la table `choix`
--
ALTER TABLE `choix`
  ADD PRIMARY KEY (`specialite`,`etudiant`),
  ADD KEY `IDX_4F488091E7D6FCC1` (`specialite`),
  ADD KEY `IDX_4F488091717E22E3` (`etudiant`);

--
-- Index pour la table `codes`
--
ALTER TABLE `codes`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `doctrine_migration_versions`
--
ALTER TABLE `doctrine_migration_versions`
  ADD PRIMARY KEY (`version`);

--
-- Index pour la table `element`
--
ALTER TABLE `element`
  ADD PRIMARY KEY (`codeelt`);

--
-- Index pour la table `epreuve`
--
ALTER TABLE `epreuve`
  ADD PRIMARY KEY (`codeepreuve`),
  ADD UNIQUE KEY `UNIQ_D6ADE47F41405E39` (`element`),
  ADD KEY `IDX_D6ADE47F9014574A` (`matiere`);

--
-- Index pour la table `etudiant`
--
ALTER TABLE `etudiant`
  ADD PRIMARY KEY (`numetd`),
  ADD UNIQUE KEY `UNIQ_717E22E3E7927C74` (`email`),
  ADD KEY `IDX_717E22E3BE4E55D8` (`codegrp`);

--
-- Index pour la table `etudsup`
--
ALTER TABLE `etudsup`
  ADD PRIMARY KEY (`formation`,`etudiant`),
  ADD KEY `IDX_5DDD686404021BF` (`formation`),
  ADD KEY `IDX_5DDD686717E22E3` (`etudiant`);

--
-- Index pour la table `filiere`
--
ALTER TABLE `filiere`
  ADD PRIMARY KEY (`codefiliere`),
  ADD UNIQUE KEY `UNIQ_2ED05D9E41405E39` (`element`);

--
-- Index pour la table `formation_ant`
--
ALTER TABLE `formation_ant`
  ADD PRIMARY KEY (`codef`);

--
-- Index pour la table `groupe`
--
ALTER TABLE `groupe`
  ADD PRIMARY KEY (`codegrp`);

--
-- Index pour la table `matiere`
--
ALTER TABLE `matiere`
  ADD PRIMARY KEY (`codemat`),
  ADD UNIQUE KEY `UNIQ_9014574A41405E39` (`element`),
  ADD KEY `IDX_9014574A1D64C118` (`unite`);

--
-- Index pour la table `messenger_messages`
--
ALTER TABLE `messenger_messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_75EA56E0FB7336F0` (`queue_name`),
  ADD KEY `IDX_75EA56E0E3BD61CE` (`available_at`),
  ADD KEY `IDX_75EA56E016BA31DB` (`delivered_at`);

--
-- Index pour la table `note`
--
ALTER TABLE `note`
  ADD PRIMARY KEY (`anneeuniversitaire`,`etudiant`,`element`),
  ADD KEY `IDX_CFBDFA1469D43CC0` (`anneeuniversitaire`),
  ADD KEY `IDX_CFBDFA14717E22E3` (`etudiant`),
  ADD KEY `IDX_CFBDFA1441405E39` (`element`);

--
-- Index pour la table `resultatbac`
--
ALTER TABLE `resultatbac`
  ADD PRIMARY KEY (`bac`,`etudiant`),
  ADD KEY `IDX_A83D80341C4FAC58` (`bac`),
  ADD KEY `IDX_A83D8034717E22E3` (`etudiant`);

--
-- Index pour la table `specialite`
--
ALTER TABLE `specialite`
  ADD PRIMARY KEY (`codespe`);

--
-- Index pour la table `unite`
--
ALTER TABLE `unite`
  ADD PRIMARY KEY (`codeunite`),
  ADD UNIQUE KEY `UNIQ_1D64C11841405E39` (`element`),
  ADD KEY `IDX_1D64C118C778955A` (`bloc`);

--
-- Index pour la table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQ_IDENTIFIER_EMAIL` (`email`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `bac`
--
ALTER TABLE `bac`
  MODIFY `idbac` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `codes`
--
ALTER TABLE `codes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=597;

--
-- AUTO_INCREMENT pour la table `messenger_messages`
--
ALTER TABLE `messenger_messages`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `bloc`
--
ALTER TABLE `bloc`
  ADD CONSTRAINT `FK_C778955A2ED05D9E` FOREIGN KEY (`filiere`) REFERENCES `filiere` (`codefiliere`),
  ADD CONSTRAINT `FK_C778955A41405E39` FOREIGN KEY (`element`) REFERENCES `element` (`codeelt`);

--
-- Contraintes pour la table `choix`
--
ALTER TABLE `choix`
  ADD CONSTRAINT `FK_4F488091717E22E3` FOREIGN KEY (`etudiant`) REFERENCES `etudiant` (`numetd`),
  ADD CONSTRAINT `FK_4F488091E7D6FCC1` FOREIGN KEY (`specialite`) REFERENCES `specialite` (`codespe`);

--
-- Contraintes pour la table `epreuve`
--
ALTER TABLE `epreuve`
  ADD CONSTRAINT `FK_D6ADE47F41405E39` FOREIGN KEY (`element`) REFERENCES `element` (`codeelt`),
  ADD CONSTRAINT `FK_D6ADE47F9014574A` FOREIGN KEY (`matiere`) REFERENCES `matiere` (`codemat`);

--
-- Contraintes pour la table `etudiant`
--
ALTER TABLE `etudiant`
  ADD CONSTRAINT `FK_717E22E3BE4E55D8` FOREIGN KEY (`codegrp`) REFERENCES `groupe` (`codegrp`) ON DELETE SET NULL;

--
-- Contraintes pour la table `etudsup`
--
ALTER TABLE `etudsup`
  ADD CONSTRAINT `FK_5DDD686404021BF` FOREIGN KEY (`formation`) REFERENCES `formation_ant` (`codef`),
  ADD CONSTRAINT `FK_5DDD686717E22E3` FOREIGN KEY (`etudiant`) REFERENCES `etudiant` (`numetd`);

--
-- Contraintes pour la table `filiere`
--
ALTER TABLE `filiere`
  ADD CONSTRAINT `FK_2ED05D9E41405E39` FOREIGN KEY (`element`) REFERENCES `element` (`codeelt`);

--
-- Contraintes pour la table `matiere`
--
ALTER TABLE `matiere`
  ADD CONSTRAINT `FK_9014574A1D64C118` FOREIGN KEY (`unite`) REFERENCES `unite` (`codeunite`),
  ADD CONSTRAINT `FK_9014574A41405E39` FOREIGN KEY (`element`) REFERENCES `element` (`codeelt`);

--
-- Contraintes pour la table `note`
--
ALTER TABLE `note`
  ADD CONSTRAINT `FK_CFBDFA1441405E39` FOREIGN KEY (`element`) REFERENCES `element` (`codeelt`),
  ADD CONSTRAINT `FK_CFBDFA1469D43CC0` FOREIGN KEY (`anneeuniversitaire`) REFERENCES `annee_universitaire` (`annee`),
  ADD CONSTRAINT `FK_CFBDFA14717E22E3` FOREIGN KEY (`etudiant`) REFERENCES `etudiant` (`numetd`);

--
-- Contraintes pour la table `resultatbac`
--
ALTER TABLE `resultatbac`
  ADD CONSTRAINT `FK_A83D80341C4FAC58` FOREIGN KEY (`bac`) REFERENCES `bac` (`idbac`),
  ADD CONSTRAINT `FK_A83D8034717E22E3` FOREIGN KEY (`etudiant`) REFERENCES `etudiant` (`numetd`);

--
-- Contraintes pour la table `unite`
--
ALTER TABLE `unite`
  ADD CONSTRAINT `FK_1D64C11841405E39` FOREIGN KEY (`element`) REFERENCES `element` (`codeelt`),
  ADD CONSTRAINT `FK_1D64C118C778955A` FOREIGN KEY (`bloc`) REFERENCES `bloc` (`codebloc`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
