<?php
/* Name: mailalerts.php
 * Purpose: sends email alerts to networks with down nodes.
 * Written By: Shaddi Hasan
 * Last Modified: April 7, 2008
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

//configure mail sending options
require 'Mail.php';
$smtp_params['host'] = "your.smtp.server";	// Must change this to your smtp server
$smtp_params['auth'] = FALSE; // Change to true if your smtp server requires authentication
$params['username'] = ""; // The username to use for SMTP authentication.
$params['password'] = ""; // The password to use for SMTP authentication.
$params['persist'] = FALSE; // Allows you to use one SMTP connection for multiple emails.
$headers['From'] = "from@address";	// The from address
$headers['Reply-To'] = "reply-to@address"; // Reply-to
$headers['X-Mailer'] = "OrangeMesh PHP /".phpversion();	// Makes this look less like spam

//Set how long a node can be down before it's alerted (in seconds)
$OK_DOWNTIME = 1800;

//Get the current time
$currentTime = getdate();
$currentTime = $currentTime['0'];

//Setup db connection
require 'connectDB.php';

//Select all the networks from the database
$query = "SELECT * FROM network";
$network_result = mysqli_query($conn,$query);
if(mysqli_num_rows($network_result)==0) die("No networks in database, mailing halted.");

//For every network in the dashboard
while($network = mysqli_fetch_assoc($network_result)){
	$body = "Network alert for '".$network['display_name']."'\n\nThe following nodes have not checked in recently:\n\n";
	//Get the nodes associated with this network
	$query = "SELECT * FROM node WHERE netid='".$network['id']."'";
	$node_result = mysqli_query($conn,$query);
	if(mysqli_num_rows($node_result)==0)  {continue;}
	
	//For every node that is in the network
	while($node = mysqli_fetch_assoc($node_result)){
		//if the node is down, add a line to the email
		$down = $currentTime - strtotime($node['time']);
		if($down>=$OK_DOWNTIME){
			$body .= $node['name']." has not checked in for ".(int)($down /(60))." minutes.\n";
		}
	}
	
	//Conclude email
	$body .= "You can view the status of the network at THE SERVER ADDRESS!!\n";
	$recipients = $network['email1'];
	$headers['To'] = $recipients;
	$headers['Subject'] = 'Network Alerts for '.$network['net_name'];
	
	//Send the message
	$mail_object =& Mail::factory('smtp', $smtp_params);
	
	if($mail_object->send($recipients, $headers, $body)){
		echo "sent!";
	} else {echo "fail!";}

}
?>
