<?php
session_start();

require '../lib/connectDB.php';
setTable('network');

//get the network id
$net_name = $_SESSION["net_name"];
$query = "SELECT id FROM ".$dbTable." WHERE net_name='".$net_name."'";
$result = mysqli_query($conn,$query);

//if we have rows, we have a matching network
if(mysqli_num_rows($result)>=1){
	$resArray = mysqli_fetch_array($result, MYSQLI_ASSOC);
	$_SESSION['netid'] = $resArray['id'];

	//set the user type to 'user'
	$_SESSION['user_type'] = 'user';
	$_SESSION['login_error'] = false;
}

//otherwise there was no matching network
else {
	$_SESSION['login_error'] = true;
	unset($_SESSION['user_type']);
	
}

?>