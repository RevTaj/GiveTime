-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : lun. 17 mars 2025 à 11:29
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
-- Base de données : `bdd_givetime`
--

-- --------------------------------------------------------

--
-- Structure de la table `gt_admin`
--

CREATE TABLE `gt_admin` (
  `id_admin` smallint(10) UNSIGNED NOT NULL,
  `nomAdmin` varchar(50) NOT NULL,
  `prenomAdmin` varchar(50) NOT NULL,
  `mailAdmin` varchar(20) NOT NULL,
  `loginAdmin` varchar(50) NOT NULL,
  `mdpAdmin` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `gt_admin`
--

INSERT INTO `gt_admin` (`id_admin`, `nomAdmin`, `prenomAdmin`, `mailAdmin`, `loginAdmin`, `mdpAdmin`) VALUES
(1, 'Pottin', 'Jarode', 'admin@gmail.com', 'pottijar', '7110eda4d09e062aa5e4a390b0a572ac0d2c0220'),
(2, 'Revelle', 'Sacha', 'Sachaadmin@gmail.com', 'revelsac', '8a51851ae82a470fe9a89c52a7fb0fb8c8dda544');

-- --------------------------------------------------------

--
-- Structure de la table `gt_association`
--

CREATE TABLE `gt_association` (
  `id_Association` smallint(10) UNSIGNED NOT NULL,
  `nomAssociation` varchar(50) NOT NULL,
  `prenomAssociation` varchar(50) NOT NULL,
  `mailAssociation` varchar(50) NOT NULL,
  `loginAssociation` varchar(50) NOT NULL,
  `mdpAssociation` varchar(255) NOT NULL,
  `tag` enum('Solidarite','Ecologie','Culture','Sport','Education') DEFAULT NULL,
  `localisation` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `gt_association`
--

INSERT INTO `gt_association` (`id_Association`, `nomAssociation`, `prenomAssociation`, `mailAssociation`, `loginAssociation`, `mdpAssociation`, `tag`, `localisation`, `description`) VALUES
(10, 'zebi', 'loula', 'loulazebi@gmail.com', 'test', '7110eda4d09e062aa5e4a390b0a572ac0d2c0220', 'Solidarite', 'Rouen', 'Association axée sur la Solidarité');

-- --------------------------------------------------------

--
-- Structure de la table `gt_benevole`
--

CREATE TABLE `gt_benevole` (
  `id_benevole` smallint(10) UNSIGNED NOT NULL,
  `nomBenevole` varchar(50) NOT NULL,
  `prenomBenevole` varchar(50) NOT NULL,
  `mailBenevole` varchar(50) NOT NULL,
  `loginBenevole` varchar(50) NOT NULL,
  `mdpBenevole` varchar(255) NOT NULL,
  `tag` enum('Solidarite','Ecologie','Culture','Sport','Education') DEFAULT NULL,
  `localisation` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `gt_benevole`
--

INSERT INTO `gt_benevole` (`id_benevole`, `nomBenevole`, `prenomBenevole`, `mailBenevole`, `loginBenevole`, `mdpBenevole`, `tag`, `localisation`, `description`) VALUES
(7, 'Auchon', 'Paul', 'PaulAuchon@gmail.Com', 'PaulA', '7110eda4d09e062aa5e4a390b0a572ac0d2c0220', 'Culture', 'Paris', 'Benevole motivé...');

-- --------------------------------------------------------

--
-- Structure de la table `gt_inscription`
--

CREATE TABLE `gt_inscription` (
  `id_inscription` smallint(10) UNSIGNED NOT NULL,
  `posts_id` smallint(10) UNSIGNED NOT NULL,
  `benevole_id` smallint(10) UNSIGNED NOT NULL,
  `date_debut` date NOT NULL,
  `progression` float NOT NULL,
  `date_fin` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `gt_inscription`
--

INSERT INTO `gt_inscription` (`id_inscription`, `posts_id`, `benevole_id`, `date_debut`, `progression`, `date_fin`) VALUES
(7, 8, 7, '2025-03-17', 0, '2025-03-17'),
(8, 9, 7, '2025-03-17', 0, '2025-03-17');

-- --------------------------------------------------------

--
-- Structure de la table `gt_message`
--

CREATE TABLE `gt_message` (
  `id_message` smallint(10) NOT NULL,
  `id_posts` smallint(10) UNSIGNED NOT NULL,
  `date` date NOT NULL DEFAULT current_timestamp(),
  `contenu` text NOT NULL,
  `notification` enum('lu','non-lu','','') NOT NULL DEFAULT 'non-lu'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `gt_message`
--

INSERT INTO `gt_message` (`id_message`, `id_posts`, `date`, `contenu`, `notification`) VALUES
(1, 8, '2025-03-17', 'test', 'non-lu');

-- --------------------------------------------------------

--
-- Structure de la table `gt_posts`
--

CREATE TABLE `gt_posts` (
  `id_Posts` smallint(10) UNSIGNED NOT NULL,
  `TitrePosts` varchar(50) NOT NULL,
  `Association_id` smallint(10) UNSIGNED NOT NULL,
  `tag` varchar(50) NOT NULL,
  `Description` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `gt_posts`
--

INSERT INTO `gt_posts` (`id_Posts`, `TitrePosts`, `Association_id`, `tag`, `Description`) VALUES
(8, 'Compte Rendu du TD', 10, 'developpement', 'bonjour'),
(9, 'Mon article', 10, 'Jardin', 'petit article pour Gamevert');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `gt_admin`
--
ALTER TABLE `gt_admin`
  ADD PRIMARY KEY (`id_admin`);

--
-- Index pour la table `gt_association`
--
ALTER TABLE `gt_association`
  ADD PRIMARY KEY (`id_Association`);

--
-- Index pour la table `gt_benevole`
--
ALTER TABLE `gt_benevole`
  ADD PRIMARY KEY (`id_benevole`);

--
-- Index pour la table `gt_inscription`
--
ALTER TABLE `gt_inscription`
  ADD PRIMARY KEY (`id_inscription`),
  ADD KEY `benevole_id` (`benevole_id`),
  ADD KEY `posts_id` (`posts_id`);

--
-- Index pour la table `gt_message`
--
ALTER TABLE `gt_message`
  ADD PRIMARY KEY (`id_message`),
  ADD KEY `id_posts` (`id_posts`);

--
-- Index pour la table `gt_posts`
--
ALTER TABLE `gt_posts`
  ADD PRIMARY KEY (`id_Posts`),
  ADD KEY `Association_id` (`Association_id`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `gt_admin`
--
ALTER TABLE `gt_admin`
  MODIFY `id_admin` smallint(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT pour la table `gt_association`
--
ALTER TABLE `gt_association`
  MODIFY `id_Association` smallint(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT pour la table `gt_benevole`
--
ALTER TABLE `gt_benevole`
  MODIFY `id_benevole` smallint(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT pour la table `gt_inscription`
--
ALTER TABLE `gt_inscription`
  MODIFY `id_inscription` smallint(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT pour la table `gt_message`
--
ALTER TABLE `gt_message`
  MODIFY `id_message` smallint(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT pour la table `gt_posts`
--
ALTER TABLE `gt_posts`
  MODIFY `id_Posts` smallint(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `gt_inscription`
--
ALTER TABLE `gt_inscription`
  ADD CONSTRAINT `GT_Inscription_ibfk_1` FOREIGN KEY (`benevole_id`) REFERENCES `gt_benevole` (`id_benevole`),
  ADD CONSTRAINT `GT_Inscription_ibfk_2` FOREIGN KEY (`posts_id`) REFERENCES `gt_posts` (`id_Posts`);

--
-- Contraintes pour la table `gt_message`
--
ALTER TABLE `gt_message`
  ADD CONSTRAINT `gt_message_ibfk_1` FOREIGN KEY (`id_posts`) REFERENCES `gt_posts` (`id_Posts`) ON DELETE CASCADE;

--
-- Contraintes pour la table `gt_posts`
--
ALTER TABLE `gt_posts`
  ADD CONSTRAINT `GT_Posts_ibfk_1` FOREIGN KEY (`Association_id`) REFERENCES `gt_association` (`id_Association`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
