--
-- Database: `leaguewatch`
--

-- --------------------------------------------------------

--
-- Table structure for table `runes`
--

CREATE TABLE IF NOT EXISTS `runes` (
  `rune_entry_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `rune_id` int(10) DEFAULT NULL,
  `slot` varchar(255) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `type` varchar(255) DEFAULT NULL,
  `stats` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`rune_entry_id`)
) ENGINE=myisam  DEFAULT CHARSET=latin1;
