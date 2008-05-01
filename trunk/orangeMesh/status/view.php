<?php 
/* Name: view.php
 * Purpose: master view for network settings.
 * Written By: Shaddi Hasan, Mac Mollison
 * Last Modified: May 1, 2008
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

//Set how long a node can be down before it's name turns red (in seconds)
$OK_DOWNTIME = 1800;

//Get the current time
$currentTime = getdate();
$currentTime = $currentTime['0'];

//check if we have a network selected, if not redirect to select page
if (!isset($_SESSION['netid'])) 
	header("Location: ../entry/select.php");

include "../lib/style.php";
//include javascript to close the tip box
?>
<head>
<script>
	function close(){
		document.getElementById("tip").style.display="none";
	}
</script>
<!-- Set up the table (HTML output) - the Javascript causes it to be sortable by clicking the top of a column. -->
<script src='../lib/sorttable.js'></script>

</head>
<body onload=Nifty("div.note");>
<?
include "../lib/menu.php";

//setup database connection
require "../lib/connectDB.php";
setTable("node");

//display the title of the page
$result = mysqli_query($conn,"SELECT * FROM network WHERE id=".$_SESSION['netid']);
$resArray = mysqli_fetch_assoc($result);
if($resArray['display_name']=="") {$display_name = $resArray['net_name'];}
else {$display_name = $resArray['display_name'];}
echo <<<TITLE
<h2>Node Status List for $display_name</h2>
<div class="note" id="tip">
<div class=error>Nodes in red need attention.</div>
<b>Names of gateway nodes appear in bold.
<a href="javascript:close()">hide</a></div>
TITLE;

//get nodes that match network id from database
$query = "SELECT * FROM node WHERE netid=" . $_SESSION["netid"];
$result = mysqli_query($conn,$query);
if(mysqli_num_rows($result)==0) die("<div class=error>There are no nodes associated with this network yet. You might want to <a href=\"../nodes/addnode.php\">add some</a>.</div>");


//Table columns, in format Display Name => DB field name.
//You can choose whatever order you like... and these are not all the options... any DB field is game.
$node_fields = array("Node Name" => "name","Description" => "description","Uptime" => "uptime",
  "Quality" => "gw-qual","Hops" => "hops","Down kb" => "kbdown","Up kb" => "kbup","Users" =>"users","Max Users" => "usershi",
  "Last Checkin" => "time","MAC" => "mac");

echo "<table class='sortable' border='1'>";

//Output the top row of the table (display names)
echo "<tr class=\"fields\">";
foreach($node_fields as $key => $value) {
    echo "<td>" . $key . "</td>";
}
echo "</tr>";

//Output the rest of the table
while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
    if ($row["approval_status"] == "A") {    //show only activated nodes
        if($currentTime - strtotime($row['time']) >= $OK_DOWNTIME) {
    	    echo "<tr class=\"down\">";
        }
        else {
    	    echo "<tr>";
        }
        foreach($node_fields as $key => $value) {
            echo "<td>";
            if ($value=="name" && $row["gateway_bit"]==1) {
                echo "<b>" . $row[$value] . "</b>";                      
            }
            elseif ($value=="hops" && $row["gateway_bit"]==1) {
                echo "0";                      
            }
            elseif ($value=="gw-qual") {    //Convert rank from x {x | 0 < x < 255} to %
                echo floor(100 * ($row[$value] / 255)) . "%";
            }
            else {
                echo $row[$value];
            }
            echo "</td>";
        }
        echo "</tr>";
    }
}
echo "</table>";

//Set up NiftyCorners
?>
<br>
</body>

