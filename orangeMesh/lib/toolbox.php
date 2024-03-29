<?php
/* Name: toolbox.php
 * Purpose: general utility functions for the dashboard.
 * Written By: Shaddi Hasan
 * Last Modified: April 13, 2008
 * 
 * (c) 2008 Orange Networking.
 * 
 * This toolbox contains:
 * - a quick way to insert a bunch of values into a table
 * - a quick way to get specified values from POST
 * - an input sanitizer (both html and sql)
 * - a quick way to sanitize all POST and GET inputs
 * - a regex-based mac address validator
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

//insert values corresponding to fields into the table
function insert($table,$fields,$values){
	global $conn;	//the db connection
	
	//convert the fields array into a comma-seperated list
	$fields = implode(",",$fields);
	
	//seperate the values array into a comma/' seperated list
	$values = implode("','",$values);
	
	//generate sql query
	$query = "INSERT INTO ".$table;
	$query .= " (".$fields.") ";
	$query .= "VALUES('".$values."')";
	
	//echo $result."<br>";
	
	//execute the query
	mysqli_query($conn,$query) or die("Error executing query: ".mysqli_error($conn));
}

//Get the values we ask for from post
function getValuesFromPOST($fields){
	foreach ($fields as $f){
		$val = $_POST[$f];
		$values[]=$_POST[$f];
	}
	return $values;
}

//Sanitize a string
//I think this is best used in controllers, to check their own input. We don't
//want someone to be able to mess stuff up by calling a controller script.
function sanitize($string){
	global $conn;
	return mysqli_real_escape_string($conn, htmlspecialchars($string));

}
function sanitizeAll(){
	foreach($_POST as $key=>$value){
		$_POST[$key] = sanitize($value);
	}
	foreach($_GET as $key=>$value){
		$_GET[$key] = sanitize($value);
	}
}
function is_mac($mac){
	if(preg_match("/^([0-9A-F][0-9A-F]:){5}[0-9A-F][0-9A-F]$/i",$mac)){
		echo "valid!";
		return true;
	} else {
		echo "invalid. :(";
		return false;
	}
}
function humantime($time){
	$ctime = getdate();
	$ctime = $ctime[0];
	$up = $ctime-strtotime($time);

	$days  = (int)($up / 86400);
	$hours = (int)(($up - ($days * 86400)) / 3600);
	$mins  = (int)(($up - ($days * 86400) - ($hours * 3600)) / 60);
	$secs  = (int)(($up - ($days * 86400) - ($hours * 3600) - ($mins * 60)));

	if($days>=14000)
		return "Never checked in.";
	if($days>50)
		return "More than 50 days ago.";
		
	if ($days)
		$humantime = "$days Days, $hours Hours, $mins Minutes";
	else if ($hours)
		$humantime = "$hours Hours, $mins Minutes";
	else if ($mins)
		$humantime = "$mins Minutes, $secs Seconds";
	else
		$humantime = "$secs Seconds";
		
	return $humantime;
}
?>
