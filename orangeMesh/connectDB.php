<?php
/* Name: connectDB.php
 * Purpose: manages database connection.
 * Written By: Shaddi Hasan
 * Last Modified: March 14, 2008
 * 
 * (c) 2008 Orange Networking.
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
 * 
 */

//define the database configuration stuff
$dbHost = "localhost";
$dbUser = "orangemesh";
$dbPass = "default";
$dbName = "orangemesh";
$dbTable;

function setTable($table){
	global $dbTable;
	$dbTable = $table;
}

//create connection to db
$conn = mysqli_connect($dbHost,$dbUser,$dbPass,$dbName) or die("Error connecting to database: ".$dbHost);
?>