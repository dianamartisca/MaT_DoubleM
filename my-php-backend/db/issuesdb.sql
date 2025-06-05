SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

CREATE OR REPLACE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `user_name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) DEFAULT NULL,
  `role` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_name` (`user_name`),
  UNIQUE KEY `email` (`email`)
);

CREATE OR REPLACE TABLE `requests` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL, 
  `email` varchar(255) NOT NULL, 
  `problem_type` enum('Masina', 'Bicicleta', 'Trotineta') NOT NULL, 
  `date_requested` datetime NOT NULL, 
  `description` text NOT NULL, 
  `images` text DEFAULT NULL, 
  `response` text DEFAULT NULL,
  `status` varchar(50) DEFAULT 'in asteptare', 
  PRIMARY KEY (`id`)
);

ALTER TABLE requests ADD done TINYINT(1) DEFAULT 0;

CREATE TABLE IF NOT EXISTS reviews (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(255) NOT NULL,
  `email` VARCHAR(255) NOT NULL,
  `text` TEXT NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE OR REPLACE TABLE comenzi_furnizori (
    id INT AUTO_INCREMENT PRIMARY KEY,
    produs VARCHAR(255),
    furnizor VARCHAR(255),
    cantitate INT,
    data_comanda DATE
);


CREATE OR REPLACE TABLE piese (
  id INT AUTO_INCREMENT PRIMARY KEY,
  denumire VARCHAR(100),
  categorie VARCHAR(50),
  cantitate INT
);

INSERT INTO users (`id`, `name`, `user_name`, `email`, `password`, `role`) VALUES
(1, 'Popescu Ana', 'Anika', 'ania@uaic.ro', 'xpass', 'client'),
(2, 'Admin', 'admin', 'admin@example.com', '1234', 'admin'),
(3, 'Tractoristu', 'bucsa', 'bucsa@email.com', '1234', 'mecanic');

INSERT INTO piese (`id`, `denumire`, `categorie`,`cantitate`) VALUES
(1, 'Placute frana auto','Masini',20),
(2, 'Lant bicicleta', 'Biciclete', 35),
(3, 'Baterie trotineta electrica', 'Trotinete', 12),
(4, 'Filtru aer motor', 'Masini', 25),
(5,'Camera roata', 'Biciclete', 40),
(6,'Maneta frana', 'Trotinete', 18),
(7,'Bec stop frana', 'Masini', 50),
(8,'Pompa manuala', 'Biciclete', 30),
(9,'Incarcator trotineta', 'Trotinete', 10),
(10,'Capace roti','Masini',10);


INSERT INTO comenzi_furnizori (id, produs, furnizor, cantitate, data_comanda) VALUES
(1, 'Baterii Masina', 'NRG SRL', 10, '2025-06-30'),
(2, 'Roti Bicicleta', 'VeliVelo', 15, '2025-06-20'),
(3, 'Lichid de parpriz', 'Fluids SRL', 30, '2025-07-25'),
(4, 'Stergatoare', 'Car Society', 45, '2025-07-21'),
(5, 'Ulei Motor', 'AutoLub SRL', 20, '2025-07-10');


INSERT INTO reviews (name, email, text) VALUES
('Andrei Popescu', 'andrei.auto@gmail.com', 'Foarte multumit! Mi-au inlocuit placutele in 30 de minute. Recomand.'),
('Elena Ionescu', 'elena_bike@yahoo.com', 'Service rapid si personal amabil. Am schimbat lantul de bicicleta.'),
('Bogdan Mihai', 'bogdan.trot@gmail.com', 'Mi-au reparat trotineta electrica, merge ca noua.');



INSERT INTO `requests` (`id`, `name`, `email`, `problem_type`, `date_requested`, `description`, `images`, `response`, `status`, `done`) VALUES
(2, 'Maciuc Mihai', 'maciucmihai2@gmail.com', 'Masina', '2025-06-19 17:00:00', 'Am mers cu turanul offroad si nu stiam ca nu are 4x4 si nu mai am scut sub masina.', '../uploads/masina1.jpeg', NULL, 'aprobata', 0),
(3, 'Mircea Cartarescu', 'maciucmihai2@gmail.com', 'Bicicleta', '2025-06-19 11:00:00', 'Am o cursiera dar mi s-a stricat frana', '../uploads/bicicleta1.jpg', NULL, 'aprobata', 0),
(4, 'Radu Trotinaru', 'radu.trotinaru@gmail.com', 'Trotineta', '2025-06-19 13:00:00', 'Mergeam cu trotineta si am cazut intr-o groapa si cred ca s-a stricat', '../uploads/trotineta1.jpg', NULL, 'aprobata', 0),
(5, 'Calin Georgescu', 'calin.georgescu@gmail.com', 'Masina', '2025-06-19 16:00:00', 'mi-au furat astia catalizatorul, o stii pe aia cu apa?', '../uploads/masina2.png', NULL, 'in asteptare', 0),
(6, 'Mita Biciclista', 'maciucmihai2@gmail.com', 'Bicicleta', '2025-06-19 10:00:00', 'mi-am gasit bicicleta stricata puteti sa o reparati?', '../uploads/bicicleta2.jpg', NULL, 'in asteptare', 0),
(7, 'Ciprian Trotian', 'maciucmihai2@gmail.com', 'Masina', '2025-06-20 15:00:00', 'Mi s-a rupt maneta de acceleratie puteti sa mi-o schimbati?', '../uploads/trotineta2.jpg', NULL, 'in asteptare', 0);


COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;