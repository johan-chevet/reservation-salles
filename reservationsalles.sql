START TRANSACTION;
-- SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
-- SET time_zone = "+00:00";
CREATE DATABASE IF NOT EXISTS reservationsalles CHARACTER SET utf8 COLLATE utf8_general_ci;
use reservationsalles;
--
-- Base de données : `livreor`
--

-- --------------------------------------------------------

--
-- Structure de la table `comments`
--

DROP TABLE IF EXISTS `reservations`;
CREATE TABLE IF NOT EXISTS `reservations` (
  `id` int NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `start` DATETIME NOT NULL,
  `end` DATETIME NOT NULL,
  `user_id` int NOT NULL,
  FOREIGN KEY(user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `login` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL
);

COMMIT;
