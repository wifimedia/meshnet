<?php
require '../lib/connectDB.php';
include '../lib/toolbox.php';
sanitizeAll();

$mac = $_POST["mac"];
$net_name = $_POST["net_name"];
$result = mysqli_query($conn,"SELECT * FROM network WHERE net_name='$net_name'");
if($resArray = mysqli_fetch_array($result, MYSQLI_ASSOC)){
	$netid = $resArray['id'];
}

mysqli_query($conn,"UPDATE node SET approval_status='D' WHERE mac='$mac' AND netid='$netid'");

?>