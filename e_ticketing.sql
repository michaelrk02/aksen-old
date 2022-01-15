-- Adminer 4.7.1 MySQL dump

SET NAMES utf8;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

SET NAMES utf8mb4;

DROP TABLE IF EXISTS `accounts`;
CREATE TABLE `accounts` (
  `id` char(5) NOT NULL,
  `email` char(254) NOT NULL,
  `password` char(32) NOT NULL,
  `tickets` int(11) NOT NULL,
  `visitors` text NOT NULL,
  `last_order` int(11) NOT NULL,
  `check_ins` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


DROP TABLE IF EXISTS `accounts_tmp`;
CREATE TABLE `accounts_tmp` (
  `registration_code` char(10) NOT NULL,
  `email` char(254) NOT NULL,
  `password` char(32) NOT NULL,
  PRIMARY KEY (`registration_code`),
  UNIQUE KEY `verification_code` (`registration_code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


DROP TABLE IF EXISTS `categories`;
CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `name` char(50) NOT NULL,
  `capacity` int(11) NOT NULL,
  `price` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `categories` (`id`, `name`, `capacity`, `price`) VALUES
(0,	'(pemesanan ditutup sementara)',	0,	0),
(1,	'Presale 1',	100,	72000),
(2,	'Presale 2',	20,	75000),
(3,	'Presale 3',	0,	78000),
(4,	'Alumni SMAGA',	1000,	40000);

DROP TABLE IF EXISTS `contact_persons`;
CREATE TABLE `contact_persons` (
  `phone_number` char(24) NOT NULL,
  `name` char(100) NOT NULL,
  `role` char(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `contact_persons` (`phone_number`, `name`, `role`) VALUES
('62895343845423',	'Miko',	'Teknis');

DROP TABLE IF EXISTS `info`;
CREATE TABLE `info` (
  `highlight` text NOT NULL,
  `order_id_next` int(11) NOT NULL,
  `admin_password` char(32) NOT NULL,
  `bank_account` char(100) NOT NULL,
  `instagram_page` char(100) NOT NULL,
  `mail_enable` int(11) NOT NULL,
  `last_check_timestamp` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `alumni_id` int(11) NOT NULL,
  `ticketbox_schedule` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `info` (`highlight`, `order_id_next`, `admin_password`, `bank_account`, `instagram_page`, `mail_enable`, `last_check_timestamp`, `category_id`, `alumni_id`, `ticketbox_schedule`) VALUES
('<h3><b>AKSEN XXVI - Highschool Memories</b></h3>\r\n<h4>Menampilkan: <b>Avenged Sevenfold</b></h4>\r\n<h4>Minggu, 32 Agustus 2025</h4>\r\n<h4>Pukul 32:00 - selesai</h4>\r\n<h4>Pura Mangkunegaran, Surakarta</h4>',	51,	'7e798c806e0599fcf5afda19e673bd64',	'1234567890 (Joko Widodo/Bank Negara Indonesia)',	'aksensmaga',	0,	1543597200,	2,	4,	'15.45 s/d 17.00 (senin-kamis), 15.00 s/d 17.00 (jumat), 09.00 s/d 12.00 (sabtu)');

DROP TABLE IF EXISTS `orders`;
CREATE TABLE `orders` (
  `id` char(5) NOT NULL,
  `order_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `tickets` int(11) NOT NULL,
  KEY `id` (`id`),
  CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`id`) REFERENCES `accounts` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


DROP TABLE IF EXISTS `recoveries`;
CREATE TABLE `recoveries` (
  `id` char(5) NOT NULL,
  `recovery_code` char(10) NOT NULL,
  KEY `id` (`id`),
  CONSTRAINT `recoveries_ibfk_1` FOREIGN KEY (`id`) REFERENCES `accounts` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


-- 2019-11-14 14:38:23
