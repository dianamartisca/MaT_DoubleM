SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `user_name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) DEFAULT NULL,
  `role` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_name` (`user_name`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `users` (`id`, `name`, `user_name`, `email`, `password`, `role`) VALUES
(1, 'Popescu Ana', 'Anika', 'ania@uaic.ro', 'xpass', 'client'),
(2, 'Admin', 'admin', 'admin@example.com', '1234', 'admin');

CREATE TABLE `requests` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL, 
  `email` varchar(255) NOT NULL, 
  `problem_type` enum('Masina', 'Bicicleta', 'Trotineta') NOT NULL, 
  `date_requested` datetime NOT NULL, 
  `description` text NOT NULL, 
  `images` text DEFAULT NULL, 
  `response` text DEFAULT NULL, 
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


CREATE TABLE IF NOT EXISTS comenzi_furnizori (
    id INT AUTO_INCREMENT PRIMARY KEY,
    produs VARCHAR(255),
    furnizor VARCHAR(255),
    cantitate INT,
    data_comanda DATE
);


CREATE TABLE IF NOT EXISTS piese (
  id INT AUTO_INCREMENT PRIMARY KEY,
  denumire VARCHAR(100),
  categorie VARCHAR(50),
  cantitate INT
);

INSERT INTO `piese` (`id`, `denumire`, `categorie`,`cantitate`) VALUES
(1, 'Plăcuțe frână auto','Masini',20),
(2, 'Lanț bicicletă', 'Biciclete', 35),
(3, 'Baterie trotinetă electrică', 'Trotinete', 12);


COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;