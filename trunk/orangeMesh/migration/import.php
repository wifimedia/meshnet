<?php
/* Name: import.php
 * Purpose: import data from another dashboard server (for data migration).
 * Written By: Shaddi Hasan
 * Last Modified: April 2, 2008
 * 
 * (c) 2008 Orange Networking.
 *  
 * This file is part of the OrangeMesh Dashboard (OrangeMesh).
 *
 * OrangeMesh is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * OrangeMesh is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with OrangeMesh.  If not, see <http://www.gnu.org/licenses/>.
 * 
 */
include '../lib/connectDB.php';

if($_POST['migration_phase']=='network'){
	setTable('network');

	$net_name = $_POST['net_name'];
	$query = 'SELECT migration_enable FROM '.$dbTable.' WHERE net_name="'.$net_name.'"';
	$result = mysqli_query($conn,$query);
	
	//check if the network account exists on this server.
	if(mysqli_num_rows($result)<1){
		die('ERROR: There is no matching network on this server. Please create the network, then try exporting again.');
	}
	$resArray = mysqli_fetch_array($result, MYSQLI_ASSOC);
	if($resArray['migration_enable']!=0){
		
	}
	else{
		die('ERROR: The network you are exporting does not have migration enabled on this server. Enable migration, then try again.');
	}
}




?>