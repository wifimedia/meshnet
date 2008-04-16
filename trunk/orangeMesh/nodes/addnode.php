<?php
/* Name: addnode.php
 * Purpose: map to add node to network
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
session_start();

$utype = $_SESSION['user_type'];
$netid = $_SESSION['netid'];
$net_name = $_SESSION['net_name'];
$updated = $_SESSION['updated'];

// a lot of the following is from Mike; forgive me if the code is unclear.
?>
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:v="urn:schemas-microsoft-com:vml">
<head>
<meta http-equiv="content-type" content="text/html; charset=UTF-8"/>
<title>Add/Edit Nodes for - <?php echo $net_name; ?></title>
<?php include("../lib/mapkeys.php"); 
	include "../lib/style.php";?>
<script type="text/javascript" src="../lib/map.js"></script>  
<script type="text/javascript">
<!--[CDATA[

	var map = null;
	var geocoder = null;
	function onLoad() 
	{
		// Display Info Windows Above Markers
		//
		// Show a custom info window above each marker by listening
		// to the click event for each marker. We take advantage of function
		// closures to customize the info window content for each marker.
	  
		// Center the map is done later after we read data points
		map = new GMap2(document.getElementById("map"));
		map.addControl(new GLargeMapControl());
		map.addControl(new GMapTypeControl());
		map.addControl(new GOverviewMapControl());
		geocoder = new GClientGeocoder();

		// Create a marker whenever the user clicks the map   
		GEvent.addListener(map, 'click', function(overlay, point) 
		{
			if (point) 
			{
				var html = '<form name="addnode" method="POST">' +
				'<p align="center" class="style1"><B>Add Node: </B></p>' +
				'<table width="310"  border="0" cellpadding="0" cellspacing="0" id="node">' +
				'<tr>' +
				  '<td class="style1">Name:</td>' +
				  '<td><input type="text" size="32"  name="name"></td>' +
				'</tr><tr>' +
				  '<td><span class="style1">MAC:</span><span class="style2">&nbsp;&#42;&nbsp;</span></td>' +
				  '<td><input type="text" size="32" name="mac"></td>' +
				'</tr><tr>' +
				  '<td><span class="style1">Description:</td>' +
				  '<td><input type="text" size="32"  name="description"></td>' +
				'</tr><tr>' +
				  '<td><span class="style1">Latitude:</span></td>' +
				  '<td><input type="text" size="32"  name="latitude" value="' + point.y + '" readonly></td>' +
				'</tr><tr>' +
				  '<td><span class="style1">Longitude:&nbsp;</span></td>' +
				  '<td><input type="text" size="32"  name="longitude" value="' + point.x + '" readonly></td>' +
				'</tr><tr>' +
				  '<td><span class="style1">Owner Name:</span></td>' +
				  '<td><input type="text" size="32" name="owner_name"></td>' +
				'</tr><tr>' +
				'</tr><tr>' +
				  '<td><span class="style1">Owner Email:</span></td>' +
				  '<td><input type="text" size="32" name="owner_email"></td>' +
				'</tr><tr>' +
				'</tr><tr>' +
				  '<td><span class="style1">Owner Phone:</span></td>' +
				  '<td><input type="text" size="32" name="owner_phone"></td>' +
				'</tr><tr>' +
				'</tr><tr>' +
				  '<td><span class="style1">Owner Address:</span></td>' +
				  '<td><input type="text" size="32" name="owner_address"></td>' +
				'</tr><tr>' +
				'</tr><tr>' +
				  '<td><input type="hidden" name="net_name" value="' + document.getElementById("net_name").value + '"></td>' +
				  '<td align="right"><input type="button" name="Add" value="Add" onClick="addNode(this.form)"></td></tr>' +
            '</tr><tr><td scolspan=2><span class="style1">&nbsp;&nbsp;&nbsp;*Use MAC address in form xx:xx:xx:xx:xx:xx.</span></td></tr></table></form>';

				map.openInfoWindowHtml(point, html);
			}
		});

	var point;
	var marker;
	
	window.onresize=setMapSizePos;

<?php
include("../lib/connectDB.php");

//
// Get our markers from database and add to the map viewport
//
{

	$query="SELECT net_location FROM network WHERE id='$netid'";
	$result=mysqli_query($conn,$query);
	if (mysqli_num_rows($result)==1){
		$net_location = mysqli_fetch_array($result,MYSQLI_ASSOC);
		$net_location = $net_location["net_location"];
	}
	$netid = mysqli_real_escape_string($conn,$netid);
	$query="SELECT *, UNIX_TIMESTAMP(TIME) as epoch_time FROM node WHERE netid='$netid'";
	$result=mysqli_query($conn,$query);
	$num=mysqli_num_rows($result);
	
	if ($num)
	{	
		$resArray = mysqli_fetch_array($result,MYSQLI_ASSOC);
		$longitude = $resArray["longitude"];
		$latitude=$resArray["latitude"];
		mysqli_data_seek($result,0);
echo <<<NODES
	map.setCenter(new GLatLng($latitude, $longitude), 17);
	map.setMapType(G_NORMAL_MAP);
NODES;
	} else {
echo <<<NO_NODES
		address = "$net_location";
		geocoder.getLatLng(address,function(point) {
      		if (!point) {
        		alert(address + " We tried to find your network, and this was the best estimate we could do. If it's incorrect, just move the map to the correct location.");
      		} else {
        		map.setCenter(point, 13);
      		}
    	  }
  		);
NO_NODES;
	}

	$i=0;
	$minX=90;
	$maxX=-90;
	$minY=360;
	$maxY=-360;
	
	//
	// Plot our nodes
	//
	while ($row = mysqli_fetch_array($result,MYSQLI_ASSOC)) 
	{
		$name=$row["name"];
		$notes=$row["description"];
		$ip=$row["ip"];
		$mac=$row["mac"];
		$longitude=$row["longitude"];
		$latitude=$row["latitude"];
		$gateway=$row["gateway"];
		$gw_metric=$row["gw-qual"];
		$users=$row["users"];
		$time=$row["epoch_time"];
		
		//
		// Calculate min, max latitude, longitude for center and zoom later
		//
		if ($latitude < $minX) $minX = $latitude;
		if ($latitude > $maxX) $maxX = $latitude;
		if ($longitude < $minY) $minY = $longitude;
		if ($longitude > $maxY) $maxY = $longitude;
		
		if (!strlen($gw_metric)) $gw_metric = 0;
	
		$up = time() - $time;
		$ctime = time();
	
		$days  = (int)($up / 86400);
		$hours = (int)(($up - ($days * 86400)) / 3600);
		$mins  = (int)(($up - ($days * 86400) - ($hours * 3600)) / 60);
		$secs  = (int)(($up - ($days * 86400) - ($hours * 3600) - ($mins * 60)));
	
		if ($days)
			$LastCheckin = "$days Days, $hours Hours, $mins Minutes";
		else if ($hours)
			$LastCheckin = "$hours Hours, $mins Minutes";
		else if ($mins)
			$LastCheckin = "$mins Minutes, $secs Seconds";
		else
			$LastCheckin = "$secs Seconds";
	
		switch($utype){
		case 'admin':
			$draggable = true;
			break;
		case 'user':
			$draggable = false;
			break;
		default:
			$draggable = false;
			break;
		}
		
//
// Create the Marker
//
echo <<<END
 		
	point = new GPoint($longitude, $latitude);
	marker = createMarker(map, "$net_name", point, "$name", "$notes", "$mac", "$gateway", "$gw_metric", "$up", "$draggable", "$users");
	map.addOverlay(marker);
	
END;
	
	}
}

mysqli_close($conn);

//
// We're done, so center and zoom the map
//
echo <<<END
	
		myCenterAndZoom(map, $minX, $maxX, $minY, $maxY, "$node_loc");
	}  

END;

?>

//]]>
</script>
</head>
<body bgcolor="#FFFFFF" align="center" onload="onLoad()" onResize="setMapSizePos()" onunload="GUnload()" >
<?php include '../lib/menu.php'; ?>

<div align="center" id="top">
  <input name="net_name" id="net_name" type=hidden value=<?php print $net_name?>>
</div>
Click anywhere on the map to add a new node to this network.<br>
<?if($utype == "admin") echo "Drag an existing node to a new location, or click on it to change its settings."?>
<div id="map" style="width: 100%; height: 70%" text-align="center"></div>
</body>
</html>
