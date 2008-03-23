<?php
/* Name: menu.php
 * Purpose: main menu for dashboard.
 * Written By: Mike Burmeister-Brown, Shaddi Hasan
 * Last Modified: March 22, 2008
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

$uri = $_SERVER['REQUEST_URI']; 

//if we're not on the index page
if(strpos($uri,'index.php') == false){
?>
<div id="menu">
<ul id="nav">
<li id="home" class="first"><a href="../index.php">Home</a></li>
<li id="create"><a href="../entry/create.php">Add Network</a></li>
<li id="edit"><a href="../net_settings/edit.php">Edit Network</a></li>
<li id="view"><a href="../status/view.php">Network Status</a></li>
<li id="logout"><a href="../entry/logout.php">Log Out</a></li>
</ul>
<p>
</div>
<?
} else {
	//we're on the index page
?>
	<div id="menu">
	<ul id="nav">
	<li id="home" class="first"><a href="index.php">Home</a></li>
	<li id="create"><a href="entry/create.php">Add Network</a></li>
	<li id="edit"><a href="net_settings/edit.php">Edit Network</a></li>
	<li id="view"><a href="status/view.php">Network Status</a></li>
	<li id="logout"><a href="entry/logout.php">Log Out</a></li>
	</ul>
	<p>
	</div>
<? } ?>