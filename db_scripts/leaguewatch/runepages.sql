--
-- Database: `leaguewatch`
--

-- --------------------------------------------------------

--
-- Table structure for table `runepages`
--

CREATE TABLE IF NOT EXISTS `runepages` (
  `entry_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `summoner_id` int(20) DEFAULT NULL,
  `date_updated` int(20) DEFAULT NULL,
  `current` varchar(255) DEFAULT NULL,
  `runeset` text DEFAULT NULL,
  `page_id` int(20) DEFAULT NULL,
  `page_name` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`entry_id`)
) ENGINE=myisam  DEFAULT CHARSET=latin1 ;
