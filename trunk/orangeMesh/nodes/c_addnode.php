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
$name = $_POST['name'];
$description = $_POST['description'];
$latitude = $_POST['latitude'];
$longitude = $_POST['longitude'];
$approval_status = $_POST['approval_status'];
$owner_name = $_POST['owner_name'];
$owner_email = $_POST['owner_email'];
$owner_phone = $_POST['owner_phone'];
$owner_address = $_POST['owner_address'];

$result = mysqli_query($conn,"SELECT * FROM network WHERE net_name='$net_name'");
if($resArray = mysqli_fetch_array($result, MYSQLI_ASSOC)){
	$_POST['netid'] = $resArray['id'];
	$netid = $_POST['netid'];
}
else {
	header("HTTP/1.1 400 Bad Request");
	die("Network does not exist!");
}


		
if(is_mac($mac)){
//	//for move or updated of name, description
	mysqli_query($conn,"UPDATE node SET name='$name', description='$description',latitude='$latitude',longitude='$longitude',approval_status='$approval_status',owner_name='$owner_name',owner_email='$owner_email',owner_phone='$owner_phone',owner_address='$owner_address' WHERE mac='$mac' AND netid='$netid'");
	if(mysqli_affected_rows($conn)==0){
//		//for change of MAC (eg, replaced a node so MAC changed)
//		mysqli_query($conn,"UPDATE node SET mac='$mac' WHERE netid='$netid' AND longitude='$longitude' AND latitude='$latitude'");
//		if(mysqli_affected_rows($conn)==0){
			//must be an add...
			$fields = array('mac','netid','name','description','latitude','longitude','approval_status','owner_name','owner_email','owner_phone','owner_address');
			$values = getValuesFromPOST($fields);
			insert('node',$fields,$values);	
			if(mysqli_affected_rows($conn)>0){}
				//echo "Success";
			 else 
				die("Error: ".mysqli_error());
//		} else 
//			echo "Success";
	} else 
		echo "Success";
	mysqli_close($conn);
}

else
	header('HTTP/1.1 400 Bad Request');

	
	
//include '../lib/toolbox.php';
//

//require '../lib/connectDB.php';
//
//$fields = array('mac','name','description','latitude','longitude','owner_name',
//				'owner_email','owner_phone','owner_address','approval_status','netid');
//
//$values = getValuesFromPOST($fields);
//
//insert('node',$fields,$values);
//
//mysqli_close($conn);
//
//$_SESSION['updated'] = 'true';
//
//header("Location: addnode.php");
?>