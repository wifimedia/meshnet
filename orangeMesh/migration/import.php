<?php
/* Name: import.php
 * Purpose: import data from another dashboard server (for data migration).
 * Written By: Shaddi Hasan
 * Last Modified: April 5, 2008
 * 
 * Note: Translation between Open-Mesh and OrangeMesh happens here. When completed, this
 * script will accept both exports from the OrangeMesh structure as well as the 
 * Open-Mesh structure. It should accept information from Open-Mesh networks now
 * (April 12, 2008), but it has not been tested live yet.
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
require '../lib/connectDB.php';
include '../lib/toolbox.php';
setTable('network');
sanitizeAll();

//do checking to make sure we can actually do the import
$net_name = $_POST['net_name'];
$query = 'SELECT * FROM '.$dbTable.' WHERE net_name="'.$net_name.'"';
$result = mysqli_query($conn,$query);

//check if the network account exists on this server.
if(mysqli_num_rows($result)<1){
	die('ERROR: There is no matching network on this server. Please create the network, then try exporting again.');
}
$resArray = mysqli_fetch_array($result, MYSQLI_ASSOC);

//check if the network allows migration
if($resArray['migration_enable']==0){
	die('ERROR: The network you are exporting does not have migration enabled on this server. Enable migration, then try again.');
}

//get the local network id
$netid = $resArray['id'];

if($_POST['migration_phase']=='network'){
	setTable('network');
	
	if($_POST['structure']=='orangemesh'){
		//Expect Orangemesh field names
		$values[] = "display_name = '".$_POST['display_name']."'";
		$values[] = "email1 = '".$_POST['email1']."'";
		$values[] = "email2 = '".$_POST['email2']."'";
		$values[] = "net_location = '".$_POST['net_location']."'";
		$values[] = "ap1_essid = '".$_POST['ap1_essid']."'";
		$values[] = "ap1_key = '".$_POST['ap1_key']."'";
		$values[] = "ap2_enable = '".$_POST['ap2_enable']."'";
		$values[] = "ap2_essid = '".$_POST['ap2_essid']."'";
		$values[] = "node_pwd = '".$_POST['node_pwd']."'";
		$values[] = "splash_enable = '".$_POST['splash_enable']."'";
		$values[] = "splash_html = '".$_POST['splash_html']."'";
		$values[] = "splash_redirect_url = '".$_POST['splash_redirect_url']."'";
		$values[] = "splash_idle_timeout = '".$_POST['splash_idle_timeout']."'";
		$values[] = "splash_force_timeout = '".$_POST['splash_force_timeout']."'";
		$values[] = "throttling_enable = '".$_POST['throttling_enable']."'";
		$values[] = "download_limit = '".$_POST['downoad_limit']."'";
		$values[] = "upload_limit = '".$_POST['upload_limit']."'";
		$values[] = "network_clients = '".$_POST['network_clients']."'";
		$values[] = "network_bytes = '".$_POST['network_bytes']."'";
		$values[] = "access_control_list = '".$_POST['access_control_list']."'";
		$values[] = "lan_block = '".$_POST['lan_block']."'";
		$values[] = "ap1_isolate = '".$_POST['ap1_isolate']."'";
		$values[] = "ap2_isolate = '".$_POST['ap2_isolate']."'";
		$values[] = "test_firmware_enable = '".$_POST['test_firmware_enable']."'";
		$values[] = "migration_enable = '1'";	//could cause problems if not set to one
	} else if($_POST['structure']=='open-mesh'){
		//Expect Open-Mesh field names
		$values[] = "display_name = '".$_POST['pretty_name']."'";
		$values[] = "email1 = '".$_POST['email']."'";
		$values[] = "email2 = '".$_POST['email2']."'";
		$values[] = "net_location = '".$_POST['node_loc']."'";
		$values[] = "ap1_essid = '".$_POST['essid']."'";
		$values[] = "ap1_key = '".$_POST['ap1_key']."'";
		
		//enable/disable ap2 -- poor naming!
		if($_POST['ap1_disable']=='1'){
			$values[] = "ap2_enable = '0'";
		} else {
			$values[] = "ap2_enable = '1'";
		}
		
		$values[] = "ap2_essid = '".$_POST['essid_priv']."'";
		$values[] = "node_pwd = '".$_POST['root_pw']."'";
		$values[] = "splash_enable = '".$_POST['splash_enable']."'";
		//$values[] = "splash_html = '".$_POST['splash_html']."'";
		$values[] = "splash_redirect_url = '".$_POST['splash']."'";
		$values[] = "splash_idle_timeout = '".$_POST['client_idle_timeout']."'";
		$values[] = "splash_force_timeout = '".$_POST['client_force_timeout']."'";
		$values[] = "throttling_enable = '".$_POST['traffic_control']."'";
		$values[] = "download_limit = '".$_POST['downoad_limit']."'";
		$values[] = "upload_limit = '".$_POST['upload_limit']."'";
		$values[] = "network_clients = '".$_POST['network_clients']."'";
		$values[] = "network_bytes = '".$_POST['network_bytes']."'";
		$values[] = "access_control_list = '".$_POST['acl']."'";
		//$values[] = "lan_block = '".$_POST['lan_block']."'";
		//$values[] = "ap1_isolate = '".$_POST['ap1_isolate']."'";
		//$values[] = "ap2_isolate = '".$_POST['ap2_isolate']."'";
		$values[] = "test_firmware_enable = '".$_POST['test']."'";
		$values[] = "migration_enable = '1'";	//could cause problems if not set to one
	} else {
		die("No or improper export structure given!");
	}
	$values = implode(", ",$values);
	
	$query = 'UPDATE '.$dbTable.' SET '.$values.' WHERE id="'.$netid.'"';
	echo $query;
	$result = mysqli_query($conn,$query);
}

else if($_POST['migration_phase']=='node'){
	setTable('node');
	
	/* The fields we accept are:
	 * name (node name), description, latitude, longitude
	 * owner_name, owner_email, owner_phone, owner_address
	 * approval_status, mac
	 */
	$mac = $_POST['mac'];
	
	$query = 'SELECT * FROM '.$dbTable.' WHERE mac="'.$mac.'"';
	$result = mysqli_query($conn,$query);
	if(mysqli_num_rows($result)>0){
		//if the node already exists, we're going to update (check by mac)
		//generate update field/value pairs first
		if($_POST['structure']=='orangemesh'){
			$values[] = "netid = '".$netid."'";
			$values[] = "name = '".$_POST['name']."'";
			$values[] = "description = '".$_POST['description']."'";
			$values[] = "latitude = '".$_POST['latitude']."'";
			$values[] = "longitude = '".$_POST['longitude']."'";
			$values[] = "owner_name = '".$_POST['owner_name']."'";
			$values[] = "owner_email = '".$_POST['owner_email']."'";
			$values[] = "owner_phone = '".$_POST['owner_phone']."'";
			$values[] = "owner_address = '".$_POST['owner_address']."'";
			$values[] = "approval_status = '".$_POST['approval_status']."'";
		} else if($_POST['structure']=='open-mesh'){
			$values[] = "netid = '".$netid."'";
			$values[] = "name = '".$_POST['name']."'";
			$values[] = "description = '".$_POST['description']."'";
			$values[] = "latitude = '".$_POST['latitude']."'";
			$values[] = "longitude = '".$_POST['longitude']."'";
			$values[] = "approval_status = 'A'";
		} else {
			die("No or improper export structure given!");
		}
		$values = implode(", ",$values);
		
		$query = 'UPDATE '.$dbTable.' SET '.$values.' WHERE mac="'.$mac.'"';
		$result = mysqli_query($conn,$query);
		
	} else {
		echo "DOES NOT EXIST!!!<BR>";
		$_POST['netid'] = $netid;
		//if the node does not already exist, we have to insert
		if($_POST['structure']=="orangemesh"){
			$values[] = $mac;
			$values[] = $netid;
			$values[] = $_POST['name'];
			$values[] = $_POST['description'];
			$values[] = $_POST['latitude'];
			$values[] = $_POST['longitude'];
			$values[] = $_POST['owner_name'];
			$values[] = $_POST['owner_email'];
			$values[] = $_POST['owner_phone'];
			$values[] = $_POST['owner_address'];
			$values[] = $_POST['approval_status'];
		} else if($_POST['structure']=="open-mesh"){
			$values[] = $mac;
			$values[] = $netid;
			$values[] = $_POST['name'];
			$values[] = $_POST['description'];
			$values[] = $_POST['latitude'];
			$values[] = $_POST['longitude'];
			$values['approval_status'] = "A";	//automatically accept nodes
		} else {
			die("No or improper export structure given!");
		}
		$values = implode(",",$values);
		$fields = array('mac','netid','name','description','latitude','longitude','approval_status');
		$fields = implode(",",$fields);
		
		insert('node',$fields,$values);
	}
	
	
}



?>