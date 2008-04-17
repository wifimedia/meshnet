<?php
/* Name: c_create.php
 * Purpose: process network creation data.
 * Written By: Shaddi Hasan
 * Last Modified: April 16, 2008
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
 */


//get the toolbox
include '../lib/toolbox.php';
sanitizeAll();
		
//setup database connection
require '../lib/connectDB.php';
setTable('network');

//make sure there is not a duplicate network
$query = 'SELECT * FROM network WHERE net_name="'.$_POST['net_name'].'"';
$result = mysqli_query($conn,$query);
if(mysqli_num_rows($result)>0){
	header("Refresh: 3 url=create.php");
	include "../lib/menu.php";
	include "../lib/style.php";
	die("<div class=error>Network name is taken, please enter a new network name.</div>");
}

//the fields we want to insert into the database
$fields = array("net_name","password","email1","net_location","email2");

//hash the input password
$pass = $_POST["password"];
$_POST["password"] = md5($_POST["password"]);

//get the values corresponding to the above fields from the user input 
$values = getValuesFromPOST($fields);
		
//insert the values into the database
insert($dbTable,$fields,$values);

//if we're here, everything went as planned. tell the user that and log them in.
session_start();
$query = 'SELECT * FROM network WHERE net_name="'.$_POST['net_name'].'"';
$res = mysqli_query($conn,$query);
$resArray = mysqli_fetch_assoc($res);
$_SESSION['netid'] = $resArray['id'];
$_SESSION['user_type'] = 'admin';
$_SESSION['net_name'] = $resArray['net_name'];
header("Refresh: 3 url=../net_settings/edit.php");

include "../lib/menu.php";
include "../lib/style.php";
?>
<div class="success">Network sucessfully created!</div>