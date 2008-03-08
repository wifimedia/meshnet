<?php
include 'export.php';
include 'config.php';
$validData=false;

//make the form, eh?
echo '<form action="migrationUI.php" method="post">
	Please enter the source host (blank for local):<input type="text" name="sourceHost"><br>
	Please enter the destination host:<input type="text" name="destHost"><br>
	Please enter the type you want:<input type="text" name="type"><br>
	<input type="submit" name="trans" value="Transfer data">
	</form>';

//quick! the user hit submit, set some bits!
if(isset($_POST["trans"])){
	$input = $_POST["sourceHost"];
	$output = $_POST["destHost"];
	$type = $_POST["type"];
	
	//check if the user put data in the destination
	if($output!=="" && $type!==""){$validData=true;}
	
	if($validData){
		setConfig($input,$output);
		export($type);
	} else {
		echo "you didn't enter anything nitwit, try again.<br>";
	}
}

?>