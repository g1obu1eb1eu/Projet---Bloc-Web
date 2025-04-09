-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : mer. 09 avr. 2025 à 16:31
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
-- Base de données : `utilisateurs`
--

-- --------------------------------------------------------

--
-- Structure de la table `commentaires`
--

CREATE TABLE `commentaires` (
  `id` int(11) NOT NULL,
  `entreprise_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `commentaire` text DEFAULT NULL,
  `nom_utilisateur` varchar(100) DEFAULT NULL,
  `prenom_utilisateur` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `commentaires`
--

INSERT INTO `commentaires` (`id`, `entreprise_id`, `user_id`, `commentaire`, `nom_utilisateur`, `prenom_utilisateur`) VALUES
(8, 1, 14, 'J\'aime beaucoup', 'bob', 'bob'),
(9, 1, 14, 'Bob\r\n', 'bob', 'bob'),
(10, 1, 14, 'Je s\'appelle grout\r\n', 'bob', 'bob'),
(11, 3, 15, 'Ceci est un test', 'Smail', 'Benali'),
(16, 1, 16, 'test 2.0', 'Grad', 'Julien'),
(17, 1, 16, 'c\'est moi J G', 'Grad', 'Julien'),
(18, 2, 16, 'Test de J G', 'Grad', 'Julien'),
(19, 1, 14, 'Ceci est un test', 'Bob', 'bob');

-- --------------------------------------------------------

--
-- Structure de la table `entreprises`
--

CREATE TABLE `entreprises` (
  `id` int(11) NOT NULL,
  `nom` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `ville` varchar(100) NOT NULL,
  `code_postal` varchar(10) NOT NULL,
  `logo` varchar(255) DEFAULT NULL,
  `email` varchar(255) NOT NULL,
  `contact` varchar(255) NOT NULL,
  `nombre_offres` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `entreprises`
--

INSERT INTO `entreprises` (`id`, `nom`, `description`, `ville`, `code_postal`, `logo`, `email`, `contact`, `nombre_offres`) VALUES
(1, 'Google', 'Entreprise technologique', 'Paris', '75000', 'google.png', '', '', NULL),
(2, 'Microsoft', 'Développement de logiciels', 'Lyon', '69000', 'microsoft.png', 'microsoft@gmail.com', 'microsoft', NULL),
(3, 'Amazon', 'Commerce en ligne', 'Marseille', '13000', 'amazon.png', '', '', NULL),
(4, 'Tesla', 'Fabricant de voitures électriques', 'Bordeaux', '33000', 'tesla.png', '', '', NULL),
(18, 'Lego', 'Lego', 'Lille', '59000', 'LEGO_logo.svg.png', '', '', NULL),
(19, 'jfjfjs', 'ss', 'ss', 'ss', '', '', '', NULL),
(20, 'aaaaaaaaaaaaa', 'QSDSFDGFHGJJ.K/', 'Tours', '37000', 'tomates-fraiches_1053-566.jpeg', '', '', NULL),
(21, 'DFSF', 'FDSFSF', 'FDSFS', '2300', '', '', '', NULL),
(22, 'Bien', 'qqch', 'Paris', '59000', '', '', '', NULL),
(23, 'fdsf', 'dfs', 'dfsf', '12340', '', '', '', NULL),
(24, 'aaaaaaaaaaaaa', 'FHJFJHF', 'BB?', '12309', '', '', '', NULL);

-- --------------------------------------------------------

--
-- Structure de la table `evaluations`
--

CREATE TABLE `evaluations` (
  `id` int(11) NOT NULL,
  `entreprise_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `rating` float DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `evaluations`
--

INSERT INTO `evaluations` (`id`, `entreprise_id`, `user_id`, `rating`) VALUES
(9, 1, 0, 2),
(10, 1, 0, 5),
(11, 1, 0, 1),
(12, 1, 0, 3.5),
(13, 1, 0, 2),
(14, 1, 0, 5),
(15, 1, 0, 4),
(16, 1, 0, 2),
(17, 1, 0, 5),
(18, 1, 0, 5),
(19, 1, 0, 5),
(20, 1, 0, 5),
(21, 1, 0, 5),
(22, 1, 0, 5),
(23, 1, 0, 5),
(24, 1, 0, 5),
(25, 1, 0, 5),
(26, 1, 0, 5),
(27, 1, 0, 5),
(28, 1, 0, 1),
(29, 1, 0, 2),
(30, 1, NULL, 2),
(31, 1, NULL, 3),
(32, 1, NULL, 5),
(33, 1, NULL, 4),
(34, 1, NULL, 3),
(35, 1, 14, 4),
(36, 1, 14, 5);

-- --------------------------------------------------------

--
-- Structure de la table `inscription`
--

CREATE TABLE `inscription` (
  `id` int(11) NOT NULL,
  `nom` varchar(100) NOT NULL,
  `prenom` varchar(100) NOT NULL,
  `email` varchar(255) NOT NULL,
  `telephone` varchar(20) NOT NULL,
  `date_de_naissance` date NOT NULL,
  `genre` enum('Homme','Femme','Autre') NOT NULL,
  `mot_de_passe` varchar(255) NOT NULL,
  `date_inscription` timestamp NOT NULL DEFAULT current_timestamp(),
  `duree_stage` varchar(255) DEFAULT NULL,
  `localite` varchar(255) DEFAULT NULL,
  `cv_path` varchar(255) DEFAULT NULL,
  `role` enum('utilisateur','pilote','admin') NOT NULL DEFAULT 'utilisateur'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `inscription`
--

INSERT INTO `inscription` (`id`, `nom`, `prenom`, `email`, `telephone`, `date_de_naissance`, `genre`, `mot_de_passe`, `date_inscription`, `duree_stage`, `localite`, `cv_path`, `role`) VALUES
(14, 'Bob', 'bob', 'bob@bob.bob', '0787263893', '2005-12-21', 'Homme', '$2y$10$Mv85EjZxkZbmqjNzDkk9U.xPMhWQnlPMfzLHDlQwVdj./m9mi4IiG', '2025-04-02 13:54:00', '1', 'Lille', 'C:\\xampp\\htdocs\\StageAire/uploads/marteel-theo-2434188-projet-web-backend-cctl-20250324-171632.pdf', 'utilisateur'),
(15, 'Benali', 'Smail', 'benali.smail@viacesi.fr', '0782938272', '2222-12-21', 'Homme', '$2y$10$T5wipZ4a0hyn4k8.PL0q9uZbFiGoKvjI/hpvhV2w0FcqPH/t7x2le', '2025-04-02 13:55:05', '1', 'Lille', 'C:\\xampp\\htdocs\\StageAire/uploads/marteel-theo-2434188-projet-web-backend-cctl-20250324-171632.pdf', 'pilote'),
(16, 'Grad', 'Julien', 'juliengrad@gmail.com', '0782937564', '2025-03-22', 'Homme', '$2y$10$QLff4jThkQZPDQWoHr1NK.1Kg0gQj7lBwv6OI58AmPpjP9RmlMF0W', '2025-04-02 13:56:52', NULL, NULL, NULL, 'admin'),
(17, 'aaaaaaaaaaaaa', 'DSFS', 'DSFF@gmail.com', '000000', '2025-04-09', 'Homme', '$2y$10$9p5yRsc3vfkm1dZiLM9M2u1rAklbi3VSYlDMFvRuECJ08FNOQsHYO', '2025-04-03 09:24:33', NULL, NULL, NULL, 'utilisateur');

-- --------------------------------------------------------

--
-- Structure de la table `offres`
--

CREATE TABLE `offres` (
  `id` int(11) NOT NULL,
  `entreprise_id` int(11) NOT NULL,
  `titre` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `date_publication` date DEFAULT NULL,
  `entreprise` varchar(255) DEFAULT NULL,
  `competence` varchar(255) NOT NULL,
  `localisation` varchar(255) NOT NULL,
  `remuneration` decimal(10,2) NOT NULL,
  `duree_offre` int(11) NOT NULL,
  `logo` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `offres`
--

INSERT INTO `offres` (`id`, `entreprise_id`, `titre`, `description`, `date_publication`, `entreprise`, `competence`, `localisation`, `remuneration`, `duree_offre`, `logo`) VALUES
(3, 1, 'Développeur Web Junior', 'Nous recherchons un développeur web junior pour rejoindre notre équipe dynamique et participer au développement de notre site e-commerce.', '2025-04-03', 'TechSolutions', 'HTML, CSS, JavaScript, PHP', 'Paris', 2500.00, 6, 'techsolutions_logo.jpg');

-- --------------------------------------------------------

--
-- Structure de la table `offre_stage`
--

CREATE TABLE `offre_stage` (
  `ID_offre` int(11) NOT NULL,
  `Lieu` text DEFAULT NULL,
  `Titre_offre` varchar(100) DEFAULT NULL,
  `Description_offre` varchar(3000) DEFAULT NULL,
  `Compétences` varchar(200) DEFAULT NULL,
  `Base_rémunération` int(11) DEFAULT NULL,
  `Date_offre` varchar(100) DEFAULT NULL,
  `ID_Offre_1` varchar(50) NOT NULL,
  `Code_postal` int(11) NOT NULL,
  `ID_entreprise` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `stats_entreprise`
--

CREATE TABLE `stats_entreprise` (
  `ID_entreprise` varchar(50) NOT NULL,
  `Moyenne_évaluation` decimal(15,2) DEFAULT NULL,
  `Nombre_evaluations` int(11) DEFAULT NULL,
  `Commentaires` varchar(1000) DEFAULT NULL,
  `Nb_consultations` int(11) DEFAULT NULL,
  `Nb_Stage_Dispo` int(11) DEFAULT NULL,
  `Nb_Candidatures_30J` int(11) DEFAULT NULL,
  `Nb_stagiaires_postulés` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `stats_offre`
--

CREATE TABLE `stats_offre` (
  `ID_Offre` varchar(50) NOT NULL,
  `Date_apparition` date DEFAULT NULL,
  `Nb_Etudiant_Postulés` int(11) DEFAULT NULL,
  `Nb_consultations` int(11) DEFAULT NULL,
  `Nb_stage_dispo` int(11) DEFAULT NULL,
  `Nb_Candidatures_30J` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `utilisateurs`
--

CREATE TABLE `utilisateurs` (
  `id` int(11) NOT NULL,
  `nom` varchar(100) NOT NULL,
  `prenom` varchar(100) NOT NULL,
  `email` varchar(255) NOT NULL,
  `telephone` varchar(20) NOT NULL,
  `date_de_naissance` date NOT NULL,
  `genre` enum('Homme','Femme','Autre') NOT NULL,
  `mot_de_passe` varchar(255) NOT NULL,
  `date_inscription` timestamp NOT NULL DEFAULT current_timestamp(),
  `duree_stage` varchar(255) DEFAULT NULL,
  `localite` varchar(255) DEFAULT NULL,
  `cv_path` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `utilisateurs`
--

INSERT INTO `utilisateurs` (`id`, `nom`, `prenom`, `email`, `telephone`, `date_de_naissance`, `genre`, `mot_de_passe`, `date_inscription`, `duree_stage`, `localite`, `cv_path`) VALUES
(19, 'GOGOOOOOO', 'gogo', 'eeeee@gmail.com', '0634684865', '2025-04-10', 'Homme', '$2y$10$/MyHDZjfj3EvKzrES0Z8rebyODT1EuhLSyJy60jArxvbI1CRGv2qK', '2025-04-01 09:20:44', '1', '1', 'C:\\xampp\\htdocs\\Projet---Bloc-Web/uploads/Dossier_Synthese_Gauthier_SMIGIELSKI_CPI A2 Informatique 24-25 Lille_Semestre_3 (4).PDF'),
(20, 'eliott', 'eliott', 'eliott@gmail.com', '0634684865', '2025-04-26', 'Homme', '$2y$10$3TL/NHdcE1z24nPCVJFsIO.IVN.hfjftU5i5F8t7UuAG1oebnko2S', '2025-04-01 09:30:59', NULL, NULL, NULL),
(21, 'neuille', 'neuille@gmail.com', 'neuille@gmail.com', '0634684865', '2025-04-19', 'Homme', '$2y$10$wmNEpFfrxfi/fOcS0Atx.ORrEqh5WH6DKpC7Y//cWUvSsgxjycQn2', '2025-04-02 13:34:51', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Structure de la table `villes`
--

CREATE TABLE `villes` (
  `Code_postal` int(11) NOT NULL,
  `Nom_ville` varchar(50) DEFAULT NULL,
  `Département` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `wishlist`
--

CREATE TABLE `wishlist` (
  `id` int(11) NOT NULL,
  `entreprise_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `wishlist`
--

INSERT INTO `wishlist` (`id`, `entreprise_id`, `user_id`) VALUES
(3, NULL, 16),
(18, 1, 14),
(19, 18, 14);

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `commentaires`
--
ALTER TABLE `commentaires`
  ADD PRIMARY KEY (`id`),
  ADD KEY `entreprise_id` (`entreprise_id`);

--
-- Index pour la table `entreprises`
--
ALTER TABLE `entreprises`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `evaluations`
--
ALTER TABLE `evaluations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `entreprise_id` (`entreprise_id`);

--
-- Index pour la table `inscription`
--
ALTER TABLE `inscription`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `offres`
--
ALTER TABLE `offres`
  ADD PRIMARY KEY (`id`),
  ADD KEY `entreprise_id` (`entreprise_id`);

--
-- Index pour la table `offre_stage`
--
ALTER TABLE `offre_stage`
  ADD PRIMARY KEY (`ID_offre`),
  ADD UNIQUE KEY `ID_Offre_1` (`ID_Offre_1`),
  ADD KEY `Code_postal` (`Code_postal`),
  ADD KEY `ID_entreprise` (`ID_entreprise`);

--
-- Index pour la table `stats_entreprise`
--
ALTER TABLE `stats_entreprise`
  ADD PRIMARY KEY (`ID_entreprise`);

--
-- Index pour la table `stats_offre`
--
ALTER TABLE `stats_offre`
  ADD PRIMARY KEY (`ID_Offre`);

--
-- Index pour la table `utilisateurs`
--
ALTER TABLE `utilisateurs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Index pour la table `villes`
--
ALTER TABLE `villes`
  ADD PRIMARY KEY (`Code_postal`);

--
-- Index pour la table `wishlist`
--
ALTER TABLE `wishlist`
  ADD PRIMARY KEY (`id`),
  ADD KEY `entreprise_id` (`entreprise_id`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `commentaires`
--
ALTER TABLE `commentaires`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT pour la table `entreprises`
--
ALTER TABLE `entreprises`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT pour la table `evaluations`
--
ALTER TABLE `evaluations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT pour la table `inscription`
--
ALTER TABLE `inscription`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT pour la table `offres`
--
ALTER TABLE `offres`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT pour la table `utilisateurs`
--
ALTER TABLE `utilisateurs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT pour la table `wishlist`
--
ALTER TABLE `wishlist`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `commentaires`
--
ALTER TABLE `commentaires`
  ADD CONSTRAINT `commentaires_ibfk_1` FOREIGN KEY (`entreprise_id`) REFERENCES `entreprises` (`id`);

--
-- Contraintes pour la table `evaluations`
--
ALTER TABLE `evaluations`
  ADD CONSTRAINT `evaluations_ibfk_1` FOREIGN KEY (`entreprise_id`) REFERENCES `entreprises` (`id`);

--
-- Contraintes pour la table `offres`
--
ALTER TABLE `offres`
  ADD CONSTRAINT `offres_ibfk_1` FOREIGN KEY (`entreprise_id`) REFERENCES `entreprises` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
