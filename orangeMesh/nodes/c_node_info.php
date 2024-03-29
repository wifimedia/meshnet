<?php
/* Name: c_node_info.php
 * Purpose: controller for node info page
 * Written By: Shaddi Hasan, Mike Burmeister-Brown, Mac Mollison
 * Last Modified: April 23, 2008
 * 
 * (c) 2008 Open-Mesh, Inc. and Orange Networking.
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

//Setup session and db connection
session_start();
require '../lib/connectDB.php';
setTable("node");

//Sanitize input info
include '../lib/toolbox.php';
sanitizeAll();

//Generate string of values to update in dashboard
foreach ($node_fields as $f){
	//if the originating form didn't sent a value for this field, skip it
	if(!isset($_POST[$f])){continue;}
	
	//add the field to the result array: "field = 'value'"
	$temp=$f." = "."'".$_POST[$f]."'";
	$result[] = $temp;
}

//Turn result array into result string
$result = implode(", ",$result);

//Create query string using result string
$query = "UPDATE ".$dbTable." SET ".$result." WHERE mac='" . $_POST["mac"] . "'";

//Execute query
mysqli_query($conn,$query) or die("Error executing query: ".mysqli_error($conn));
mysqli_close($conn);

//If we got here, everything went ok
$_SESSION["updated"] = 'true';
echo '<HTML><HEAD><META HTTP-EQUIV="refresh" CONTENT="0; URL=nodes_info.php"></HEAD></HTML>';
?>
