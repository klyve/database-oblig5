DROP TABLE IF EXISTS `city`;

CREATE TABLE `city` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `city` varchar(100) DEFAULT NULL,
  `county` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table clubs
# ------------------------------------------------------------

DROP TABLE IF EXISTS `clubs`;

CREATE TABLE `clubs` (
  `clubid` varchar(50) NOT NULL DEFAULT '',
  `name` varchar(100) DEFAULT NULL,
  `cityid` int(11) DEFAULT NULL,
  UNIQUE KEY `clubid` (`clubid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table season
# ------------------------------------------------------------

DROP TABLE IF EXISTS `season`;

CREATE TABLE `season` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `year` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table logs
# ------------------------------------------------------------

DROP TABLE IF EXISTS `logs`;

CREATE TABLE `logs` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `season` varchar(11) DEFAULT NULL,
  `clubid` varchar(50) DEFAULT NULL,
  `skierid` int(11) DEFAULT NULL,
  `date` date DEFAULT NULL,
  `area` varchar(100) DEFAULT NULL,
  `distance` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table skiers
# ------------------------------------------------------------

DROP TABLE IF EXISTS `skiers`;

CREATE TABLE `skiers` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(60) DEFAULT NULL,
  `firstName` varchar(50) DEFAULT NULL,
  `lastName` varchar(50) DEFAULT NULL,
  `yearOfBirth` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table totalDistance
# ------------------------------------------------------------

DROP TABLE IF EXISTS `totalDistance`;

CREATE TABLE `totalDistance` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `skierid` int(11) DEFAULT NULL,
  `season` int(11) DEFAULT NULL,
  `distance` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;




/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
