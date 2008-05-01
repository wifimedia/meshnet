<?php
/* Name: create.php
 * Purpose: change the password of a network.
 * Written By: Shaddi Hasan
 * Last Modified: April 30, 2008
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
 ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
  <? include "../lib/style.php"; ?>
  <? include "../lib/menu.php"; ?>
  <meta content="text/html; charset=ISO-8859-1" http-equiv="content-type">
  <title>Change Password</title>
</head>
<body onload=Nifty("div.comment");>
	<form method="POST" action="c_password.php" name="createNetwork">
	Please fill in the following information to register a new network. Fields outlined in red have errors.
	<table id="edit_net">
		<tr>
			<td>Current Password (no spaces, please)</td>
			<td><input name="old_pass" type="password"></td>
			<td><div class="comment">Your current password.</div></td>
		</tr>
		<tr>
			<td>New Password</td>
			<td><input name="new_pass" type="password"></td>
			<td><div class="comment">Your new password.</div></td>
		</tr>
		<tr>
			<td>Confirm New Password </td>
			<td><input name="confirm_pass" type="password"></td>
			<td><div class="comment">Confirm your password.</div></td>
		<tr>
			<td><input name="submit" value="Create Network" type="submit"></td>
			<td><input name="reset" value="Reset" type="reset"></td>
		</tr>
	</table>
	</form>
	</body>
</html>
