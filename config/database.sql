-- ********************************************************
-- *                                                      *
-- * IMPORTANT NOTE                                       *
-- *                                                      *
-- * Do not import this file manually but use the Contao  *
-- * install tool to create and maintain database tables! *
-- *                                                      *
-- ********************************************************

--
-- Table `tl_geoipcountrywhois`
--

CREATE TABLE `tl_geoipcountrywhois` (
  `ip_from` varchar(64) NOT NULL default '',
  `ip_to` varchar(64) NOT NULL default '',
  `ip_from_dec` int(10) unsigned NOT NULL default '0',
  `ip_to_dec` int(10) unsigned NOT NULL default '0',
  `iso_country_code` varchar(2) NOT NULL default '',
  `country_name` varchar(255) NOT NULL default '',
  UNIQUE KEY `ip_range` (`ip_from_dec`, `ip_to_dec`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


-----------------------------

--
-- Table `tl_countryresp`
--

CREATE TABLE `tl_countryresp` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `pid` int(10) unsigned NOT NULL default '0',
  `iso_country_code` varchar(2) NOT NULL default '',
  PRIMARY KEY  (`id`),
  KEY `iso_country_code` (`iso_country_code`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-----------------------------

--
-- Table `tl_page`
--

CREATE TABLE `tl_page` (
  `geo_country_resp` blob NULL,
  `geo_region_name` varchar(255) NOT NULL default '',
  `geo_fallback` char(1) NOT NULL default '',
  `geo_protected` char(1) NOT NULL default '',
) ENGINE=MyISAM DEFAULT CHARSET=utf8;