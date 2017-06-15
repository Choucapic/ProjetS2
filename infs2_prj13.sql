-- phpMyAdmin SQL Dump
-- version 4.4.15.10
-- https://www.phpmyadmin.net
--
-- Client :  mysql
-- Généré le :  Jeu 15 Juin 2017 à 16:09
-- Version du serveur :  5.5.50-MariaDB
-- Version de PHP :  5.4.16

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données :  `infs2_prj13`
--

-- --------------------------------------------------------

--
-- Structure de la table `caracteristique`
--

CREATE TABLE IF NOT EXISTS `caracteristique` (
  `id` smallint(6) NOT NULL,
  `nom` varchar(200) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

--
-- Contenu de la table `caracteristique`
--

INSERT INTO `caracteristique` (`id`, `nom`) VALUES
(1, 'poids'),
(2, 'couleur');

-- --------------------------------------------------------

--
-- Structure de la table `marque`
--

CREATE TABLE IF NOT EXISTS `marque` (
  `id` smallint(6) NOT NULL,
  `nom` varchar(15) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

--
-- Contenu de la table `marque`
--

INSERT INTO `marque` (`id`, `nom`) VALUES
(1, 'Dell'),
(2, 'HP');

-- --------------------------------------------------------

--
-- Structure de la table `materiel`
--

CREATE TABLE IF NOT EXISTS `materiel` (
  `ref` varchar(20) NOT NULL,
  `prix` int(11) NOT NULL,
  `idM` smallint(6) NOT NULL,
  `idT` smallint(6) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `personne`
--

CREATE TABLE IF NOT EXISTS `personne` (
  `id` smallint(6) NOT NULL,
  `login` varchar(60) NOT NULL,
  `mdp` varchar(200) NOT NULL DEFAULT '',
  `nom` varchar(20) NOT NULL,
  `prenom` varchar(20) NOT NULL,
  `datns` date NOT NULL,
  `adr` varchar(32) DEFAULT NULL,
  `cp` char(5) DEFAULT NULL,
  `ville` char(32) DEFAULT NULL,
  `sexe` enum('Homme','Femme') DEFAULT NULL,
  `grade` enum('Administrateur','Membre') NOT NULL,
  `tel` char(20) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

--
-- Contenu de la table `personne`
--

INSERT INTO `personne` (`id`, `login`, `mdp`, `nom`, `prenom`, `datns`, `adr`, `cp`, `ville`, `sexe`, `grade`, `tel`) VALUES
(1, 'admin', 'd033e22ae348aeb5660fc2140aec35850c4da997', 'Admin', 'Istrateur', '1996-04-20', '88 rue du glouglou', '51100', 'REIMS', 'Homme', 'Administrateur', '0632050437'),
(2, 'test', 'a94a8fe5ccb19ba61c4c0873d391e987982fbbd3', 'Test', 'Ost&eacute;rone', '2017-06-02', '88 rue du glouglou dupr&eacute;', '51100', 'CHATIVESLE', 'Homme', 'Membre', '0666666651');

-- --------------------------------------------------------

--
-- Structure de la table `type`
--

CREATE TABLE IF NOT EXISTS `type` (
  `id` smallint(6) NOT NULL,
  `nom` varchar(15) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

--
-- Contenu de la table `type`
--

INSERT INTO `type` (`id`, `nom`) VALUES
(1, 'Souris'),
(2, 'Clavier');

-- --------------------------------------------------------

--
-- Structure de la table `valeurcaracteristique`
--

CREATE TABLE IF NOT EXISTS `valeurcaracteristique` (
  `id` smallint(6) NOT NULL,
  `ref` varchar(20) NOT NULL,
  `valeur` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Index pour les tables exportées
--

--
-- Index pour la table `caracteristique`
--
ALTER TABLE `caracteristique`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `marque`
--
ALTER TABLE `marque`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `materiel`
--
ALTER TABLE `materiel`
  ADD PRIMARY KEY (`ref`),
  ADD KEY `idM` (`idM`),
  ADD KEY `idT` (`idT`);

--
-- Index pour la table `personne`
--
ALTER TABLE `personne`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `type`
--
ALTER TABLE `type`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `valeurcaracteristique`
--
ALTER TABLE `valeurcaracteristique`
  ADD PRIMARY KEY (`id`,`ref`);

--
-- AUTO_INCREMENT pour les tables exportées
--

--
-- AUTO_INCREMENT pour la table `caracteristique`
--
ALTER TABLE `caracteristique`
  MODIFY `id` smallint(6) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT pour la table `marque`
--
ALTER TABLE `marque`
  MODIFY `id` smallint(6) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT pour la table `personne`
--
ALTER TABLE `personne`
  MODIFY `id` smallint(6) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT pour la table `type`
--
ALTER TABLE `type`
  MODIFY `id` smallint(6) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
--
-- Contraintes pour les tables exportées
--

--
-- Contraintes pour la table `materiel`
--
ALTER TABLE `materiel`
  ADD CONSTRAINT `materiel_ibfk_1` FOREIGN KEY (`idM`) REFERENCES `marque` (`id`),
  ADD CONSTRAINT `materiel_ibfk_2` FOREIGN KEY (`idT`) REFERENCES `type` (`id`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
