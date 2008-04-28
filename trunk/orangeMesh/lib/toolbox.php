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

//get the values we ask for from post
//TODO: sanitize. provide an error result if the input is not good.
function getValuesFromPOST($fields){
	foreach ($fields as $f){
		//echo $f.": ";
		$val = $_POST[$f];
		//echo $val."<br>";
		$values[]=$_POST[$f];
	}
	return $values;
}

//sanitize a string
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
?>