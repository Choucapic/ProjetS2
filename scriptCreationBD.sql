-- phpMyAdmin SQL Dump
-- version 4.6.4
-- https://www.phpmyadmin.net/
--
-- Client :  127.0.0.1
-- Généré le :  Lun 19 Juin 2017 à 23:34
-- Version du serveur :  5.7.14
-- Version de PHP :  5.6.25

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

CREATE TABLE `caracteristique` (
  `idC` smallint(6) NOT NULL,
  `nomC` varchar(25) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Contenu de la table `caracteristique`
--

INSERT INTO `caracteristique` (`idC`, `nomC`) VALUES
(1, 'Couleur'),
(2, 'Poids');

-- --------------------------------------------------------

--
-- Structure de la table `marque`
--

CREATE TABLE `marque` (
  `idM` smallint(6) NOT NULL,
  `nomM` varchar(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Contenu de la table `marque`
--

INSERT INTO `marque` (`idM`, `nomM`) VALUES
(1, 'HP'),
(2, 'DELL');

-- --------------------------------------------------------

--
-- Structure de la table `materiel`
--

CREATE TABLE `materiel` (
  `ref` varchar(20) NOT NULL,
  `prix` int(11) NOT NULL,
  `idM` smallint(6) NOT NULL,
  `idT` smallint(6) NOT NULL,
  `idP` smallint(6) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Contenu de la table `materiel`
--

INSERT INTO `materiel` (`ref`, `prix`, `idM`, `idT`, `idP`) VALUES
('5269XP', 100, 1, 2, 1),
('DX15', 550, 1, 1, 1);

-- --------------------------------------------------------

--
-- Structure de la table `personne`
--

CREATE TABLE `personne` (
  `idP` smallint(6) NOT NULL,
  `login` varchar(60) NOT NULL,
  `mdp` varchar(200) NOT NULL DEFAULT '',
  `nomP` varchar(20) NOT NULL,
  `prenom` varchar(20) NOT NULL,
  `datns` date NOT NULL,
  `adr` varchar(32) DEFAULT NULL,
  `cp` char(5) DEFAULT NULL,
  `ville` char(32) DEFAULT NULL,
  `sexe` enum('Homme','Femme') DEFAULT NULL,
  `grade` enum('Administrateur','Membre') NOT NULL,
  `tel` char(20) DEFAULT NULL,
  `datDepart` date DEFAULT NULL,
  `datAjout` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Contenu de la table `personne`
--

INSERT INTO `personne` (`idP`, `login`, `mdp`, `nomP`, `prenom`, `datns`, `adr`, `cp`, `ville`, `sexe`, `grade`, `tel`, `datDepart`, `datAjout`) VALUES
(1, 'admin', 'd033e22ae348aeb5660fc2140aec35850c4da997', 'Admin', 'Istrateur', '1996-04-20', '88 rue du glouglou', '51100', 'REIMS', 'Homme', 'Administrateur', '0632050437', NULL, '0000-00-00'),
(2, 'test', 'a94a8fe5ccb19ba61c4c0873d391e987982fbbd3', 'Test', 'Ost&eacute;rone', '2017-06-02', '88 rue du glouglou dupr&eacute;', '51100', 'CHATIVESLE', 'Homme', 'Membre', '0666666651', NULL, '0000-00-00'),
(4, 'desac', 'dd65791ecda0a17661a78d35ee7e9e3be147155f', 'lol', 'lol', '2017-06-13', 'lol', '51100', 'lol', 'Homme', 'Membre', '0666666666', '2017-06-19', '2017-06-19');

-- --------------------------------------------------------

--
-- Structure de la table `type`
--

CREATE TABLE `type` (
  `idT` smallint(6) NOT NULL,
  `nomT` varchar(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Contenu de la table `type`
--

INSERT INTO `type` (`idT`, `nomT`) VALUES
(1, 'Clavier'),
(2, 'Souris');

-- --------------------------------------------------------

--
-- Structure de la table `valeurcaracteristique`
--

CREATE TABLE `valeurcaracteristique` (
  `id` smallint(6) NOT NULL,
  `ref` varchar(20) NOT NULL,
  `valeur` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Contenu de la table `valeurcaracteristique`
--

INSERT INTO `valeurcaracteristique` (`id`, `ref`, `valeur`) VALUES
(1, '5269XP', 'Bleu'),
(1, 'DX15', 'Rouge'),
(2, 'DX15', '50');

--
-- Index pour les tables exportées
--

--
-- Index pour la table `caracteristique`
--
ALTER TABLE `caracteristique`
  ADD PRIMARY KEY (`idC`);

--
-- Index pour la table `marque`
--
ALTER TABLE `marque`
  ADD PRIMARY KEY (`idM`);

--
-- Index pour la table `materiel`
--
ALTER TABLE `materiel`
  ADD PRIMARY KEY (`ref`),
  ADD KEY `idM` (`idM`),
  ADD KEY `idT` (`idT`),
  ADD KEY `materiel_ibfk_3` (`idP`);

--
-- Index pour la table `personne`
--
ALTER TABLE `personne`
  ADD PRIMARY KEY (`idP`);

--
-- Index pour la table `type`
--
ALTER TABLE `type`
  ADD PRIMARY KEY (`idT`);

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
  MODIFY `idC` smallint(6) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT pour la table `marque`
--
ALTER TABLE `marque`
  MODIFY `idM` smallint(6) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT pour la table `personne`
--
ALTER TABLE `personne`
  MODIFY `idP` smallint(6) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT pour la table `type`
--
ALTER TABLE `type`
  MODIFY `idT` smallint(6) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- Contraintes pour les tables exportées
--

--
-- Contraintes pour la table `materiel`
--
ALTER TABLE `materiel`
  ADD CONSTRAINT `materiel_ibfk_1` FOREIGN KEY (`idM`) REFERENCES `marque` (`idM`),
  ADD CONSTRAINT `materiel_ibfk_2` FOREIGN KEY (`idT`) REFERENCES `type` (`idT`),
  ADD CONSTRAINT `materiel_ibfk_3` FOREIGN KEY (`idP`) REFERENCES `personne` (`idP`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
