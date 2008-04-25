<?php 
/* Name: node_info.php
 * Purpose: Form to edit node information.
 * Written By: Mac Mollison, Shaddi Hassan
 * Last Modified: April 23, 2008
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

//check if we have a network selected, if not redirect to select page
if (!isset($_SESSION['netid'])) 
    header("Location: ../entry/select.php");

include "../lib/style.php";

?>
<body onload=Nifty("div.comment");>
<?
include "../lib/menu.php";

//setup database connection
require "../lib/connectDB.php";
setTable("node");

//display the title of the page
$result = mysqli_query($conn,"SELECT * FROM network WHERE id=".$_SESSION['netid']);
$resArray = mysqli_fetch_assoc($result);
if($resArray['display_name']=="") {$display_name = $resArray['net_name'];}
else {$display_name = $resArray['display_name'];}
echo "<h2>Edit Node Information for " . $_GET["mac"] . "</h2>";

//get nodes that match MAC address from GET string
$query = "SELECT * FROM node WHERE netid=" . $_SESSION["netid"] . " AND mac='" . $_GET['mac'] . "'";
$result = mysqli_query($conn,$query);
if(mysqli_num_rows($result)==0) die("<div class=error>We could not find that node.</a></div>");
$row = mysqli_fetch_array($result, MYSQLI_ASSOC); 

//set up variables needed to display current activation status properly
if ($row["approval_status"] == A) {
    $selected_flag_letter = "A";
    $selected_flag = "Activated";
    $other_flag_letter = "D";
    $other_flag = "Deactivated";
}
else {
    $selected_flag_letter = "D";
    $selected_flag = "Deactivated"; 
    $other_flag_letter = "A";
    $other_flag = "Activated";
}
?>

<form method="POST" action="c_node_info.php" name="editNode">
<input name="mac" type="hidden" value="<?echo $_GET["mac"];?>">    <!--Need to send MAC address on as POST field-->
<table align="left" cellpadding="4" cellspacing="0" border=0>
	<tr><td colspan=2><h2>Node Information</h2></td></tr>
	<tr>
		<td>Node Name</td>
		<td><input name="name" value="<?echo $row["name"];?>"></td>
		<td><div class="comment">A useful name for the node.</div></td>
	</tr>
	<tr>
		<td>Description</td>
		<td><input name="description" value="<?echo $row["description"];?>"></td>
		<td><div class="comment">A useful description of the node.</div></td>
	</tr>
	<tr>
		<td>Node Owner Name</td>
		<td><input name="owner_name" value=<?echo $row["owner_name"];?>></td>
		<td><div class="comment">Name of the node owner. Only visible to the administrator.</div></td>
	</tr>
	<tr>
		<td>Node Owner Email</td>
		<td><input name="owner_email" value="<?echo $row["owner_email"];?>"></td>
		<td><div class="comment">Owner's email address. Only visible to the administrator.</div></td>
	</tr>
	<tr>
		<td>Node Owner Phone Number</td>
		<td><input name="owner_phone" value="<?echo $row["owner_phone"];?>"></td>
		<td><div class="comment">Owner's phone number. Only visible to the administrator.</div></td>
	</tr>
	<tr>
		<td>Node Owner Address</td>
		<td><input name="owner_address" value="<?echo $row["owner_address"];?>"></td>
		<td><div class="comment">Owner's address. Only visible to the administrator.</div></td>
	</tr>
        <tr>
                <td>Activation Flag</td>
                <td>
                    <SELECT NAME="approval_status">
                    <OPTION VALUE=<? echo $selected_flag_letter?> SELECTED><?echo $selected_flag?>
                    <OPTION VALUE=<?echo $other_flag_letter?>><?echo $other_flag;?>
                    <OPTION VALUE=X>Delete This Node
                    </SELECT>
                </td>
		<td><div class="comment">If your node is deactivated, it will not appear in the dashboard until you reactivate it.<br>
                     This is the default setting for nodes added by community members.<br>
                     If your node is deleted, you will not be able to reactivate it.</div></td>
        </tr>
	<tr>
		<td colspan=3 align=center><input name="submit" value="Save Settings" type="submit"></td>
	</tr>
</table>
</form>

