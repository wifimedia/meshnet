<?php
/* Name: c_addnode.php
 * Purpose: controller for addnodes.php
 * Written By: Mike Burmeister-Brown, Shaddi Hasan
 * Last Modified: April 12, 2008
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

//Set up the session and includes
session_start();
require '../lib/connectDB.php';
include '../lib/toolbox.php';
sanitizeAll();
$netid = $_SESSION['netid'];
$utype = $_SESSION['user_type'];
$_POST['netid'] = $netid;

//Determine node approval status based on type of logged in user
switch($utype){
	case 'admin':
		$_POST['approval_status'] = 'A';
		break;
	case 'user':
		$_POST['approval_status'] = 'P';
		break;
	default:
		header("Location: ../entry/select.php");
		break;
}

//Get other node vars
$mac = $_POST['mac'];
$net_name = $_POST['net_name'];

//Get the network id
$result = mysqli_query($conn,"SELECT * FROM network WHERE net_name='$net_name'");
if($resArray = mysqli_fetch_array($result, MYSQLI_ASSOC)){
	$_POST['netid'] = $resArray['id'];
	$netid = $_POST['netid'];
}
else {
	header("HTTP/1.1 400 Bad Request");
	die("Network does not exist!");
}

//Find the node
$result = mysqli_query($conn,'SELECT * FROM node WHERE mac="'.$mac.'" AND netid="'.$netid.'"');
if(mysqli_num_rows($result)==0 && $_POST['form_name']!="addNode"){
	die("Tried to update a non-existent node!");
}
$resArray = mysqli_fetch_assoc($result);

//Get all the variables we need from POST
switch($_POST['form_name']){
	case 'addNode':
		$name = $_POST['name'];
		$description = $_POST['description'];
		$latitude = $_POST['latitude'];
		$longitude = $_POST['longitude'];
		$approval_status = $_POST['approval_status'];
		$owner_name = $_POST['owner_name'];
		$owner_email = $_POST['owner_email'];
		$owner_phone = $_POST['owner_phone'];
		$owner_address = $_POST['owner_address'];
		break;
	case 'basicEdit':
		$name = $_POST['name'];
		$description = $_POST['description'];
		$latitude = $_POST['latitude'];
		$longitude = $_POST['longitude'];
		$approval_status = $resArray['approval_status'];
		$owner_name = $resArray['owner_name'];
		$owner_email = $resArray['owner_email'];
		$owner_phone = $resArray['owner_phone'];
		$owner_address = $resArray['owner_address'];
		break;
	case 'ownerEdit':
		$name = $resArray['name'];
		$description = $resArray['description'];
		$latitude = $resArray['latitude'];
		$longitude = $resArray['longitude'];
		$approval_status = $resArray['approval_status'];
		$owner_name = $_POST['owner_name'];
		$owner_email = $_POST['owner_email'];
		$owner_phone = $_POST['owner_phone'];
		$owner_address = $_POST['owner_address'];
		break;
	default:
		$name = $resArray['name'];
		$description = $resArray['description'];
		$latitude = $_POST['latitude'];
		$longitude = $_POST['longitude'];
		$approval_status = $resArray['approval_status'];
		$owner_name = $resArray['owner_name'];
		$owner_email = $resArray['owner_email'];
		$owner_phone = $resArray['owner_phone'];
		$owner_address = $resArray['owner_address'];
		break;
};

//Make update
if(is_mac($mac)){
	if(mysqli_num_rows(mysqli_query($conn,"SELECT * FROM node WHERE mac='$mac' AND netid='$netid'"))>0){
		//update the existing entry
		$query = "UPDATE node SET name='$name', description='$description',latitude='$latitude',longitude='$longitude',approval_status='$approval_status',owner_name='$owner_name',owner_email='$owner_email',owner_phone='$owner_phone',owner_address='$owner_address' WHERE mac='$mac' AND netid='$netid'";
		mysqli_query($conn,$query);
	} else {
		//add the node
		$fields = array('mac','netid','name','description','latitude','longitude','approval_status','owner_name','owner_email','owner_phone','owner_address');
		$values = getValuesFromPOST($fields);
		insert('node',$fields,$values);	
	}
	mysqli_close($conn);
}

else
	header('HTTP/1.1 400 Bad Request');
?>
