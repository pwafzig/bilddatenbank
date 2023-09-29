SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

DROP TABLE IF EXISTS `accessids`;
CREATE TABLE `accessids` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `hash` varchar(255) NOT NULL,
  `date` varchar(8) NOT NULL,
  `bemerkung` text NOT NULL,
  `name` varchar(255) NOT NULL,
  `resolution` varchar(255) NOT NULL,
  `downloads` int(11) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `lastlogin` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `collections`;
CREATE TABLE `collections` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `comment` text NOT NULL,
  `ids` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `downloads`;
CREATE TABLE `downloads` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user` varchar(255) NOT NULL,
  `filename` varchar(255) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `ip` varchar(15) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `konfig`;
CREATE TABLE `konfig` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `value` varchar(255) NOT NULL,
  `key` text NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

INSERT INTO `konfig` (`id`, `value`, `key`) VALUES
(1, 'name', ''),
(2, 'besitzer', ''),
(3, 'email', ''),
(5, 'schriftart', 'Verdana,Helvetica,Arial,Sans-Serif'),
(6, 'vordergrund', '0C4A8A'),
(7, 'hintergrund', 'DEEAF4'),
(8, 'kontrast', '4DB1F1'),
(9, 'logo', '/bilddatenbank/images/logo.gif'),
(10, 'description', ''),
(11, 'login', 'admin'),
(12, 'password', '12345678'),
(14, 'lastchanges', '2011-01-30 15:37:58'),
(15, 'robots', 'no'),
(21, 'mailer_text', ''),
(17, 'anzthumbs', '24'),
(18, 'impressum_text', ''),
(20, 'sendemail', ''),
(22, 'firma', ''),
(23, 'keywords', ''),
(24, 'telnr', ''),
(25, 'anznewfiles', '10'),
(26, 'maxfiles', '250');
(27, 'smtphost', ''),
(28, 'smtplogin', ''),
(29, 'smtppass', ''),
(30, 'smtpport', '');

DROP TABLE IF EXISTS `picture_data`;
CREATE TABLE `picture_data` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `filename` varchar(255) NOT NULL DEFAULT '',
  `caption` text NOT NULL,
  `headline` varchar(255) NOT NULL DEFAULT '',
  `special_instructions` text NOT NULL,
  `photographer` varchar(255) NOT NULL DEFAULT '',
  `title` varchar(255) NOT NULL DEFAULT '',
  `source` varchar(255) NOT NULL DEFAULT '',
  `object_name` varchar(255) NOT NULL DEFAULT '',
  `transref` varchar(255) NOT NULL DEFAULT '',
  `date` int(8) NOT NULL DEFAULT '0',
  `city` varchar(255) NOT NULL DEFAULT '',
  `state` varchar(255) NOT NULL DEFAULT '',
  `country` varchar(255) NOT NULL DEFAULT '',
  `cat` varchar(255) NOT NULL DEFAULT '',
  `urgency` int(1) NOT NULL DEFAULT '0',
  `keywords` text NOT NULL,
  `copyright` varchar(255) NOT NULL DEFAULT '',
  `time` varchar(255) NOT NULL DEFAULT '',
  `country_code` varchar(255) NOT NULL DEFAULT '',
  `location` varchar(255) NOT NULL DEFAULT '',
  `picsize` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `date` (`date`),
  KEY `time` (`time`),
  FULLTEXT KEY `filename` (`filename`,`caption`,`headline`,`photographer`,`city`,`state`,`country`,`keywords`,`location`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `login` varchar(255) NOT NULL,
  `passwort` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `prename` varchar(255) NOT NULL,
  `organisation` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `lang` varchar(2) NOT NULL,
  `resolution` varchar(255) NOT NULL,
  `downloads` int(5) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `lastlogin` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;