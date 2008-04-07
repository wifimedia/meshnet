<?php
/* Name: mailalerts.php
 * Purpose: sends email alerts to networks with down nodes.
 * Written By: Shaddi Hasan
 * Last Modified: April 7, 2008
 * 
 * Known Issues:
 * - due to sendmail() config problems in Windows, the actual mail sending has not 
 *	 been tested. I have confirmed that the function is receiving the input it expects.
 *   I will test this once it is on omnis tonight.
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

//set how long a node can be down before it's alerted (in seconds)
$OK_DOWNTIME = 1800;

//get the current time
$currentTime = getdate();
$currentTime = $currentTime['0'];

//setup db connection
require 'connectDB.php';

//select all the networks from the database
$query = "SELECT * FROM network";
$network_result = mysqli_query($conn,$query);
if(mysqli_num_rows($network_result)==0) die("No networks in database, mailing halted.");

//for every network in the dashboard
while($network = mysqli_fetch_assoc($network_result)){
	$body = "Network alert for '".$network['display_name']."'\n\nThe following nodes have not checked in recently:\n\n";
	//get the nodes associated with that network
	$query = "SELECT * FROM node WHERE netid='".$network['id']."'";
	$node_result = mysqli_query($conn,$query);
	if(mysqli_num_rows($node_result)==0)  {continue;}
	
	//for every node that is in the network
	while($node = mysqli_fetch_assoc($node_result)){
		//if the node is down, add a line to the email
		$down = $currentTime - strtotime($node['time']);
		if($down>=$OK_DOWNTIME){
			$body .= $node['name']." has not checked in for ".(int)($down /(60))." minutes.\n";
		}
	}
	
	//prepare the email for sending
	$body .= "\nYou can view your network status at ".$_SERVER['SERVER_NAME'].".";
	$to = $network['email1'];
	$subject = "OrangeMesh Network Alert for ".$network['name'];
	$headers = "From: alerts@orangenetworking.org";
	
	//send the message for this network
	mail($to,$subject,$body,$headers);
}
?>