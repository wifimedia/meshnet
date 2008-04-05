<?php	
/* Name: c_export.php
 * Purpose: sends a network to a remote dashboard server.
 * Written By: Shaddi Hasan
 * Last Modified: April 5, 2008
 * TODO: allow users to set a new name for their network on the remote server.
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

$net_name = $_SESSION['net_name'];
$netid = $_SESSION['netid'];
$host = $_POST['host'];
$path = $_POST['path']."/migration/import.php";
if(isset($_POST['new_name'])){
	$net_name = $_POST['new_name'];
}
	echo $net_name;
	
if(strlen($host)==0){$host = "localhost";}


//TEMPORARY VARIABLE FOR TESTING!! choose to read from o-m db or onm db
$structure = "open-mesh";

//clear out post array
$_POST[] = array();

/* The process of migration is designed to be as atomic as possible.
 * 
 * The network account information is read from the local database and converted
 * into the format expected by the import script. This data 
 */
//setup the network connection
require '../lib/connectDB.php';
setTable('network');

//get the PEAR files for this 
require '../lib/PEAR/Request.php';

echo "Gathering network information from database...<br>";

//get the account/network data from the database and put it in a result array
//$query = "SELECT * FROM $dbTable WHERE net_name='$net_name'"; //Mike: use this line to get accounts by name
$query = "SELECT * FROM $dbTable WHERE id='$netid'";
$result = mysqli_query($conn,$query);
if(mysqli_num_rows($result)<1){die('No network exists with that name on this server.');}
$resArray = mysqli_fetch_array($result, MYSQLI_ASSOC);

//make sure we're using a ROBIN network
if($resArray['type']!='R' && false){ //TODO: BE SURE to get rid of '&& false' -- it's only for testing right now
	die("Sorry, it appears you're using a Meraki network. You can only export ROBIN networks.");
}

//create export request
$req = &new HTTP_Request("http://".$host.$path."/migration/import.php");
$req->setMethod(HTTP_REQUEST_METHOD_POST);

//load network account information into the post request
//Mike: your script can send all its fields to my import. My import will handle conversion.
//		We'll just have to coordinate so I can be sure the open servers can properly 
//		get input from Open-Mesh.
$req->addPostData('migration_phase','network');
foreach($resArray as $key => $value){
	$req->addPostData($key,$value);
}
$req->addPostData('net_name',$net_name);

echo "Sending network configuration information...<br>";

//send the request
if (PEAR::isError($req->sendRequest())) {
	die('Error sending request to remote server!');		//fail if there is an http error
} else {
	$response = $req->getResponseBody();	//get the response from the import server
	if(strstr($response,"ERROR")==FALSE)	//check if the remote server gave an error
		echo "Network configuration sent succesfully: ".$response."<br>"; //ok
	else
		die('Migration error! Remote server says: "'.$response.'"');	//error
}


echo "Gathering node information from database...<br>";


//now get all the node information from the database. 
$query = 'SELECT * FROM node WHERE netid="'.$netid.'"';
$result = mysqli_query($conn,$query);

//make sure we have nodes in the network, otherwise we'll get null errors
if(mysqli_num_rows($result)<1)
	echo 'This network has no associated nodes.<br>';
else {
	while($row = mysqli_fetch_array($result,MYSQLI_ASSOC)){
		$req = &new HTTP_Request("http://".$host.$path."/migration/import.php");
		$req->setMethod(HTTP_REQUEST_METHOD_POST);
		
		$req->addPostData('migration_phase','node');
		$req->addPostData('net_name',$net_name);
		//get all the values for every node
		foreach($row as $key => $value){
			$req->addPostData($key,$value);
		}
		
		//send the request
		if (PEAR::isError($req->sendRequest())) {
			die('Error sending request to remote server!');		//fail if there is an http error
		} else {
			$response = $req->getResponseBody();	//get the response from the import server
			if(strstr($response,"ERROR")==FALSE){	//check if the remote server gave an error
				echo "Node sent succesfully: ".$response."<br>"; //ok
			}
			else{
				die('Migration error! Remote server says: "'.$response.'"');	//error
			}
		}
	}
}

//if we're here, everything went fine
echo "Migration succesfully completed!";
?>