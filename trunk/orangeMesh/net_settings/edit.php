<?php
/* Name: edit.php
 * Purpose: edit network settings.
 * Written By: Shaddi Hasan, Mike Burmeister-Brown
 * Last Modified: April 15, 2008
 * 
 * (c) 2008 Open-Mesh, Inc. and Orange Networking.
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
if ($_SESSION['user_type']!='admin') 
	header("Location: ../entry/login.php?rd=net_settings/edit");

//set up database connection
require '../lib/connectDB.php';
setTable('network');

//select the network from the database and get the values
$netid = $_SESSION["netid"];
$query = "SELECT * FROM ".$dbTable." WHERE id='".$netid."'";
$result = mysqli_query($conn,$query);
$resArray = mysqli_fetch_array($result, MYSQLI_ASSOC);

//get all the current values from the database
$net_name = $resArray['net_name'];
$display_name = $resArray['display_name'];
$email1 = $resArray['email1'];
$email2 = $resArray['email2'];
$ap1_essid = $resArray['ap1_essid'];
$ap1_key = $resArray['ap1_key'];
$download_limit = $resArray['download_limit'];
$upload_limit = $resArray['upload_limit'];
$access_control_list = $resArray['access_control_list'];
$splash_enable = $resArray['splash_enable'];
$splash_redirect_url = $resArray['splash_redirect_url'];
$splash_idle_timeout = $resArray['splash_idle_timeout'];
$splash_force_timeout = $resArray['splash_force_timeout'];
$ap2_enable = $resArray['ap2_enable'];
$ap2_essid = $resArray['ap2_essid'];
$ap2_key = $resArray['ap2_key'];
$node_pwd = $resArray['node_pwd'];
$lan_block = $resArray['lan_block'];
$ap1_isolate = $resArray['ap1_isolate'];
$ap2_isolate = $resArray['ap2_isolate'];
$migration_enable = $resArray['migration_enable'];

//check if the user just updated the network
$updated = $_SESSION['updated'];
unset($_SESSION['updated']);
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
	<meta content="text/html; charset=ISO-8859-1" http-equiv="content-type">
	<title>Edit Network</title>
	<?include '../lib/style.php';?>
	<script type=text/javascript>
	function showAdvanced(){
		document.getElementById("root_pwd").style.display="";
		document.getElementById("lan_block").style.display="";
		document.getElementById("ap1_isolate").style.display="";
		document.getElementById("ap2_isolate").style.display="";
		document.getElementById("migration").style.display="";
  	}
  	function hideAdvanced(){
		document.getElementById("root_pwd").style.display="none";
		document.getElementById("lan_block").style.display="none";
		document.getElementById("ap1_isolate").style.display="none";
		document.getElementById("ap2_isolate").style.display="none";
		document.getElementById("migration").style.display="none";
  	}
  	</script>
</head>
<?

////include dojo framework and styles
//include '../lib/dojo.php';
////dojo requires
//echo <<< REQUIRES
//	<script type = "text/javascript">
//		dojo.require("dojo.parser");
//		dojo.require("dijit.TitlePane");
//	</script>
//REQUIRES;

//determines the value of a boolean in the db
//probably should be located in a different file.
function isChecked($field){
	if ($field==0)
		return "";
	else return 'checked="checked"';
}

?>

<body onload=hideAdvanced();Nifty("div.comment");>
<?
//setup the menu
include '../lib/menu.php';

if ($updated=='true') echo "<div class=success>Network successfully updated!</div>";
$query = "SELECT * FROM node WHERE netid='".$netid."'";
$result = mysqli_query($conn,$query);
if(mysqli_num_rows($result)==0) echo "<div class=error>There are no nodes associated with this network yet. You might want to <a href=\"../nodes/addnode.php\">add some</a>.</div>";
?>
<h1><?echo $display_name ?></h1>
<form method="POST" action="c_edit.php" name="editNetwork">
<table align="left" cellpadding="4" cellspacing="0" border=0>
	<tr><td> </td></tr><tr><td colspan=2><h2>Network Account Settings</h2></td></tr>
	<tr>
		<td>Network Name</td>
		<td><input readonly="readonly" name="net_name" value="<?echo $net_name ?>"></td>
		<td><div class="comment">Login ID for this network.</div></td>
	</tr>
	<tr>
		<td>Display name</td>
		<td><input name="display_name" value="<?echo $display_name?>"></td>
		<td><div class="comment">The name to use on reports and the splash page.</div></td>
	</tr>
	<tr>
		<td></td>
		<td><a href="changePass.php?netid=$netid" target="_blank">Change Password</a></td>
		<td><div class="comment">Administrator password for this network.</div></td>
	</tr>
	<tr>
		<td>Primary Email Address</td>
		<td><input name="email1" value=<?echo $email1?>></td>
		<td><div class="comment">Your email in case we need to contact you. We will not share this with others.</div></td>
	</tr>
	<tr>
  		<td colspan=2><h2>Network Notifications</h2></td>
  	</tr>
	<tr>
		<td>Additional Notification Emails</td>
		<td><input name="email2" value=<?echo $email2?>></td>
		<td><div class="comment">Separate multiple email addresses with spaces. Gateway outages will be sent on the hour, repeater outages will be sent daily.</div></td>
	</tr>
	<tr>
		<td>Enable/Disable/Configure notifications...</td>
		<td>Coming soon!</td>
		<td><div class="comment">Enable or disable status notifications for this network.</div></td>
	</tr>
	<tr>
		<td colspan=2><h2>Access Point 1 (Public)</h2></td>
	</tr>
	<tr>
		<td>Network Name</td>
		<td><input name="ap1_essid" value=<?echo $ap1_essid?>></td>
		<td><div class="comment">The SSID to use to connect to this access point.</div></td>
	</tr>
	<tr>
		<td>Network Key</td>
		<td><input name="ap1_key" value=<?echo $ap1_key?>></td>
		<td><div class="comment">Password (key) for the this access point. Leave blank for an open network. KEYS MUST BE 8 CHARACTERS OR LONGER.</div></td>
	</tr>
	<tr>
		<td>Download Limit</td>
		<td><input name="download_limit" value=<?echo $download_limit?>></td>
		<td><div class="comment">Download limit (throttling) in Kbits/sec.</div></td>
	</tr>
	<tr>
		<td>Upload Limit</td>
		<td><input name="upload_limit" value=<?echo $upload_limit?>></td>
		<td><div class="comment">Upload limit (throttling) in Kbits/sec.</div></td>
	</tr>
	<tr>
		<td>Whitelist</td>
		<td><textarea cols="20" rows="4" name="access_control_list"><?echo $access_control_list?></textarea></td>
		<td><div class="comment">MAC address allowed to use this Access Point, one per line. All other users (MAC addresses) will not be able to browse on this access point. Leave blank to allow all MAC addresses.</div></td>
	</tr>
	<tr>
		<td colspan=2><h2>Splash Page</h2></td>
	</tr>
	<tr>
		<td></td>
  		<td>Enable<input name="splash_enable" <?echo isChecked($splash_enable) ?>value=1 type="checkbox"> Configure Splash Page (coming soon...)</td>
  		<td><div class="comment">The splash page is a page users will see first and must click an "enter" link to use the network.</div></td>
  	</tr>
  	<tr>
  		<td>Splash Redirect URL</td>
  		<td><input name="splash_redirect_url" value=<?echo $splash_redirect_url?>></td>
  		<td><div class="comment">The page to display after the Splash page. Leave blank to display the user's requested page.</div></td>
  	</tr>
	<tr>
		<td>Idle Splash Page Timeout</td>
		<td><input name="splash_idle_timeout"  value=<?echo $splash_idle_timeout?>></td>
		<td><div class="comment">Minutes client is idle before showing Splash Page</div></td>
	</tr>
	<tr>
		<td>Require Splash Page Timeout</td>
		<td><input name="splash_force_timeout" value=<?echo $splash_force_timeout?>></td>
		<td><div class="comment">Minutes to show splash page regardless of activity</div></td>
	</tr>
	<tr>
		<td colspan=2><h2>Access Point 2 (Private)</h2></td>
	</tr>
  	<tr>
		<td>Enable</td>
		<td><input <?echo isChecked($ap2_enable) ?> name="ap2_enable" value=1 type="checkbox"></td>
		<td><div class="comment">Uncheck to disable this access point.</div></td>
	</tr>
	<tr>
		<td>Network Name</td>
		<td><input name="ap2_essid" value=<?echo $ap2_essid ?>></td>
		<td><div class="comment">The SSID to use to connect to this access point.</div></td>
	</tr>
	<tr>
		<td>Network Key</td>
		<td><input name="ap2_key" value=<?echo $ap2_key ?>></td>
		<td><div class="comment">Password (key) for this access point. It is NOT possible to leave this field blank and have this be an open AP. MUST BE 8 CHARACTERS OR LONGER.</div></td>
	</tr>
	<tr>
  		<td colspan=2><h2>Advanced Settings</h2></td><td align=left><a href="javascript:showAdvanced();">show</a>&nbsp;&nbsp;&nbsp;<a href="javascript:hideAdvanced();">hide</a></td>
  	</tr>
  	<tr id="root_pwd">
  		<td>Root Password for Nodes</td>
  		<td><input name="node_pwd" value=<?echo $node_pwd?>></td>
  		<td><div class="comment">Root password for all nodes on your network used for ssh. You should change this for security.</div></td>
  	</tr>
  	<tr id="lan_block">
  		<td>LAN Block</td>
  		<td><input <?echo isChecked($lan_block) ?>name="lan_block" value=1 type="checkbox"></td>
  		<td><div class="comment">Checking this box will prevent users on the wireless networks from accessing your wired LAN</div></td>
  	</tr>
  	<tr id="ap1_isolate">
  		<td>AP1 Isolation</td>
  		<td><input <?echo isChecked($ap1_isolate) ?>name="ap1_isolate" value=1 type="checkbox"></td>
  		<td><div class="comment">Check this box to prevent your AP#1 users from being able to access each other's computers.</div></td>
  	</tr>
  	<tr id="ap2_isolate">
  		<td>AP2 Isolation</td>
  		<td><input <?echo isChecked($ap2_isolate) ?> name="ap2_isolate" value=1 type="checkbox"></td>
  		<td><div class="comment">Check this box to prevent your AP#2 users from being able to access each other's computers.</div></td>
  	</tr>
  	<tr id="migration">
  		<td>Migration</td>
  		<td><a href="../migration/export.php">Migrate network to another Orangemesh Server</a><input <?echo isChecked($migration_enable) ?>name="migration_enable" value="1" type="checkbox"></td>
		<td><div class="comment">Will revert to off in one hour)</div></td>
	</tr>
	<tr>
		<td colspan=3 align=center><input name="submit" value="Save Settings" type="submit"></td>
	</tr>
</table>
 	
</form>
</body>
</html>