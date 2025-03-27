-- phpMyAdmin SQL Dump
-- version 4.5.4.1
-- http://www.phpmyadmin.net
--
-- Client :  localhost
-- Généré le :  Jeu 27 Mars 2025 à 20:51
-- Version du serveur :  5.7.11
-- Version de PHP :  7.0.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données :  `bdd_givetime`
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Contenu de la table `gt_admin`
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
  `prenomAssociation` varchar(50) DEFAULT NULL,
  `mailAssociation` varchar(50) NOT NULL,
  `loginAssociation` varchar(50) NOT NULL,
  `mdpAssociation` varchar(255) NOT NULL,
  `tag` enum('Solidarite','Ecologie','Culture','Sport','Education') DEFAULT NULL,
  `localisation` varchar(255) DEFAULT NULL,
  `description` text,
  `image` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Contenu de la table `gt_association`
--

INSERT INTO `gt_association` (`id_Association`, `nomAssociation`, `prenomAssociation`, `mailAssociation`, `loginAssociation`, `mdpAssociation`, `tag`, `localisation`, `description`, `image`) VALUES
(10, 'zebi', 'loula', 'loulazebi@gmail.com', 'test', 'a94a8fe5ccb19ba61c4c0873d391e987982fbbd3', 'Solidarite', 'Rouen', 'Association axée sur la Solidarité', NULL),
(11, 'Cairo', NULL, 'ultrices@google.edu', 'Moon', 'a94a8fe5ccb19ba61c4c0873d391e987982fbbd3', 'Sport', 'Belfort', 'sodales purus, in molestie tortor nibh sit amet orci. Ut sagittis lobortis mauris. Suspendisse aliquet', NULL),
(12, 'Gavin', NULL, 'et.magnis@hotmail.edu', 'Cardenas', 'a94a8fe5ccb19ba61c4c0873d391e987982fbbd3', 'Sport', 'Limoges', 'vitae risus. Duis a mi fringilla mi lacinia mattis. Integer eu lacus. Quisque imperdiet, erat', NULL),
(13, 'Claire', NULL, 'purus.sapien.gravida@icloud.com', 'York', 'a94a8fe5ccb19ba61c4c0873d391e987982fbbd3', 'Culture', 'La Rochelle', 'neque vitae semper egestas, urna justo faucibus lectus, a sollicitudin orci sem eget massa. Suspendisse', NULL),
(14, 'Hasad', NULL, 'tempor@yahoo.ca', 'Brooks', 'a94a8fe5ccb19ba61c4c0873d391e987982fbbd3', 'Culture', 'Vierzon', 'Mauris blandit enim consequat purus. Maecenas libero est, congue a, aliquet vel, vulputate eu, odio.', NULL),
(15, 'Upton', NULL, 'nunc.sed@hotmail.edu', 'Leach', 'a94a8fe5ccb19ba61c4c0873d391e987982fbbd3', 'Solidarite', 'Brive-la-Gaillarde', 'risus. Nunc ac sem ut dolor dapibus gravida. Aliquam tincidunt, nunc ac mattis ornare, lectus', NULL),
(16, 'Cairo', NULL, 'proin@google.ca', 'Lynch', 'a94a8fe5ccb19ba61c4c0873d391e987982fbbd3', 'Education', 'Clermont-Ferrand', 'penatibus et magnis dis parturient montes, nascetur ridiculus mus. Proin vel arcu eu odio tristique', NULL),
(17, 'Daquan', NULL, 'laoreet.lectus@outlook.com', 'Ford', 'a94a8fe5ccb19ba61c4c0873d391e987982fbbd3', 'Solidarite', 'Mulhouse', 'consequat auctor, nunc nulla vulputate dui, nec tempus mauris erat eget ipsum. Suspendisse sagittis. Nullam', NULL),
(18, 'Kevyn', NULL, 'a.odio@google.com', 'Carlson', 'a94a8fe5ccb19ba61c4c0873d391e987982fbbd3', 'Culture', 'Châlons-en-Champagne', 'mollis dui, in sodales elit erat vitae risus. Duis a mi fringilla mi lacinia mattis.', NULL),
(19, 'Axel', NULL, 'libero.nec@yahoo.couk', 'Sheppard', 'a94a8fe5ccb19ba61c4c0873d391e987982fbbd3', 'Sport', 'Carcassonne', 'ligula. Aenean euismod mauris eu elit. Nulla facilisi. Sed neque. Sed eget lacus. Mauris non', NULL),
(20, 'Emery', NULL, 'amet.risus.donec@yahoo.couk', 'Massey', 'a94a8fe5ccb19ba61c4c0873d391e987982fbbd3', 'Ecologie', 'Vichy', 'est arcu ac orci. Ut semper pretium neque. Morbi quis urna. Nunc quis arcu vel', NULL),
(21, 'Donovan', NULL, 'nam.ac.nulla@aol.net', 'Price', 'a94a8fe5ccb19ba61c4c0873d391e987982fbbd3', 'Solidarite', 'Cherbourg-Octeville', 'ipsum non arcu. Vivamus sit amet risus. Donec egestas. Aliquam nec enim. Nunc ut erat.', NULL),
(22, 'Jena', NULL, 'nulla.semper@hotmail.edu', 'Mccarthy', 'a94a8fe5ccb19ba61c4c0873d391e987982fbbd3', 'Education', 'Nancy', 'mauris sit amet lorem semper auctor. Mauris vel turpis. Aliquam adipiscing lobortis risus. In mi', NULL),
(23, 'Cody', NULL, 'sed.malesuada@hotmail.ca', 'Snider', 'a94a8fe5ccb19ba61c4c0873d391e987982fbbd3', 'Solidarite', 'Auxerre', 'mus. Donec dignissim magna a tortor. Nunc commodo auctor velit. Aliquam nisl. Nulla eu neque', NULL),
(24, 'Sarah', NULL, 'iaculis.nec@icloud.ca', 'Montgomery', 'a94a8fe5ccb19ba61c4c0873d391e987982fbbd3', 'Culture', 'Lorient', 'pede sagittis augue, eu tempor erat neque non quam. Pellentesque habitant morbi tristique senectus et', NULL),
(25, 'Noah', NULL, 'et.nunc@icloud.org', 'Nelson', 'a94a8fe5ccb19ba61c4c0873d391e987982fbbd3', 'Culture', 'Courbevoie', 'Quisque varius. Nam porttitor scelerisque neque. Nullam nisl. Maecenas malesuada fringilla est. Mauris eu turpis.', NULL),
(26, 'Uriah', NULL, 'tempus@outlook.com', 'Montoya', 'a94a8fe5ccb19ba61c4c0873d391e987982fbbd3', 'Solidarite', 'Lorient', 'fringilla. Donec feugiat metus sit amet ante. Vivamus non lorem vitae odio sagittis semper. Nam', NULL),
(27, 'Chester', NULL, 'sed.neque.sed@google.com', 'Nolan', 'a94a8fe5ccb19ba61c4c0873d391e987982fbbd3', 'Ecologie', 'Aix-en-Provence', 'Quisque varius. Nam porttitor scelerisque neque. Nullam nisl. Maecenas malesuada fringilla est. Mauris eu turpis.', NULL),
(28, 'Portia', NULL, 'id.mollis@yahoo.edu', 'Alvarado', 'a94a8fe5ccb19ba61c4c0873d391e987982fbbd3', 'Solidarite', 'Nevers', 'arcu imperdiet ullamcorper. Duis at lacus. Quisque purus sapien, gravida non, sollicitudin a, malesuada id,', NULL),
(29, 'Penelope', NULL, 'lacus.quisque@google.couk', 'Norton', 'a94a8fe5ccb19ba61c4c0873d391e987982fbbd3', 'Solidarite', 'Forbach', 'semper rutrum. Fusce dolor quam, elementum at, egestas a, scelerisque sed, sapien. Nunc pulvinar arcu', NULL),
(30, 'Shellie', NULL, 'suspendisse.aliquet@outlook.net', 'Hunt', 'a94a8fe5ccb19ba61c4c0873d391e987982fbbd3', 'Ecologie', 'Istres', 'nisi magna sed dui. Fusce aliquam, enim nec tempus scelerisque, lorem ipsum sodales purus, in', NULL),
(31, 'Rae', NULL, 'lorem.luctus@google.net', 'Newman', 'a94a8fe5ccb19ba61c4c0873d391e987982fbbd3', 'Ecologie', 'Saint-Médard-en-Jalles', 'dolor sit amet, consectetuer adipiscing elit. Etiam laoreet, libero et tristique pellentesque, tellus sem mollis', NULL),
(32, 'Ariel', NULL, 'at@google.edu', 'Hogan', 'a94a8fe5ccb19ba61c4c0873d391e987982fbbd3', 'Education', 'Lisieux', 'nibh. Phasellus nulla. Integer vulputate, risus a ultricies adipiscing, enim mi tempor lorem, eget mollis', NULL),
(33, 'Griffith', NULL, 'mauris.sapien@protonmail.net', 'Woodward', 'a94a8fe5ccb19ba61c4c0873d391e987982fbbd3', 'Education', 'Bayonne', 'Nulla aliquet. Proin velit. Sed malesuada augue ut lacus. Nulla tincidunt, neque vitae semper egestas,', NULL),
(34, 'Victor', NULL, 'commodo.ipsum.suspendisse@aol.org', 'Oliver', 'a94a8fe5ccb19ba61c4c0873d391e987982fbbd3', 'Solidarite', 'Saint-Malo', 'neque. Morbi quis urna. Nunc quis arcu vel quam dignissim pharetra. Nam ac nulla. In', NULL),
(35, 'Macaulay', NULL, 'quis.massa.mauris@yahoo.net', 'Sutton', 'a94a8fe5ccb19ba61c4c0873d391e987982fbbd3', 'Solidarite', 'Saint-Lô', 'et libero. Proin mi. Aliquam gravida mauris ut mi. Duis risus odio, auctor vitae, aliquet', NULL),
(36, 'Lara', NULL, 'ac.fermentum.vel@hotmail.net', 'Steele', 'a94a8fe5ccb19ba61c4c0873d391e987982fbbd3', 'Solidarite', 'Laval', 'at arcu. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae', NULL),
(37, 'Ora', NULL, 'vitae.nibh@icloud.ca', 'Flores', 'a94a8fe5ccb19ba61c4c0873d391e987982fbbd3', 'Ecologie', 'Tarbes', 'sed, hendrerit a, arcu. Sed et libero. Proin mi. Aliquam gravida mauris ut mi. Duis', NULL),
(38, 'Burke', NULL, 'pede@outlook.org', 'Holloway', 'a94a8fe5ccb19ba61c4c0873d391e987982fbbd3', 'Culture', 'Sotteville-lès-Rouen', 'ad litora torquent per conubia nostra, per inceptos hymenaeos. Mauris ut quam vel sapien imperdiet', NULL),
(39, 'Lamar', NULL, 'quam.elementum@icloud.net', 'Blackwell', 'a94a8fe5ccb19ba61c4c0873d391e987982fbbd3', 'Sport', 'Rouen', 'sagittis lobortis mauris. Suspendisse aliquet molestie tellus. Aenean egestas hendrerit neque. In ornare sagittis felis.', NULL),
(40, 'Porter', NULL, 'conubia.nostra.per@outlook.ca', 'Molina', 'a94a8fe5ccb19ba61c4c0873d391e987982fbbd3', 'Culture', 'Aix-en-Provence', 'libero. Integer in magna. Phasellus dolor elit, pellentesque a, facilisis non, bibendum sed, est. Nunc', NULL),
(41, 'Wylie', NULL, 'non@yahoo.com', 'Holmes', 'a94a8fe5ccb19ba61c4c0873d391e987982fbbd3', 'Culture', 'Épernay', 'ac mi eleifend egestas. Sed pharetra, felis eget varius ultrices, mauris ipsum porta elit, a', NULL),
(42, 'Eleanor', NULL, 'accumsan.laoreet@yahoo.com', 'Levine', 'a94a8fe5ccb19ba61c4c0873d391e987982fbbd3', 'Solidarite', 'Angoulême', 'et malesuada fames ac turpis egestas. Aliquam fringilla cursus purus. Nullam scelerisque neque sed sem', NULL),
(43, 'Jerome', NULL, 'fringilla@outlook.org', 'Blackburn', 'a94a8fe5ccb19ba61c4c0873d391e987982fbbd3', 'Culture', 'Auxerre', 'ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae Donec tincidunt. Donec', NULL),
(44, 'Ina', NULL, 'semper.cursus.integer@google.com', 'Strickland', 'a94a8fe5ccb19ba61c4c0873d391e987982fbbd3', 'Sport', 'Épinal', 'fermentum fermentum arcu. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia', NULL),
(45, 'Aimee', NULL, 'metus.aenean.sed@hotmail.net', 'Fowler', 'a94a8fe5ccb19ba61c4c0873d391e987982fbbd3', 'Sport', 'Lambersart', 'odio vel est tempor bibendum. Donec felis orci, adipiscing non, luctus sit amet, faucibus ut,', NULL),
(46, 'Meghan', NULL, 'in.hendrerit@yahoo.com', 'Cote', 'a94a8fe5ccb19ba61c4c0873d391e987982fbbd3', 'Ecologie', 'Aubagne', 'tempor, est ac mattis semper, dui lectus rutrum urna, nec luctus felis purus ac tellus.', NULL),
(47, 'Helen', NULL, 'curae@protonmail.couk', 'Peck', 'a94a8fe5ccb19ba61c4c0873d391e987982fbbd3', 'Culture', 'Colmar', 'enim consequat purus. Maecenas libero est, congue a, aliquet vel, vulputate eu, odio. Phasellus at', NULL),
(48, 'Keegan', NULL, 'faucibus.orci@google.org', 'Stephens', 'a94a8fe5ccb19ba61c4c0873d391e987982fbbd3', 'Ecologie', 'Tarbes', 'nunc sed libero. Proin sed turpis nec mauris blandit mattis. Cras eget nisi dictum augue', NULL),
(49, 'Autumn', NULL, 'nunc.quis@outlook.net', 'Page', 'a94a8fe5ccb19ba61c4c0873d391e987982fbbd3', 'Culture', 'Tournefeuille', 'nibh. Donec est mauris, rhoncus id, mollis nec, cursus a, enim. Suspendisse aliquet, sem ut', NULL),
(50, 'Peter', NULL, 'enim.mi@yahoo.couk', 'Galloway', 'a94a8fe5ccb19ba61c4c0873d391e987982fbbd3', 'Education', 'Amiens', 'felis. Donec tempor, est ac mattis semper, dui lectus rutrum urna, nec luctus felis purus', NULL),
(51, 'Dominic', NULL, 'pellentesque.habitant.morbi@google.com', 'Ayers', 'a94a8fe5ccb19ba61c4c0873d391e987982fbbd3', 'Education', 'Istres', 'venenatis lacus. Etiam bibendum fermentum metus. Aenean sed pede nec ante blandit viverra. Donec tempus,', NULL),
(52, 'Louis', NULL, 'neque.et@google.couk', 'Waters', 'a94a8fe5ccb19ba61c4c0873d391e987982fbbd3', 'Solidarite', 'Mont-de-Marsan', 'Integer vulputate, risus a ultricies adipiscing, enim mi tempor lorem, eget mollis lectus pede et', NULL),
(53, 'Jonas', NULL, 'justo.sit.amet@protonmail.net', 'Trujillo', 'a94a8fe5ccb19ba61c4c0873d391e987982fbbd3', 'Solidarite', 'Saint-Quentin', 'lobortis risus. In mi pede, nonummy ut, molestie in, tempus eu, ligula. Aenean euismod mauris', NULL),
(54, 'Ciaran', NULL, 'egestas.duis.ac@icloud.ca', 'Parks', 'a94a8fe5ccb19ba61c4c0873d391e987982fbbd3', 'Sport', 'Lens', 'magnis dis parturient montes, nascetur ridiculus mus. Proin vel arcu eu odio tristique pharetra. Quisque', NULL),
(55, 'Audrey', NULL, 'torquent.per@icloud.org', 'Dickson', 'a94a8fe5ccb19ba61c4c0873d391e987982fbbd3', 'Solidarite', 'Saint-Dizier', 'sapien imperdiet ornare. In faucibus. Morbi vehicula. Pellentesque tincidunt tempus risus. Donec egestas. Duis ac', NULL),
(56, 'Nomlanga', NULL, 'nec.luctus.felis@yahoo.org', 'Bond', 'a94a8fe5ccb19ba61c4c0873d391e987982fbbd3', 'Sport', 'Besançon', 'mauris elit, dictum eu, eleifend nec, malesuada ut, sem. Nulla interdum. Curabitur dictum. Phasellus in', NULL),
(57, 'Wynter', NULL, 'gravida@hotmail.net', 'Gallagher', 'a94a8fe5ccb19ba61c4c0873d391e987982fbbd3', 'Solidarite', 'Limoges', 'Donec tempus, lorem fringilla ornare placerat, orci lacus vestibulum lorem, sit amet ultricies sem magna', NULL),
(58, 'Harrison', NULL, 'posuere.cubilia@yahoo.com', 'Stark', 'a94a8fe5ccb19ba61c4c0873d391e987982fbbd3', 'Education', 'Quimper', 'pede, malesuada vel, venenatis vel, faucibus id, libero. Donec consectetuer mauris id sapien. Cras dolor', NULL),
(59, 'Kellie', NULL, 'sit.amet@hotmail.com', 'Meyer', 'a94a8fe5ccb19ba61c4c0873d391e987982fbbd3', 'Ecologie', 'Tours', 'arcu. Sed eu nibh vulputate mauris sagittis placerat. Cras dictum ultricies ligula. Nullam enim. Sed', NULL),
(60, 'Madison', NULL, 'facilisis@icloud.net', 'Mcknight', 'a94a8fe5ccb19ba61c4c0873d391e987982fbbd3', 'Solidarite', 'Rezé', 'Praesent eu dui. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus.', NULL),
(61, 'Irma', NULL, 'cras.sed.leo@aol.net', 'Guy', 'a94a8fe5ccb19ba61c4c0873d391e987982fbbd3', 'Education', 'Forbach', 'semper et, lacinia vitae, sodales at, velit. Pellentesque ultricies dignissim lacus. Aliquam rutrum lorem ac', NULL),
(62, 'Keiko', NULL, 'sit.amet@aol.com', 'Vazquez', 'a94a8fe5ccb19ba61c4c0873d391e987982fbbd3', 'Sport', 'Saintes', 'fermentum vel, mauris. Integer sem elit, pharetra ut, pharetra sed, hendrerit a, arcu. Sed et', NULL),
(63, 'Hilel', NULL, 'magna.a@outlook.couk', 'Potter', 'a94a8fe5ccb19ba61c4c0873d391e987982fbbd3', 'Education', 'Montigny-lès-Metz', 'sem magna nec quam. Curabitur vel lectus. Cum sociis natoque penatibus et magnis dis parturient', NULL),
(64, 'Charde', NULL, 'est@outlook.ca', 'Nielsen', 'a94a8fe5ccb19ba61c4c0873d391e987982fbbd3', 'Education', 'Colomiers', 'Quisque tincidunt pede ac urna. Ut tincidunt vehicula risus. Nulla eget metus eu erat semper', NULL),
(65, 'Fitzgerald', NULL, 'magna.cras@google.edu', 'Moreno', 'a94a8fe5ccb19ba61c4c0873d391e987982fbbd3', 'Sport', 'Saint-Nazaire', 'Duis sit amet diam eu dolor egestas rhoncus. Proin nisl sem, consequat nec, mollis vitae,', NULL),
(66, 'Alfonso', NULL, 'ac@yahoo.ca', 'Snow', 'a94a8fe5ccb19ba61c4c0873d391e987982fbbd3', 'Solidarite', 'Castres', 'pede nec ante blandit viverra. Donec tempus, lorem fringilla ornare placerat, orci lacus vestibulum lorem,', NULL),
(67, 'Cedric', NULL, 'ornare.tortor.at@outlook.org', 'Oneal', 'a94a8fe5ccb19ba61c4c0873d391e987982fbbd3', 'Education', 'Rodez', 'quis urna. Nunc quis arcu vel quam dignissim pharetra. Nam ac nulla. In tincidunt congue', NULL),
(68, 'Caryn', NULL, 'enim.gravida@google.ca', 'Hopkins', 'a94a8fe5ccb19ba61c4c0873d391e987982fbbd3', 'Sport', 'Châteauroux', 'non, cursus non, egestas a, dui. Cras pellentesque. Sed dictum. Proin eget odio. Aliquam vulputate', NULL),
(69, 'Wanda', NULL, 'mauris@aol.net', 'Graves', 'a94a8fe5ccb19ba61c4c0873d391e987982fbbd3', 'Sport', 'Poitiers', 'nibh dolor, nonummy ac, feugiat non, lobortis quis, pede. Suspendisse dui. Fusce diam nunc, ullamcorper', NULL),
(70, 'Stuart', NULL, 'aliquam@aol.org', 'Norris', 'a94a8fe5ccb19ba61c4c0873d391e987982fbbd3', 'Education', 'Thionville', 'lectus ante dictum mi, ac mattis velit justo nec ante. Maecenas mi felis, adipiscing fringilla,', NULL),
(71, 'Illiana', NULL, 'sem.elit.pharetra@aol.ca', 'Vincent', 'a94a8fe5ccb19ba61c4c0873d391e987982fbbd3', 'Solidarite', 'Nanterre', 'turpis. In condimentum. Donec at arcu. Vestibulum ante ipsum primis in faucibus orci luctus et', NULL),
(72, 'Jaden', NULL, 'facilisis@yahoo.net', 'Barrera', 'a94a8fe5ccb19ba61c4c0873d391e987982fbbd3', 'Sport', 'Béthune', 'ipsum sodales purus, in molestie tortor nibh sit amet orci. Ut sagittis lobortis mauris. Suspendisse', NULL),
(73, 'Ezra', NULL, 'a.enim@google.edu', 'Munoz', 'a94a8fe5ccb19ba61c4c0873d391e987982fbbd3', 'Ecologie', 'Calais', 'et, commodo at, libero. Morbi accumsan laoreet ipsum. Curabitur consequat, lectus sit amet luctus vulputate,', NULL),
(74, 'Hoyt', NULL, 'velit.eu@google.org', 'Avila', 'a94a8fe5ccb19ba61c4c0873d391e987982fbbd3', 'Culture', 'Limoges', 'lorem eu metus. In lorem. Donec elementum, lorem ut aliquam iaculis, lacus pede sagittis augue,', NULL),
(75, 'Aidan', NULL, 'eros@outlook.com', 'Velasquez', 'a94a8fe5ccb19ba61c4c0873d391e987982fbbd3', 'Ecologie', 'Marseille', 'sagittis. Nullam vitae diam. Proin dolor. Nulla semper tellus id nunc interdum feugiat. Sed nec', NULL),
(76, 'Simone', NULL, 'eu.accumsan@google.com', 'Nolan', 'a94a8fe5ccb19ba61c4c0873d391e987982fbbd3', 'Solidarite', 'Haguenau', 'mauris. Suspendisse aliquet molestie tellus. Aenean egestas hendrerit neque. In ornare sagittis felis. Donec tempor,', NULL),
(77, 'Nadine', NULL, 'felis.orci.adipiscing@hotmail.ca', 'Mitchell', 'a94a8fe5ccb19ba61c4c0873d391e987982fbbd3', 'Solidarite', 'Vandoeuvre-lès-Nancy', 'molestie. Sed id risus quis diam luctus lobortis. Class aptent taciti sociosqu ad litora torquent', NULL),
(78, 'Vance', NULL, 'urna@google.com', 'Cannon', 'a94a8fe5ccb19ba61c4c0873d391e987982fbbd3', 'Education', 'Cagnes-sur-Mer', 'senectus et netus et malesuada fames ac turpis egestas. Aliquam fringilla cursus purus. Nullam scelerisque', NULL),
(79, 'Simon', NULL, 'curabitur.dictum@aol.net', 'Riley', 'a94a8fe5ccb19ba61c4c0873d391e987982fbbd3', 'Education', 'Castres', 'lorem tristique aliquet. Phasellus fermentum convallis ligula. Donec luctus aliquet odio. Etiam ligula tortor, dictum', NULL),
(80, 'Kirby', NULL, 'aenean@outlook.edu', 'Alvarado', 'a94a8fe5ccb19ba61c4c0873d391e987982fbbd3', 'Culture', 'Épernay', 'tellus faucibus leo, in lobortis tellus justo sit amet nulla. Donec non justo. Proin non', NULL),
(81, 'Mariko', NULL, 'diam.duis@aol.couk', 'Wooten', 'a94a8fe5ccb19ba61c4c0873d391e987982fbbd3', 'Ecologie', 'Schiltigheim', 'ridiculus mus. Proin vel arcu eu odio tristique pharetra. Quisque ac libero nec ligula consectetuer', NULL),
(82, 'Brianna', NULL, 'adipiscing.elit@icloud.net', 'Schultz', 'a94a8fe5ccb19ba61c4c0873d391e987982fbbd3', 'Ecologie', 'Dijon', 'Sed congue, elit sed consequat auctor, nunc nulla vulputate dui, nec tempus mauris erat eget', NULL),
(83, 'Signe', NULL, 'sem.egestas@yahoo.org', 'Page', 'a94a8fe5ccb19ba61c4c0873d391e987982fbbd3', 'Sport', 'Limoges', 'In tincidunt congue turpis. In condimentum. Donec at arcu. Vestibulum ante ipsum primis in faucibus', NULL),
(84, 'Elvis', NULL, 'primis.in@yahoo.ca', 'Adams', 'a94a8fe5ccb19ba61c4c0873d391e987982fbbd3', 'Ecologie', 'Tarbes', 'magna. Phasellus dolor elit, pellentesque a, facilisis non, bibendum sed, est. Nunc laoreet lectus quis', NULL),
(85, 'Noelani', NULL, 'interdum.sed@icloud.ca', 'Bishop', 'a94a8fe5ccb19ba61c4c0873d391e987982fbbd3', 'Education', 'Belfort', 'penatibus et magnis dis parturient montes, nascetur ridiculus mus. Donec dignissim magna a tortor. Nunc', NULL),
(86, 'Chiquita', NULL, 'suscipit.nonummy@google.couk', 'Hernandez', 'a94a8fe5ccb19ba61c4c0873d391e987982fbbd3', 'Ecologie', 'Cherbourg-Octeville', 'dictum eleifend, nunc risus varius orci, in consequat enim diam vel arcu. Curabitur ut odio', NULL),
(87, 'Anjolie', NULL, 'duis@outlook.net', 'Walker', 'a94a8fe5ccb19ba61c4c0873d391e987982fbbd3', 'Culture', 'Dijon', 'libero. Integer in magna. Phasellus dolor elit, pellentesque a, facilisis non, bibendum sed, est. Nunc', NULL),
(88, 'Valentine', NULL, 'elit@outlook.com', 'Lawson', 'a94a8fe5ccb19ba61c4c0873d391e987982fbbd3', 'Culture', 'Bordeaux', 'vel turpis. Aliquam adipiscing lobortis risus. In mi pede, nonummy ut, molestie in, tempus eu,', NULL),
(89, 'Yuli', NULL, 'sodales.mauris@aol.org', 'Miller', 'a94a8fe5ccb19ba61c4c0873d391e987982fbbd3', 'Culture', 'Bastia', 'Nullam velit dui, semper et, lacinia vitae, sodales at, velit. Pellentesque ultricies dignissim lacus. Aliquam', NULL),
(90, 'Ross', NULL, 'et.magnis@google.com', 'Mccray', 'a94a8fe5ccb19ba61c4c0873d391e987982fbbd3', 'Solidarite', 'Saint-Médard-en-Jalles', 'lobortis, nisi nibh lacinia orci, consectetuer euismod est arcu ac orci. Ut semper pretium neque.', NULL),
(91, 'Eagan', NULL, 'nullam.scelerisque@yahoo.org', 'Vazquez', 'a94a8fe5ccb19ba61c4c0873d391e987982fbbd3', 'Culture', 'Alençon', 'tincidunt orci quis lectus. Nullam suscipit, est ac facilisis facilisis, magna tellus faucibus leo, in', NULL),
(92, 'Colt', NULL, 'vitae.erat@yahoo.org', 'Caldwell', 'a94a8fe5ccb19ba61c4c0873d391e987982fbbd3', 'Sport', 'Épinal', 'elementum at, egestas a, scelerisque sed, sapien. Nunc pulvinar arcu et pede. Nunc sed orci', NULL),
(93, 'Eleanor', NULL, 'augue@icloud.ca', 'Ellis', 'a94a8fe5ccb19ba61c4c0873d391e987982fbbd3', 'Culture', 'Brive-la-Gaillarde', 'auctor quis, tristique ac, eleifend vitae, erat. Vivamus nisi. Mauris nulla. Integer urna. Vivamus molestie', NULL),
(94, 'Petra', NULL, 'mus.proin@aol.com', 'Cruz', 'a94a8fe5ccb19ba61c4c0873d391e987982fbbd3', 'Sport', 'Wattrelos', 'facilisi. Sed neque. Sed eget lacus. Mauris non dui nec urna suscipit nonummy. Fusce fermentum', NULL),
(95, 'Bell', NULL, 'nostra.per@icloud.net', 'Guzman', 'a94a8fe5ccb19ba61c4c0873d391e987982fbbd3', 'Education', 'Charleville-Mézières', 'purus, in molestie tortor nibh sit amet orci. Ut sagittis lobortis mauris. Suspendisse aliquet molestie', NULL),
(96, 'Adrienne', NULL, 'cursus@icloud.org', 'Robinson', 'a94a8fe5ccb19ba61c4c0873d391e987982fbbd3', 'Ecologie', 'Alençon', 'fermentum fermentum arcu. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia', NULL),
(97, 'Kieran', NULL, 'arcu.morbi@icloud.edu', 'Huffman', 'a94a8fe5ccb19ba61c4c0873d391e987982fbbd3', 'Solidarite', 'Haguenau', 'eu tellus. Phasellus elit pede, malesuada vel, venenatis vel, faucibus id, libero. Donec consectetuer mauris', NULL),
(98, 'Tanek', NULL, 'elit.etiam.laoreet@hotmail.edu', 'Goodman', 'a94a8fe5ccb19ba61c4c0873d391e987982fbbd3', 'Ecologie', 'Tarbes', 'Sed eget lacus. Mauris non dui nec urna suscipit nonummy. Fusce fermentum fermentum arcu. Vestibulum', NULL),
(99, 'Ferris', NULL, 'commodo.tincidunt@icloud.ca', 'Cooper', 'a94a8fe5ccb19ba61c4c0873d391e987982fbbd3', 'Ecologie', 'Aurillac', 'urna suscipit nonummy. Fusce fermentum fermentum arcu. Vestibulum ante ipsum primis in faucibus orci luctus', NULL),
(100, 'Eugenia', NULL, 'netus@icloud.com', 'Cobb', 'a94a8fe5ccb19ba61c4c0873d391e987982fbbd3', 'Education', 'Bastia', 'et, rutrum eu, ultrices sit amet, risus. Donec nibh enim, gravida sit amet, dapibus id,', NULL),
(101, 'Aimee', NULL, 'donec.sollicitudin.adipiscing@icloud.edu', 'Underwood', 'a94a8fe5ccb19ba61c4c0873d391e987982fbbd3', 'Culture', 'Ajaccio', 'aliquet, sem ut cursus luctus, ipsum leo elementum sem, vitae aliquam eros turpis non enim.', NULL),
(102, 'Xander', NULL, 'non@icloud.ca', 'Trujillo', 'a94a8fe5ccb19ba61c4c0873d391e987982fbbd3', 'Ecologie', 'Nanterre', 'cursus. Integer mollis. Integer tincidunt aliquam arcu. Aliquam ultrices iaculis odio. Nam interdum enim non', NULL),
(103, 'Emerson', NULL, 'quam.elementum@google.ca', 'Stephens', 'a94a8fe5ccb19ba61c4c0873d391e987982fbbd3', 'Sport', 'Aurillac', 'varius. Nam porttitor scelerisque neque. Nullam nisl. Maecenas malesuada fringilla est. Mauris eu turpis. Nulla', NULL),
(104, 'Jocelyn', NULL, 'laoreet.ipsum@google.edu', 'Conner', 'a94a8fe5ccb19ba61c4c0873d391e987982fbbd3', 'Culture', 'Mérignac', 'at augue id ante dictum cursus. Nunc mauris elit, dictum eu, eleifend nec, malesuada ut,', NULL),
(105, 'Paloma', NULL, 'malesuada.vel@yahoo.com', 'Hebert', 'a94a8fe5ccb19ba61c4c0873d391e987982fbbd3', 'Sport', 'Aix-en-Provence', 'feugiat. Sed nec metus facilisis lorem tristique aliquet. Phasellus fermentum convallis ligula. Donec luctus aliquet', NULL),
(106, 'Hashim', NULL, 'enim.etiam@yahoo.org', 'Lynch', 'a94a8fe5ccb19ba61c4c0873d391e987982fbbd3', 'Ecologie', 'Belfort', 'lectus justo eu arcu. Morbi sit amet massa. Quisque porttitor eros nec tellus. Nunc lectus', NULL),
(107, 'Hayden', NULL, 'condimentum@icloud.org', 'Sexton', 'a94a8fe5ccb19ba61c4c0873d391e987982fbbd3', 'Solidarite', 'Ajaccio', 'nonummy ut, molestie in, tempus eu, ligula. Aenean euismod mauris eu elit. Nulla facilisi. Sed', NULL),
(108, 'Dawn', NULL, 'morbi@aol.net', 'Carter', 'a94a8fe5ccb19ba61c4c0873d391e987982fbbd3', 'Ecologie', 'Le Puy-en-Velay', 'venenatis lacus. Etiam bibendum fermentum metus. Aenean sed pede nec ante blandit viverra. Donec tempus,', NULL),
(109, 'Kaitlin', NULL, 'pede.nonummy@hotmail.net', 'Waters', 'a94a8fe5ccb19ba61c4c0873d391e987982fbbd3', 'Culture', 'Brive-la-Gaillarde', 'mollis dui, in sodales elit erat vitae risus. Duis a mi fringilla mi lacinia mattis.', NULL),
(110, 'Raya', NULL, 'ipsum.ac.mi@outlook.org', 'Cummings', 'a94a8fe5ccb19ba61c4c0873d391e987982fbbd3', 'Solidarite', 'Cambrai', 'ligula. Nullam enim. Sed nulla ante, iaculis nec, eleifend non, dapibus rutrum, justo. Praesent luctus.', NULL),
(111, 'sdfghjkl', NULL, 'ghjf@gmail.com', 'zefzefze', '7110eda4d09e062aa5e4a390b0a572ac0d2c0220', 'Solidarite', 'zefzefz', 'efzefzefzef', '67d8bb1748283.jpg');

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
  `description` text,
  `image` varchar(255) DEFAULT NULL,
  `point` bigint(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Contenu de la table `gt_benevole`
--

INSERT INTO `gt_benevole` (`id_benevole`, `nomBenevole`, `prenomBenevole`, `mailBenevole`, `loginBenevole`, `mdpBenevole`, `tag`, `localisation`, `description`, `image`, `point`) VALUES
(7, 'Auchon', 'Paul', 'PaulAuchon@gmail.Com', 'PaulA', '6abc743bbde4a1562b6538f3e26f71b23b5cd345', 'Culture', 'Paris', 'Benevole motivé...', NULL, 0),
(8, 'Cailin', 'Akeem', 'tellus.sem.mollis@outlook.net', 'Nolan', 'a94a8fe5ccb19ba61c4c0873d391e987982fbbd3', 'Sport', 'Forbach', 'Integer vulputate, risus a ultricies adipiscing, enim mi tempor lorem, eget mollis lectus pede et', NULL, 0),
(9, 'Malcolm', 'Hayfa', 'proin.velit.sed@google.couk', 'Rosales', 'a94a8fe5ccb19ba61c4c0873d391e987982fbbd3', 'Ecologie', 'Mulhouse', 'Pellentesque tincidunt tempus risus. Donec egestas. Duis ac arcu. Nunc mauris. Morbi non sapien molestie', NULL, 0),
(10, 'Boris', 'Cassidy', 'scelerisque.sed@icloud.com', 'Ramirez', 'a94a8fe5ccb19ba61c4c0873d391e987982fbbd3', 'Solidarite', 'Champigny-sur-Marne', 'neque. Nullam ut nisi a odio semper cursus. Integer mollis. Integer tincidunt aliquam arcu. Aliquam', NULL, 0),
(11, 'Clarke', 'Martena', 'id.sapien.cras@hotmail.com', 'Crane', 'a94a8fe5ccb19ba61c4c0873d391e987982fbbd3', 'Ecologie', 'Brive-la-Gaillarde', 'vel, convallis in, cursus et, eros. Proin ultrices. Duis volutpat nunc sit amet metus. Aliquam', NULL, 0),
(12, 'Inga', 'Fletcher', 'ornare@protonmail.net', 'Olson', 'a94a8fe5ccb19ba61c4c0873d391e987982fbbd3', 'Ecologie', 'Albi', 'dolor sit amet, consectetuer adipiscing elit. Etiam laoreet, libero et tristique pellentesque, tellus sem mollis', NULL, 0),
(13, 'Alea', 'Tara', 'molestie.tellus@hotmail.org', 'Guthrie', 'a94a8fe5ccb19ba61c4c0873d391e987982fbbd3', 'Education', 'Saintes', 'scelerisque neque sed sem egestas blandit. Nam nulla magna, malesuada vel, convallis in, cursus et,', NULL, 0),
(14, 'Geoffrey', 'Jessica', 'ac@google.couk', 'Koch', 'a94a8fe5ccb19ba61c4c0873d391e987982fbbd3', 'Solidarite', 'Wattrelos', 'ultrices iaculis odio. Nam interdum enim non nisi. Aenean eget metus. In nec orci. Donec', NULL, 0),
(15, 'Ima', 'Jaime', 'cursus.vestibulum.mauris@google.edu', 'Serrano', 'a94a8fe5ccb19ba61c4c0873d391e987982fbbd3', 'Sport', 'Limoges', 'enim. Etiam gravida molestie arcu. Sed eu nibh vulputate mauris sagittis placerat. Cras dictum ultricies', NULL, 0),
(16, 'Zeus', 'Tasha', 'tempor@aol.com', 'Wilson', 'a94a8fe5ccb19ba61c4c0873d391e987982fbbd3', 'Education', 'Belfort', 'Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Proin vel arcu', NULL, 0),
(17, 'Shelly', 'Nayda', 'suscipit.nonummy.fusce@google.net', 'Mcgowan', 'a94a8fe5ccb19ba61c4c0873d391e987982fbbd3', 'Education', 'Auxerre', 'vulputate eu, odio. Phasellus at augue id ante dictum cursus. Nunc mauris elit, dictum eu,', NULL, 0),
(18, 'Fatima', 'Kaye', 'et.eros@hotmail.ca', 'Conley', 'a94a8fe5ccb19ba61c4c0873d391e987982fbbd3', 'Sport', 'Belfort', 'nec tellus. Nunc lectus pede, ultrices a, auctor non, feugiat nec, diam. Duis mi enim,', NULL, 0),
(19, 'Fatima', 'Aaron', 'nec.cursus.a@icloud.ca', 'Russell', 'a94a8fe5ccb19ba61c4c0873d391e987982fbbd3', 'Education', 'Saint-Lô', 'ligula. Nullam enim. Sed nulla ante, iaculis nec, eleifend non, dapibus rutrum, justo. Praesent luctus.', NULL, 0),
(20, 'Stacey', 'Aaron', 'tincidunt.aliquam@protonmail.org', 'Larson', 'a94a8fe5ccb19ba61c4c0873d391e987982fbbd3', 'Solidarite', 'Hénin-Beaumont', 'a, malesuada id, erat. Etiam vestibulum massa rutrum magna. Cras convallis convallis dolor. Quisque tincidunt', NULL, 0),
(21, 'Jin', 'Jack', 'elit.etiam.laoreet@hotmail.net', 'Morales', 'a94a8fe5ccb19ba61c4c0873d391e987982fbbd3', 'Education', 'Vertou', 'consequat enim diam vel arcu. Curabitur ut odio vel est tempor bibendum. Donec felis orci,', NULL, 0),
(22, 'Eaton', 'Wade', 'at.egestas@icloud.org', 'Stafford', 'a94a8fe5ccb19ba61c4c0873d391e987982fbbd3', 'Culture', 'Créteil', 'Duis cursus, diam at pretium aliquet, metus urna convallis erat, eget tincidunt dui augue eu', NULL, 0),
(23, 'Hayes', 'Garrett', 'elit.pellentesque.a@aol.couk', 'Buckner', 'a94a8fe5ccb19ba61c4c0873d391e987982fbbd3', 'Ecologie', 'Roubaix', 'nec quam. Curabitur vel lectus. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur', NULL, 0),
(24, 'Rhea', 'May', 'duis@outlook.couk', 'Valentine', 'a94a8fe5ccb19ba61c4c0873d391e987982fbbd3', 'Ecologie', 'Brive-la-Gaillarde', 'Nulla interdum. Curabitur dictum. Phasellus in felis. Nulla tempor augue ac ipsum. Phasellus vitae mauris', NULL, 0),
(25, 'Grant', 'Audra', 'nullam.suscipit.est@google.org', 'Pruitt', 'a94a8fe5ccb19ba61c4c0873d391e987982fbbd3', 'Education', 'Martigues', 'eu, eleifend nec, malesuada ut, sem. Nulla interdum. Curabitur dictum. Phasellus in felis. Nulla tempor', NULL, 0),
(26, 'Jarrod', 'Maite', 'nunc.pulvinar@protonmail.ca', 'Gross', 'a94a8fe5ccb19ba61c4c0873d391e987982fbbd3', 'Sport', 'Saint-Louis', 'tempor lorem, eget mollis lectus pede et risus. Quisque libero lacus, varius et, euismod et,', NULL, 0),
(27, 'Gwendolyn', 'Brooke', 'ante.vivamus@outlook.couk', 'Lott', 'a94a8fe5ccb19ba61c4c0873d391e987982fbbd3', 'Solidarite', 'Laval', 'ante. Maecenas mi felis, adipiscing fringilla, porttitor vulputate, posuere vulputate, lacus. Cras interdum. Nunc sollicitudin', NULL, 0),
(28, 'Farrah', 'Kaye', 'sem.mollis@outlook.org', 'Marsh', 'a94a8fe5ccb19ba61c4c0873d391e987982fbbd3', 'Sport', 'Levallois-Perret', 'montes, nascetur ridiculus mus. Proin vel arcu eu odio tristique pharetra. Quisque ac libero nec', NULL, 0),
(29, 'Camilla', 'Rose', 'tincidunt.nibh@yahoo.couk', 'Cain', 'a94a8fe5ccb19ba61c4c0873d391e987982fbbd3', 'Ecologie', 'Besançon', 'lorem eu metus. In lorem. Donec elementum, lorem ut aliquam iaculis, lacus pede sagittis augue,', NULL, 0),
(30, 'Leah', 'Julian', 'pede@google.edu', 'Sweeney', 'a94a8fe5ccb19ba61c4c0873d391e987982fbbd3', 'Solidarite', 'Fréjus', 'eleifend, nunc risus varius orci, in consequat enim diam vel arcu. Curabitur ut odio vel', NULL, 0),
(31, 'Uriah', 'Winifred', 'iaculis@aol.couk', 'Gamble', 'a94a8fe5ccb19ba61c4c0873d391e987982fbbd3', 'Solidarite', 'Mérignac', 'libero. Integer in magna. Phasellus dolor elit, pellentesque a, facilisis non, bibendum sed, est. Nunc', NULL, 0),
(32, 'Tyler', 'Ian', 'turpis.in@yahoo.edu', 'Sears', 'a94a8fe5ccb19ba61c4c0873d391e987982fbbd3', 'Ecologie', 'Brive-la-Gaillarde', 'ullamcorper viverra. Maecenas iaculis aliquet diam. Sed diam lorem, auctor quis, tristique ac, eleifend vitae,', NULL, 0),
(33, 'Jorden', 'Shelby', 'amet.consectetuer@icloud.com', 'Macias', 'a94a8fe5ccb19ba61c4c0873d391e987982fbbd3', 'Education', 'Rennes', 'in lobortis tellus justo sit amet nulla. Donec non justo. Proin non massa non ante', NULL, 0),
(34, 'Gisela', 'Murphy', 'integer.aliquam@yahoo.org', 'Pierce', 'a94a8fe5ccb19ba61c4c0873d391e987982fbbd3', 'Sport', 'Belfort', 'Nullam feugiat placerat velit. Quisque varius. Nam porttitor scelerisque neque. Nullam nisl. Maecenas malesuada fringilla', NULL, 0),
(35, 'Sophia', 'Zeus', 'tristique@hotmail.ca', 'Fitzpatrick', 'a94a8fe5ccb19ba61c4c0873d391e987982fbbd3', 'Ecologie', 'Hyères', 'fermentum metus. Aenean sed pede nec ante blandit viverra. Donec tempus, lorem fringilla ornare placerat,', NULL, 0),
(36, 'Oren', 'Renee', 'semper.et.lacinia@protonmail.org', 'Aguilar', 'a94a8fe5ccb19ba61c4c0873d391e987982fbbd3', 'Culture', 'Angoulême', 'cubilia Curae Donec tincidunt. Donec vitae erat vel pede blandit congue. In scelerisque scelerisque dui.', NULL, 0),
(37, 'Alfonso', 'Martin', 'ullamcorper.eu@yahoo.org', 'Hancock', 'a94a8fe5ccb19ba61c4c0873d391e987982fbbd3', 'Culture', 'Le Puy-en-Velay', 'mauris ipsum porta elit, a feugiat tellus lorem eu metus. In lorem. Donec elementum, lorem', NULL, 0),
(38, 'Denton', 'Doris', 'maecenas.ornare@protonmail.ca', 'Malone', 'a94a8fe5ccb19ba61c4c0873d391e987982fbbd3', 'Solidarite', 'Clermont-Ferrand', 'lorem ut aliquam iaculis, lacus pede sagittis augue, eu tempor erat neque non quam. Pellentesque', NULL, 0),
(39, 'Mari', 'Julian', 'donec.fringilla@aol.org', 'Haynes', 'a94a8fe5ccb19ba61c4c0873d391e987982fbbd3', 'Culture', 'Lunel', 'eu dolor egestas rhoncus. Proin nisl sem, consequat nec, mollis vitae, posuere at, velit. Cras', NULL, 0),
(40, 'Zahir', 'Giacomo', 'donec.feugiat.metus@icloud.net', 'Maddox', 'a94a8fe5ccb19ba61c4c0873d391e987982fbbd3', 'Solidarite', 'Mâcon', 'vehicula aliquet libero. Integer in magna. Phasellus dolor elit, pellentesque a, facilisis non, bibendum sed,', NULL, 0),
(41, 'Sacha', 'Nelle', 'accumsan.neque.et@hotmail.ca', 'Moore', 'a94a8fe5ccb19ba61c4c0873d391e987982fbbd3', 'Ecologie', 'Pessac', 'Sed id risus quis diam luctus lobortis. Class aptent taciti sociosqu ad litora torquent per', NULL, 0),
(42, 'Price', 'Benjamin', 'natoque.penatibus@yahoo.edu', 'Matthews', 'a94a8fe5ccb19ba61c4c0873d391e987982fbbd3', 'Sport', 'Châlons-en-Champagne', 'facilisis eget, ipsum. Donec sollicitudin adipiscing ligula. Aenean gravida nunc sed pede. Cum sociis natoque', NULL, 0),
(43, 'Lucy', 'Geoffrey', 'orci.phasellus@yahoo.org', 'Odom', 'a94a8fe5ccb19ba61c4c0873d391e987982fbbd3', 'Sport', 'Schiltigheim', 'In mi pede, nonummy ut, molestie in, tempus eu, ligula. Aenean euismod mauris eu elit.', NULL, 0),
(44, 'Sophia', 'Sonya', 'aenean.massa@yahoo.couk', 'Sanders', 'a94a8fe5ccb19ba61c4c0873d391e987982fbbd3', 'Sport', 'Marcq-en-Baroeul', 'a, facilisis non, bibendum sed, est. Nunc laoreet lectus quis massa. Mauris vestibulum, neque sed', NULL, 0),
(45, 'Olga', 'Jin', 'vitae.aliquet@hotmail.edu', 'Scott', 'a94a8fe5ccb19ba61c4c0873d391e987982fbbd3', 'Culture', 'Limoges', 'magna. Sed eu eros. Nam consequat dolor vitae dolor. Donec fringilla. Donec feugiat metus sit', NULL, 0),
(46, 'Gemma', 'Liberty', 'orci.luctus.et@yahoo.org', 'Spencer', 'a94a8fe5ccb19ba61c4c0873d391e987982fbbd3', 'Solidarite', 'Istres', 'Proin eget odio. Aliquam vulputate ullamcorper magna. Sed eu eros. Nam consequat dolor vitae dolor.', NULL, 0),
(47, 'Karly', 'Sydnee', 'et.rutrum@outlook.net', 'Justice', 'a94a8fe5ccb19ba61c4c0873d391e987982fbbd3', 'Solidarite', 'Moulins', 'est, mollis non, cursus non, egestas a, dui. Cras pellentesque. Sed dictum. Proin eget odio.', NULL, 0),
(48, 'Barbara', 'Holmes', 'elementum.purus@protonmail.ca', 'Alston', 'a94a8fe5ccb19ba61c4c0873d391e987982fbbd3', 'Education', 'Marseille', 'lectus pede et risus. Quisque libero lacus, varius et, euismod et, commodo at, libero. Morbi', NULL, 0),
(49, 'Yvonne', 'Dara', 'eu.euismod.ac@google.net', 'Cabrera', 'a94a8fe5ccb19ba61c4c0873d391e987982fbbd3', 'Education', 'Sète', 'ac turpis egestas. Fusce aliquet magna a neque. Nullam ut nisi a odio semper cursus.', NULL, 0),
(50, 'Justine', 'Kyle', 'lobortis.nisi@aol.com', 'Conrad', 'a94a8fe5ccb19ba61c4c0873d391e987982fbbd3', 'Culture', 'Auxerre', 'ante dictum cursus. Nunc mauris elit, dictum eu, eleifend nec, malesuada ut, sem. Nulla interdum.', NULL, 0),
(51, 'Zephr', 'Ivan', 'vel.faucibus@hotmail.net', 'Bean', 'a94a8fe5ccb19ba61c4c0873d391e987982fbbd3', 'Solidarite', 'Limoges', 'orci, adipiscing non, luctus sit amet, faucibus ut, nulla. Cras eu tellus eu augue porttitor', NULL, 0),
(52, 'Darius', 'Griffith', 'pede.nec.ante@hotmail.ca', 'Pittman', 'a94a8fe5ccb19ba61c4c0873d391e987982fbbd3', 'Education', 'Montpellier', 'at sem molestie sodales. Mauris blandit enim consequat purus. Maecenas libero est, congue a, aliquet', NULL, 0),
(53, 'Marsden', 'Zachery', 'convallis@aol.ca', 'Coffey', 'a94a8fe5ccb19ba61c4c0873d391e987982fbbd3', 'Education', 'Thionville', 'malesuada id, erat. Etiam vestibulum massa rutrum magna. Cras convallis convallis dolor. Quisque tincidunt pede', NULL, 0),
(54, 'Barclay', 'Benedict', 'mollis@aol.ca', 'Mcintyre', 'a94a8fe5ccb19ba61c4c0873d391e987982fbbd3', 'Sport', 'Lunel', 'velit. Pellentesque ultricies dignissim lacus. Aliquam rutrum lorem ac risus. Morbi metus. Vivamus euismod urna.', NULL, 0),
(55, 'Zeph', 'September', 'aliquam.rutrum@outlook.net', 'Jimenez', 'a94a8fe5ccb19ba61c4c0873d391e987982fbbd3', 'Solidarite', 'Alès', 'nisi. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Proin vel', NULL, 0),
(56, 'Kieran', 'Troy', 'mauris.quis@google.couk', 'Sweet', 'a94a8fe5ccb19ba61c4c0873d391e987982fbbd3', 'Culture', 'Bastia', 'dis parturient montes, nascetur ridiculus mus. Proin vel nisl. Quisque fringilla euismod enim. Etiam gravida', NULL, 0),
(57, 'Sara', 'Guy', 'sit.amet@icloud.net', 'Lopez', 'a94a8fe5ccb19ba61c4c0873d391e987982fbbd3', 'Solidarite', 'Strasbourg', 'molestie. Sed id risus quis diam luctus lobortis. Class aptent taciti sociosqu ad litora torquent', NULL, 0),
(58, 'Murphy', 'Suki', 'phasellus.dolor@protonmail.org', 'Kerr', 'a94a8fe5ccb19ba61c4c0873d391e987982fbbd3', 'Ecologie', 'Cherbourg-Octeville', 'sodales purus, in molestie tortor nibh sit amet orci. Ut sagittis lobortis mauris. Suspendisse aliquet', NULL, 0),
(59, 'Imogene', 'Mary', 'egestas.a@aol.org', 'Goodwin', 'a94a8fe5ccb19ba61c4c0873d391e987982fbbd3', 'Sport', 'Besançon', 'pretium aliquet, metus urna convallis erat, eget tincidunt dui augue eu tellus. Phasellus elit pede,', NULL, 0),
(60, 'Colt', 'Giselle', 'nunc.quis@aol.edu', 'Pacheco', 'a94a8fe5ccb19ba61c4c0873d391e987982fbbd3', 'Culture', 'Nanterre', 'nunc. Quisque ornare tortor at risus. Nunc ac sem ut dolor dapibus gravida. Aliquam tincidunt,', NULL, 0),
(61, 'Yoshi', 'Sean', 'risus.donec@outlook.org', 'Vazquez', 'a94a8fe5ccb19ba61c4c0873d391e987982fbbd3', 'Education', 'Vierzon', 'Fusce mi lorem, vehicula et, rutrum eu, ultrices sit amet, risus. Donec nibh enim, gravida', NULL, 0),
(62, 'Ocean', 'Leo', 'tellus@yahoo.ca', 'Keith', 'a94a8fe5ccb19ba61c4c0873d391e987982fbbd3', 'Solidarite', 'Ajaccio', 'orci tincidunt adipiscing. Mauris molestie pharetra nibh. Aliquam ornare, libero at auctor ullamcorper, nisl arcu', NULL, 0),
(63, 'Hedy', 'Colette', 'ullamcorper.duis.at@hotmail.com', 'Herman', 'a94a8fe5ccb19ba61c4c0873d391e987982fbbd3', 'Solidarite', 'Limoges', 'Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Aenean eget magna.', NULL, 0),
(64, 'Briar', 'Wylie', 'tempor.diam.dictum@protonmail.ca', 'Fuller', 'a94a8fe5ccb19ba61c4c0873d391e987982fbbd3', 'Culture', 'Gap', 'nunc interdum feugiat. Sed nec metus facilisis lorem tristique aliquet. Phasellus fermentum convallis ligula. Donec', NULL, 0),
(65, 'Charles', 'Idola', 'tellus.suspendisse@protonmail.com', 'Harrington', 'a94a8fe5ccb19ba61c4c0873d391e987982fbbd3', 'Ecologie', 'Narbonne', 'sagittis. Duis gravida. Praesent eu nulla at sem molestie sodales. Mauris blandit enim consequat purus.', NULL, 0),
(66, 'Bevis', 'Justine', 'pharetra.nibh.aliquam@outlook.couk', 'Talley', 'a94a8fe5ccb19ba61c4c0873d391e987982fbbd3', 'Education', 'La Roche-sur-Yon', 'non, bibendum sed, est. Nunc laoreet lectus quis massa. Mauris vestibulum, neque sed dictum eleifend,', NULL, 0),
(67, 'Teagan', 'Aidan', 'sed.dictum@protonmail.net', 'Padilla', 'a94a8fe5ccb19ba61c4c0873d391e987982fbbd3', 'Sport', 'Montpellier', 'ac mattis semper, dui lectus rutrum urna, nec luctus felis purus ac tellus. Suspendisse sed', NULL, 0),
(68, 'Maxwell', 'Devin', 'pede@hotmail.com', 'Armstrong', 'a94a8fe5ccb19ba61c4c0873d391e987982fbbd3', 'Ecologie', 'Bourges', 'ultrices posuere cubilia Curae Phasellus ornare. Fusce mollis. Duis sit amet diam eu dolor egestas', NULL, 0),
(69, 'Ryder', 'Ryder', 'enim@outlook.couk', 'Hutchinson', 'a94a8fe5ccb19ba61c4c0873d391e987982fbbd3', 'Solidarite', 'Aurillac', 'montes, nascetur ridiculus mus. Proin vel nisl. Quisque fringilla euismod enim. Etiam gravida molestie arcu.', NULL, 0),
(70, 'Kim', 'Lareina', 'ligula.aliquam.erat@outlook.com', 'Edwards', 'a94a8fe5ccb19ba61c4c0873d391e987982fbbd3', 'Ecologie', 'Haguenau', 'ipsum. Suspendisse non leo. Vivamus nibh dolor, nonummy ac, feugiat non, lobortis quis, pede. Suspendisse', NULL, 0),
(71, 'Shea', 'Ali', 'purus.nullam.scelerisque@icloud.net', 'Herman', 'a94a8fe5ccb19ba61c4c0873d391e987982fbbd3', 'Solidarite', 'Limoges', 'magnis dis parturient montes, nascetur ridiculus mus. Proin vel arcu eu odio tristique pharetra. Quisque', NULL, 0),
(72, 'Zachary', 'Colin', 'suspendisse.ac@yahoo.couk', 'Ramsey', 'a94a8fe5ccb19ba61c4c0873d391e987982fbbd3', 'Ecologie', 'Dijon', 'feugiat metus sit amet ante. Vivamus non lorem vitae odio sagittis semper. Nam tempor diam', NULL, 0),
(73, 'Maryam', 'Zane', 'ornare@google.org', 'Hyde', 'a94a8fe5ccb19ba61c4c0873d391e987982fbbd3', 'Education', 'Arras', 'Nunc laoreet lectus quis massa. Mauris vestibulum, neque sed dictum eleifend, nunc risus varius orci,', NULL, 0),
(74, 'Wang', 'Tashya', 'nec.enim@protonmail.couk', 'Hooper', 'a94a8fe5ccb19ba61c4c0873d391e987982fbbd3', 'Ecologie', 'Niort', 'id risus quis diam luctus lobortis. Class aptent taciti sociosqu ad litora torquent per conubia', NULL, 0),
(75, 'Hollee', 'Joelle', 'a.felis.ullamcorper@outlook.org', 'Benton', 'a94a8fe5ccb19ba61c4c0873d391e987982fbbd3', 'Culture', 'Soissons', 'facilisis eget, ipsum. Donec sollicitudin adipiscing ligula. Aenean gravida nunc sed pede. Cum sociis natoque', NULL, 0),
(76, 'Timothy', 'Maile', 'ligula.eu@google.ca', 'West', 'a94a8fe5ccb19ba61c4c0873d391e987982fbbd3', 'Sport', 'Blois', 'eros turpis non enim. Mauris quis turpis vitae purus gravida sagittis. Duis gravida. Praesent eu', NULL, 0),
(77, 'Yael', 'Carter', 'nascetur.ridiculus@icloud.couk', 'Henderson', 'a94a8fe5ccb19ba61c4c0873d391e987982fbbd3', 'Education', 'Toulon', 'ipsum nunc id enim. Curabitur massa. Vestibulum accumsan neque et nunc. Quisque ornare tortor at', NULL, 0),
(78, 'Theodore', 'Lucas', 'massa.integer.vitae@hotmail.ca', 'Mosley', 'a94a8fe5ccb19ba61c4c0873d391e987982fbbd3', 'Solidarite', 'Sarreguemines', 'ligula. Nullam feugiat placerat velit. Quisque varius. Nam porttitor scelerisque neque. Nullam nisl. Maecenas malesuada', NULL, 0),
(79, 'Carly', 'Upton', 'non@yahoo.couk', 'Gross', 'a94a8fe5ccb19ba61c4c0873d391e987982fbbd3', 'Education', 'Châlons-en-Champagne', 'turpis nec mauris blandit mattis. Cras eget nisi dictum augue malesuada malesuada. Integer id magna', NULL, 0),
(80, 'Christen', 'Cody', 'ornare.fusce.mollis@icloud.net', 'Ballard', 'a94a8fe5ccb19ba61c4c0873d391e987982fbbd3', 'Solidarite', 'Châlons-en-Champagne', 'metus. Vivamus euismod urna. Nullam lobortis quam a felis ullamcorper viverra. Maecenas iaculis aliquet diam.', NULL, 0),
(81, 'Basil', 'Sean', 'mus.aenean@yahoo.edu', 'Jennings', 'a94a8fe5ccb19ba61c4c0873d391e987982fbbd3', 'Solidarite', 'Tours', 'Pellentesque tincidunt tempus risus. Donec egestas. Duis ac arcu. Nunc mauris. Morbi non sapien molestie', NULL, 0),
(82, 'Arthur', 'Freya', 'dui.lectus@outlook.com', 'Norris', 'a94a8fe5ccb19ba61c4c0873d391e987982fbbd3', 'Sport', 'Dole', 'Suspendisse aliquet, sem ut cursus luctus, ipsum leo elementum sem, vitae aliquam eros turpis non', NULL, 0),
(83, 'Kaitlin', 'Oprah', 'curabitur@aol.com', 'Walsh', 'a94a8fe5ccb19ba61c4c0873d391e987982fbbd3', 'Education', 'Roubaix', 'et, eros. Proin ultrices. Duis volutpat nunc sit amet metus. Aliquam erat volutpat. Nulla facilisis.', NULL, 0),
(84, 'Darrel', 'Tatum', 'urna.ut@yahoo.edu', 'Rosa', 'a94a8fe5ccb19ba61c4c0873d391e987982fbbd3', 'Ecologie', 'Vernon', 'convallis in, cursus et, eros. Proin ultrices. Duis volutpat nunc sit amet metus. Aliquam erat', NULL, 0),
(85, 'Rhonda', 'Courtney', 'ut.lacus.nulla@google.edu', 'Kirby', 'a94a8fe5ccb19ba61c4c0873d391e987982fbbd3', 'Ecologie', 'Alençon', 'elit, pharetra ut, pharetra sed, hendrerit a, arcu. Sed et libero. Proin mi. Aliquam gravida', NULL, 0),
(86, 'Wesley', 'Chaim', 'luctus.vulputate.nisi@hotmail.com', 'Reyes', 'a94a8fe5ccb19ba61c4c0873d391e987982fbbd3', 'Ecologie', 'Mont-de-Marsan', 'mauris id sapien. Cras dolor dolor, tempus non, lacinia at, iaculis quis, pede. Praesent eu', NULL, 0),
(87, 'Sasha', 'Illiana', 'posuere@protonmail.org', 'Bennett', 'a94a8fe5ccb19ba61c4c0873d391e987982fbbd3', 'Education', 'Blois', 'placerat, augue. Sed molestie. Sed id risus quis diam luctus lobortis. Class aptent taciti sociosqu', NULL, 0),
(88, 'Wylie', 'Flavia', 'neque@protonmail.couk', 'Fletcher', 'a94a8fe5ccb19ba61c4c0873d391e987982fbbd3', 'Solidarite', 'Quimper', 'non, bibendum sed, est. Nunc laoreet lectus quis massa. Mauris vestibulum, neque sed dictum eleifend,', NULL, 0),
(89, 'Paula', 'Theodore', 'turpis@outlook.edu', 'Ramirez', 'a94a8fe5ccb19ba61c4c0873d391e987982fbbd3', 'Education', 'Aubervilliers', 'dictum eleifend, nunc risus varius orci, in consequat enim diam vel arcu. Curabitur ut odio', NULL, 0),
(90, 'Basia', 'Urielle', 'odio.aliquam@aol.com', 'Rice', 'a94a8fe5ccb19ba61c4c0873d391e987982fbbd3', 'Sport', 'Alençon', 'odio sagittis semper. Nam tempor diam dictum sapien. Aenean massa. Integer vitae nibh. Donec est', NULL, 0),
(91, 'Leo', 'Mechelle', 'nunc.sit@protonmail.edu', 'Hopper', 'a94a8fe5ccb19ba61c4c0873d391e987982fbbd3', 'Sport', 'Narbonne', 'risus. Quisque libero lacus, varius et, euismod et, commodo at, libero. Morbi accumsan laoreet ipsum.', NULL, 0),
(92, 'Jin', 'Scarlett', 'integer@aol.com', 'Gould', 'a94a8fe5ccb19ba61c4c0873d391e987982fbbd3', 'Solidarite', 'Épinal', 'nunc interdum feugiat. Sed nec metus facilisis lorem tristique aliquet. Phasellus fermentum convallis ligula. Donec', NULL, 0),
(93, 'Courtney', 'Valentine', 'vulputate.dui@aol.couk', 'Murphy', 'a94a8fe5ccb19ba61c4c0873d391e987982fbbd3', 'Solidarite', 'Le Petit-Quevilly', 'nibh lacinia orci, consectetuer euismod est arcu ac orci. Ut semper pretium neque. Morbi quis', NULL, 0),
(94, 'Julian', 'Zia', 'posuere@icloud.couk', 'Ramirez', 'a94a8fe5ccb19ba61c4c0873d391e987982fbbd3', 'Education', 'Amiens', 'sed pede. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Proin', NULL, 0),
(95, 'Yetta', 'Fritz', 'vehicula@hotmail.net', 'Shelton', 'a94a8fe5ccb19ba61c4c0873d391e987982fbbd3', 'Sport', 'Sarreguemines', 'Mauris quis turpis vitae purus gravida sagittis. Duis gravida. Praesent eu nulla at sem molestie', NULL, 0),
(96, 'Timothy', 'Elmo', 'eget.volutpat@icloud.ca', 'French', 'a94a8fe5ccb19ba61c4c0873d391e987982fbbd3', 'Ecologie', 'Montauban', 'consequat nec, mollis vitae, posuere at, velit. Cras lorem lorem, luctus ut, pellentesque eget, dictum', NULL, 0),
(97, 'Autumn', 'Xanthus', 'non.quam@protonmail.couk', 'Bauer', 'a94a8fe5ccb19ba61c4c0873d391e987982fbbd3', 'Education', 'Pontarlier', 'sit amet lorem semper auctor. Mauris vel turpis. Aliquam adipiscing lobortis risus. In mi pede,', NULL, 0),
(98, 'Brody', 'Whoopi', 'lacinia@yahoo.org', 'Boyd', 'a94a8fe5ccb19ba61c4c0873d391e987982fbbd3', 'Culture', 'Auxerre', 'Donec nibh enim, gravida sit amet, dapibus id, blandit at, nisi. Cum sociis natoque penatibus', NULL, 0),
(99, 'Cameron', 'Jolene', 'lectus.sit@protonmail.com', 'Serrano', 'a94a8fe5ccb19ba61c4c0873d391e987982fbbd3', 'Sport', 'Créteil', 'dui, semper et, lacinia vitae, sodales at, velit. Pellentesque ultricies dignissim lacus. Aliquam rutrum lorem', NULL, 0),
(100, 'Robert', 'Charity', 'non@yahoo.edu', 'Clemons', 'a94a8fe5ccb19ba61c4c0873d391e987982fbbd3', 'Solidarite', 'Brive-la-Gaillarde', 'at augue id ante dictum cursus. Nunc mauris elit, dictum eu, eleifend nec, malesuada ut,', NULL, 0),
(101, 'Daquan', 'Orlando', 'mollis@google.edu', 'Snow', 'a94a8fe5ccb19ba61c4c0873d391e987982fbbd3', 'Culture', 'Lunel', 'Aliquam erat volutpat. Nulla facilisis. Suspendisse commodo tincidunt nibh. Phasellus nulla. Integer vulputate, risus a', NULL, 0),
(102, 'Riley', 'Preston', 'consectetuer.adipiscing@protonmail.org', 'Delacruz', 'a94a8fe5ccb19ba61c4c0873d391e987982fbbd3', 'Sport', 'Illkirch-Graffenstaden', 'Nulla eget metus eu erat semper rutrum. Fusce dolor quam, elementum at, egestas a, scelerisque', NULL, 0),
(103, 'Giacomo', 'Kyra', 'eu.metus.in@yahoo.edu', 'Hansen', 'a94a8fe5ccb19ba61c4c0873d391e987982fbbd3', 'Solidarite', 'Talence', 'et, magna. Praesent interdum ligula eu enim. Etiam imperdiet dictum magna. Ut tincidunt orci quis', NULL, 0),
(104, 'Roary', 'Catherine', 'sollicitudin.adipiscing.ligula@protonmail.com', 'Solomon', 'a94a8fe5ccb19ba61c4c0873d391e987982fbbd3', 'Ecologie', 'Limoges', 'neque venenatis lacus. Etiam bibendum fermentum metus. Aenean sed pede nec ante blandit viverra. Donec', NULL, 0),
(105, 'Azalia', 'Quinn', 'eget.nisi@hotmail.ca', 'Mccall', 'a94a8fe5ccb19ba61c4c0873d391e987982fbbd3', 'Ecologie', 'Sarreguemines', 'risus. Nulla eget metus eu erat semper rutrum. Fusce dolor quam, elementum at, egestas a,', NULL, 0),
(106, 'Kathleen', 'Micah', 'sed@yahoo.com', 'Reid', 'a94a8fe5ccb19ba61c4c0873d391e987982fbbd3', 'Education', 'Vandoeuvre-lès-Nancy', 'Nullam velit dui, semper et, lacinia vitae, sodales at, velit. Pellentesque ultricies dignissim lacus. Aliquam', NULL, 0),
(107, 'Gary', 'Judah', 'fusce@aol.couk', 'Downs', 'a94a8fe5ccb19ba61c4c0873d391e987982fbbd3', 'Education', 'Montbéliard', 'nec ante blandit viverra. Donec tempus, lorem fringilla ornare placerat, orci lacus vestibulum lorem, sit', NULL, 0),
(108, 'nbvcx', 'bvcxw', 'gfd@gmail.comlkjhgfdsq', 'jhgfds', '0449a53dbc6dc89f4d18bce26412bd6ecf9d9ac1', 'Sport', 'fghjklmÃ¹*', 'dfghjklmÃ¹', '67d8bb174821a.png', 0),
(109, 'jhgfdsq', 'jhgfdsq', 'jhgfds@mail.com', 'lkjhgfd', '63bd46e4000ab3cf5f3403a3ff222605e70f5e73', 'Culture', 'ergergergerg', 'ergergergerg', '67d8bb55a5bc5.jpg', 0),
(110, 'kjhgfdsthrth', 'jhgfdsrthrth', 'rthrthrth@mail.Com', 'erfergfergerg', '98c16745cac7e2d5ab711ce5d7f60ac9ca47f948', 'Culture', 'ergergerg', 'ergergerg', 'default-profile.png', 0),
(111, 'Pottin', 'Jarode', 'pottinjarode@gmail.com', 'pottijar', '7110eda4d09e062aa5e4a390b0a572ac0d2c0220', 'Education', 'ClÃ©on', 'Une brÃ¨ve description', '67d967723319e.jpg', 0),
(112, 'zzzzzzzz', 'zzzzzzzzzzz', 'zzzzzzzzzzzz@zzzzzzzz', 'zzzzzz', '395df8f7c51f007019cb30201c49e884b46b92fa', 'Culture', 'zzzzz', 'zzzzzzzzzzzzz', '67d9742c191db.jpg', 0),
(113, 'jjjjjjjjjjj', 'jjjjjjjjjjjjjj', 'jjjjjjjjjjjjjj@jjjjjjjjjj', 'jjjjjjjjjjjj', '7110eda4d09e062aa5e4a390b0a572ac0d2c0220', 'Culture', 'jjjjjjjjjjj', 'jjjjjjjjjjjj', '67d97845acf01.png', 0),
(114, 'Putman', 'Wesley', 'iezfhoeufhzfezoef@gmail.com', 'fepzÃªfoz^f', 'b0cc30695cb56220cdd0b4a673d945c2088f0f65', 'Ecologie', 'Paris', 'mksjgf^pÃ erjgpej', '67dc146637bf6.jpg', 0),
(119, 'LeclÃ¨re', 'Kyllian', 'Le@gmai.com', 'a', '86f7e437faa5a7fce15d1ddcb9eaeaea377667b8', 'Culture', 'La saussaye', 'egtregerg', 'profile_119_1743108247.jpg', 0);

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Contenu de la table `gt_inscription`
--

INSERT INTO `gt_inscription` (`id_inscription`, `posts_id`, `benevole_id`, `date_debut`, `progression`, `date_fin`) VALUES
(7, 7, 11, '2025-03-24', 0, '2025-03-24'),
(26, 8, 119, '2025-03-26', 0, '2025-03-26'),
(32, 8, 7, '2025-03-26', 0, '2025-03-26'),
(34, 9, 119, '2025-03-27', 0, '2025-03-27');

-- --------------------------------------------------------

--
-- Structure de la table `gt_message`
--

CREATE TABLE `gt_message` (
  `id_message` smallint(10) UNSIGNED NOT NULL,
  `id_posts` smallint(10) UNSIGNED NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `contenu` text NOT NULL,
  `notification` enum('lu','non-lu') NOT NULL DEFAULT 'non-lu'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Contenu de la table `gt_message`
--

INSERT INTO `gt_message` (`id_message`, `id_posts`, `date`, `contenu`, `notification`) VALUES
(12, 10, '2025-03-26 10:34:58', 'Aide aux Personnes en DifficultÃ© : Rendez-vous au [Lieu], le [Date & Heure], pour distribuer des repas et Ã©changer avec les bÃ©nÃ©ficiaires. PrÃ©voir une tenue confortable. Contactez [Nom de lâ€™organisateur] pour toute question.', 'non-lu'),
(14, 9, '2025-03-26 10:48:21', 'Distribution de Repas aux Sans-Abri : Rendez-vous au [Lieu], le [Date & Heure], pour distribuer des repas et offrir un moment de rÃ©confort aux personnes en difficultÃ©. PrÃ©voir une tenue adaptÃ©e. Pour toute question, contactez [Nom de lâ€™organisateur].', 'non-lu'),
(15, 9, '2025-03-27 20:46:04', 'yup', 'non-lu');

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Contenu de la table `gt_posts`
--

INSERT INTO `gt_posts` (`id_Posts`, `TitrePosts`, `Association_id`, `tag`, `Description`) VALUES
(9, 'Distribution de repas aux sans-abri', 11, 'SolidaritÃ©', 'Aider Ã  la prÃ©paration et Ã  la distribution de repas pour les personnes sans domicile.'),
(10, 'Ramassage de dÃ©chets en milieu urbain ou nature', 11, 'Ecologie', 'Organiser des sorties pour nettoyer les parcs, plages, forÃªts.');

-- --------------------------------------------------------

--
-- Structure de la table `image`
--

CREATE TABLE `image` (
  `id` int(11) NOT NULL,
  `libelle` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Contenu de la table `image`
--

INSERT INTO `image` (`id`, `libelle`) VALUES
(6, 'Kyllian_20250317_214506.png'),
(7, 'druid_20250317_214741.png');

--
-- Index pour les tables exportées
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
  ADD KEY `fk_id_posts` (`id_posts`);

--
-- Index pour la table `gt_posts`
--
ALTER TABLE `gt_posts`
  ADD PRIMARY KEY (`id_Posts`),
  ADD KEY `Association_id` (`Association_id`);

--
-- Index pour la table `image`
--
ALTER TABLE `image`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT pour les tables exportées
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
  MODIFY `id_Association` smallint(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=112;
--
-- AUTO_INCREMENT pour la table `gt_benevole`
--
ALTER TABLE `gt_benevole`
  MODIFY `id_benevole` smallint(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=120;
--
-- AUTO_INCREMENT pour la table `gt_inscription`
--
ALTER TABLE `gt_inscription`
  MODIFY `id_inscription` smallint(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;
--
-- AUTO_INCREMENT pour la table `gt_message`
--
ALTER TABLE `gt_message`
  MODIFY `id_message` smallint(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;
--
-- AUTO_INCREMENT pour la table `gt_posts`
--
ALTER TABLE `gt_posts`
  MODIFY `id_Posts` smallint(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;
--
-- AUTO_INCREMENT pour la table `image`
--
ALTER TABLE `image`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
--
-- Contraintes pour les tables exportées
--

--
-- Contraintes pour la table `gt_inscription`
--
ALTER TABLE `gt_inscription`
  ADD CONSTRAINT `GT_Inscription_ibfk_1` FOREIGN KEY (`benevole_id`) REFERENCES `gt_benevole` (`id_benevole`),
  ADD CONSTRAINT `GT_Inscription_ibfk_2` FOREIGN KEY (`posts_id`) REFERENCES `gt_posts` (`id_Posts`);

--
-- Contraintes pour la table `gt_posts`
--
ALTER TABLE `gt_posts`
  ADD CONSTRAINT `GT_Posts_ibfk_1` FOREIGN KEY (`Association_id`) REFERENCES `gt_association` (`id_Association`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
