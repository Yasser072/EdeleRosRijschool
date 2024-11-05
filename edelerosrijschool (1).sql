-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Gegenereerd op: 05 nov 2024 om 10:09
-- Serverversie: 10.4.20-MariaDB
-- PHP-versie: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `edelerosrijschool`
--

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `gebruikers`
--

CREATE TABLE `gebruikers` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('leerling','docent','admin') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Gegevens worden geëxporteerd voor tabel `gebruikers`
--

INSERT INTO `gebruikers` (`id`, `username`, `email`, `password`, `role`) VALUES
(1, 'admin', 'admin@example.com', '$2y$10$ApCWuM0UcvapFG7K0wtbjukIMeKr4rZke65vZt/KzS0eAgAcD1Yqm', 'admin'),
(2, 'Jaap', 'jaap@example.com', '$2y$10$oxyySiWf7KIWiZVDU2D2TuiTlHVJZC3acd1GWrxBio2oqNEwL8xl.', 'docent'),
(3, 'Hans', 'hans@example.com', '$2y$10$e0R3mc/YCzqtcS4dvx3J2ev4U6CkFqy/uZ7H48Dfs6p7TI/LZwAe.', 'docent'),
(4, 'Peter', 'peter@example.com', '$2y$10$DIokLMQWWZHbTefT17bA6untRCZbRhTytQ96nJ.8Th6/OU7CUNAA6', 'docent'),
(5, 'Jan', 'jan@example.com', '$2y$10$8oodWLF1oO2h71HCpl4P5etAcelFLEDrkM/zwdRwkdineCzOtmaK6', 'docent'),
(6, 'Tim', 'tim@example.com', '$2y$10$P0tmNGbnHv6Ysx99iO.fVuLsBjfyQlrMKwoyGpu5aSMNGUUXJXOOO', 'docent'),
(7, 'yk', 'yk@hotmail.com', '$2y$10$gRCSATjnU33LFDHTStKVmuDDCam6Lc.9C0lwci8.FJ50oHnBOqjka', 'leerling'),
(14, 'Yasser@hotmail.com', 'Yasser@hotmail.com', '$2y$10$sytWTG39PxqNI5ShtBxoc.cipHO1uUaUpZfQ5E6EuXGJkKgQ.FhOq', 'leerling'),
(61, 'docent', 'docent@test.com', '$2y$10$dbHlQB/dA6PCCUuloAphLuigtKKGgLThXixuxYHYWMpslJGcXL9N.', 'docent'),
(62, 'test', 'test@test', '$2y$10$Rosdb1JMnu0zVaAi3XyY4eyW7HTJ0G3uG75HqRp.Qd9.VCermoPke', 'docent');

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `leerlingen`
--

CREATE TABLE `leerlingen` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `lessen`
--

