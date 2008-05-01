<?php
/* Name: connectDB.php
 * Purpose: manages database connection.
 * Written By: Shaddi Hasan
 * Last Modified: March 20, 2008
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
 * 
 */

//Welcome to OrangeMesh! If you are trying to configure your server, just look at the next section.
//After that, you can ignore the stuff in this file!

//Database configuration options
$dbHost = "localhost";
$dbUser = "orangemesh";
$dbPass = "default";	//be sure to change this!
$dbName = "orangemesh";

//Create global dbTable variable
$dbTable;

//Create arrays of DB fields
$network_fields = array('id','net_name','display_name','password','email1','email2', 'net_location','ap1_essid',
  'ap1_key','ap2_enable','ap2_essid','ap2_key','node_pwd','splash_enable','splash_redirect_url',
  'splash_idle_timeout','splash_force_timeout','throttling_enable','download_limit','upload_limit',
  'network_clients','network_bytes','access_control_list','lan_block','ap1_isolate','ap2_isolate',
  'test_firmware_enable','migration_enable');

$node_fields = array('id','netid','name','description','ip','mac','latitude','longitude','gateway',
  'gateway_bit','uptime','robin','batman','memfree','memlow','time','nbs','gw-qual','routes','users','usershi',
  'kbdown','kbup','owner_name','owner_email','owner_phone','owner_address','approval_status','hops','rank');

//setTable function
function setTable($table){
	global $dbTable;
	$dbTable = $table;
}

//create connection to db
$conn = mysqli_connect($dbHost,$dbUser,$dbPass,$dbName) or die("Error connecting to database: ".$dbHost);
?>
