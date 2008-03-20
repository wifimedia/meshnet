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
--

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

--
-- Dumping data for table `network`
--

INSERT INTO `network` (`id`, `net_name`, `display_name`, `password`, `email1`, `email2`, `net_location`, `ap1_essid`, `ap1_key`, `ap2_enable`, `ap2_essid`, `ap2_key`, `node_pwd`, `splash_enable`, `splash_redirect_url`, `splash_idle_timeout`, `splash_force_timeout`, `throttling_enable`, `download_limit`, `upload_limit`, `network_clients`, `network_bytes`, `access_control_list`, `lan_block`, `ap1_isolate`, `ap2_isolate`, `test_firmware_enable`, `migration_enable`) VALUES
(27, 'foo', '', 'acbd18db4cc2f85cedef654fccc4a4d8', 'foo@foobar.foo', '', '12345', 'open-mesh', '', 1, 'mySecure', '0p3nm35h', '0p3nm35h', 0, '', 1440, 1440, 1, '400', '100', '', '', '', 1, 1, 1, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `node`
--

CREATE TABLE IF NOT EXISTS `node` (
  `id` int(11) NOT NULL auto_increment,
  `netid` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` varchar(100) NOT NULL,
  `ip_addr` varchar(16) NOT NULL COMMENT 'IP address of node',
  `mac_addr` varchar(20) NOT NULL COMMENT 'MAC address of Node',
  `latitude` varchar(20) NOT NULL COMMENT 'Latitude of node (for map interface)',
  `longitude` varchar(20) NOT NULL COMMENT 'Longitude of node (for map interface)',
  `gateway` tinyint(1) NOT NULL COMMENT 'is this node a gateway?',
  `uptime` varchar(100) NOT NULL COMMENT 'uptime string from node',
  `build` varchar(25) NOT NULL COMMENT 'robin/batman version string',
  `memfree` varchar(20) NOT NULL COMMENT 'free memory on node',
  `memlow` varchar(10) NOT NULL COMMENT 'lowest reported free memory',
  `time` timestamp NOT NULL default CURRENT_TIMESTAMP COMMENT 'time of last update',
  `neighbors` mediumtext NOT NULL COMMENT 'neighbor list',
  `nearest_gw` varchar(17) NOT NULL COMMENT 'MAC of nearest gateway',
  `gw_metric` varchar(16) NOT NULL COMMENT 'quality of nearest gateway',
  `gw_route` mediumtext NOT NULL COMMENT 'route to nearest gateway',
  `clients` char(3) NOT NULL COMMENT 'current users',
  `clients_hi` varchar(3) NOT NULL COMMENT 'highest count of users',
  `bytes_down` varchar(10) NOT NULL COMMENT 'downloaded bytes',
  `bytes_up` varchar(12) NOT NULL COMMENT 'uploaded bytes',
  `owner_name` varchar(50) NOT NULL COMMENT 'node owner''s name',
  `owner_email` varchar(50) NOT NULL COMMENT 'node owner''s email address',
  `owner_phone` varchar(25) NOT NULL COMMENT 'node owner''s phone number',
  `owner_address` varchar(100) NOT NULL COMMENT 'node owner''s address',
  `approval_status` varchar(1) NOT NULL COMMENT 'approval status: A (accepted), R (rejected), P (pending)',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COMMENT='node database' AUTO_INCREMENT=1 ;

--
-- Dumping data for table `node`
--

