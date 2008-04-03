<?php
/* Name: create.php
 * Purpose: create a new network in the dashboard.
 * Written By: Shaddi Hasan
 * Last Modified: March 22, 2008
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
	include '../lib/menu.php';
	if(!isset($_POST['submit'])){
 ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
  <? include("../lib/validateInput.js");?>
  <meta content="text/html; charset=ISO-8859-1" http-equiv="content-type">
  <title>Create Network</title>
</head>
<body>
	<form method="POST" action="<?php echo $_SERVER["PHP_SELF"] ?>" onsubmit="if(!isFormValid()){
			alert('The fields highlighted in red have errors. Please correct this and resubmit.');return false;}"name="createNetwork">
	Please fill in the following information to register a new network. Fields outlined in red have errors.
	<table>
		<tr>
			<td><font color="red">*</font> Network Name (no spaces, please)</td>
			<td><input name="net_name" required="1"></td>
		</tr>
		<tr>
			<td><font color="red">*</font> Password </td>
			<td><input name="password" required="1" type="password"></td>
		</tr>
		<tr>
			<td><font color="red">*</font> Primary email address </td>
			<td><input name="email1" required="1" mask="email"></td>
		</tr>
		<tr>
			<td><font color="red">*</font> Network Location (5-digit zipcode)</td>
			<td><input name="net_location" required="1" freemask="NNNNN" maxlength="5"></td>
		</tr>
		<tr>
			<td>Email for notifications</td>
			<td><input name="email2" default=" "></td>
		</tr>
		<tr>
			<td><input name="submit" value="Create Network" type="submit"></td>
			<td><input name="reset" value="Reset" type="reset"></td>
		</tr>
		<tr>
			<td><font color="red">* Required Field</font></td>
		</tr>
	</table>
	</form>
	Problem? Check the <a href="../help/help.php">help,</a> then <a href="mailto:shasan@email.unc.edu">notify us.</a>
	</body>
</html>

<?
	} else{
		
		//get the toolbox
		include '../lib/toolbox.php';
				
		//setup database connection
		require '../lib/connectDB.php';
		setTable('network');
		
		$query = 'SELECT * FROM network WHERE net_name="'.$net_name.'"';
		$result = mysqli_query($conn,$query);
		
		
		//the fields we want to insert into the database
		$fields = array("net_name","password","email1","net_location","email2");
		
		//hash the input password
		$_POST["password"] = md5($_POST["password"]);
		
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
