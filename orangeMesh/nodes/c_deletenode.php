<?php
/* Name: c_deletenode.php
 * Purpose: Controller for deleting a node
 * Written By: Mike Burmeister-Brown, Shaddi Hasan
 * Last Modified: April 12, 2008
 * 
 * (c) 2008 Open Mesh, Inc. and Orange Networking.
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

//Basic setup
require '../lib/connectDB.php';
include '../lib/toolbox.php';
sanitizeAll();

//Get all needed info
$mac = $_POST["mac"];
$net_name = $_POST["net_name"];
$result = mysqli_query($conn,"SELECT * FROM network WHERE net_name='$net_name'");
if($resArray = mysqli_fetch_array($result, MYSQLI_ASSOC)){
	$netid = $resArray['id'];
}

//Change the DB flag
mysqli_query($conn,"UPDATE node SET approval_status='D' WHERE mac='$mac' AND netid='$netid'");

?>
