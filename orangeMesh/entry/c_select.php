<?php
/* Name: c_select.php
 * Purpose: process input from select page form
 * Written By: Shaddi Hasan, Mike Burmeister-Brown
 * Last Modified: April 16, 2008
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

require '../lib/connectDB.php';
setTable('network');

include '../lib/toolbox.php';
sanitizeAll();

//get the network id
$net_name = $_POST["net_name"];
$query = "SELECT id FROM ".$dbTable." WHERE net_name='".$net_name."'";
$result = mysqli_query($conn,$query);

//if we have rows, we have a matching network
if(mysqli_num_rows($result)>=1){
	$resArray = mysqli_fetch_array($result, MYSQLI_ASSOC);
	$_SESSION['netid'] = $resArray['id'];
	$_SESSION['net_name'] = $resArray['net_name'];
	//set the user type to 'user'
	$_SESSION['user_type'] = 'user';
	$_SESSION['error'] = false;
	header('location: ../status/map.php');
}

//otherwise there was no matching network
else {
	$_SESSION['error'] = true;
	unset($_SESSION['user_type']);
	header('location: select.php');
	
}

?>