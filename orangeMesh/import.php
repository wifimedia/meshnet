<?php

$query = $_POST["values"];
$user = "root";
$pwd = "";
$db = "test";
$table = "animals";

$conn = mysql_connect("localhost",$user,$pwd) 
	or die ('Error connecting to dest DB! :(');
echo 'Connected to local DB!<br>';

if(!mysql_select_db($destDBname)){	//do some error checking to make sure the db is there
	echo 'DB does not exist.<br>';
}

$query = "INSERT INTO ".$table." VALUES (".$query.");";
//import the item to the db
$result = mysql_query($query) or die('Query failed: ' . mysql_error());

mysql_close($conn);
?>