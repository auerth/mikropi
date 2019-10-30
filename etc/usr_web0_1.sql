-- phpMyAdmin SQL Dump
-- version 4.9.0.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Erstellungszeit: 30. Okt 2019 um 14:15
-- Server-Version: 10.0.38-MariaDB-0+deb8u1
-- PHP-Version: 7.3.5

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Datenbank: `usr_web0_1`
--

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `admin`
--

CREATE TABLE `admin` (
  `id` int(11) NOT NULL,
  `userId` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `category`
--

CREATE TABLE `category` (
  `id` int(11) NOT NULL,
  `name` varchar(256) CHARACTER SET latin1 NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `cut`
--

CREATE TABLE `cut` (
  `id` int(11) NOT NULL,
  `name` varchar(100) CHARACTER SET latin1 NOT NULL,
  `description` longtext CHARACTER SET latin1 NOT NULL,
  `uploader` int(11) NOT NULL,
  `file` varchar(256) CHARACTER SET latin1 NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `toDelete` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `dashboard`
--

CREATE TABLE `dashboard` (
  `id` int(11) NOT NULL,
  `title` varchar(256) CHARACTER SET latin1 NOT NULL,
  `userId` int(11) NOT NULL,
  `text` longtext CHARACTER SET latin1 NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `diagnosisgroup`
--

CREATE TABLE `diagnosisgroup` (
  `id` int(11) NOT NULL,
  `categoryId` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `hash`
--

CREATE TABLE `hash` (
  `id` int(11) NOT NULL,
  `userId` int(11) NOT NULL,
  `hash` varchar(256) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `ICD_0`
--

CREATE TABLE `ICD_0` (
  `id` int(11) NOT NULL,
  `categoryId` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `ICD_10`
--

CREATE TABLE `ICD_10` (
  `id` int(11) NOT NULL,
  `categoryId` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `lecturer`
--

CREATE TABLE `lecturer` (
  `id` int(11) NOT NULL,
  `categoryId` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `matrikelnumber`
--

CREATE TABLE `matrikelnumber` (
  `id` int(11) NOT NULL,
  `number` int(11) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `moduls`
--

CREATE TABLE `moduls` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `path` longtext NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `organ`
--

CREATE TABLE `organ` (
  `id` int(11) NOT NULL,
  `categoryId` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `organgroup`
--

CREATE TABLE `organgroup` (
  `id` int(11) NOT NULL,
  `categoryId` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `overlay`
--

CREATE TABLE `overlay` (
  `id` int(11) NOT NULL,
  `name` text NOT NULL,
  `fromX` double NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `cutId` int(11) NOT NULL,
  `fromY` double NOT NULL,
  `sizeX` double NOT NULL,
  `sizeY` double NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `schnittquelle`
--

CREATE TABLE `schnittquelle` (
  `id` int(11) NOT NULL,
  `categoryId` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `semester`
--

CREATE TABLE `semester` (
  `id` int(11) NOT NULL,
  `categoryId` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `ttCutCategory`
--

CREATE TABLE `ttCutCategory` (
  `id` int(11) NOT NULL,
  `cutId` int(11) NOT NULL,
  `categoryId` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `ttModulCut`
--

CREATE TABLE `ttModulCut` (
  `id` int(11) NOT NULL,
  `cutId` int(11) NOT NULL,
  `modulId` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(256) NOT NULL,
  `activated` tinyint(1) NOT NULL DEFAULT '0',
  `matrikelnummer` varchar(10) NOT NULL,
  `name` varchar(100) NOT NULL,
  `forename` varchar(100) NOT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `verified_email`
--

CREATE TABLE `verified_email` (
  `id` int(11) NOT NULL,
  `userId` int(11) NOT NULL,
  `hash` varchar(255) NOT NULL,
  `activated` bit(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Indizes der exportierten Tabellen
--

--
-- Indizes für die Tabelle `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`),
  ADD KEY `userId` (`userId`);

--
-- Indizes für die Tabelle `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `cut`
--
ALTER TABLE `cut`
  ADD PRIMARY KEY (`id`),
  ADD KEY `uploader` (`uploader`);

--
-- Indizes für die Tabelle `dashboard`
--
ALTER TABLE `dashboard`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `diagnosisgroup`
--
ALTER TABLE `diagnosisgroup`
  ADD PRIMARY KEY (`id`),
  ADD KEY `CATEGORY_diag` (`categoryId`);

--
-- Indizes für die Tabelle `hash`
--
ALTER TABLE `hash`
  ADD PRIMARY KEY (`id`),
  ADD KEY `userId` (`userId`);

--
-- Indizes für die Tabelle `ICD_0`
--
ALTER TABLE `ICD_0`
  ADD PRIMARY KEY (`id`),
  ADD KEY `CATEGORY_icd_0` (`categoryId`);

--
-- Indizes für die Tabelle `ICD_10`
--
ALTER TABLE `ICD_10`
  ADD PRIMARY KEY (`id`),
  ADD KEY `CATEGORY_icd_10` (`categoryId`);

--
-- Indizes für die Tabelle `lecturer`
--
ALTER TABLE `lecturer`
  ADD PRIMARY KEY (`id`),
  ADD KEY `CATEGORY_lec` (`categoryId`);

--
-- Indizes für die Tabelle `matrikelnumber`
--
ALTER TABLE `matrikelnumber`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `moduls`
--
ALTER TABLE `moduls`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `organ`
--
ALTER TABLE `organ`
  ADD PRIMARY KEY (`id`),
  ADD KEY `CATEGORY_organ` (`categoryId`);

--
-- Indizes für die Tabelle `organgroup`
--
ALTER TABLE `organgroup`
  ADD PRIMARY KEY (`id`),
  ADD KEY `CATEGORY_organgroup` (`categoryId`);

--
-- Indizes für die Tabelle `overlay`
--
ALTER TABLE `overlay`
  ADD PRIMARY KEY (`id`),
  ADD KEY `OVERALY_CUT` (`cutId`);

--
-- Indizes für die Tabelle `schnittquelle`
--
ALTER TABLE `schnittquelle`
  ADD PRIMARY KEY (`id`),
  ADD KEY `CATEGORY_schnittq` (`categoryId`);

--
-- Indizes für die Tabelle `semester`
--
ALTER TABLE `semester`
  ADD PRIMARY KEY (`id`),
  ADD KEY `CATEGORY_semes` (`categoryId`);

--
-- Indizes für die Tabelle `ttCutCategory`
--
ALTER TABLE `ttCutCategory`
  ADD UNIQUE KEY `id` (`id`),
  ADD KEY `cutId` (`cutId`),
  ADD KEY `categoryId` (`categoryId`);

--
-- Indizes für die Tabelle `ttModulCut`
--
ALTER TABLE `ttModulCut`
  ADD PRIMARY KEY (`id`),
  ADD KEY `CUTMODUL` (`cutId`),
  ADD KEY `MODUL` (`modulId`);

--
-- Indizes für die Tabelle `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `verified_email`
--
ALTER TABLE `verified_email`
  ADD PRIMARY KEY (`id`),
  ADD KEY `USER` (`userId`);

--
-- AUTO_INCREMENT für exportierte Tabellen
--

--
-- AUTO_INCREMENT für Tabelle `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT für Tabelle `category`
--
ALTER TABLE `category`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT für Tabelle `cut`
--
ALTER TABLE `cut`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT für Tabelle `dashboard`
--
ALTER TABLE `dashboard`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT für Tabelle `diagnosisgroup`
--
ALTER TABLE `diagnosisgroup`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT für Tabelle `hash`
--
ALTER TABLE `hash`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT für Tabelle `ICD_0`
--
ALTER TABLE `ICD_0`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT für Tabelle `ICD_10`
--
ALTER TABLE `ICD_10`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT für Tabelle `lecturer`
--
ALTER TABLE `lecturer`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT für Tabelle `matrikelnumber`
--
ALTER TABLE `matrikelnumber`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT für Tabelle `moduls`
--
ALTER TABLE `moduls`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT für Tabelle `organ`
--
ALTER TABLE `organ`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT für Tabelle `organgroup`
--
ALTER TABLE `organgroup`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT für Tabelle `overlay`
--
ALTER TABLE `overlay`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT für Tabelle `schnittquelle`
--
ALTER TABLE `schnittquelle`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT für Tabelle `semester`
--
ALTER TABLE `semester`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT für Tabelle `ttCutCategory`
--
ALTER TABLE `ttCutCategory`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT für Tabelle `ttModulCut`
--
ALTER TABLE `ttModulCut`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT für Tabelle `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT für Tabelle `verified_email`
--
ALTER TABLE `verified_email`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints der exportierten Tabellen
--

--
-- Constraints der Tabelle `diagnosisgroup`
--
ALTER TABLE `diagnosisgroup`
  ADD CONSTRAINT `CATEGORY_diag` FOREIGN KEY (`categoryId`) REFERENCES `category` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints der Tabelle `ICD_0`
--
ALTER TABLE `ICD_0`
  ADD CONSTRAINT `CATEGORY_icd_0` FOREIGN KEY (`categoryId`) REFERENCES `category` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints der Tabelle `ICD_10`
--
ALTER TABLE `ICD_10`
  ADD CONSTRAINT `CATEGORY_icd_10` FOREIGN KEY (`categoryId`) REFERENCES `category` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints der Tabelle `lecturer`
--
ALTER TABLE `lecturer`
  ADD CONSTRAINT `CATEGORY_lec` FOREIGN KEY (`categoryId`) REFERENCES `category` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints der Tabelle `organ`
--
ALTER TABLE `organ`
  ADD CONSTRAINT `CATEGORY_organ` FOREIGN KEY (`categoryId`) REFERENCES `category` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints der Tabelle `organgroup`
--
ALTER TABLE `organgroup`
  ADD CONSTRAINT `CATEGORY_organgroup` FOREIGN KEY (`categoryId`) REFERENCES `category` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints der Tabelle `overlay`
--
ALTER TABLE `overlay`
  ADD CONSTRAINT `OVERALY_CUT` FOREIGN KEY (`cutId`) REFERENCES `cut` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints der Tabelle `schnittquelle`
--
ALTER TABLE `schnittquelle`
  ADD CONSTRAINT `CATEGORY_schnittq` FOREIGN KEY (`categoryId`) REFERENCES `category` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints der Tabelle `semester`
--
ALTER TABLE `semester`
  ADD CONSTRAINT `CATEGORY_semes` FOREIGN KEY (`categoryId`) REFERENCES `category` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints der Tabelle `ttCutCategory`
--
ALTER TABLE `ttCutCategory`
  ADD CONSTRAINT `CATEGORY` FOREIGN KEY (`categoryId`) REFERENCES `category` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `CUT` FOREIGN KEY (`cutId`) REFERENCES `cut` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints der Tabelle `ttModulCut`
--
ALTER TABLE `ttModulCut`
  ADD CONSTRAINT `CUTMODUL` FOREIGN KEY (`cutId`) REFERENCES `cut` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `MODUL` FOREIGN KEY (`modulId`) REFERENCES `moduls` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints der Tabelle `verified_email`
--
ALTER TABLE `verified_email`
  ADD CONSTRAINT `USER` FOREIGN KEY (`userId`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
