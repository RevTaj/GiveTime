-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : ven. 28 fév. 2025 à 10:57
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
-- Base de données : `give_time_db`
--

-- --------------------------------------------------------

--
-- Structure de la table `groupes`
--

CREATE TABLE `groupes` (
  `id_groupe` int(11) NOT NULL,
  `id_post` int(11) DEFAULT NULL,
  `id_message` int(11) DEFAULT NULL,
  `id_users` int(11) NOT NULL,
  `date_crea` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Déchargement des données de la table `groupes`
--

INSERT INTO `groupes` (`id_groupe`, `id_post`, `id_message`, `id_users`, `date_crea`) VALUES
(2, 1, 1, 1, '2025-02-28 09:20:54');

-- --------------------------------------------------------

--
-- Structure de la table `messages`
--

CREATE TABLE `messages` (
  `id_message` int(11) NOT NULL,
  `id_users` int(11) NOT NULL,
  `contenu` text DEFAULT NULL,
  `date_crea` datetime DEFAULT current_timestamp(),
  `id_groupe` int(11) DEFAULT NULL,
  `notification` enum('lu','non-lu') DEFAULT 'non-lu'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Déchargement des données de la table `messages`
--

INSERT INTO `messages` (`id_message`, `id_users`, `contenu`, `date_crea`, `id_groupe`, `notification`) VALUES
(1, 3, 'coucou je suis le message', '2025-02-28 09:19:10', 1, 'non-lu');

-- --------------------------------------------------------

--
-- Structure de la table `posts`
--

CREATE TABLE `posts` (
  `id_posts` int(11) NOT NULL,
  `id_users` int(11) NOT NULL,
  `content` text DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `titre` varchar(100) DEFAULT NULL,
  `id_groupe` int(11) DEFAULT NULL,
  `date_crea` datetime DEFAULT current_timestamp(),
  `skills` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Déchargement des données de la table `posts`
--

INSERT INTO `posts` (`id_posts`, `id_users`, `content`, `image`, `titre`, `id_groupe`, `date_crea`, `skills`) VALUES
(1, 3, 'nous cherchons du monde', NULL, 'recherches bénévoles', 1, '2025-02-28 09:20:39', 'saussice');

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

CREATE TABLE `users` (
  `id_users` int(11) NOT NULL,
  `nom` varchar(50) DEFAULT NULL,
  `prenom` varchar(50) DEFAULT NULL,
  `localisation` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `image_PP` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `type` enum('asso','bene','moderator') DEFAULT 'bene',
  `date_crea` datetime DEFAULT current_timestamp(),
  `skills` text DEFAULT NULL,
  `points` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Déchargement des données de la table `users`
--

INSERT INTO `users` (`id_users`, `nom`, `prenom`, `localisation`, `email`, `password`, `image_PP`, `description`, `type`, `date_crea`, `skills`, `points`) VALUES
(1, 'Auchon', 'Paul', 'France', 'paul.auchon@gmail.com', 'PA', NULL, 'desc', 'bene', '2025-02-28 09:13:39', 'saussice', 0),
(2, 'Anas', 'Anne', 'gouadeloupe', 'Anne.Anas@gmail.com', 'AA', NULL, 'ananas', 'bene', '2025-02-28 09:15:00', 'fruits', 0),
(3, 'lasso', '6', 'paris', 'lAssoSix@asso.fr', 'A6', NULL, 'ecologie', 'asso', '2025-02-28 09:16:50', 'saucisses', 0),
(4, 'asso', 'lution', 'lyon', 'lassoluction@gmail.com', 'AL', NULL, 'solutions', 'asso', '2025-02-28 09:17:40', 'programmes', 0);

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `groupes`
--
ALTER TABLE `groupes`
  ADD PRIMARY KEY (`id_groupe`),
  ADD KEY `id_post` (`id_post`),
  ADD KEY `id_message` (`id_message`),
  ADD KEY `id_users` (`id_users`);

--
-- Index pour la table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`id_message`),
  ADD KEY `id_users` (`id_users`);

--
-- Index pour la table `posts`
--
ALTER TABLE `posts`
  ADD PRIMARY KEY (`id_posts`),
  ADD KEY `id_users` (`id_users`);

--
-- Index pour la table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id_users`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `groupes`
--
ALTER TABLE `groupes`
  MODIFY `id_groupe` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT pour la table `messages`
--
ALTER TABLE `messages`
  MODIFY `id_message` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT pour la table `posts`
--
ALTER TABLE `posts`
  MODIFY `id_posts` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT pour la table `users`
--
ALTER TABLE `users`
  MODIFY `id_users` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `groupes`
--
ALTER TABLE `groupes`
  ADD CONSTRAINT `groupes_ibfk_1` FOREIGN KEY (`id_post`) REFERENCES `posts` (`id_posts`) ON DELETE SET NULL,
  ADD CONSTRAINT `groupes_ibfk_2` FOREIGN KEY (`id_message`) REFERENCES `messages` (`id_message`) ON DELETE SET NULL,
  ADD CONSTRAINT `groupes_ibfk_3` FOREIGN KEY (`id_users`) REFERENCES `users` (`id_users`) ON DELETE CASCADE;

--
-- Contraintes pour la table `messages`
--
ALTER TABLE `messages`
  ADD CONSTRAINT `messages_ibfk_1` FOREIGN KEY (`id_users`) REFERENCES `users` (`id_users`) ON DELETE CASCADE;

--
-- Contraintes pour la table `posts`
--
ALTER TABLE `posts`
  ADD CONSTRAINT `posts_ibfk_1` FOREIGN KEY (`id_users`) REFERENCES `users` (`id_users`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
