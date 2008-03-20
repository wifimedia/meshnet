<?php
/* Name: edit.php
 * Purpose: edit network settings.
 * Written By: Shaddi Hasan, Mike Burmeister-Brown
 * Last Modified: March 20, 2008
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
if (!isset($_SESSION['registered'])) 
	header("Location: login.php?rd=edit");

//set up database connection
require 'connectDB.php';
setTable('network');

//select the network from the database and get the values
$netid = $_SESSION["netid"];
$password = $_SESSION["password"];
$query = "SELECT * FROM ".$dbTable." WHERE id='".$netid."' AND password='".$password."'";;
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

//setup the menu
include 'menu.php';

//determines the value of a boolean in the db
//probably should be located in a different file.
function isChecked($field){
	if ($field==0)
		return "";
	else return 'checked="checked"';
}


?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
  <meta content="text/html; charset=ISO-8859-1"
 http-equiv="content-type">
  <title>Edit Network</title>
</head>
<body>
<?if ($updated=='true') echo "Network successfully updated"; ?>
<?echo $display_name ?><br>
<form method="POST" action="c_edit.php"
 name="editNetwork"><b>Network Account Settings</b><br>
Network Name <input readonly="readonly" name="net_name"
 value=<?echo $net_name ?>><br>
Display name <input name="display_name" value=<?echo $display_name?>><br>
  <a href="changePass.php?netid=$netid" target="_blank">Change
Password</a><br>
Primary Email Address&nbsp;<input name="email1"
 value=<?echo $email1?>><br>
  <br>
  <b>Network Notifications</b><br>
Notification Emails: <input name="email2"
 value=<?echo $email1.','.$email2?>><br>
Enable/Disable/Configure notifications...<br>
  <br>
  <b>Access Point 1 (Public)</b><br>
Network Name <input name="ap1_essid" value=<?echo $ap1_essid?>><br>
Network&nbsp; Key <input name="ap1_key" value=<?echo $ap1_key?>><br>
Download Limit <input name="download_limit"
 value=<?echo $download_limit?>><br>
Upload Limit <input name="upload_limit" value=<?echo $upload_limit?>><br>
Whitelist&nbsp;<textarea cols="20" rows="4"
 name="access_control_list"><?echo $access_control_list?></textarea><br>
  <b><br>
Splash Page<br>
  </b>Enable<input name="splash_enable"
 value=<?echo $splash_enable?> type="checkbox"><br>
Configure Splash Page (somehow...)<br>
Splash Redirect URL <input name="splash_redirect_url"
 value=<?echo $splash_redirect_url?>><br>
Idle Splash Page Timeout <input name="splash_idle_timeout"
 value=<?echo $splash_idle_timeout?>><br>
Require Splash Page Timeout <input name="splash_force_timeout"
 value=<?echo $splash_force_timeout?>><br>
  <br>
  <b>Access Point 2 (Private)</b><br>
Enable <input <?echo isChecked($ap2_enable) ?> name="ap2_enable"
 value=<?echo $ap2_enable ?> type="checkbox"><br>
Network Name <input name="ap2_essid" value=<?echo $ap2_essid ?>><br>
Network Key <input name="ap2_key" value=<?echo $ap2_key ?>><br>
  <br>
  <b>Advanced Settings<br>
  </b>Root Password for Nodes <input name="node_pwd" value=<?echo $node_pwd?>><br>
LAN Block <input <?echo isChecked($lan_block) ?>name="lan_block" value=<?echo $lan_block?>
 type="checkbox"><br>
AP1 Isolation <input <?echo isChecked($ap1_isolate) ?>name="ap1_isolate" value=<?echo $ap1_isolate?>
 type="checkbox"><br>
AP2 Isolation <input <?echo isChecked($ap2_isolate) ?> name="ap2_isolate" value=<?echo $ap2_isolate?>
 type="checkbox"><br>
Enable Migration (will revert to off in one hour) <input name="migration_enable" value=<?echo $migration_enable?>
 type="checkbox"><br>
 <br>
 <br>
 <input name="save" value="Save Settings" type="submit">
</form>
<br>
</body>
</html>