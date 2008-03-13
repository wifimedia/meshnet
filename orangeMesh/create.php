<?php
/* Name: create.php
 * Purpose: create a new network in the dashboard.
 * Written By: Shaddi Hasan
 * Last Modified: March 13, 2008 12.30pm
 * 
 * (c) 2008 Orange Networking.
 *  
 * This file is part of OrangeMesh.
 *
 * OrangeMesh is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * OrangeMesh is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with OrangeMesh.  If not, see <http://www.gnu.org/licenses/>.
 * 
 */

	if(!isset($_POST['create'])){
 ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
  <? include("validateInput.js");?>
  <meta content="text/html; charset=ISO-8859-1" http-equiv="content-type">
  <title>Create Network</title>
</head>
<body>
	<form method="POST" action="<?php echo $_SERVER["PHP_SELF"] ?>" onsubmit="if(!isFormValid()){
			alert('The fields highlighted in red have errors. Please correct this and resubmit.');return false;}"name="createNetwork">Please fill in the following
	information to register a new network.<br>
	  <br>
	Network Name <input name="acct_name" required="1"><br>
	  <br>
	Password <input name="password" required="1" type="password"><br>
	  <br>
	Primary email address <input name="email1" required="1" mask="email"><br>
	  <br>
	Network Location <input name="net_location" required="1" freemask="NNNNN" maxlength="5"><br>
	  <br>
	Email for notifications <input name="email2" default=" "><br>
	  <br>
	  <input name="create" value="Create Network" type="submit"> <input name="reset" value="Reset" type="reset"><br>
	</form>
	<br>
	Problem? Check the <a href="help/help.php">help,</a> then <a href="mailto:shasan@email.unc.edu">notify us.</a>
	</body>
</html>

<?
	} else{
		function insert($table,$fields,$values){
			global $conn;	//the db connection
			
			//convert the fields array into a comma-seperated list
			$fields = implode(",",$fields);
			
			//seperate the values array into a comma/' seperated list
			$values = implode("','",$values);
			
			//generate sql query
			$result = "INSERT INTO ".$table;
			$result .= " (".$fields.") ";
			$result .= "VALUES('".$values."')";
			
			//echo $result."<br>";
			
			//execute the query
			mysqli_query($conn,$result) or die("Error executing query: ".mysqli_error($conn));
		}
		
		//get the values we ask for from post
		//TODO: sanitize. provide an error result if the input is not good.
		function getValuesFromPOST($fields){
			foreach ($fields as $f){
				echo $f.": ";
				$val = $_POST[$f];
				echo $val."<br>";
				$values[]=$_POST[$f];
			}
			return $values;
		}
		
		//define the database configuration stuff
		$dbHost = "localhost";
		$dbUser = "orangemesh";
		$dbPass = "default";
		$dbName = "orangemesh";
		$dbTable = "accounts";
		
		//create connection to db
		$conn = mysqli_connect($dbHost,$dbUser,$dbPass,$dbName) or die("Error connecting to database: ".$dbHost);
		
		//the fields we want to insert into the database
		$fields = array("acct_name","password","email1","net_location","email2");
		
		//get the values corresponding to the above fields from the user input 
		$values = getValuesFromPOST($fields);
		
		//foreach ($values as $v){echo $v."<BR>";}
		
		//insert the values into the database
		insert($dbTable,$fields,$values);

		//close the connection
		mysqli_close($conn);
		
		//if we're here, everything went as planned. tell the user that.
		echo("Network sucessfully created!");
	}
?>
