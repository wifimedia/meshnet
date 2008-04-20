<?php
session_start();
require '../lib/connectDB.php';
include '../lib/toolbox.php';
sanitizeAll();

$netid = $_SESSION['netid'];
$utype = $_SESSION['user_type'];

$_POST['netid'] = $netid;

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

$mac = $_POST['mac'];
$net_name = $_POST['net_name'];

$result = mysqli_query($conn,"SELECT * FROM network WHERE net_name='$net_name'");
if($resArray = mysqli_fetch_array($result, MYSQLI_ASSOC)){
	$_POST['netid'] = $resArray['id'];
	$netid = $_POST['netid'];
}
else {
	header("HTTP/1.1 400 Bad Request");
	die("Network does not exist!");
}

$result = mysqli_query($conn,'SELECT * FROM node WHERE mac="'.$mac.'" AND netid="'.$netid.'"');
if(mysqli_num_rows($result)==0 && $_POST['form_name']!="addNode"){
	die("Tried to update a non-existent node!");
}
$resArray = mysqli_fetch_assoc($result);

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

if(is_mac($mac)){
//	//for move or updated of name, description
	if(mysqli_num_rows(mysqli_query($conn,"SELECT * FROM node WHERE mac='$mac' AND netid='$netid'"))>0){
		//update the existing entry
		mysqli_query($conn,"UPDATE node SET name='$name', description='$description',latitude='$latitude',longitude='$longitude',approval_status='$approval_status',owner_name='$owner_name',owner_email='$owner_email',owner_phone='$owner_phone',owner_address='$owner_address' WHERE mac='$mac' AND netid='$netid'");
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