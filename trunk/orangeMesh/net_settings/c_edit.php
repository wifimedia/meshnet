<?php
/* Name: c_edit.php
 * Purpose: controller for network edit page. processes input from edit.php.
 * Written By: Shaddi Hasan, Mike Burmeister-Brown
 * Last Modified: March 20, 2008
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

session_start();

//setup db connection
require 'connectDB.php';
setTable("network");

//get the network id we're working with
$id = $_SESSION['netid'];

//generate string of values to update in dashboard
foreach ($network_fields as $f){
	//if the originating form didn't sent a value for this field, skip it
	if(!isset($_POST[$f])){continue;}
	
	//add the field to the result array: "field = 'value'"
	$result[]=$f." = "."'".$_POST[$f]."'";
}

//turn result array into result string
$result = implode(", ",$result);

//create query string using result string
$query = "UPDATE ".$dbTable." SET ".$result." WHERE id='".$id."'";

//execute query
mysqli_query($conn,$query) or die("Error executing query: ".mysqli_error($conn));

mysqli_close($conn);

//if we got here, everything went ok
$_SESSION["updated"] = 'true';
echo '<HTML><HEAD><META HTTP-EQUIV="refresh" CONTENT="0; URL=edit.php"></HEAD></HTML>';
?>