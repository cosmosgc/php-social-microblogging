/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

CREATE TABLE IF NOT EXISTS `microblogs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `content` text NOT NULL,
  `embed_file` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `microblogs_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

DELETE FROM `microblogs`;
INSERT INTO `microblogs` (`id`, `user_id`, `content`, `embed_file`, `created_at`) VALUES
	(1, 1, 'Olha meu gato', 'https://media.tenor.com/38pL8CmHcPsAAAAS/cat.gif', '2023-08-02 18:10:51'),
	(10, 2, 'E esse é meu gato', 'https://cdn.discordapp.com/attachments/1135942222978752632/1136029054814929006/00164-312802817.png', '2023-08-02 18:27:54'),
	(12, 1, 'Oi', '', '2023-08-02 21:00:42');

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `avatar` varchar(255) NOT NULL DEFAULT 'default_avatar.png',
  `info` text DEFAULT NULL,
  `nickname` varchar(50) DEFAULT NULL,
  `tags` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

DELETE FROM `users`;
INSERT INTO `users` (`id`, `username`, `password`, `avatar`, `info`, `nickname`, `tags`) VALUES
	(1, 'cosmosgc', '$2y$10$2do7X917sI6PKQ3Wdu4jlu9pxGbfn9xzihATECnyo799YCgtHPvfi', 'https://cdn.discordapp.com/attachments/1135942222978752632/1136029054814929006/00164-312802817.png', NULL, NULL, NULL),
	(2, 'teste', '$2y$10$Muhxd382Vnd1o62WXWPpx.LkxI/S3/N3OeiMTqyiQXgZ/Nq.LMni6', 'default_avatar.png', NULL, NULL, NULL);

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
