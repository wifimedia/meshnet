<?php
/* Name: c_password.php
 * Purpose: process password change.
 * Written By: Shaddi Hasan
 * Last Modified: April 30, 2008
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

//Make sure the person is logged in
session_start();
if(!isset($_SESSION['netid'])){
	header("Refresh: 0 url=../entry/login.php");
}

//get the toolbox
include '../lib/toolbox.php';
		
//setup database connection
require '../lib/connectDB.php';
setTable('network');
sanitizeAll();



//first check that the passwords entered matched
if($_POST["new_pass"]!=$_POST["confirm_pass"]){
	header("Refresh: 3 url=../net_settings/password.php");
	include "../lib/menu.php";
	include "../lib/style.php";
	die("<div class=error>The passwords you entered did not match!</div>");
}

//check to see if the user entered the correct current password
$password = md5($_POST["old_pass"]);
$query = "SELECT * FROM ".$dbTable." WHERE id='".$_SESSION['netid']."' AND password='".$password."'";
$result = mysqli_query($conn,$query);
$num = mysqli_num_rows($result);

//if yes, update the password
if($num > 0){
	$query = "UPDATE network SET password='".md5($_POST["new_pass"])."' WHERE id='".$_SESSION['netid']."' AND password='".$password."'";
	$result = mysqli_query($conn,$query);
	header("Refresh: 10 url=../net_settings/edit.php");
	include "../lib/menu.php";
	include "../lib/style.php";
	echo '<div class=success>Password changed!</div>';
	
}

//if no, don't update the password
else {
	header("Refresh: 3 url=../net_settings/password.php");
	include "../lib/menu.php";
	include "../lib/style.php";
	die("<div class=error>You did not provide the correct current password.</div>");
}
