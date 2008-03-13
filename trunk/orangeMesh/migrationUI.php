<?php
/* Name: migrationUI.php
 * Purpose: old migration ui. NOT FOR USE!
 * Written By: Shaddi Hasan
 * Last Modified: March 8, 2008 12.30pm
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