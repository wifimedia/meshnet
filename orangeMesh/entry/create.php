<?php
/* Name: create.php
 * Purpose: create a new network in the dashboard.
 * Written By: Shaddi Hasan
 * Last Modified: April 16, 2008
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
 ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
 	<? 
		include "../lib/validateInput.js";
	  	include "../lib/style.php";
  	?>
  	<meta content="text/html; charset=ISO-8859-1" http-equiv="content-type">
  	<title>Create Network</title>
  	<script type="text/javascript">
		NiftyLoad=function(){
		Nifty("div.comment");
		}
	</script>
</head>
<body>
	<form method="POST" action="c_create.php" onsubmit="if(!isFormValid()){
			alert('The fields highlighted in red have errors. Please correct this and resubmit.');return false;}"name="createNetwork">
	Please fill in the following information to register a new network. Fields outlined in red have errors.
	<table id=edit_net>
		<tr>
			<td><font color="red">*</font> Network Name (no spaces, please)</td>
			<td><input name="net_name" required="1"></td>
			<td><div class="comment">The login name for your network account. This is NOT the SSID of your network.</div></td>
		</tr>
		<tr>
			<td><font color="red">*</font> Password </td>
			<td><input name="password" required="1" type="password"></td>
			<td><div class="comment">The password for your network account, used to log into this server. This is NOT the network key for your network.</div></td>
		</tr>
		<tr>
			<td><font color="red">*</font> Confirm Password </td>
			<td><input name="confirm_pass" required="1" type="password"></td>
			<td><div class="comment">Confirm your password.</div></td>
		<tr>
			<td><font color="red">*</font> Primary email address </td>
			<td><input name="email1" required="1" mask="email"></td>
			<td><div class="comment">Your email address, used for network alerts.</div></td>
		</tr>
		<tr>
			<td><font color="red">*</font> Network Location</td>
			<td><input name="net_location" required="1"></td>
			<td><div class="comment">The physical location of your network. You can enter a street address, postal code, or city/state name.</div></td>
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
	</body>
</html>
