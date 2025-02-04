-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Gép: 127.0.0.1
-- Létrehozás ideje: 2024. Dec 06. 11:58
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
(1, 'Gypo Red - Csévharaszti Kékfrankos', 79990, 'Ízjegyek: Gazdag gyümölcsös aromák, piros bogyós gyümölcsök és fűszeres jegyek. Tálalás: 16-18 °C között, tökéletes steakhez vagy grillezett húsokhoz.', 50),
(2, 'Gypo Reserve - Csévharaszti Cabernet Sauvignon', 56999, 'Ízjegyek: Mély, komplex ízek, fekete ribizli és tölgyes árnyalatok. Tálalás: 18-20 °C, ajánljuk füstölt sajtokkal vagy gazdag ételekkel.', 30),
(3, 'Gypo Sparkling - Csévharaszti Olaszrizling', 53990, 'Ízjegyek: Friss és üde, citrusos és virágos aromákkal. Tálalás: 8-10 °C, kiváló választás tenger gyümölcseivel vagy salátákkal.', 40),
(4, 'Gypo Sweet - Csévharaszti Zöld Veltelini', 47000, 'Ízjegyek: Könnyed, fűszeres ízek, zöldalma és fehér virágok. Tálalás: 10-12 °C, ideális éttermek előtt, könnyű ételek mellé.', 35),
(5, 'Gypo White - Csévharaszti Rosé', 120853, 'Ízjegyek: Friss, gyümölcsös ízvilág, eper és cseresznye jegyekkel. Tálalás: 8-10 °C, remekül illik piknikekhez vagy könnyű nyári fogásokhoz.', 20),
(6, 'Gypo Rosé - Csévharaszti Jégbor', 200000, 'Különleges édes borunk, amelyet fagyott szőlőből készítünk. Gazdag, mély ízű, édes és harmonikus. Tálalás: 6-8 °C, tökéletes desszertekhez vagy különleges alkalmakra.', 15);

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `login`
--

CREATE TABLE `login` (
  `ID` int(11) NOT NULL,
  `vezeteknev` varchar(255) NOT NULL,
  `keresztnev` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `telefonszam` varchar(20) DEFAULT NULL,
  `jelszo` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_hungarian_ci;

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `rendeles`
--

CREATE TABLE `rendeles` (
  `ID` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `bor_id` int(11) NOT NULL,
  `darab` int(11) NOT NULL,
  `rendeles_datum` date NOT NULL DEFAULT curdate()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_hungarian_ci;

CREATE TABLE rendeles_tetelek (
    ID INT AUTO_INCREMENT PRIMARY KEY,
    rendeles_id INT,
    bor_id INT,
    quantity INT,
    FOREIGN KEY (rendeles_id) REFERENCES rendelesek(ID),
    FOREIGN KEY (bor_id) REFERENCES borok(ID)
);

--
-- Indexek a kiírt táblákhoz
--

--
-- A tábla indexei `borok`
--
ALTER TABLE `borok`
  ADD PRIMARY KEY (`ID`);

--
-- A tábla indexei `login`
--
ALTER TABLE `login`
  ADD PRIMARY KEY (`ID`),
  ADD UNIQUE KEY `email` (`email`);

--
-- A tábla indexei `rendeles`
--
ALTER TABLE `rendeles`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `bor_id` (`bor_id`);

--
-- A kiírt táblák AUTO_INCREMENT értéke
--

--
-- AUTO_INCREMENT a táblához `borok`
--
ALTER TABLE `borok`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT a táblához `login`
--
ALTER TABLE `login`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT a táblához `rendeles`
--
ALTER TABLE `rendeles`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- Megkötések a kiírt táblákhoz
--

--
-- Megkötések a táblához `rendeles`
--
ALTER TABLE `rendeles`
  ADD CONSTRAINT `rendeles_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `login` (`ID`),
  ADD CONSTRAINT `rendeles_ibfk_2` FOREIGN KEY (`bor_id`) REFERENCES `borok` (`ID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
