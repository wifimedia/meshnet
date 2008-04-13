<?php
session_start();
foreach ($_POST as $key=>$value){
	echo $key .": ". $value;
}


require '../lib/connectDB.php';

mysqli_query($conn,'INSERT INTO node (mac,name) VALUES("'.$_POST['mac'].'","'.$_POST['name'].'")');










//include '../lib/toolbox.php';
//
//$netid = $_SESSION['netid'];
//$utype = $_SESSION['user_type'];
//
//$_POST['netid'] = $netid;
//
//switch($utype){
//	case 'admin':
//		$_POST['approval_status'] = 'A';
//		break;
//	case 'user':
//		$_POST['approval_status'] = 'P';
//		break;
//	default:
//		header("Location: ../entry/select.php");
//		break;
//}
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