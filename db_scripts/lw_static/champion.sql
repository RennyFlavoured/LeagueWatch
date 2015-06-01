--
-- Database: `champion`
--

-- --------------------------------------------------------

--
-- Table structure for table `champion`
--

CREATE TABLE IF NOT EXISTS `champion` (
  `id` bigint(20) unsigned NOT NULL,
  `key` varchar(255),
  `name` varchar(255),
  `title` varchar(255),
  `image` varchar(255),
  `skins` varchar(255),
  `info` varchar(255),
  `stats` varchar(255),
  PRIMARY KEY (`id`)
) ENGINE=myisam  DEFAULT CHARSET=latin1 ;
