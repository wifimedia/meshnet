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


//Start session, do includes
session_start();
include '../lib/toolbox.php';

//setup db connection
require '../lib/connectDB.php';
setTable("network");
sanitizeAll();

//get the network id we're working with
$id = $_SESSION['netid'];

//process all the checkbox-based values
if(!isset($_POST['splash_enable'])){$_POST['splash_enable'] = 0;}
if(!isset($_POST['ap2_enable'])){$_POST['ap2_enable'] = 0;}
if(!isset($_POST['lan_block'])){$_POST['lan_block'] = 0;}
if(!isset($_POST['ap1_isolate'])){$_POST['ap1_isolate'] = 0;}
if(!isset($_POST['ap2_isolate'])){$_POST['ap2_isolate'] = 0;}
if(!isset($_POST['migration_enable'])){$_POST['migration_enable'] = 0;}

//generate string of values to update in dashboard
foreach ($network_fields as $f){
	//if the originating form didn't sent a value for this field, skip it
	if(!isset($_POST[$f])){continue;}
	
	//add the field to the result array: "field = 'value'"
	$temp=$f." = "."'".$_POST[$f]."'";
	$result[] = $temp;
}

//turn result array into result string
$result = implode(", ",$result);

//create query string using result string
$query = "UPDATE ".$dbTable." SET ".$result." WHERE id='".$id."'";

//execute query
mysqli_query($conn,$query) or die("Error executing query: ".mysqli_error($conn));

//if we got here, everything went ok
mysqli_close($conn);
$_SESSION["updated"] = 'true';
echo '<HTML><HEAD><META HTTP-EQUIV="refresh" CONTENT="0; URL=edit.php"></HEAD></HTML>';
?>
