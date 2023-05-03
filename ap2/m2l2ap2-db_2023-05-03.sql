-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : mer. 03 mai 2023 à 05:17
-- Version du serveur : 8.0.31
-- Version de PHP : 8.2.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `m2l2ap2`
--

-- --------------------------------------------------------

--
-- Structure de la table `etat`
--

CREATE TABLE `etat` (
  `idetat` int NOT NULL,
  `libelle` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Déchargement des données de la table `etat`
--

INSERT INTO `etat` (`idetat`, `libelle`) VALUES
(1, 'Libre'),
(2, 'Occupée');

-- --------------------------------------------------------

--
-- Structure de la table `reservation`
--

CREATE TABLE `reservation` (
  `id_reservation` int NOT NULL,
  `date` date NOT NULL,
  `heure_debut` time NOT NULL,
  `idsalle` int NOT NULL,
  `heure_fin` time NOT NULL,
  `Nom` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Déchargement des données de la table `reservation`
--

INSERT INTO `reservation` (`id_reservation`, `date`, `heure_debut`, `idsalle`, `heure_fin`, `Nom`) VALUES
(2, '2023-05-05', '09:00:00', 4, '10:00:00', 'Saul'),
(3, '2023-10-10', '09:00:00', 1, '10:00:00', 'Saul'),
(4, '2023-10-05', '11:00:00', 6, '16:00:00', 'Saul'),
(5, '2023-10-05', '11:00:00', 8, '16:00:00', 'Saul'),
(6, '2023-05-03', '08:00:00', 1, '18:00:00', 'Saul');

-- --------------------------------------------------------

--
-- Structure de la table `role`
--

CREATE TABLE `role` (
  `idrole` int NOT NULL,
  `typerole` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Déchargement des données de la table `role`
--

INSERT INTO `role` (`idrole`, `typerole`) VALUES
(1, 'admin'),
(2, 'utilisateur');

-- --------------------------------------------------------

--
-- Structure de la table `salle`
--

CREATE TABLE `salle` (
  `idsalle` int NOT NULL,
  `nomsalle` varchar(30) NOT NULL,
  `idtype` int NOT NULL,
  `idetat` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Déchargement des données de la table `salle`
--

INSERT INTO `salle` (`idsalle`, `nomsalle`, `idtype`, `idetat`) VALUES
(1, 'R1', 1, 2),
(2, 'R2', 1, 1),
(3, 'R3', 1, 1),
(4, 'R4', 1, 1),
(5, 'R5', 1, 1),
(6, 'R6', 1, 1),
(7, 'R7', 1, 1),
(8, 'R8', 1, 1),
(9, 'A1', 2, 1),
(10, 'M1', 3, 1);

-- --------------------------------------------------------

--
-- Structure de la table `typesalle`
--

CREATE TABLE `typesalle` (
  `idtype` int NOT NULL,
  `nomtype` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Déchargement des données de la table `typesalle`
--

INSERT INTO `typesalle` (`idtype`, `nomtype`) VALUES
(1, 'salle de réunion'),
(2, 'amphithéâtre'),
(3, 'salle multimédia');

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

CREATE TABLE `users` (
  `iduser` int NOT NULL,
  `login` varchar(30) NOT NULL,
  `mdp` varchar(30) NOT NULL,
  `idrole` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Déchargement des données de la table `users`
--

INSERT INTO `users` (`iduser`, `login`, `mdp`, `idrole`) VALUES
(1, 'Henri', '1234', 1),
(2, 'Saul', '1234', 2);

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `etat`
--
ALTER TABLE `etat`
  ADD PRIMARY KEY (`idetat`);

--
-- Index pour la table `reservation`
--
ALTER TABLE `reservation`
  ADD PRIMARY KEY (`id_reservation`),
  ADD KEY `idsalle` (`idsalle`);

--
-- Index pour la table `role`
--
ALTER TABLE `role`
  ADD PRIMARY KEY (`idrole`);

--
-- Index pour la table `salle`
--
ALTER TABLE `salle`
  ADD PRIMARY KEY (`idsalle`),
  ADD KEY `idtype` (`idtype`),
  ADD KEY `idetat` (`idetat`);

--
-- Index pour la table `typesalle`
--
ALTER TABLE `typesalle`
  ADD PRIMARY KEY (`idtype`);

--
-- Index pour la table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`iduser`),
  ADD KEY `idrole` (`idrole`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `reservation`
--
ALTER TABLE `reservation`
  MODIFY `id_reservation` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT pour la table `salle`
--
ALTER TABLE `salle`
  MODIFY `idsalle` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT pour la table `users`
--
ALTER TABLE `users`
  MODIFY `iduser` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `salle`
--
ALTER TABLE `salle`
  ADD CONSTRAINT `fk_salle_etat` FOREIGN KEY (`idetat`) REFERENCES `etat` (`idetat`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `salle_ibfk_1` FOREIGN KEY (`idtype`) REFERENCES `typesalle` (`idtype`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Contraintes pour la table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_ibfk_1` FOREIGN KEY (`idrole`) REFERENCES `role` (`idrole`) ON DELETE RESTRICT ON UPDATE RESTRICT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
