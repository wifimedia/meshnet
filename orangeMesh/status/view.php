<?php 
/* Name: view.php
 * Purpose: master view for network settings.
 * Written By: Shaddi Hasan
 * Last Modified: April 1, 2008
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

include '../lib/menu.php';

//setup database connection
require '../lib/connectDB.php';
setTable('node');

//get nodes that match network id from database
$query = "SELECT * FROM ".$dbTable." WHERE netid='".$_SESSION['netid']."''";
$result = mysqli_query($conn,$query);
$resArray = mysqli_fetch_array($result, MYSQLI_ASSOC);

/* take a look at net_settings/edit.php to see how to work with a result array
 * remember, a result array is a 2d array. rows are indexed by field name (from db).
 * so you can get mac address, for instance, by: $resArray['mac']
 * 
 * if your result has more than one row in the database, then you can iterate 
 * through the result array to get to each row.
 * 
 * So if your result has multiple nodes, you can echo all the mac addresses by:
 * 
 * for($resArray as $row){
 * 		echo $row['mac'];
 * }
 * 
 * I'm pretty sure that's how it works anyway. Take a look at the php manual 
 * page for mysqli_fetch_array for details...

?>

//this lets you create a sortable table. click on the top field to sort it.
<script src="../lib/sorttable.js"></script>

//the table you make here will be sortable.
<table class="sortable">
</table>
