CREATE DATABASE IF NOT EXISTS `phpmyadmin`
  DEFAULT CHARACTER SET utf8 COLLATE utf8_bin;
USE phpmyadmin;

-- --------------------------------------------------------

--
-- Privileges
--
-- (activate this statement if necessary)
-- GRANT SELECT, INSERT, DELETE, UPDATE, ALTER ON `phpmyadmin`.* TO
--    'pma'@localhost;

-- --------------------------------------------------------

--
-- Table structure for table `pma__bookmark`
--

CREATE TABLE IF NOT EXISTS `pma__bookmark` (
  `id` int(11) NOT NULL auto_increment,
  `dbase` varchar(255) NOT NULL default '',
  `user` varchar(255) NOT NULL default '',
  `label` varchar(255) COLLATE utf8_general_ci NOT NULL default '',
  `query` text NOT NULL,
  PRIMARY KEY  (`id`)
)