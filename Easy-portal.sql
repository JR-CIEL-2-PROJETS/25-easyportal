-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Hôte : mysql
-- Généré le : mer. 12 fév. 2025 à 09:18
-- Version du serveur : 8.4.4
-- Version de PHP : 8.2.27

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `Easy-portal`
--

-- --------------------------------------------------------

--
-- Structure de la table `AdminUsers`
--

CREATE TABLE `AdminUsers` (
  `id` int NOT NULL,
  `email` varchar(255) NOT NULL,
  `mot_de_passe` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  `is_super_admin` tinyint(1) NOT NULL DEFAULT '0'
) ;

--
-- Déchargement des données de la table `AdminUsers`
--

INSERT INTO `AdminUsers` (`id`, `email`, `mot_de_passe`, `is_super_admin`) VALUES
(1, 'francois.b@gmail.com', 'test1', 1),
(5, 'testadmin@gmail.com', 'motdepasse', 0);

-- --------------------------------------------------------

--
-- Structure de la table `logs`
--

CREATE TABLE `logs` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `plaque` varchar(9) NOT NULL,
  `date_entree` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `action` enum('ouverture automatique','ouverture manuelle') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Structure de la table `plaques`
--

CREATE TABLE `plaques` (
  `id` int NOT NULL,
  `numero` varchar(9) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  `user_id` int DEFAULT NULL,
  `statut` enum('actif','bloqué') DEFAULT 'actif',
  `date_ajout` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

CREATE TABLE `users` (
  `id` int NOT NULL,
  `nom` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  `prenom` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  `email` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  `mot_de_passe` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Déchargement des données de la table `users`
--

INSERT INTO `users` (`id`, `nom`, `prenom`, `email`, `mot_de_passe`) VALUES
(1, 'Ballet', 'Francois', 'francois.b@gmail.com', 'azerty1!'),
(2, 'Merdji', 'Marwan', 'merdji.m@gmail.com', 'djidji93'),
(3, 'Surnin', 'Ivan', 'surnin.i@gmail.com', 'IvanMonkaOpp'),
(4, 'Kalleche', 'Medy', 'kalleche.m@gmail.com', 'med12');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `AdminUsers`
--
ALTER TABLE `AdminUsers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `only_one_super_admin` (`is_super_admin`);

--
-- Index pour la table `logs`
--
ALTER TABLE `logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Index pour la table `plaques`
--
ALTER TABLE `plaques`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `numero` (`numero`),
  ADD KEY `user_id` (`user_id`);

--
-- Index pour la table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `AdminUsers`
--
ALTER TABLE `AdminUsers`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `logs`
--
ALTER TABLE `logs`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `plaques`
--
ALTER TABLE `plaques`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `users`
--
ALTER TABLE `users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `logs`
--
ALTER TABLE `logs`
  ADD CONSTRAINT `logs_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `plaques`
--
ALTER TABLE `plaques`
  ADD CONSTRAINT `plaques_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
