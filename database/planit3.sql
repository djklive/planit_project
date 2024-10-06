-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : ven. 04 oct. 2024 à 20:33
-- Version du serveur : 10.4.24-MariaDB
-- Version de PHP : 7.4.29

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `planit`
--

-- --------------------------------------------------------

--
-- Structure de la table `archived_projects`
--

CREATE TABLE `archived_projects` (
  `id` int(11) NOT NULL,
  `original_project_id` int(11) NOT NULL,
  `nom` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `date_fin` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `archived_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Structure de la table `avancement`
--

CREATE TABLE `avancement` (
  `id` int(11) NOT NULL,
  `id_tache` int(11) NOT NULL,
  `statut` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Structure de la table `messages`
--

CREATE TABLE `messages` (
  `id` int(11) NOT NULL,
  `expediteur_id` int(11) NOT NULL,
  `destinataire_id` int(11) NOT NULL,
  `contenu` text NOT NULL,
  `date_envoi` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Structure de la table `notifications`
--

CREATE TABLE `notifications` (
  `id` int(11) NOT NULL,
  `utilisateur_id` int(11) NOT NULL,
  `message` text NOT NULL,
  `date_creation` timestamp NOT NULL DEFAULT current_timestamp(),
  `lu` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Structure de la table `product_backlog`
--

CREATE TABLE `product_backlog` (
  `id` int(11) NOT NULL,
  `titre` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `priorite` enum('haute','moyenne','basse') NOT NULL,
  `date_creation` timestamp NOT NULL DEFAULT current_timestamp(),
  `nom_fonctionnalite` varchar(255) NOT NULL,
  `fonctionnalite` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `product_backlog`
--

INSERT INTO `product_backlog` (`id`, `titre`, `description`, `priorite`, `date_creation`, `nom_fonctionnalite`, `fonctionnalite`) VALUES
(2, '', 'cdqfqesrn,hkjhgfedqsfgh,n', 'haute', '2024-10-01 22:05:52', '', 'bonjours'),
(3, '', 'werew', 'haute', '2024-10-03 16:26:25', '', 'bonjour');

-- --------------------------------------------------------

--
-- Structure de la table `projects`
--

CREATE TABLE `projects` (
  `id` int(11) NOT NULL,
  `nom` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `date_fin` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `projects`
--

INSERT INTO `projects` (`id`, `nom`, `description`, `date_fin`) VALUES
(1, 'Projet vision pub app', 'Développement d\'une application mobile.', '2024-12-01 08:00:00'),
(2, 'Projet Beta paynkap', 'Refonte du site web principal de l\'entreprise.', '2024-11-15 08:00:00'),
(3, 'Projet Gadeaapp', 'Mise en place d\'un nouveau système de gestion de clients.', '2025-01-30 08:00:00'),
(4, 'Projet Deposymoney', 'Migration de la base de données vers un serveur cloud.', '2024-10-20 07:00:00'),
(5, 'Projet Eplogaby', 'Optimisation des processus internes avec un nouveau logiciel.', '2024-12-10 08:00:00');

-- --------------------------------------------------------

--
-- Structure de la table `projets`
--

CREATE TABLE `projets` (
  `id` int(11) NOT NULL,
  `nom_projet` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `budget` float DEFAULT NULL,
  `date_debut` date DEFAULT NULL,
  `date_fin` date DEFAULT NULL,
  `responsable_id` int(11) DEFAULT NULL,
  `date_creation` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `projets`
--

INSERT INTO `projets` (`id`, `nom_projet`, `description`, `budget`, `date_debut`, `date_fin`, `responsable_id`, `date_creation`) VALUES
(2, 'Hublots', 'app de mise en relation entre clients et prestataires', 5, NULL, NULL, 1, '2024-10-01 19:00:50');

-- --------------------------------------------------------

--
-- Structure de la table `sprints`
--

CREATE TABLE `sprints` (
  `id` int(11) NOT NULL,
  `nom` varchar(255) NOT NULL,
  `date_debut` datetime DEFAULT current_timestamp(),
  `date_fin` datetime DEFAULT NULL,
  `nom_sprint` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Structure de la table `sprint_backlog`
--

CREATE TABLE `sprint_backlog` (
  `id` int(11) NOT NULL,
  `product_backlog_id` int(11) NOT NULL,
  `sprint_id` int(11) NOT NULL,
  `statut` enum('à faire','en cours','terminé') NOT NULL,
  `date_debut` timestamp NULL DEFAULT NULL,
  `date_fin` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Structure de la table `taches`
--

CREATE TABLE `taches` (
  `id` int(11) NOT NULL,
  `id_fonctionnalite` int(11) NOT NULL,
  `nom_tache` varchar(255) NOT NULL,
  `id_developpeur` int(11) DEFAULT NULL,
  `statut` enum('à faire','en cours','terminé') DEFAULT 'à faire'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `taches`
--

INSERT INTO `taches` (`id`, `id_fonctionnalite`, `nom_tache`, `id_developpeur`, `statut`) VALUES
(1, 1, 'Boire', NULL, 'à faire'),
(3, 1, 'manger', NULL, 'à faire'),
(4, 2, 'dormir', 4, 'en cours'),
(5, 3, 'dormir', 4, 'à faire'),
(6, 2, 'danser', 9, 'terminé'),
(7, 2, 'dormir', NULL, 'à faire');

-- --------------------------------------------------------

--
-- Structure de la table `teams`
--

CREATE TABLE `teams` (
  `id` int(11) NOT NULL,
  `nom` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Structure de la table `team_members`
--

CREATE TABLE `team_members` (
  `id` int(11) NOT NULL,
  `team_id` int(11) NOT NULL,
  `utilisateur_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Structure de la table `utilisateurs`
--

CREATE TABLE `utilisateurs` (
  `id` int(11) NOT NULL,
  `nom` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `mot_de_passe` varchar(255) DEFAULT NULL,
  `role` enum('product_owner','admin','scrum_master','developpeur','autre_role') NOT NULL,
  `date_creation` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `utilisateurs`
--

INSERT INTO `utilisateurs` (`id`, `nom`, `email`, `mot_de_passe`, `role`, `date_creation`) VALUES
(1, 'enolla', 'visionpubnew@gmail.com', '$2y$10$47LadR3kNmaPQT1GoTvA4.o.Xu7CV.a.oBN72b5cE.dHaNIh.fjP6', 'admin', '2024-10-01 17:21:45'),
(2, 'ulrich', 'andechoudimi@gmail.com', '$2y$10$zoMBJoH1E35De3S88F4B2enHpwRDHTdsRsvwaXsoC72ocO8N/dOr2', 'product_owner', '2024-10-01 19:18:39'),
(3, 'tegounou', 'tegounou@gmail.com', '$2y$10$lXNGb2ZhYtYpNEnb/yXR9.eMsScGbqt4r.d2hi8LUWArexbIjY/vK', 'product_owner', '2024-10-01 21:13:30'),
(4, 'kamdoum', 'kamdoum@gmail.com', '$2y$10$HwrrKblo.gAoSiwMIQV1/O6ef/tTPmP/pP9rOucF.2SMYeBCmv7M.', 'developpeur', '2024-10-01 21:23:21'),
(5, 'kamdouma', 'kamdouma@gmail.com', '$2y$10$nYPCnDZ03yhPRluRQCP0.eyNwOO35KMkq4OUn/VYs8H74cEtr5e1G', 'scrum_master', '2024-10-01 21:32:19'),
(7, 'kamdoumaff', 'kamdoumaff@gmail.com', '$2y$10$0PYkQVICngndzVlUyJx28OAqWvoVPsD2w6xLs1O/9r3IZDIa0wv6K', '', '2024-10-01 22:01:35'),
(8, 'gabi', 'gabi@gmail.com', '$2y$10$zd87fR6CPwyrUnF9NurvoOpH0tDj/HAJYajpYoFrCTt0W15oxKAPm', 'scrum_master', '2024-10-01 22:07:29'),
(9, 'nick', 'kamdemnick@gmail.coom', '$2y$10$B40.Zx48fuWnxqZIHnkpC.SYnnyLnlTgZwrvo7lOVt./SwrubQm7C', 'developpeur', '2024-10-03 16:21:03');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `archived_projects`
--
ALTER TABLE `archived_projects`
  ADD PRIMARY KEY (`id`),
  ADD KEY `original_project_id` (`original_project_id`);

--
-- Index pour la table `avancement`
--
ALTER TABLE `avancement`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `expediteur_id` (`expediteur_id`),
  ADD KEY `destinataire_id` (`destinataire_id`);

--
-- Index pour la table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `utilisateur_id` (`utilisateur_id`);

--
-- Index pour la table `product_backlog`
--
ALTER TABLE `product_backlog`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `projects`
--
ALTER TABLE `projects`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `projets`
--
ALTER TABLE `projets`
  ADD PRIMARY KEY (`id`),
  ADD KEY `responsable_id` (`responsable_id`);

--
-- Index pour la table `sprints`
--
ALTER TABLE `sprints`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `sprint_backlog`
--
ALTER TABLE `sprint_backlog`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_backlog_id` (`product_backlog_id`),
  ADD KEY `sprint_id` (`sprint_id`);

--
-- Index pour la table `taches`
--
ALTER TABLE `taches`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `teams`
--
ALTER TABLE `teams`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `team_members`
--
ALTER TABLE `team_members`
  ADD PRIMARY KEY (`id`),
  ADD KEY `team_id` (`team_id`),
  ADD KEY `utilisateur_id` (`utilisateur_id`);

--
-- Index pour la table `utilisateurs`
--
ALTER TABLE `utilisateurs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `archived_projects`
--
ALTER TABLE `archived_projects`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `avancement`
--
ALTER TABLE `avancement`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `messages`
--
ALTER TABLE `messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `product_backlog`
--
ALTER TABLE `product_backlog`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT pour la table `projects`
--
ALTER TABLE `projects`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT pour la table `projets`
--
ALTER TABLE `projets`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT pour la table `sprints`
--
ALTER TABLE `sprints`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `sprint_backlog`
--
ALTER TABLE `sprint_backlog`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT pour la table `taches`
--
ALTER TABLE `taches`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT pour la table `teams`
--
ALTER TABLE `teams`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `team_members`
--
ALTER TABLE `team_members`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `utilisateurs`
--
ALTER TABLE `utilisateurs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `archived_projects`
--
ALTER TABLE `archived_projects`
  ADD CONSTRAINT `archived_projects_ibfk_1` FOREIGN KEY (`original_project_id`) REFERENCES `projects` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `messages`
--
ALTER TABLE `messages`
  ADD CONSTRAINT `messages_ibfk_1` FOREIGN KEY (`expediteur_id`) REFERENCES `utilisateurs` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `messages_ibfk_2` FOREIGN KEY (`destinataire_id`) REFERENCES `utilisateurs` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `notifications`
--
ALTER TABLE `notifications`
  ADD CONSTRAINT `notifications_ibfk_1` FOREIGN KEY (`utilisateur_id`) REFERENCES `utilisateurs` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `projets`
--
ALTER TABLE `projets`
  ADD CONSTRAINT `projets_ibfk_1` FOREIGN KEY (`responsable_id`) REFERENCES `utilisateurs` (`id`);

--
-- Contraintes pour la table `sprint_backlog`
--
ALTER TABLE `sprint_backlog`
  ADD CONSTRAINT `sprint_backlog_ibfk_1` FOREIGN KEY (`product_backlog_id`) REFERENCES `product_backlog` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `sprint_backlog_ibfk_2` FOREIGN KEY (`sprint_id`) REFERENCES `sprints` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `team_members`
--
ALTER TABLE `team_members`
  ADD CONSTRAINT `team_members_ibfk_1` FOREIGN KEY (`team_id`) REFERENCES `teams` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `team_members_ibfk_2` FOREIGN KEY (`utilisateur_id`) REFERENCES `utilisateurs` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
