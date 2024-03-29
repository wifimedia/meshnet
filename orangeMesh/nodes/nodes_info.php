<?php 
/* Name: info.php
 * Purpose: view and edit node information.
 * Written By: Shaddi Hasan, Mac Mollison
 * Last Modified: April 23, 2008
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

//Start session
session_start();

?>
<head>
<script language="javascript" type="text/javascript">
	function close(){
		document.getElementById("tip").style.display="none";
	}
</script>
<script src='../lib/sorttable.js'></script>
<?

//Check if we have a network selected, if not redirect to select page
if (!isset($_SESSION['netid'])) 
	header("Location: ../entry/select.php");

//Includes
include "../lib/style.php";
?>
</head>
<body onload=Nifty("div.comment#tip");>
<?
include "../lib/menu.php";

//Setup database connection
require "../lib/connectDB.php";
setTable("node");

//Display the title of the page
$result = mysqli_query($conn,"SELECT * FROM network WHERE id=".$_SESSION['netid']);
$resArray = mysqli_fetch_assoc($result);
if($resArray['display_name']=="") {$display_name = $resArray['net_name'];}
else {$display_name = $resArray['display_name'];}
?>
<h2>Node Information List for <?echo $display_name;?></h2>
<div class="note" id="tip">You can edit node information by clicking on the node's name. <a href="javascript:close()">hide</a></div>
<?

//Get nodes that match network id from database
$query = "SELECT * FROM node WHERE netid=" . $_SESSION["netid"];
$result = mysqli_query($conn,$query);
if(mysqli_num_rows($result)==0) die("<div class=error>There are no nodes associated with this network yet. You might want to <a href=\"../nodes/addnode.php\">add some</a>.</div>");

//Table columns, in format Display Name => DB field name.
//You can choose whatever order you like... and these are not all the options... any DB field is game.
$node_fields = array("Node Name" => "name","MAC" => "mac","Description" => "description","Owner Name" => "owner_name","Owner Email" => "owner_email","Owner Phone" => "owner_phone","Owner Address" => "owner_address","Activation Status" => "approval_status");

//Set up the table (HTML output) - the Javascript causes it to be sortable by clicking the top of a column.
echo "";
echo "<table class='sortable' border='1'>";

//Output the top row of the table (display names)
echo "<tr class=\"fields\">";
foreach($node_fields as $key => $value) {
    echo "<td>" . $key . "</td>";
}
echo "</tr>";

//Output the rest of the table
while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
    if ($row["approval_status"] == "A" ||     //show only activated, pending or deactivated nodes
        $row["approval_status"] == "P" || 
        $row["approval_status"] == "D") {       
        echo "<tr>\n"; 
        foreach($node_fields as $key => $value) {
            echo "<td>\n";
            if ($value=="name") {
               echo '<a href="node_info.php?mac=' . $row["mac"] . '">' . $row[$value] . '</a>';                      
            }
            elseif ($value=="approval_status") {    //Translate approval flags into English
                switch ($row[$value]) {
                    case "A": echo "Activated"; break;
                    case "P": echo "Pending Approval"; break;
                    case "D": echo "Deactivated"; break;
                }
            }
            else {
                echo $row[$value];
            }
            echo "</td>\n";
        }
        echo "</tr>\n";
    }
}
echo "</table>";

//Display NiftyCorners effects
?>
<br>
</body>

