<?php
/* Name: import.php
 * Purpose: import data from another dashboard server (for data migration).
 * Written By: Shaddi Hasan
 * Last Modified: March 9, 2008
 * 
 * (c) 2008 Orange Networking.
 *  
 * This file is part of the OrangeMesh Dashboard (OrangeMesh).
 *
 * OrangeMesh is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * OrangeMesh is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with OrangeMesh.  If not, see <http://www.gnu.org/licenses/>.
 * 
 */

$query = $_POST["values"];
$user = "root";
$pwd = "";
$db = "test";
$table = "animals";

$enabled = false;

h
	or die ('Error connecting to dest DB! :(');
echo 'Connected to local DB!<br>';

if(!mysql_select_db($destDBname)){	//do some error checking to make sure the db is there
	echo 'DB does not exist.<br>';
}

$query = "INSERT INTO ".$table." VALUES (".$query.");";
//import the item to the db
$result = mysql_query($query) or die('Query failed: ' . mysql_error());

mysql_close($conn);

function import(){
	
}

function enable(){
	global $enabled;
	$enabled = true;
}

function disable(){
	global $enabled;
	$enabled = false;
}
?>