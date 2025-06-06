-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Gép: 127.0.0.1
-- Létrehozás ideje: 2025. Már 03. 12:56
-- Kiszolgáló verziója: 10.4.28-MariaDB
-- PHP verzió: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Adatbázis: `gypowinery`
--

DELIMITER $$
--
-- Eljárások
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `GenerateCoupons` ()   BEGIN
    DECLARE i INT DEFAULT 0;
    DECLARE random_code VARCHAR(6);
    WHILE i < 1000 DO
        SET random_code = CONCAT(
            CHAR(FLOOR(65 + (RAND() * 26))),
            CHAR(FLOOR(65 + (RAND() * 26))),
            CHAR(FLOOR(48 + (RAND() * 10))),
            CHAR(FLOOR(65 + (RAND() * 26))),
            CHAR(FLOOR(48 + (RAND() * 10))),
            CHAR(FLOOR(65 + (RAND() * 26)))
        );
        INSERT IGNORE INTO kuponok (kupon_kod) VALUES (random_code);
       
        SET i = i + 1;
    END WHILE;
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `borok`
--

CREATE TABLE `borok` (
  `ID` int(11) NOT NULL,
  `nev` varchar(100) NOT NULL,
  `ar` int(7) NOT NULL,
  `leiras` varchar(500) NOT NULL,
  `keszlet` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_hungarian_ci;

--
-- A tábla adatainak kiíratása `borok`
--

INSERT INTO `borok` (`ID`, `nev`, `ar`, `leiras`, `keszlet`) VALUES
(1, 'Csévharaszti Kékfrankos', 79990, 'Ízjegyek: Gazdag gyümölcsös aromák, piros bogyós gyümölcsök és fűszeres jegyek. Tálalás: 16-18 °C között, tökéletes steakhez vagy grillezett húsokhoz.', 42),
(2, 'Csévharaszti Cabernet Sauvignon', 56999, 'Ízjegyek: Mély, komplex ízek, fekete ribizli és tölgyes árnyalatok. Tálalás: 18-20 °C, ajánljuk füstölt sajtokkal vagy gazdag ételekkel.', 40),
(3, 'Csévharaszti Olaszrizling', 53990, 'Ízjegyek: Friss és üde, citrusos és virágos aromákkal. Tálalás: 8-10 °C, kiváló választás tenger gyümölcseivel vagy salátákkal.', 36),
(4, 'Csévharaszti Zöld Veltelini', 47000, 'Ízjegyek: Könnyed, fűszeres ízek, zöldalma és fehér virágok. Tálalás: 10-12 °C, ideális éttermek előtt, könnyű ételek mellé.', 35),
(6, 'Csévharaszti Jégbor', 200000, 'Különleges édes borunk, amelyet fagyott szőlőből készítünk. Gazdag, mély ízű, édes és harmonikus. Tálalás: 6-8 °C, tökéletes desszertekhez vagy különleges alkalmakra.', 15),
(10, 'Csévharaszti Rosé', 120853, 'Ízjegyek: Friss, gyümölcsös ízvilág, eper és cseresznye jegyekkel. Tálalás: 8-10 °C, remekül illik piknikekhez vagy könnyű nyári fogásokhoz.', 20);

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `bor_kepek`
--

CREATE TABLE `bor_kepek` (
  `id` int(11) NOT NULL,
  `bor_id` int(11) NOT NULL,
  `kep_url` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_hungarian_ci;

--
-- A tábla adatainak kiíratása `bor_kepek`
--

INSERT INTO `bor_kepek` (`id`, `bor_id`, `kep_url`) VALUES
(1, 1, 'https://idrinks.cdn.shoprenter.hu/custom/idrinks/image/cache/w245h245/product/Bor/Hazai_borok/idrinks-sauska-cuvee-5.webp?lastmod=0.1666343376'),
(2, 2, 'https://idrinks.cdn.shoprenter.hu/custom/idrinks/image/cache/w245h245/product/Bor/Hazai_borok/idrinks-szepsy-6-puttonyos-aszu.jpg.webp?lastmod=0.1666343376'),
(3, 3, 'https://idrinks.cdn.shoprenter.hu/custom/idrinks/image/cache/w245h245/product/Bor/idrinks-vesztergombi-alpha-cuvee.jpg.webp?lastmod=0.1666343376'),
(4, 4, 'https://idrinks.cdn.shoprenter.hu/custom/idrinks/image/cache/w245h245/product/Bor/idrinks-mad-gold.jpg.webp?lastmod=0.1666343376'),
(5, 10, 'https://idrinks.cdn.shoprenter.hu/custom/idrinks/image/cache/w245h245/product/Bor/Hazai_Vino/idrinks-gere-attila-cuvee.jpg.webp?lastmod=0.1666343376'),
(6, 6, 'https://idrinks.cdn.shoprenter.hu/custom/idrinks/image/cache/w245h245/product/Bor/Dereszla/idrinks-chateau-dereszla-tokaji-aszu-eszencia-2008.jpg.webp?lastmod=0.1666343376');

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `cart`
--

CREATE TABLE `cart` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `bor_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_hungarian_ci;

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `kuponok`
--

CREATE TABLE `kuponok` (
  `id` int(11) NOT NULL,
  `kupon_kod` varchar(6) NOT NULL,
  `felhasznalt` tinyint(1) DEFAULT 0,
  `kiosztott` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_hungarian_ci;

--
-- Tábla szerkezet ehhez a táblához `login`
--

CREATE TABLE `login` (
  `ID` int(11) NOT NULL,
  `vezeteknev` varchar(255) NOT NULL,
  `keresztnev` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `telefonszam` varchar(20) DEFAULT NULL,
  `jelszo` varchar(255) NOT NULL,
  `quiz_completed` tinyint(1) DEFAULT 0,
  `usertype` varchar(50) NOT NULL DEFAULT '',
  `coupon_code` varchar(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_hungarian_ci;

--
-- A tábla adatainak kiíratása `login`
--

INSERT INTO `login` (`ID`, `vezeteknev`, `keresztnev`, `email`, `telefonszam`, `jelszo`, `quiz_completed`, `usertype`, `coupon_code`) VALUES
(17, 'Szekrényes', 'Gábor', 'alma@bela.hu', '06302452160', '$2y$10$LBGvUNXhvSDi/kriVlvEue3Jq1Fl0W7EnjHmXLPlVD2Xk5jF6mtpS', 0, 'admin', NULL),
(19, 'Bence', 'gabor', 'gabben878@hengersor.hu', '06202522207', '$2y$10$PD/2jRzVZtodPgIFfwZxO.M1Jp8KJr9RXW9ynIiGf3ob4nvPFr8Fm', 0, '', NULL),
(20, 'majzik', 'bence', 'majzi0421@gmail.com', '06300145396', '$2y$10$euQTOGPdv4qhE0ULICqBku4SIUsaJx2/PPyi7axljjkNd6A3nnZWW', 1, '', NULL),
(21, 'kovács', 'pista', 'asd@asd', '06302452160', '$2y$10$ZDfSTRcGwkeNodE0hiTp2ur0gQUU0kOigp.eLHQ5CR7wEY0TLRdBW', 1, '', 'BL0U8B');

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `quiz_questions`
--

CREATE TABLE `quiz_questions` (
  `id` int(11) NOT NULL,
  `question_text` text NOT NULL,
  `option_a` varchar(255) NOT NULL,
  `option_b` varchar(255) NOT NULL,
  `option_c` varchar(255) NOT NULL,
  `option_d` varchar(255) NOT NULL,
  `correct_option` char(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_hungarian_ci;

--
-- A tábla adatainak kiíratása `quiz_questions`
--

INSERT INTO `quiz_questions` (`id`, `question_text`, `option_a`, `option_b`, `option_c`, `option_d`, `correct_option`) VALUES
(1, 'Hol alakult meg a GypoWinery?', 'Csévharaszti régió', 'Tokaji régió', 'Villány', 'Eger', 'A'),
(2, 'Mikor ültették el a GypoWinery első szőlőültetvényét?', '1985', '1990', '2000', '2005', 'B'),
(3, 'Milyen technológiai újítást vezetett be a GypoWinery 2005-ben?', 'Organikus gazdálkodási módszerek', 'Modern borászat-technológiai újítások', 'Új szőlőültetvények elhelyezése', 'Nemzetközi forgalmazás', 'C'),
(4, 'Mi a GypoWinery küldetése?', 'A világ legnépszerűbb borát készíteni', 'Bemutatni a Csévharaszti terroir egyedülálló ízvilágát', 'Új technológiák bevezetése a borászatban', 'Csak vörösborokra koncentrálni', 'D'),
(5, 'Mi a GypoWinery elköteleződése?', 'Fenntarthatóság és a helyi közösségek támogatása', 'Nemzetközi piacokra való terjeszkedés', 'Csak hagyományos borászmódszerek alkalmazása', 'Csak édes borok készítése', 'A'),
(6, 'Milyen jövőbeli tervei vannak a GypoWinerynek?', 'Új helyszínek nyitása világszerte', 'Új borfajták bevezetése és a borászat bővítése', 'Csak vörösborokra koncentrálni', 'Borok kizárólagos online értékesítése', 'B'),
(7, 'Melyik vörösborunk illik legjobban steakhez vagy grillezett húsokhoz?', 'Csévharaszti Kékfrankos', 'Csévharaszti Cabernet Sauvignon', 'Csévharaszti Rosé', 'Csévharaszti Olaszrizling', 'C'),
(8, 'Milyen hőmérsékleten kell tálalni a GypoWinery Csévharaszti Zöld Veltelinit?', '8-10°C', '10-12°C', '16-18°C', '18-20°C', 'B'),
(9, 'Melyik borunkban található eper és cseresznye ízvilág, és tökéletes a nyári fogásokhoz?', 'Csévharaszti Cabernet Sauvignon', 'Csévharaszti Rosé', 'Csévharaszti Olaszrizling', 'Csévharaszti Kékfrankos', 'D'),
(10, 'Mi az ideális hőmérséklet a Csévharaszti Jégbor tálalásához?', '6-8°C', '8-10°C', '16-18°C', '18-20°C', 'A'),
(11, 'Mi a GypoWinery Mottója?', '„A legjobb módja, hogy élvezd egy pohár bort, ha megosztod egy barátoddal.” – Ismeretlen', '„Az élet túl rövid ahhoz, hogy rossz bort igyunk.” – Johann Wolfgang von Goethe', '„A bor folyamatos bizonyítéka annak, hogy Isten szeret minket, és szeret bennünket boldognak látni.” – Benjamin Franklin', '„A bor minden étkezést alkalmassá tesz, minden asztalt elegánsabbá, és minden napot civilizáltabbá.” – Andre Simon', 'B');

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `rendelesek`
--

CREATE TABLE `rendelesek` (
  `ID` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `rendeles_datuma` timestamp NOT NULL DEFAULT current_timestamp(),
  `statusz` varchar(20) DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_hungarian_ci;

--
-- A tábla adatainak kiíratása `rendelesek`
--

INSERT INTO `rendelesek` (`ID`, `user_id`, `rendeles_datuma`, `statusz`) VALUES
(5, 17, '2025-02-06 12:10:57', 'completed'),
(6, 20, '2025-02-13 13:54:11', 'completed'),
(7, 20, '2025-02-13 13:58:35', 'completed'),
(8, 17, '2025-02-26 07:12:12', 'completed'),
(9, 17, '2025-02-27 17:42:03', 'completed'),
(10, 17, '2025-03-03 11:53:55', 'completed');

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `rendeles_tetelek`
--

CREATE TABLE `rendeles_tetelek` (
  `ID` int(11) NOT NULL,
  `rendeles_id` int(11) DEFAULT NULL,
  `bor_id` int(11) DEFAULT NULL,
  `quantity` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_hungarian_ci;

--
-- A tábla adatainak kiíratása `rendeles_tetelek`
--

INSERT INTO `rendeles_tetelek` (`ID`, `rendeles_id`, `bor_id`, `quantity`) VALUES
(5, 5, 2, 8),
(6, 6, 2, 1),
(7, 7, 3, 1),
(8, 8, 2, 1),
(9, 9, 2, 1),
(10, 9, 1, 1),
(11, 10, 2, 1);

--
-- Indexek a kiírt táblákhoz
--

--
-- A tábla indexei `borok`
--
ALTER TABLE `borok`
  ADD PRIMARY KEY (`ID`);

--
-- A tábla indexei `bor_kepek`
--
ALTER TABLE `bor_kepek`
  ADD PRIMARY KEY (`id`),
  ADD KEY `bor_id` (`bor_id`);

--
-- A tábla indexei `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `bor_id` (`bor_id`);

--
-- A tábla indexei `login`
--
ALTER TABLE `login`
  ADD PRIMARY KEY (`ID`),
  ADD UNIQUE KEY `email` (`email`);

--
-- A tábla indexei `rendelesek`
--
ALTER TABLE `rendelesek`
  ADD PRIMARY KEY (`ID`);

--
-- A tábla indexei `rendeles_tetelek`
--
ALTER TABLE `rendeles_tetelek`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `rendeles_id` (`rendeles_id`),
  ADD KEY `bor_id` (`bor_id`);

--
-- A kiírt táblák AUTO_INCREMENT értéke
--

--
-- AUTO_INCREMENT a táblához `borok`
--
ALTER TABLE `borok`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT a táblához `bor_kepek`
--
ALTER TABLE `bor_kepek`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT a táblához `cart`
--
ALTER TABLE `cart`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT a táblához `login`
--
ALTER TABLE `login`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT a táblához `rendelesek`
--
ALTER TABLE `rendelesek`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT a táblához `rendeles_tetelek`
--
ALTER TABLE `rendeles_tetelek`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- Megkötések a kiírt táblákhoz
--

--
-- Megkötések a táblához `bor_kepek`
--
ALTER TABLE `bor_kepek`
  ADD CONSTRAINT `bor_kepek_ibfk_1` FOREIGN KEY (`bor_id`) REFERENCES `borok` (`ID`) ON DELETE CASCADE;

--
-- Megkötések a táblához `cart`
--
ALTER TABLE `cart`
  ADD CONSTRAINT `cart_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `login` (`ID`),
  ADD CONSTRAINT `cart_ibfk_2` FOREIGN KEY (`bor_id`) REFERENCES `borok` (`ID`);

--
-- Megkötések a táblához `rendeles_tetelek`
--
ALTER TABLE `rendeles_tetelek`
  ADD CONSTRAINT `rendeles_tetelek_ibfk_1` FOREIGN KEY (`rendeles_id`) REFERENCES `rendelesek` (`ID`),
  ADD CONSTRAINT `rendeles_tetelek_ibfk_2` FOREIGN KEY (`bor_id`) REFERENCES `borok` (`ID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

--
-- A tábla adatainak kiíratása `kuponok`
--

INSERT INTO `kuponok` (`id`, `kupon_kod`, `felhasznalt`, `kiosztott`) VALUES
(1, 'BL0U8B', 0, 1),
(2, 'RC4E3D', 0, 0),
(3, 'QW3D7D', 0, 0),
(4, 'LW0N4Y', 0, 0),
(5, 'EB7K8E', 0, 0),
(6, 'GT0W3W', 0, 0),
(7, 'NW7K6Z', 0, 0),
(8, 'XR6E9D', 0, 0),
(9, 'UR9V2S', 0, 0),
(10, 'UW9F1C', 0, 0),
(11, 'AU9N5L', 0, 0),
(12, 'KS3T5Q', 0, 0),
(13, 'LJ4Z6J', 0, 0),
(14, 'YM7A9X', 0, 0),
(15, 'MS1N2R', 0, 0),
(16, 'OV4W8S', 0, 0),
(17, 'BD5I9Y', 0, 0),
(18, 'UC0E6U', 0, 0),
(19, 'AQ0M2P', 1, 0),
(20, 'HS6D7K', 1, 0),
(21, 'US1K7S', 0, 0),
(22, 'CL9H6F', 1, 0),
(23, 'FM6Y7A', 1, 0),
(24, 'VC9J0C', 0, 0),
(25, 'IK0X3H', 0, 0),
(26, 'FH7C0B', 0, 0),
(27, 'EQ5B6W', 0, 0),
(28, 'LP7T6Z', 0, 0),
(29, 'XQ4L8R', 0, 0),
(30, 'AC4Y4E', 0, 0),
(31, 'RV1J2J', 0, 0),
(32, 'YQ2Q1D', 0, 0),
(33, 'CB0W2P', 0, 0),
(34, 'JZ7W0U', 0, 0),
(35, 'SF9F0S', 0, 0),
(36, 'LB0W3Z', 1, 1),
(37, 'XQ4J4Z', 0, 0),
(38, 'TT5G8F', 0, 0),
(39, 'SY6E0V', 0, 0),
(40, 'XB6Y7B', 0, 0),
(41, 'AW2T0T', 0, 0),
(42, 'SJ6E9F', 0, 0),
(43, 'DA8X0U', 0, 0),
(44, 'RB1Q6K', 0, 0),
(45, 'BA9U0R', 0, 0),
(46, 'KA8E3D', 0, 0),
(47, 'QV2V2Z', 0, 0),
(48, 'ZA1U4S', 0, 0),
(49, 'KT5S8W', 0, 0),
(50, 'YB5O0P', 0, 0),
(51, 'VL6W4N', 0, 0),
(52, 'JE8V5K', 0, 0),
(53, 'ET1S0Z', 0, 0),
(54, 'UZ5A2F', 0, 0),
(55, 'IZ8M7J', 0, 0),
(56, 'MH0P6K', 0, 0),
(57, 'FT1I2K', 0, 0),
(58, 'HC6X6O', 0, 0),
(59, 'XS9J1T', 0, 0),
(60, 'DN2P3T', 0, 0),
(61, 'VW9Z0L', 0, 0),
(62, 'YM5I9O', 0, 0),
(63, 'EC0Z7X', 0, 0),
(64, 'DB9H7Z', 0, 0),
(65, 'OW5K2B', 0, 0),
(66, 'OR5X8J', 0, 0),
(67, 'HL2A2J', 0, 0),
(68, 'ZU9Q1U', 0, 0),
(69, 'MD1O1C', 0, 0),
(70, 'ZT6F0S', 0, 0),
(71, 'HH6M2B', 0, 0),
(72, 'JQ1S1U', 0, 0),
(73, 'II5X8S', 0, 0),
(74, 'YM6Q2M', 0, 0),
(75, 'RU9N7Z', 0, 0),
(76, 'UA7T4W', 0, 0),
(77, 'CV8P5U', 0, 0),
(78, 'MA6Z0N', 0, 0),
(79, 'KJ6J6I', 0, 0),
(80, 'PB4A7U', 0, 0),
(81, 'QT8X0I', 0, 0),
(82, 'RH5V4Z', 0, 0),
(83, 'LI2M5F', 0, 0),
(84, 'JI4M8B', 0, 0),
(85, 'PV3K8B', 0, 0),
(86, 'SJ7T4B', 0, 0),
(87, 'VZ3X4K', 0, 0),
(88, 'UT3J8I', 0, 0),
(89, 'AB1V6R', 0, 0),
(90, 'KB9U9Q', 0, 0),
(91, 'DW8V5E', 0, 0),
(92, 'FQ4L7O', 0, 0),
(93, 'NW8M0O', 0, 0),
(94, 'VJ3R3X', 0, 0),
(95, 'KH2K2X', 0, 0),
(96, 'WR7N5A', 0, 0),
(97, 'MK6V3F', 0, 0),
(98, 'YD8T2X', 0, 0),
(99, 'WO2L6R', 0, 0),
(100, 'PZ1S1T', 0, 0),
(101, 'DL8U4B', 0, 0),
(102, 'XH6N6S', 0, 0),
(103, 'PW4T3L', 0, 0),
(104, 'DM8Y1V', 0, 0),
(105, 'RW2V2U', 0, 0),
(106, 'FS8D1L', 0, 0),
(107, 'RA1P6J', 0, 0),
(108, 'YN8M9E', 0, 0),
(109, 'DB0V1H', 0, 0),
(110, 'YY7B9M', 0, 0),
(111, 'SC2E0N', 0, 0),
(112, 'NE1I1V', 0, 0),
(113, 'SB0G0O', 0, 0),
(114, 'QN6S5V', 0, 0),
(115, 'IF9H5X', 0, 0),
(116, 'UH0H2Q', 0, 0),
(117, 'IQ2J0I', 0, 0),
(118, 'KB0F7G', 0, 0),
(119, 'AG2H7B', 0, 0),
(120, 'YN8S0A', 0, 0),
(121, 'AZ8D2W', 0, 0),
(122, 'QL3Q0C', 0, 0),
(123, 'LY4I3X', 0, 0),
(124, 'IA0G0Q', 0, 0),
(125, 'YW3J6A', 0, 0),
(126, 'GG4I4K', 0, 0),
(127, 'MH9Z0C', 0, 0),
(128, 'LZ4K7G', 0, 0),
(129, 'FE3G1Y', 0, 0),
(130, 'GK3R1V', 0, 0),
(131, 'QT8U5F', 0, 0),
(132, 'OC8Z2L', 0, 0),
(133, 'MA7L0Z', 0, 0),
(134, 'SP9U1N', 0, 0),
(135, 'BR2G4L', 0, 0),
(136, 'WC8D0W', 0, 0),
(137, 'DE3C5R', 0, 0),
(138, 'NR7O7Y', 0, 0),
(139, 'MR7A7Q', 0, 0),
(140, 'XQ4K6B', 0, 0),
(141, 'IL2W5D', 0, 0),
(142, 'CA8G6K', 0, 0),
(143, 'DM9G5U', 0, 0),
(144, 'LV7I3R', 0, 0),
(145, 'KX4L8V', 0, 0),
(146, 'RY6F2P', 0, 0),
(147, 'GM6W2S', 0, 0),
(148, 'WC8D0Z', 0, 0),
(149, 'SQ9V3C', 0, 0),
(150, 'PP2G5F', 0, 0),
(151, 'HT8G6K', 0, 0),
(152, 'DK6A0J', 0, 0),
(153, 'PY9V2Y', 0, 0),
(154, 'VH9X6S', 0, 0),
(155, 'OO2L4Y', 0, 0),
(156, 'IV2S7P', 0, 0),
(157, 'VJ2C7J', 0, 0),
(158, 'TN4S0K', 0, 0),
(159, 'TP6I8H', 0, 0),
(160, 'XO1W0T', 0, 0),
(161, 'NJ2F3Y', 0, 0),
(162, 'UA7T4D', 0, 0),
(163, 'EO1F6K', 0, 0),
(164, 'CJ5U1M', 0, 0),
(165, 'WX8U2V', 0, 0),
(166, 'JM1O2S', 0, 0),
(167, 'UR0E7A', 0, 0),
(168, 'BF7J4E', 0, 0),
(169, 'OF3A1R', 0, 0),
(170, 'VG7X3W', 0, 0),
(171, 'JG1D1O', 0, 0),
(172, 'DY4F8K', 0, 0),
(173, 'OP3V1G', 0, 0),
(174, 'WQ4K6C', 0, 0),
(175, 'MD1N1B', 0, 0),
(176, 'YM6O0I', 0, 0),
(177, 'SL1Q4Q', 0, 0),
(178, 'RK9U9E', 0, 0),
(179, 'FN9J8E', 0, 0),
(180, 'HX7Y5Y', 0, 0),
(181, 'ZE8W6O', 0, 0),
(182, 'XX7X4N', 0, 0),
(183, 'EJ3O8N', 0, 0),
(184, 'ZM3J6L', 0, 0),
(185, 'DG9W5Y', 0, 0),
(186, 'GJ1S0X', 0, 0),
(187, 'OA4D3I', 0, 0),
(188, 'PZ0O4N', 0, 0),
(189, 'HY8P2S', 0, 0),
(190, 'PX6S5N', 0, 0),
(191, 'ZJ8C9I', 0, 0),
(192, 'VG7X3Y', 0, 0),
(193, 'SV8A4I', 0, 0),
(194, 'FA4G9W', 0, 0),
(195, 'OG5A4E', 0, 0),
(196, 'LS2C7O', 0, 0),
(197, 'NY1Y1D', 0, 0),
(198, 'CA8A6H', 0, 0),
(199, 'NP4R9M', 0, 0),
(200, 'RY6J9M', 0, 0),
(201, 'TF9W5H', 0, 0),
(202, 'QL1Q5W', 0, 0),
(203, 'TE5J0F', 0, 0),
(204, 'XV4U6Q', 0, 0),
(205, 'KB1M9I', 0, 0),
(206, 'TV8X8K', 0, 0),
(207, 'NJ3N6P', 0, 0),
(208, 'CT3O7E', 0, 0),
(209, 'NE2T9I', 0, 0),
(210, 'ZY7Z6I', 0, 0),
(211, 'OY9X7Z', 0, 0),
(212, 'SP8M7M', 0, 0),
(213, 'CC0C3F', 0, 0),
(214, 'FI0M0U', 0, 0),
(215, 'UQ7Y4L', 0, 0),
(216, 'UQ7B9N', 0, 0),
(217, 'VQ5Z1B', 0, 0),
(218, 'QZ9Y8M', 0, 0),
(219, 'WW6W3Z', 0, 0),
(220, 'AA0J5Q', 0, 0),
(221, 'NR7Y3B', 0, 0),
(222, 'EQ7U7L', 0, 0),
(223, 'WB6A2Z', 0, 0),
(224, 'HO8M8B', 0, 0),
(225, 'PT9M5H', 0, 0),
(226, 'UD3H3A', 0, 0),
(227, 'BC3N5C', 0, 0),
(228, 'UU4Z4K', 0, 0),
(229, 'PT9M6U', 0, 0),
(230, 'ZP0M2V', 0, 0),
(231, 'KO4V7C', 0, 0),
(232, 'JM2B3M', 0, 0),
(233, 'MA5Q6J', 0, 0),
(235, 'KR1Y0N', 0, 0),
(236, 'KM1N0M', 0, 0),
(237, 'IG2S6F', 0, 0),
(238, 'CV8S1K', 0, 0),
(239, 'RE8O3Z', 0, 0),
(240, 'WK4D3B', 0, 0),
(241, 'LY3A0H', 0, 0),
(242, 'GH7S3V', 0, 0),
(243, 'YG3E8L', 0, 0),
(244, 'WA4K5K', 0, 0),
(245, 'MF5H7W', 0, 0),
(246, 'AN4W8T', 0, 0),
(247, 'DL7Q8M', 0, 0),
(248, 'RY7V9I', 0, 0),
(249, 'TS3U8T', 0, 0),
(250, 'FV4Y3W', 0, 0),
(251, 'FN9G3X', 0, 0),
(252, 'MS1L8T', 0, 0),
(253, 'HF2K3Q', 0, 0),
(254, 'DV5M7E', 0, 0),
(255, 'RX5T3H', 0, 0),
(256, 'LI3S5P', 0, 0),
(257, 'IU0Z7S', 0, 0),
(258, 'KU6D6U', 0, 0),
(259, 'ZM5G5G', 0, 0),
(260, 'MP6G3A', 0, 0),
(261, 'DP5Y9F', 0, 0),
(262, 'DZ5T0G', 0, 0),
(263, 'ZF0P9V', 0, 0),
(264, 'GW5J0A', 0, 0),
(265, 'CH2I9X', 0, 0),
(266, 'OA4E5G', 0, 0),
(267, 'NU5G5G', 0, 0),
(268, 'NU4Z4D', 0, 0),
(269, 'KP7C1P', 0, 0),
(270, 'KI4C1Q', 0, 0),
(271, 'OY0N4Q', 0, 0),
(272, 'VE4W8M', 0, 0),
(273, 'ZN5G6G', 0, 0),
(274, 'MP4R9U', 0, 0),
(275, 'ZQ2I8J', 0, 0),
(276, 'GC8T2B', 0, 0),
(277, 'OQ5S0Z', 0, 0),
(278, 'UZ5W6S', 0, 0),
(279, 'QY8J2F', 0, 0),
(280, 'JB3J9P', 0, 0),
(281, 'DV8R8G', 0, 0),
(282, 'QJ0C3O', 0, 0),
(283, 'SU8B6X', 0, 0),
(284, 'PJ0C4U', 0, 0),
(285, 'SC3Q9Y', 0, 0),
(286, 'VK4W0Q', 0, 0),
(287, 'CN3H3Y', 0, 0),
(288, 'QK0W2R', 0, 0),
(289, 'RG1H9P', 0, 0),
(290, 'IU9K1S', 0, 0),
(291, 'YO0Q0D', 0, 0),
(292, 'QV1L6A', 0, 0),
(293, 'CJ5W4T', 0, 0),
(294, 'JQ0L0S', 0, 0),
(295, 'MJ3L2Z', 0, 0),
(296, 'FA5O2L', 0, 0),
(297, 'MC0C2Y', 0, 0),
(298, 'ZD6B2D', 0, 0),
(299, 'XD8A4A', 0, 0),
(300, 'VA7N4N', 0, 0),
(301, 'LO5X8V', 0, 0),
(302, 'KP7A8G', 0, 0),
(303, 'OC8W8U', 0, 0),
(304, 'GY8Q4N', 0, 0),
(305, 'DB9R4J', 0, 0),
(306, 'JT6W5A', 0, 0),
(307, 'MJ3S4C', 0, 0),
(308, 'DL7G1Y', 0, 0),
(309, 'IT7S1R', 0, 0),
(310, 'VG6L3F', 0, 0),
(311, 'EG6L3H', 0, 0),
(312, 'MM9H6G', 0, 0),
(313, 'JD5O9E', 0, 0),
(314, 'AO7B9U', 0, 0),
(315, 'AQ1W8S', 0, 0),
(316, 'ZU0X2O', 0, 0),
(317, 'BQ9A0K', 0, 0),
(318, 'UR9Z9X', 0, 0),
(319, 'PI7W9K', 0, 0),
(320, 'BA9N8R', 0, 0),
(321, 'VE3J7N', 0, 0),
(322, 'MU5H8F', 0, 0),
(323, 'OF3B2G', 0, 0),
(324, 'LN1I1W', 0, 0),
(325, 'VO3A9X', 0, 0),
(326, 'OD9I7V', 0, 0),
(327, 'YF2R5R', 0, 0),
(328, 'TU7E7D', 0, 0),
(329, 'MZ5P4K', 0, 0),
(330, 'QA2B5S', 0, 0),
(331, 'TT4C0C', 0, 0),
(332, 'GX8K5I', 0, 0),
(333, 'DR0A1N', 0, 0),
(334, 'GT8D0X', 0, 0),
(335, 'JB1T2C', 0, 0),
(336, 'QY8J3O', 0, 0),
(337, 'VN9J8J', 0, 0),
(338, 'GA4C2U', 0, 0),
(339, 'EQ6L2V', 0, 0),
(340, 'KN4V6P', 0, 0),
(341, 'CR0L9H', 0, 0),
(342, 'QH6D8U', 0, 0),
(343, 'ND1K5N', 0, 0),
(344, 'ZJ8G6L', 0, 0),
(345, 'FW5H7A', 0, 0),
(346, 'VY2P1C', 0, 0),
(347, 'XF4M1J', 0, 0),
(348, 'GG4N2Q', 0, 0),
(349, 'LJ5N0R', 0, 0),
(350, 'HI8B8B', 0, 0),
(351, 'VX0T4Z', 0, 0),
(352, 'PA2I8D', 0, 0),
(353, 'GX6N7D', 0, 0),
(354, 'KP7X3Z', 0, 0),
(355, 'ZV2X7V', 0, 0),
(356, 'ZJ9Q4B', 0, 0),
(357, 'DL7Q7Y', 0, 0),
(358, 'JC3L1O', 0, 0),
(359, 'EI0F9I', 0, 0),
(360, 'QF1B8D', 0, 0),
(361, 'CB9Q3W', 0, 0),
(362, 'FN9E9H', 0, 0),
(363, 'QJ8A5Y', 0, 0),
(364, 'ZA1R8J', 0, 0),
(365, 'FY1V7G', 0, 0),
(366, 'AJ7N3I', 0, 0),
(367, 'NP4Q7X', 0, 0),
(368, 'FK3S4A', 0, 0),
(369, 'VC9N7B', 0, 0),
(370, 'EP5W6S', 0, 0),
(371, 'PV4R9W', 0, 0),
(372, 'JF0R1V', 0, 0),
(373, 'SY6H5A', 0, 0),
(374, 'HM4Y3V', 0, 0),
(375, 'CA7X1G', 0, 0),
(376, 'PI8D2T', 0, 0),
(377, 'ZS5O2J', 0, 0),
(378, 'FX8P3F', 0, 0),
(379, 'UJ4C1K', 0, 0),
(380, 'PU2S9K', 0, 0),
(381, 'FY0P7V', 0, 0),
(382, 'XC8U4W', 0, 0),
(383, 'ZH5R7Z', 0, 0),
(384, 'MO3E7K', 0, 0),
(385, 'PS8C9M', 0, 0),
(386, 'OG6L2X', 0, 0),
(387, 'VM8Y0K', 0, 0),
(388, 'WE1L6Y', 0, 0),
(389, 'SU8V5K', 0, 0),
(390, 'HH5X9V', 0, 0),
(391, 'LQ9S8Z', 0, 0),
(392, 'MK6A1O', 0, 0),
(393, 'MQ7R2C', 0, 0),
(394, 'VU5E3E', 0, 0),
(395, 'VO3C4D', 0, 0),
(396, 'FR7R1V', 0, 0),
(397, 'OH8H9X', 0, 0),
(398, 'QL3J8H', 0, 0),
(399, 'TW1E4M', 0, 0),
(400, 'HW5C9F', 0, 0),
(401, 'HX6N7Y', 0, 0),
(402, 'OA4G8Q', 0, 0),
(403, 'MN1C1J', 0, 0),
(404, 'KZ6H5R', 0, 0),
(405, 'XK3I7W', 0, 0),
(406, 'AM4Q9U', 0, 0),
(407, 'CB0H1R', 0, 0),
(408, 'BG1Z4G', 0, 0),
(409, 'XT0A9Q', 0, 0),
(410, 'KB0B1L', 0, 0),
(411, 'VU4A7N', 0, 0),
(412, 'JH3B1L', 0, 0),
(413, 'WX0K9O', 0, 0),
(414, 'ZG2M6U', 0, 0),
(415, 'AT7I4I', 0, 0),
(416, 'FE1F5H', 0, 0),
(417, 'TW1B8X', 0, 0),
(418, 'BO6T6A', 0, 0),
(419, 'FX8Q6D', 0, 0),
(420, 'XC7Q8D', 0, 0),
(421, 'FP4I4D', 0, 0),
(422, 'KO5C7T', 0, 0),
(423, 'HH5V5J', 0, 0),
(424, 'DO3B2E', 0, 0),
(425, 'CZ5V4A', 0, 0),
(426, 'NQ6D8X', 0, 0),
(427, 'ZE8U4S', 0, 0),
(428, 'FA4C2Y', 0, 0),
(429, 'YV3H3X', 0, 0),
(430, 'NW6W2R', 0, 0),
(431, 'RJ7P7E', 0, 0),
(432, 'MW8X7F', 0, 0),
(433, 'UH9D6Y', 0, 0),
(434, 'TY3D4Y', 0, 0),
(435, 'IU9G4M', 0, 0),
(436, 'DD3K9L', 0, 0),
(437, 'LV7K7M', 0, 0),
(438, 'CD3L0W', 0, 0),
(439, 'HW4P7V', 0, 0),
(440, 'XA4F6Q', 0, 0),
(441, 'GI9U0U', 0, 0),
(442, 'XB6F9B', 0, 0),
(443, 'NK3U7M', 0, 0),
(444, 'BX3W3H', 0, 0),
(445, 'JW2S7L', 0, 0),
(446, 'DJ3T6U', 0, 0),
(447, 'EK5K3T', 0, 0),
(448, 'QX5E1H', 0, 0),
(449, 'XS7R1V', 0, 0),
(450, 'OI8O1W', 0, 0),
(451, 'XB5N9I', 0, 0),
(452, 'QG3X4U', 0, 0),
(453, 'LU7D5J', 0, 0),
(454, 'GA4H9Y', 0, 0),
(455, 'WO1Z5S', 0, 0),
(456, 'AA8M6U', 0, 0),
(457, 'BY4P5C', 0, 0),
(458, 'UP6H6F', 0, 0),
(459, 'EG6R2M', 0, 0),
(460, 'PM6V1H', 0, 0),
(461, 'XS8C9O', 0, 0),
(462, 'YZ1Q7A', 0, 0),
(463, 'RK9M6U', 0, 0),
(464, 'YK1P4R', 0, 0),
(465, 'VC0E5G', 0, 0),
(466, 'NX0J7U', 0, 0),
(467, 'PO0Q9U', 0, 0),
(468, 'DI2G4S', 0, 0),
(469, 'AZ9S6G', 0, 0),
(470, 'HQ3U8A', 0, 0),
(471, 'KB0X4N', 0, 0),
(472, 'IC4C0D', 0, 0),
(473, 'IG3U9K', 0, 0),
(474, 'GZ2C8V', 0, 0),
(475, 'QR4K5L', 0, 0),
(476, 'QV2T9Q', 0, 0),
(477, 'GK2V5M', 0, 0),
(478, 'NE4L0X', 0, 0),
(479, 'IA0F8P', 0, 0),
(480, 'MO4J5Q', 0, 0),
(481, 'PA4Y4G', 0, 0),
(482, 'AL0A9P', 0, 0),
(483, 'DY3V0X', 0, 0),
(484, 'IX6H5W', 0, 0),
(485, 'TB1M9N', 0, 0),
(486, 'OF5V6S', 0, 0),
(487, 'QZ1N2X', 0, 0),
(488, 'SS5M8T', 0, 0),
(489, 'FT2W5K', 0, 0),
(490, 'GA2K2W', 0, 0),
(491, 'SZ6N5F', 0, 0),
(492, 'JG1Y3W', 0, 0),
(493, 'FO1X1W', 0, 0),
(494, 'ZI6J7T', 0, 0),
(495, 'KV9I8Z', 0, 0),
(496, 'MK6X6S', 0, 0),
(497, 'OO1Z4L', 0, 0),
(498, 'VS1R9M', 0, 0),
(499, 'QS6B3O', 0, 0),
(500, 'WO1D2X', 0, 0),
(501, 'SU7Q8L', 0, 0),
(502, 'ME4U5O', 0, 0),
(503, 'BQ0E7D', 0, 0),
(504, 'MC9R3O', 0, 0),
(505, 'XU3E8X', 0, 0),
(506, 'YY9S7S', 0, 0),
(507, 'GF2N8T', 0, 0),
(508, 'ER8E2W', 0, 0),
(509, 'MX0P7V', 0, 0),
(510, 'ZK0F8O', 0, 0),
(511, 'IZ8I1P', 0, 0),
(512, 'PD9H5D', 0, 0),
(513, 'UR9Y7U', 0, 0),
(514, 'VS0I3P', 0, 0),
(515, 'BL0T8S', 0, 0),
(516, 'FY1S1Q', 0, 0),
(517, 'UA6L0Y', 0, 0),
(518, 'NV6N8N', 1, 1),
(519, 'EH9A1N', 0, 0),
(520, 'HU1F6S', 0, 0),
(521, 'PW5B7K', 0, 0),
(522, 'XH7W0R', 0, 0),
(523, 'EU5J0J', 0, 0),
(524, 'OT1H0J', 0, 0),
(525, 'RG2O0J', 0, 0),
(526, 'VA5S9P', 0, 0),
(527, 'DU6X4P', 0, 0),
(528, 'RO8I2K', 0, 0),
(529, 'ES9P2G', 0, 0),
(530, 'RP9X7D', 0, 0),
(531, 'IH4J4H', 0, 0),
(532, 'CM1J3P', 0, 0),
(533, 'AG2H8L', 0, 0),
(534, 'ON9Y0L', 0, 0),
(535, 'YM6O0M', 0, 0),
(536, 'GT1H0J', 0, 0),
(537, 'SN4O5Z', 0, 0),
(538, 'GK1U4P', 0, 0),
(539, 'VJ2E1J', 0, 0),
(540, 'JQ0N3F', 0, 0),
(541, 'BR2B6E', 0, 0),
(542, 'UN1B8I', 0, 0),
(543, 'YR5S9O', 0, 0),
(544, 'XW6M5I', 0, 0),
(545, 'XQ4L7Q', 0, 0),
(546, 'VE4S2W', 0, 0),
(547, 'TB1J4J', 0, 0),
(548, 'IM5D1I', 0, 0),
(549, 'FB6D6R', 0, 0),
(550, 'OV4V7C', 0, 0),
(551, 'JO7Y5X', 0, 0),
(552, 'WP4N1J', 0, 0),
(553, 'IN5K1S', 0, 0),
(554, 'AA0A0I', 0, 0),
(555, 'KZ7X1A', 0, 0),
(556, 'QD8V5K', 0, 0),
(557, 'IK0B0C', 0, 0),
(558, 'JM3I5V', 0, 0),
(559, 'MV8P4L', 0, 0),
(560, 'WA5R7U', 0, 0),
(561, 'PS6C6X', 0, 0),
(562, 'NW7G9X', 0, 0),
(563, 'SV9K0D', 0, 0),
(564, 'MB8B7M', 0, 0),
(565, 'EJ4C1I', 0, 0),
(566, 'GG4Q7B', 0, 0),
(567, 'XH7B9K', 0, 0),
(568, 'FX7I2J', 0, 0),
(569, 'YR5U2T', 0, 0),
(570, 'CF8L7G', 0, 0),
(571, 'CR2Y0O', 0, 0),
(572, 'OC7N3A', 0, 0),
(573, 'ET2Y9C', 0, 0),
(574, 'QT8G4T', 0, 0),
(575, 'HF1F6I', 0, 0),
(576, 'XN8O3G', 0, 0),
(577, 'AL1P3B', 0, 0),
(578, 'DP6G3Z', 0, 0),
(579, 'YU1I2E', 0, 0),
(580, 'EH9B3P', 0, 0),
(581, 'WN0U7F', 0, 0),
(582, 'ZF0Q1Q', 0, 0),
(583, 'TX2N7M', 0, 0),
(584, 'AP0J6C', 0, 0),
(585, 'PT8B7K', 0, 0),
(586, 'UW8W6T', 0, 0),
(587, 'UP6L2X', 0, 0),
(588, 'TA8J2Y', 0, 0),
(589, 'CS1R8L', 0, 0),
(590, 'PP1C9H', 0, 0),
(591, 'TX2P1A', 0, 0),
(592, 'RI5Q6L', 0, 0),
(593, 'FR7V7M', 0, 0),
(594, 'BU8V5J', 0, 0),
(595, 'CJ6X6T', 0, 0),
(596, 'TL8E1G', 0, 0),
(597, 'WP2S7K', 1, 0),
(598, 'VA6B4Y', 0, 0),
(599, 'LK6C4D', 0, 0),
(600, 'EO2P1F', 0, 0),
(601, 'LP6J8I', 0, 0),
(602, 'ZZ9R6A', 0, 0),
(603, 'HK1S0A', 0, 0),
(604, 'AY7R3M', 0, 0),
(605, 'NE3Z9U', 0, 0),
(606, 'BA8G7Z', 0, 0),
(607, 'RL1M9F', 0, 0),
(608, 'HW4K8B', 0, 0),
(609, 'RH4E6V', 0, 0),
(610, 'BU7J6X', 0, 0),
(611, 'UE4T4X', 0, 0),
(612, 'EE3L9R', 0, 0),
(613, 'JV9K0F', 0, 0),
(614, 'WQ5Y9X', 0, 0),
(615, 'TZ6G3V', 0, 0),
(616, 'FQ4J5P', 0, 0),
(617, 'KE6S5W', 0, 0),
(618, 'NA6Y8O', 0, 0),
(619, 'EG7U8Z', 0, 0),
(620, 'FE2S8C', 0, 0),
(621, 'AR3P9E', 0, 0),
(622, 'XA2L4T', 0, 0),
(623, 'LZ5X8J', 0, 0),
(624, 'HM4X1V', 0, 0),
(625, 'WT2A2F', 0, 0),
(626, 'IZ8K3U', 0, 0),
(627, 'VT2C7J', 0, 0),
(628, 'QC4F5C', 0, 0),
(629, 'VY1T3R', 0, 0),
(630, 'HM4Q8P', 0, 0),
(631, 'JY6M4X', 0, 0),
(632, 'BN4V7I', 0, 0),
(633, 'IP0N4O', 0, 0),
(634, 'MT2C7I', 0, 0),
(635, 'KA9W3F', 0, 0),
(636, 'AK0A8L', 0, 0),
(637, 'MB8E2T', 0, 0),
(638, 'AT7N3I', 0, 0),
(639, 'MJ4F6S', 0, 0),
(640, 'OO2M6R', 0, 0),
(641, 'LH0G1E', 0, 0),
(642, 'JF0M3L', 0, 0),
(643, 'BZ7S4Y', 0, 0),
(644, 'LL8Z3Q', 0, 0),
(645, 'FE2S8Y', 0, 0),
(646, 'IU9H6M', 0, 0),
(647, 'JK9L3R', 0, 0),
(648, 'DQ7A6M', 0, 0),
(649, 'GX6R4A', 0, 0),
(650, 'UX1F4U', 0, 0),
(651, 'NF5E1J', 0, 0),
(652, 'JQ0K8F', 0, 0),
(653, 'IA1Q8F', 0, 0),
(654, 'NZ3W2T', 0, 0),
(655, 'WE2T9P', 0, 0),
(656, 'DV7D5C', 0, 0),
(657, 'AW2L6W', 0, 0),
(658, 'JI4L6C', 0, 0),
(659, 'NH9S7V', 0, 0),
(660, 'TI3Z6K', 0, 0),
(661, 'CF7F8M', 0, 0),
(662, 'YE1D2A', 0, 0),
(663, 'EV6W2P', 0, 0),
(664, 'HT7P6N', 0, 0),
(665, 'PN7I3U', 0, 0),
(666, 'XE1G8K', 0, 0),
(667, 'MF5C8X', 0, 0),
(668, 'ZE0N5J', 0, 0),
(669, 'BE6V1B', 0, 0),
(670, 'BC2Y0J', 1, 0),
(671, 'TQ8K3T', 1, 0),
(672, 'QZ8M7D', 0, 0),
(673, 'OL4A7R', 0, 0),
(674, 'ET2Z1R', 1, 0),
(675, 'CJ5V3K', 0, 0),
(676, 'XG5V6P', 0, 0),
(677, 'BO5F2W', 0, 0),
(678, 'JI4H1A', 0, 0),
(679, 'LH1Q8G', 0, 0),
(680, 'TA9K3Q', 0, 0),
(681, 'AF9J8Y', 0, 0),
(682, 'GK3Q0L', 0, 0),
(683, 'ZN6S6E', 0, 0),
(684, 'UL8X9C', 0, 0),
(685, 'QY6R3N', 0, 0),
(686, 'SY5V5G', 0, 0),
(687, 'PH6I6K', 0, 0),
(688, 'YP1T4A', 1, 0),
(689, 'RJ7U6R', 0, 0),
(690, 'MM9F3Y', 0, 0),
(691, 'UC1G9Y', 0, 0),
(692, 'YV3B4X', 0, 0),
(693, 'HR5N0R', 0, 0),
(694, 'EY2G5X', 0, 0),
(695, 'XU2A2G', 0, 0),
(696, 'OX9A1W', 0, 0),
(697, 'VO3B1V', 0, 0),
(698, 'ND1J3P', 0, 0),
(699, 'AG2Q2H', 0, 0),
(700, 'TY4L8X', 0, 0),
(701, 'YA1Z2J', 0, 0),
(702, 'BG0N3H', 0, 0),
(703, 'KD5E3E', 0, 0),
(704, 'WT1S0Z', 0, 0),
(705, 'TV9H5U', 0, 0),
(706, 'HD8S0I', 0, 0),
(707, 'KY4R8I', 0, 0),
(708, 'CL9J0A', 0, 0),
(709, 'ZW4O4U', 0, 0),
(710, 'KT4Z6F', 0, 0),
(711, 'FI1N2U', 0, 0),
(712, 'DH1R0J', 0, 0),
(713, 'NN9K0A', 0, 0),
(714, 'AZ9Q4D', 0, 0),
(715, 'LV7L8C', 0, 0),
(716, 'VW8M9L', 0, 0),
(717, 'HC6X6I', 0, 0),
(718, 'TT5O0M', 0, 0),
(719, 'IF1Z5V', 0, 0),
(720, 'ND0S5P', 0, 0),
(721, 'IT8E2S', 0, 0),
(722, 'TP7A8C', 0, 0),
(723, 'ZP0M2R', 0, 0),
(724, 'RJ8F4S', 0, 0),
(725, 'CL9F3H', 0, 0),
(726, 'HN7K6X', 0, 0),
(727, 'PG5X8P', 0, 0),
(728, 'MQ6I7W', 0, 0),
(729, 'BO7A8I', 0, 0),
(730, 'AA1M0U', 0, 0),
(731, 'WZ2K1Q', 0, 0),
(732, 'QJ8E2Y', 0, 0),
(733, 'VI2C8X', 0, 0),
(734, 'AH4O3Y', 0, 0),
(735, 'TY5Q6L', 0, 0),
(736, 'GZ0F9C', 0, 0),
(737, 'PS7P7C', 0, 0),
(738, 'EM0N6S', 0, 0),
(739, 'NL7L9K', 0, 0),
(740, 'GY0I4L', 0, 0),
(741, 'VW7A9P', 0, 0),
(742, 'EB7Q8O', 0, 0),
(743, 'CW0M4R', 0, 0),
(744, 'AD5H7D', 0, 0),
(745, 'FT1K6W', 0, 0),
(746, 'OD9N6M', 0, 0),
(747, 'RV1M7L', 0, 0),
(748, 'YH7R2G', 0, 0),
(749, 'MP6J9N', 0, 0),
(750, 'VO3X5W', 0, 0),
(751, 'VP5S9R', 0, 0),
(752, 'PW5D0Z', 0, 0),
(753, 'QG3C3L', 0, 0),
(754, 'EP4H1C', 0, 0),
(755, 'VY2K2E', 0, 0),
(756, 'AO7W3W', 0, 0),
(757, 'JH2L4Y', 0, 0),
(758, 'IT8B7P', 0, 0),
(759, 'SS4E4X', 0, 0),
(760, 'BP7X2S', 0, 0),
(761, 'SJ6K8D', 0, 0),
(762, 'DG8J3V', 0, 0),
(763, 'ZL3F1G', 1, 0),
(764, 'RS4Z6D', 0, 0),
(765, 'UQ7W1Y', 0, 0),
(766, 'JY5D8B', 0, 0),
(767, 'SG2G5G', 0, 0),
(768, 'MS0F9W', 0, 0),
(769, 'NC9F4L', 0, 0),
(770, 'AR2K1R', 0, 0),
(771, 'VE3I5O', 0, 0),
(772, 'FM7K7H', 0, 0),
(773, 'IU9I7A', 0, 0),
(774, 'SP6T7H', 0, 0),
(775, 'JV1F6R', 0, 0),
(776, 'JT7N2T', 0, 0),
(777, 'BZ7A6F', 0, 0),
(778, 'CU7J6X', 0, 0),
(779, 'RQ2D9P', 0, 0),
(780, 'ZF0N5E', 0, 0),
(781, 'FM8W6V', 0, 0),
(782, 'DF6P1R', 0, 0),
(783, 'AD5M6A', 0, 0),
(784, 'YS7T4T', 0, 0),
(785, 'OP2O0L', 0, 0),
(786, 'FP4J5R', 0, 0),
(787, 'RK9N8P', 0, 0),
(788, 'LN1G8O', 0, 0),
(789, 'DZ4O3E', 0, 0),
(790, 'RY6I6M', 0, 0),
(791, 'IG2N7K', 0, 0),
(792, 'RD6A1K', 0, 0),
(793, 'SJ5Y9Z', 0, 0),
(794, 'AD5P1A', 0, 0),
(795, 'RG1G6P', 0, 0),
(796, 'AH4F8L', 0, 0),
(797, 'SF0K9J', 0, 0),
(798, 'DQ6L1Q', 0, 0),
(799, 'RK9T7O', 0, 0),
(800, 'OC7L0V', 0, 0),
(801, 'CZ6F0V', 0, 0),
(802, 'YF2K4U', 0, 0),
(803, 'VQ7B8C', 0, 0),
(804, 'ZP9C6X', 0, 0),
(805, 'MV6O9E', 0, 0),
(806, 'AL2C6Z', 0, 0),
(807, 'XN9G3Y', 0, 0),
(808, 'QL3E9J', 0, 0),
(809, 'ZR4C2Y', 0, 0),
(810, 'XR6G3U', 0, 0),
(811, 'BV9L2W', 0, 0),
(812, 'SW2T8C', 0, 0),
(813, 'YK2V4A', 0, 0),
(814, 'OU2V4O', 0, 0),
(815, 'NA5M8W', 0, 0),
(816, 'TF7I2E', 0, 0),
(817, 'DF6P0J', 0, 0),
(818, 'UW9I5Z', 0, 0),
(819, 'CP7V9G', 0, 0),
(820, 'LJ5S9J', 0, 0),
(821, 'DP4T2U', 0, 0),
(822, 'JK0V1G', 0, 0),
(823, 'TZ7O7A', 0, 0),
(824, 'VD1K5S', 0, 0),
(825, 'US3J9Q', 0, 0),
(826, 'JV0A7O', 0, 0),
(827, 'PJ1J4I', 0, 0),
(828, 'DT3J9O', 0, 0),
(829, 'ZH4K5T', 0, 0),
(830, 'ZS6W5D', 0, 0),
(831, 'ZO8R6I', 0, 0),
(832, 'PA2K1O', 0, 0),
(833, 'HU0A8H', 0, 0),
(834, 'TA7U6S', 0, 0),
(835, 'UU6Q3S', 0, 0),
(836, 'PV3L0A', 0, 0),
(837, 'YO9I6D', 0, 0),
(838, 'TI4C1S', 0, 0),
(839, 'YP1X1V', 0, 0),
(840, 'UM9L3J', 0, 0),
(841, 'VB8A5P', 0, 0),
(842, 'IW3G0S', 0, 0),
(843, 'IM3K9R', 0, 0),
(844, 'JV0Y3E', 0, 0),
(845, 'SB1M0U', 0, 0),
(846, 'SF0K9P', 0, 0),
(847, 'EA6E8T', 0, 0),
(848, 'FS0D4V', 0, 0),
(849, 'VP5Z2K', 0, 0),
(850, 'FU4P8F', 0, 0),
(851, 'PK2A3P', 0, 0),
(852, 'ZD7G0P', 0, 0),
(853, 'UC1G9E', 0, 0),
(854, 'YF2L5Q', 0, 0),
(855, 'IV0X3E', 0, 0),
(856, 'RX4L9G', 0, 0),
(857, 'OB5T0Y', 0, 0),
(858, 'MS0I3R', 0, 0),
(859, 'IQ1W8R', 0, 0),
(860, 'UY3A9P', 0, 0),
(861, 'FJ0H2F', 0, 0),
(862, 'MR9X5D', 0, 0),
(863, 'YK1J5R', 0, 0),
(864, 'RJ8A5U', 0, 0),
(865, 'FR7O6M', 0, 0),
(866, 'OH7V9G', 0, 0),
(867, 'MO3Y8H', 0, 0),
(868, 'AF0K0U', 0, 0),
(869, 'XF3B2H', 0, 0),
(870, 'OW6Q3P', 0, 0),
(871, 'CQ9U1J', 0, 0),
(872, 'HL3J7S', 0, 0),
(873, 'HJ9T8C', 0, 0),
(874, 'XE1J3P', 0, 0),
(875, 'YZ9B3R', 0, 0),
(876, 'FB6C5J', 0, 0),
(877, 'GC7O4L', 0, 0),
(878, 'ZO8Q6C', 0, 0),
(879, 'QY7B9U', 0, 0),
(880, 'XD0R3U', 0, 0),
(881, 'XC7I5S', 0, 0),
(882, 'YP1C8C', 0, 0),
(883, 'WZ3Q2D', 0, 0),
(884, 'DG7F7X', 0, 0),
(885, 'LL8V6Z', 0, 0),
(886, 'WI0H2O', 0, 0),
(887, 'YC6V1O', 0, 0),
(888, 'DC9S6B', 0, 0),
(889, 'HG4M0R', 0, 0),
(890, 'JW0Y4F', 0, 0),
(891, 'WR7P8N', 0, 0),
(892, 'YF2N8W', 0, 0),
(893, 'TD3G3W', 0, 0),
(894, 'JE7E7B', 0, 0),
(895, 'ER7Z5Y', 0, 0),
(896, 'XU1I2H', 0, 0),
(897, 'SP9W4S', 0, 0),
(898, 'GE9J0W', 0, 0),
(899, 'JF0M2Z', 0, 0),
(900, 'CO3I5N', 0, 0),
(901, 'AO7Y5V', 0, 0),
(902, 'OI0C4X', 0, 0),
(903, 'DA6F0S', 0, 0),
(904, 'KX2K3J', 0, 0),
(905, 'WI9R6D', 0, 0),
(906, 'SF8W5K', 0, 0),
(907, 'HF1D1M', 0, 0),
(908, 'WY0H3X', 0, 0),
(909, 'OY1Y1A', 0, 0),
(910, 'RH3W2S', 0, 0),
(911, 'WD1H9D', 0, 0),
(912, 'PQ4E5L', 0, 0),
(913, 'LX2N7I', 0, 0),
(914, 'GJ0C4S', 0, 0),
(915, 'KV0N5K', 0, 0),
(916, 'ES0G9B', 0, 0),
(917, 'KW1F5C', 0, 0),
(918, 'WZ3S5O', 0, 0),
(919, 'DB8H7Z', 0, 0),
(920, 'OX8H1S', 0, 0),
(921, 'DQ6N5D', 0, 0),
(922, 'AU8C7L', 0, 0),
(923, 'BX3A0H', 0, 0),
(924, 'GI9T8D', 0, 0),
(925, 'ZO7H1T', 0, 0),
(926, 'LX1Y3Y', 0, 0),
(927, 'QK0G9Z', 0, 0),
(928, 'AF9C6V', 0, 0),
(929, 'IE8S9W', 0, 0),
(930, 'HX7U7M', 0, 0),
(931, 'DF7Y4P', 0, 0),
(932, 'PE0S5I', 0, 0),
(933, 'CP6K1I', 0, 0),
(934, 'IQ2E2V', 0, 0),
(935, 'JF0U7F', 0, 0),
(936, 'XV5Z4F', 0, 0),
(937, 'VM8X9C', 0, 0),
(938, 'QV2O2L', 0, 0),
(939, 'MB9O9W', 0, 0),
(940, 'QP1S3J', 0, 0),
(941, 'WE4L9K', 0, 0),
(942, 'GZ2G5X', 0, 0),
(943, 'XU2Z1U', 0, 0),
(944, 'OH8K4X', 0, 0),
(945, 'FJ1T1T', 0, 0),
(946, 'DL8V5M', 0, 0),
(947, 'SZ8L5H', 0, 0),
(948, 'XQ4F8K', 0, 0),
(949, 'OP3C3H', 0, 0),
(950, 'NT1P5U', 0, 0),
(951, 'IH4L7O', 0, 0),
(952, 'NX9E8X', 0, 0),
(953, 'UJ4D3G', 0, 0),
(954, 'FJ0K8X', 0, 0),
(955, 'AM1P4L', 0, 0),
(956, 'YG4I4G', 0, 0),
(957, 'WP3A9U', 0, 0),
(958, 'CA8C0T', 0, 0),
(959, 'UP7T6T', 0, 0),
(960, 'AU8X9G', 0, 0),
(961, 'HQ3T7L', 0, 0),
(962, 'BZ7T5J', 0, 0),
(963, 'EX9A2C', 0, 0),
(964, 'VV7C2C', 0, 0),
(965, 'SF9B4X', 0, 0),
(966, 'JD6O9H', 0, 0),
(967, 'MO3W3J', 0, 0),
(968, 'RG3T8A', 0, 0),
(969, 'LE6Q2E', 0, 0),
(970, 'IZ9Y8F', 0, 0),
(971, 'OD0T6D', 0, 0),
(972, 'SB1K7K', 0, 0),
(973, 'UV7I3U', 0, 0),
(974, 'VU5E2T', 0, 0),
(975, 'BA9U9Q', 0, 0),
(976, 'DW9Y9Z', 0, 0),
(977, 'CL9Q1T', 0, 0),
(978, 'LY3C2Z', 0, 0),
(979, 'EW8U3D', 0, 0),
(980, 'VT2A2L', 0, 0),
(981, 'JM2A2H', 0, 0),
(982, 'PD9F2K', 0, 0),
(983, 'MC0E5H', 0, 0),
(984, 'UD2T0F', 0, 0),
(985, 'VM9C6F', 0, 0),
(986, 'YA4Y4H', 0, 0),
(987, 'GG6L2A', 0, 0),
(988, 'JR2J0D', 0, 0),
(989, 'LW9J7W', 0, 0),
(990, 'XY0P7X', 0, 0),
(991, 'HT9G6G', 0, 0),
(992, 'IA0B2B', 0, 0),
(993, 'OO2H9P', 0, 0),
(994, 'IT8W8R', 0, 0),
(995, 'VB8Z4K', 0, 0),
(996, 'ON0M3C', 0, 0),
(997, 'QW3I5O', 0, 0),
(998, 'IY7W0V', 0, 0),
(999, 'VR8D2P', 0, 0),
(1000, 'LL8C8X', 1, 0);

-- --------------------------------------------------------


