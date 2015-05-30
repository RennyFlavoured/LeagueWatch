--
-- Database: `leaguewatch`
--

-- --------------------------------------------------------

--
-- Table structure for table `summoners`
--

CREATE TABLE IF NOT EXISTS `summoners` (
  `summoner_id` bigint(20) unsigned NOT NULL,
  `date_created` int(20) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `level` text,
  `avatar` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`summoner_id`)
) ENGINE=myisam  DEFAULT CHARSET=latin1 ;