CREATE TABLE `lessen` (
  `id` int(11) NOT NULL,
  `datum` date NOT NULL,
  `tijd` time NOT NULL,
  `instructeur` varchar(50) NOT NULL,
  `instructeur_id` int(11) NOT NULL,
  `titel` varchar(255) NOT NULL,
  `omschrijving` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Gegevens worden geëxporteerd voor tabel `lessen`
--

INSERT INTO `lessen` (`id`, `datum`, `tijd`, `instructeur`, `instructeur_id`, `titel`, `omschrijving`) VALUES
(53, '2024-10-24', '12:36:00', '', 2, 'test', 'testen'),
(54, '2024-10-24', '13:14:00', '', 2, 'test', 'test'),
(56, '2024-10-28', '16:33:00', '', 2, 'Notificatie test', 'Je hebt les!'),
(58, '2024-10-31', '16:50:00', '', 2, 'test', 'test'),
(60, '2024-11-02', '17:56:00', '', 2, 'dsf', 'sdf'),
(62, '2024-10-29', '17:30:00', '', 2, 'test', 'test');

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `les_leerlingen`
--

CREATE TABLE `les_leerlingen` (
  `id` int(11) NOT NULL,
  `les_id` int(11) NOT NULL,
  `leerling_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Gegevens worden geëxporteerd voor tabel `les_leerlingen`
--

INSERT INTO `les_leerlingen` (`id`, `les_id`, `leerling_id`) VALUES
(56, 53, 7),
(59, 56, 7),
(60, 56, 14),
(63, 58, 7),
(64, 58, 14),
(67, 60, 7),
(68, 60, 14),
(71, 62, 7);

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `notificaties`
--

CREATE TABLE `notificaties` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `message` text NOT NULL,
  `is_read` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Gegevens worden geëxporteerd voor tabel `notificaties`
--

INSERT INTO `notificaties` (`id`, `user_id`, `message`, `is_read`, `created_at`) VALUES
(1, 7, 'Je hebt je ziek gemeld op 2024-10-27.', 1, '2024-10-27 13:28:22'),
(2, 2, 'Er is een nieuwe les toegevoegd.', 1, '2024-10-27 13:29:09'),
(3, 7, 'Er is een nieuwe les toegevoegd op 2024-10-28 om 16:33: Notificatie test.', 1, '2024-10-27 13:33:27'),
(4, 14, 'Er is een nieuwe les toegevoegd op 2024-10-28 om 16:33: Notificatie test.', 0, '2024-10-27 13:33:27'),
(5, 7, 'Nieuwe les toegevoegd: Barca op 2024-10-29 om 15:36.', 1, '2024-10-27 13:36:50'),
(6, 14, 'Nieuwe les toegevoegd: Barca op 2024-10-29 om 15:36.', 0, '2024-10-27 13:36:50'),
(7, 7, 'Nieuwe les toegevoegd: test op 2024-10-31 om 16:50.', 1, '2024-10-27 13:50:27'),
(8, 14, 'Nieuwe les toegevoegd: test op 2024-10-31 om 16:50.', 0, '2024-10-27 13:50:27'),
(9, 7, 'Je hebt je ziek gemeld op 2024-10-27.', 0, '2024-10-27 13:51:54'),
(10, 7, 'Nieuwe les toegevoegd: elclasico op 2024-11-01 om 08:59.', 1, '2024-10-27 13:55:59'),
(11, 14, 'Nieuwe les toegevoegd: elclasico op 2024-11-01 om 08:59.', 0, '2024-10-27 13:55:59'),
(12, 7, 'Nieuwe les toegevoegd: dsf op 2024-11-02 om 17:56.', 1, '2024-10-27 13:56:34'),
(13, 14, 'Nieuwe les toegevoegd: dsf op 2024-11-02 om 17:56.', 0, '2024-10-27 13:56:34'),
(14, 7, 'Nieuwe les toegevoegd: Barca!.', 1, '2024-10-27 14:00:00'),
(15, 14, 'Nieuwe les toegevoegd: Barca!.', 0, '2024-10-27 14:00:00'),
(16, 7, 'Nieuwe les toegevoegd: test.', 0, '2024-10-27 14:08:56'),
(17, 7, 'Je hebt je ziek gemeld op 2024-10-29.', 1, '2024-10-28 11:57:24'),
(18, 2, 'Leerling yk heeft zich ziek gemeld op 2024-10-31.', 1, '2024-10-28 12:34:49'),
(19, 2, 'Leerling yk heeft zich ziek gemeld op 2024-11-01.', 1, '2024-10-28 12:34:57'),
(20, 2, 'Leerling yk heeft zich ziek gemeld op 2024-11-08.', 1, '2024-10-29 14:28:32'),
(21, 2, 'yk heeft zich ziek gemeld op 2024-11-10.', 0, '2024-10-29 14:32:15');

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `ziekmeldingen`
--

CREATE TABLE `ziekmeldingen` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `datum` date NOT NULL,
  `reden` text DEFAULT NULL,
  `rol` enum('leerling','docent') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Gegevens worden geëxporteerd voor tabel `ziekmeldingen`
--

INSERT INTO `ziekmeldingen` (`id`, `user_id`, `datum`, `reden`, `rol`) VALUES
(1, 7, '2024-10-24', 'Ik ben ziek\r\n', 'leerling'),
(3, 7, '2024-10-17', 'test', 'leerling'),
(4, 2, '2024-10-24', 'test', 'leerling'),
(13, 7, '2024-11-08', 'Test', 'leerling'),
(16, 2, '2024-10-24', 'ziek', 'leerling');

--
-- Indexen voor geëxporteerde tabellen
--

--
-- Indexen voor tabel `gebruikers`
--
ALTER TABLE `gebruikers`
  ADD PRIMARY KEY (`id`);

--
-- Indexen voor tabel `leerlingen`
--
ALTER TABLE `leerlingen`
  ADD PRIMARY KEY (`id`);

--
-- Indexen voor tabel `lessen`
--
ALTER TABLE `lessen`
  ADD PRIMARY KEY (`id`);

--
-- Indexen voor tabel `les_leerlingen`
--
ALTER TABLE `les_leerlingen`
  ADD PRIMARY KEY (`id`),
  ADD KEY `les_id` (`les_id`),
  ADD KEY `leerling_id` (`leerling_id`);

--
-- Indexen voor tabel `notificaties`
--
ALTER TABLE `notificaties`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexen voor tabel `ziekmeldingen`
--
ALTER TABLE `ziekmeldingen`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- AUTO_INCREMENT voor geëxporteerde tabellen
--

--
-- AUTO_INCREMENT voor een tabel `gebruikers`
--
ALTER TABLE `gebruikers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=75;

--
-- AUTO_INCREMENT voor een tabel `leerlingen`
--
ALTER TABLE `leerlingen`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT voor een tabel `lessen`
--
ALTER TABLE `lessen`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=63;

--
-- AUTO_INCREMENT voor een tabel `les_leerlingen`
--
ALTER TABLE `les_leerlingen`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=72;

--
-- AUTO_INCREMENT voor een tabel `notificaties`
--
ALTER TABLE `notificaties`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT voor een tabel `ziekmeldingen`
--
ALTER TABLE `ziekmeldingen`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- Beperkingen voor geëxporteerde tabellen
--

--
-- Beperkingen voor tabel `les_leerlingen`
--
ALTER TABLE `les_leerlingen`
  ADD CONSTRAINT `les_leerlingen_ibfk_1` FOREIGN KEY (`les_id`) REFERENCES `lessen` (`id`),
  ADD CONSTRAINT `les_leerlingen_ibfk_2` FOREIGN KEY (`leerling_id`) REFERENCES `gebruikers` (`id`);

--
-- Beperkingen voor tabel `notificaties`
--
ALTER TABLE `notificaties`
  ADD CONSTRAINT `notificaties_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `gebruikers` (`id`);

--
-- Beperkingen voor tabel `ziekmeldingen`
--
ALTER TABLE `ziekmeldingen`
  ADD CONSTRAINT `ziekmeldingen_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `gebruikers` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
