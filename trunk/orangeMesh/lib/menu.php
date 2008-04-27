<?php
/* Name: menu.php
 * Purpose: main menu for dashboard.
 * Written By: Shaddi Hasan, Mike Burmeister-Brown
 * Last Modified: April 6, 2008
 * 
 * Variable Summary
 * Globals: on_index
 * GET: -none-
 * POST: -none-
 * SESSION: user_type
 * 
 * (c) 2008 Open Mesh, Inc. and Orange Networking.
 * 
 * This file is part of OrangeMesh.
 *
 * OrangeMesh is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version. This license is similar to the GNU
 * General Public license, but also requires that if you extend this code and
 * use it on a publicly accessible server, you must make available the 
 * complete source source code, including your extensions.
 * 
 * OrangeMesh is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with OrangeMesh.  If not, see <http://www.gnu.org/licenses/>.
 */
session_start();

//get the user type
$utype = $_SESSION['user_type'];

//determine if we're on the index page
//this is important in determining file paths
$on_index = (boolean)strpos($_SERVER['PHP_SELF'],'index.php');
//echo (boolean)$on_index;

//decide what menu to display
switch($utype){
	case 'admin':
		showAdminMenu();
		break;
	case 'user':
		showUserMenu();
		break;
	default:
		showDefaultMenu();
		break;
}

//NOTE: be careful you don't use the id "map" for any of these elements... that's reserved for the google map.
//generate and display the admin menu
function showAdminMenu(){
	global $on_index;
	echo 'You are logged in to the "'.$_SESSION['net_name'].'" network.';
	
	?>
	<div id="menu">
	<ul id="nav">
	<li id="left"><a href="<?if(!$on_index){echo '../';}?>net_settings/edit.php">Network Settings</a></li>
	<li><a href="<?if(!$on_index){echo '../';}?>status/map.php">Node Map</a></li>
	<li><a href="<?if(!$on_index){echo '../';}?>status/view.php">Node Status List</a></li>
	<li><a href="<?if(!$on_index){echo '../';}?>nodes/nodes_info.php">Node Info List</a></li>
	<li><a href="<?if(!$on_index){echo '../';}?>nodes/addnode.php">Add/Edit Nodes</a></li>
	<li><a href="<?if(!$on_index){echo '../';}?>entry/logout.php">Logout</a></li>
	</ul>
	</div>
	<?
}

//generate and display the user menu
function showUserMenu(){
	global $on_index;
	echo 'You are viewing the "'.$_SESSION['net_name'].'" network.';
	?>
	<div id="menu">
	<ul id="nav">
	<li id="left"><a href="<?if(!$on_index){echo '../';}?>status/map.php">Node Map</a></li>
	<li><a href="<?if(!$on_index){echo '../';}?>status/view.php">Node List</a></li>
	<li><a href="<?if(!$on_index){echo '../';}?>nodes/my_nodes.php">Your Nodes</a></li>
	<li><a href="<?if(!$on_index){echo '../';}?>nodes/addnode.php">Add Node</a></li>
	<li><a href="<?if(!$on_index){echo '../';}?>entry/logout.php">Leave Network</a></li>
	</ul>
	</div>
	<?
}

//generate and display the default (no login) menu
function showDefaultMenu(){
	global $on_index;
	echo 'Login to manage a network, or select a network to view its status.';
	?>
	<HEAD>
	<LINK REL=STYLESHEET HREF="../style.css" TYPE="text/css">
	</HEAD>
	<div id="menu">
	<ul id="nav">
	<li id="left" class="first"><a href="<?if(!$on_index){echo '../';}?>index.php">Home</a></li>
	<li><a href="<?if(!$on_index){echo '../';}?>entry/create.php">Create Network</a></li>
	<li><a href="<?if(!$on_index){echo '../';}?>net_settings/edit.php">Manage Network</a></li>
	<li><a href="<?if(!$on_index){echo '../';}?>status/view.php">View Network</a></li>
	</ul>
	</div>
	<?
}
?>
