--
-- Database: `leaguewatch`
--

-- --------------------------------------------------------

--
-- Table structure for table `runes`
--

CREATE TABLE IF NOT EXISTS `runes` (
  `entry_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `date_updated` int(20) DEFAULT NULL,
  `current` varchar(255) DEFAULT NULL,
  `id` text,
  `avatar` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`RowID`)
) ENGINE=myisam  DEFAULT CHARSET=latin1 ;
