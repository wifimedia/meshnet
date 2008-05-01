-- phpMyAdmin SQL Dump
-- version 2.11.4
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Mar 20, 2008 at 01:49 PM
-- Server version: 5.0.51
-- PHP Version: 5.2.5
--
-- OrangeMesh database structure
-- Revised: March 20, 2008
-- By: Shaddi Hasan
-- Revised: March 28, 2008
-- By: Mac Mollison
-- Revised: April 30, 2008
-- By: Mac Mollison

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

--
-- Database: `orangemesh`
--
DROP DATABASE `orangemesh`;
CREATE DATABASE `orangemesh` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `orangemesh`;

-- --------------------------------------------------------

--
-- Table structure for table `network`
--

CREATE TABLE IF NOT EXISTS `network` (
 `id` int(11) NOT NULL auto_increment,
 `net_name` varchar(30) NOT NULL COMMENT 'account name',
 `display_name` varchar(200) NOT NULL COMMENT 'dashboard display name for this network',
 `password` varchar(40) NOT NULL COMMENT 'account password',
 `email1` varchar(100) NOT NULL COMMENT 'admin email',
 `email2` varchar(255) NOT NULL COMMENT 'additional contact emails',
 `net_location` varchar(100) NOT NULL COMMENT 'location of network (default start location on google map)',
 `ap1_essid` varchar(30) NOT NULL default 'open-mesh' COMMENT 'ssid for public network',
 `ap1_key` varchar(25) NOT NULL COMMENT 'wpa key for ap1. blank if none.',
 `ap2_enable` tinyint(1) NOT NULL default '1' COMMENT 'enable second network interface',
 `ap2_essid` varchar(30) NOT NULL default 'mySecure' COMMENT 'ssid for second network interface',
 `ap2_key` varchar(30) NOT NULL default '0p3nm35h' COMMENT 'WPA key for second network interface',
 `node_pwd` varchar(30) NOT NULL default '0p3nm35h' COMMENT 'root passwords for nodes (used for ssh connection to node)',
 `splash_enable` tinyint(1) NOT NULL default '0' COMMENT 'enable splash screen',
 `splash_redirect_url` varchar(75) NOT NULL COMMENT 'redirect to this url after splash page',
 `splash_idle_timeout` int(11) NOT NULL default '1440' COMMENT 'redisplay splash page to idle users after this time (minutes)',
 `splash_force_timeout` int(11) NOT NULL default '1440' COMMENT 'force all users to see splash page after this time (minutes)',
 `throttling_enable` tinyint(1) NOT NULL default '1' COMMENT 'enable network throttling',
 `download_limit` varchar(10) NOT NULL default '400' COMMENT 'download max limit (kbits/sec)',
 `upload_limit` varchar(10) NOT NULL default '100' COMMENT 'upload max limit (kbits/sec)',
 `network_clients` text NOT NULL COMMENT 'number of clients in last 24hrs',
 `network_bytes` text NOT NULL COMMENT 'total bytes for last 24hrs',
 `access_control_list` mediumtext NOT NULL COMMENT 'access control list',
 `lan_block` tinyint(1) NOT NULL default '1' COMMENT 'block clients on mesh from seeing lan users (blocked = 1)',
 `ap1_isolate` tinyint(1) NOT NULL default '1' COMMENT 'prevent users in mesh from seeing on another on ap1 (users isolated = 1)',
 `ap2_isolate` tinyint(1) NOT NULL default '1' COMMENT 'prevent users in mesh from seeing on another on ap2 (users isolated = 1)',
 `test_firmware_enable` tinyint(1) NOT NULL default '0' COMMENT 'use test firmware',
 `migration_enable` tinyint(1) NOT NULL default '0' COMMENT 'enable migration features for this network',
 PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COMMENT='network database' AUTO_INCREMENT=28 ;


-- --------------------------------------------------------

--
-- Table structure for table `node`
--

CREATE TABLE IF NOT EXISTS `node` (

-- Generic fields 

`id` int(11) NOT NULL auto_increment,
`time` varchar(20) NOT NULL COMMENT 'Time of last checkin',

-- Fields used in the front end; ignored by checkin-batman

`netid` int(11) NOT NULL,
`name` varchar(100) NOT NULL,
`description` varchar(100) NOT NULL,
`latitude` varchar(20) NOT NULL,
`longitude` varchar(20) NOT NULL,
`owner_name` varchar(50) NOT NULL COMMENT 'node owner''s name',
`owner_email` varchar(50) NOT NULL COMMENT 'node owner''s email address',
`owner_phone` varchar(25) NOT NULL COMMENT 'node owner''s phone number',
`owner_address` varchar(100) NOT NULL COMMENT 'node owner''s address',
`approval_status` varchar(1) NOT NULL COMMENT 'approval status: A (accepted), R (rejected), P (pending)',


-- Fields which directly correspond to fields in ROBIN; exactly the same names; MUST be updated/accessed by checkin-batman
-- NOTE: The ROBIN fields 'rank', 'ssid', and 'pssid' are currently not used.

`ip` varchar(20) NOT NULL COMMENT 'ROBIN',
`mac` varchar(20) NOT NULL COMMENT 'ROBIN',
`uptime` varchar(100) NOT NULL COMMENT 'ROBIN',
`robin` varchar(20) NOT NULL COMMENT 'ROBIN: robin version',
`batman` varchar(20) NOT NULL COMMENT 'ROBIN: batman version',
`memfree` varchar(20) NOT NULL COMMENT 'ROBIN',
`nbs` mediumtext NOT NULL COMMENT 'ROBIN: neighbor list',
`gateway` varchar(20) NOT NULL COMMENT 'ROBIN: nearest gateway',
`gw-qual` varchar(20) NOT NULL COMMENT 'ROBIN: quality of nearest gateway',
`routes` mediumtext NOT NULL COMMENT 'ROBIN: route to nearest gateway',
`users` char(3) NOT NULL COMMENT 'ROBIN: current number of users',
`kbdown` varchar(20) NOT NULL COMMENT 'ROBIN: downloaded kb',
`kbup` varchar(20) NOT NULL COMMENT 'ROBIN: uploaded kb',
`hops` varchar(3) NOT NULL COMMENT 'ROBIN: hops to gateway',
`rank` varchar(3) NOT NULL COMMENT 'ROBIN: ???, not currently used for anything',
`ssid` varchar(20) NOT NULL COMMENT 'ROBIN: ssid, not currently used for anything',
`pssid` varchar(20) NOT NULL COMMENT 'ROBIN: pssid, not currently used for anything',


-- Fields which are computed based on fields in ROBIN; must be MUST be updated/accessed by checkin-batman

`gateway_bit` tinyint(1) NOT NULL COMMENT 'ROBIN derivation: is this node a gateway?',
`memlow` varchar(20) NOT NULL COMMENT 'ROBIN derivation: lowest reported memory on the node',
`usershi` char(3) NOT NULL COMMENT 'ROBIN derivation: highest number of users',
 PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COMMENT='node database' AUTO_INCREMENT=1 ;
