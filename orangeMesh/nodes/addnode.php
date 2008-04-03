<?php
/* Name: addnode.php
 * Purpose: form to add node to network
 * Written By: Shaddi Hasan
 * Last Modified: April 2, 2008
 * TODO: add google map interface
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
session_start();
include '../lib/menu.php';

$utype = $_SESSION['user_type'];
$netid = $_SESSION['netid'];
$updated = $_SESSION['updated'];

include("../lib/validateInput.js");
?>

<body>

<?if($updated=='true'){echo 'Node added succesfully!';}?>
<form 	method="POST" 
		action="c_addnode.php" 
		onsubmit="if(!isFormValid()){
			alert('The fields highlighted in red have errors. Please correct this and resubmit.');return false;}" 
		name="addnode">
		
	Name 		<input name="name" required="1"><br>
	Description <input name="description" required="1"><br>
	Latitude 	<input name="latitude" value="35.91204361476439" required="1"><br>
	Longitude 	<input name="longitude" value="-79.05123084783554" required="1"><br>
	  <br>
	Owner Information (optional)<br>
	Owner Name 	<input name="owner_name"><br>
	Owner Email <input name="owner_email"><br>
	Owner Phone <input name="owner_phone"><br>
	Owner Address <input name="owner_address"><br>
				<input value="Add Node" name="submit" type="submit"><br>
				<input name="reset" value="Reset" type="reset"><br>
</form>
</body>