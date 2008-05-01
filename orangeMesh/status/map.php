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
if (!isset($_SESSION['user_type'])) 
	header("Location: ../entry/select.php");


$utype = $_SESSION['user_type'];
$netid = $_SESSION['netid'];
$net_name = $_SESSION['net_name'];
$updated = $_SESSION['updated'];

// a lot of the following is from Mike; forgive me if the code is unclear.
?>
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:v="urn:schemas-microsoft-com:vml">
<head>
<meta http-equiv="content-type" content="text/html; charset=UTF-8"/>
<title>Status Map for "<?php echo $net_name; ?>"</title>
<?
include "../lib/style.php";
include "../lib/mapkeys.php";
?>
<script type="text/javascript" src="../lib/map.js"></script>  
<script type="text/javascript">
<!--[CDATA[
	
	function close(){
		document.getElementById("tip").style.display="none";
	}
	
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


		var point;
		var marker;
		
		window.onresize=setMapSizePos;
		
		//setup nifty corners
		Nifty("div.note");

<?php
require "../lib/connectDB.php";
include "../lib/toolbox.php";

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
		$approval_status=$row["approval_status"];
		if($approval_status == "D" || $approval_status == "R"){continue;}
		
		$name=$row["name"];
		$description=$row["description"];
		$ip=$row["ip"];
		$mac=$row["mac"];
		$longitude=$row["longitude"];
		$latitude=$row["latitude"];
		if(!$owner_name=$row["owner_name"])
			$owner_name="(none)";
		if(!$owner_email=$row["owner_email"])
			$owner_email="(none)";
		if(!$owner_phone=$row["owner_phone"])
			$owner_phone="(none)";
		if(!$owner_address=$row["owner_address"])
			$owner_address="(none)";
		$gateway=$row["gateway"];
		$gw_qual=$row["gw-qual"];
		$users=$row["users"];
		$time=$row["time"];
		$kbdown=$row["kbdown"];
		$kbup=$row["kbup"];
		$hops=$row["hops"];
		$robin=$row["robin"];
		$batman=$row["batman"];
		$is_gateway=$row["gateway_bit"];
		
		//
		// Calculate min, max latitude, longitude for center and zoom later
		//
		if ($latitude < $minX) $minX = $latitude;
		if ($latitude > $maxX) $maxX = $latitude;
		if ($longitude < $minY) $minY = $longitude;
		if ($longitude > $maxY) $maxY = $longitude;
		
		if (!strlen($gw_qual)) $gw_qual = 0;
		
		//get time since last checkin and prettify it
		$ctime = getdate();
		$ctime = $ctime[0];
		$up = $ctime-strtotime($time);
		$LastCheckin = humantime($time);
	
		$draggable = false;

		
//
// Create the Marker
//
$html_string = '<h3>Node Status: '.$name.'</h3>'.'<table class="infoWindow">';
if($utype=="admin"){
	$html_string .='<tr>'.'<td>Description:</td>'.'<td>'.$description.'</td>'.'</tr>';
	$html_string .='<tr>'.'<td>MAC/IP:</td>'.'<td>'.$mac.' / '.$ip.'</td>'.'</tr>';
}
if($is_gateway=="1"){$hops="0 (gateway)";}

$html_string .='<tr>'.
				'<td>Last Checkin:</td>'.
				'<td>'.$LastCheckin.'</a></td>'.
			'</tr>'.
			'<tr>'.
				'<td>Users:</td>'.
				'<td>'.$users.'</a></td>'.
			'</tr>'.
			'<tr>'.
				'<td>Down/Up (kb):</td>'.
				'<td>'.round($kbdown/1000,1).' / '.round($kbup/1000,1).'</td>'.
			'</tr>'.
			'<tr>'.
				'<td>Hops:</td>'.
				'<td>'.$hops.'</td>'.
			'</tr>'.
			'<tr>'.
				'<td>Quality:</td>'.
				'<td>'.(floor($gw_qual/255*100)).'%</td>'.
			'</tr>'.
			'<tr>'.
				'<td>ROBIN/BATMAN:</td>'.
				'<td>'.$robin.' / '.$batman.'</td>'.
			'</tr>'.
			'</table>';
$status = addslashes($html_string);


$owner = addslashes('<h3>Node Owner Information</h3>'.
			'<table class="infoWindow">'.
			'<tr>'.
				'<td>Owner Name:</td>'.
				'<td>'.$owner_name.'</td>'.
			'</tr>'.
			'<tr>'.
				'<td>Owner Email:</td>'.
				'<td><a href="mailto:'.$owner_email.'">'.$owner_email.'</a></td>'.
			'</tr>'.
			'<tr>'.
				'<td>Owner Phone:</td>'.
				'<td>'.$owner_phone.'</td>'.
			'</tr>'.
			'<tr>'.
				'<td>Owner Address:</td>'.
				'<td>'.$owner_address.'</td>'.
			'</tr>'.
			'</table>');

echo <<<END
 		
	point = new GPoint($longitude, $latitude);
	var marker = new nodeMarker(map, "$net_name", point, "$name", "$notes", "$mac", "$gateway", "$gw_qual", "$up", "$draggable", "$users");	
	marker.addTab("Node Status","$status");
	marker.addTab("Owner Info","$owner");	
	marker.addListeners();
	map.addOverlay(marker.get());
	
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
<body bgcolor="#FFFFFF" align="center" onload="onLoad();" onResize="setMapSizePos()" onunload="GUnload()" >
<?php include '../lib/menu.php'; ?>

<div align="center" id="top">
  <input name="net_name" id="net_name" type=hidden value=<?php print $net_name?>>
</div>
<div class="note" id="tip">Click on a node for detailed status information.  <a href=javascript:close()>hide</a>
</div>
<div id="map" style="width: 100%; height: 70%" text-align="center"></div>
</body>
</html>
