CREATE TABLE IF NOT EXISTS `data_config` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `akey` varchar(255) DEFAULT NULL,
  `avalue` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=10 ;

INSERT INTO `data_config` (`id`, `akey`, `avalue`) VALUES
(1, 'site_name', 'TEST');

CREATE TABLE IF NOT EXISTS `filee` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `path` text NOT NULL,
  `ip` varchar(255) NOT NULL,
  `status` int(1) NOT NULL,
  `p1` text NOT NULL,
  `p2` text NOT NULL,
  `p3` text NOT NULL,
  `p4` text NOT NULL,
  `p5` int(1) NOT NULL,
  `p6` int(1) NOT NULL,
  `p7` int(1) NOT NULL,
  `timeout` varchar(255) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `login` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

INSERT INTO `users` (`id`, `login`, `password`) VALUES
(1, 'demo', '827ccb0eea8a706c4c34a16891f84e7b');