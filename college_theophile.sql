-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : localhost
-- Généré le : jeu. 21 mai 2026 à 15:08
-- Version du serveur : 11.8.6-MariaDB-6 from Debian
-- Version de PHP : 8.4.20

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `college_theophile`
--

-- --------------------------------------------------------

--
-- Structure de la table `administrateurs`
--

CREATE TABLE `administrateurs` (
  `id` int(11) NOT NULL,
  `nom` varchar(50) NOT NULL,
  `prenom` varchar(50) NOT NULL,
  `postnom` varchar(50) DEFAULT NULL,
  `email` varchar(100) NOT NULL,
  `telephone` varchar(20) DEFAULT NULL,
  `sexe` enum('Masculin','Féminin') DEFAULT NULL,
  `mot_de_passe` varchar(255) NOT NULL,
  `role` enum('super_admin','admin') DEFAULT 'admin',
  `date_creation` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

--
-- Déchargement des données de la table `administrateurs`
--

INSERT INTO `administrateurs` (`id`, `nom`, `prenom`, `postnom`, `email`, `telephone`, `sexe`, `mot_de_passe`, `role`, `date_creation`) VALUES
(3, 'Admin', 'Super', NULL, 'admin@college.com', NULL, NULL, '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'super_admin', '2026-05-21 09:20:50'),
(4, 'Kabengele', 'Sylvain', 'Mudingay', 'elmajor007@gmail.fr', '0096756776', 'Masculin', '$2y$12$Ss.HCjoG9KOypY7CyIuzduqiUcYQvUCEM5xlAgBCVnL0Nj3pT3BKO', 'admin', '2026-05-21 12:43:37');

-- --------------------------------------------------------

--
-- Structure de la table `eleves`
--

CREATE TABLE `eleves` (
  `id` int(11) NOT NULL,
  `nom` varchar(50) NOT NULL,
  `prenom` varchar(50) NOT NULL,
  `postnom` varchar(50) DEFAULT NULL,
  `email` varchar(30) NOT NULL,
  `date_naissance` date DEFAULT NULL,
  `lieu_naissance` varchar(100) DEFAULT NULL,
  `sexe` enum('M','F') DEFAULT NULL,
  `nationalite` varchar(50) DEFAULT NULL,
  `province_origine` varchar(100) DEFAULT NULL,
  `adresse_complete` text DEFAULT NULL,
  `telephone_eleve` varchar(20) DEFAULT NULL,
  `telephone_tuteur` varchar(20) DEFAULT NULL,
  `nom_pere` varchar(100) DEFAULT NULL,
  `nom_mere` varchar(100) DEFAULT NULL,
  `tuteurs` varchar(100) DEFAULT NULL,
  `classe_actuelle` varchar(20) DEFAULT NULL,
  `option_souhaitee` varchar(50) DEFAULT NULL,
  `statut_inscription` enum('en_attente','admis','non_admis') DEFAULT 'en_attente',
  `photo` varchar(255) DEFAULT NULL,
  `date_inscription` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

--
-- Déchargement des données de la table `eleves`
--

INSERT INTO `eleves` (`id`, `nom`, `prenom`, `postnom`, `email`, `date_naissance`, `lieu_naissance`, `sexe`, `nationalite`, `province_origine`, `adresse_complete`, `telephone_eleve`, `telephone_tuteur`, `nom_pere`, `nom_mere`, `tuteurs`, `classe_actuelle`, `option_souhaitee`, `statut_inscription`, `photo`, `date_inscription`) VALUES
(1, 'Kibambi', 'Russel', 'Nyembo', 'russelkibambi5@gmail.com', '2006-04-30', 'Kinshasa', 'M', 'Congolaise', 'Kinshasa', 'Home Unikin', '0974080082', '0974080082', 'Kibambi', 'Nyembo', NULL, '4 eme', 'Commerciale', 'admis', NULL, '2026-05-21 00:58:31'),
(2, 'Kabangu', 'Bradel', 'Eyato', 'kabangu@gmail.com', '2005-11-22', 'Kinshasa', 'M', 'Congolaise', 'Kinshasa', 'Home Lemba Unikin', '0974080082', '0974080082', 'Eyato', 'Maman', 'Papa', '4 eme', 'Pedagogie', 'admis', 'uploads/6a0e66fe51d2e.jpg', '2026-05-21 01:59:26'),
(3, 'Mutombo', 'Leonard', 'Phoenix', 'madmax@gmail.com', '2004-04-29', 'Kinshasa', 'M', 'Congolaise', 'Kinshasa', 'Kinshasa RDC', '0974594778', '0974080882', 'Phoenix', 'Margarette', 'Mr. Phoeinx', '4 eme', 'Electtronique', 'admis', 'uploads/6a0ec69730f40.', '2026-05-21 08:47:19'),
(4, 'Mutotodi', 'Agree', 'Sakuaku', 'agreemutotodi32@gmail.com', '2003-11-22', 'Kimpese', 'M', 'Congolaise', 'Kongo-Central', 'kinshasa, Ngiri-Ngiri', '0850107100', '0850107100', 'Mutotodi', 'Mutotodi', 'Mr. Mutotodi', '5 eme', 'Electronique', 'admis', 'uploads/6a0eeb6a04161.jpg', '2026-05-21 11:24:26'),
(5, 'Kambale', 'Leonnie', 'Kambale', 'hostname@gmail.fr', '2014-06-10', 'Kisantu', 'F', 'Congolaise', 'Kongo-Central', 'Kinshasa RDC', '0850107155', '0850107386', 'Kambale', 'Kimba', 'Mr. Kambale', '7 eme', 'Mecanique', 'non_admis', 'uploads/6a0efcde404bf.jpg', '2026-05-21 12:38:54'),
(6, 'Angesaka', 'Winner', 'Winner', 'angesaka@gmail.com', '2012-03-05', 'Kinshasa', 'M', '', '', '', '', '', '', '', '', '6eme', 'Math-Physique', 'admis', NULL, '2026-05-21 13:08:37');

-- --------------------------------------------------------

--
-- Structure de la table `messages`
--

CREATE TABLE `messages` (
  `id` int(11) NOT NULL,
  `nom_complet` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `service` varchar(50) DEFAULT NULL,
  `message` text NOT NULL,
  `date_envoi` timestamp NULL DEFAULT current_timestamp(),
  `lu` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

--
-- Déchargement des données de la table `messages`
--

INSERT INTO `messages` (`id`, `nom_complet`, `email`, `service`, `message`, `date_envoi`, `lu`) VALUES
(1, 'Makutano', 'madomax@gmail.com', 'inscription', 'Bonsoir', '2026-05-21 14:20:43', 1);

-- --------------------------------------------------------

--
-- Structure de la table `resultats`
--

CREATE TABLE `resultats` (
  `id` int(11) NOT NULL,
  `eleve_id` int(11) DEFAULT NULL,
  `trimestre` tinyint(4) DEFAULT NULL,
  `moyenne` decimal(5,2) DEFAULT NULL,
  `decision` enum('Admis','Non admis') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `administrateurs`
--
ALTER TABLE `administrateurs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Index pour la table `eleves`
--
ALTER TABLE `eleves`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `resultats`
--
ALTER TABLE `resultats`
  ADD PRIMARY KEY (`id`),
  ADD KEY `eleve_id` (`eleve_id`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `administrateurs`
--
ALTER TABLE `administrateurs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT pour la table `eleves`
--
ALTER TABLE `eleves`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT pour la table `messages`
--
ALTER TABLE `messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT pour la table `resultats`
--
ALTER TABLE `resultats`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `resultats`
--
ALTER TABLE `resultats`
  ADD CONSTRAINT `resultats_ibfk_1` FOREIGN KEY (`eleve_id`) REFERENCES `eleves` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
