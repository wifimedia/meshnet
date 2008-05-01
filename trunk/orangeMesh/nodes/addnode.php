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

		// Create a marker whenever the user clicks the map   
		GEvent.addListener(map, 'click', function(overlay, point) 
		{
			if (point) 
			{
				var html = 	'<form name="addnode" method="POST">' +
				'<B>Add Node</B>' +
				'<table width="310"  border="0" cellpadding="0" cellspacing="0" id="node">' +
				'<tr>' +
				  '<td class="style1">Name:</td>' +
				  '<td><input type="text" size="32" name="node_name" required="1"></td>' +
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
				  '<td><input type="hidden" name="user_type" value="<?echo $utype;?>"><input type="hidden" name="form_name" value="addNode"><input type="hidden" name="net_name" value="' + document.getElementById("net_name").value + '"></td>' +
				  '<td align="right"><input type="button" name="Add" value="Add" onClick="addNode(this.form)"></td></tr>' +
            '<tr><td colspan=2>*Use MAC address in form xx:xx:xx:xx:xx:xx.</td></tr></table></form>';

				map.openInfoWindowHtml(point, html);
			}
		});

	var point;
	var marker;
	
	window.onresize=setMapSizePos;
	
	Nifty("div.note");

<?php
include("../lib/connectDB.php");
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
		$owner_name=$row["owner_name"];
		$owner_email=$row["owner_email"];
		$owner_phone=$row["owner_phone"];
		$owner_address=$row["owner_address"];
		$gateway=$row["gateway"];
		$gw_metric=$row["gw-qual"];
		$users=$row["users"];
		$time=$row["time"];
		
		//
		// Calculate min, max latitude, longitude for center and zoom later
		//
		if ($latitude < $minX) $minX = $latitude;
		if ($latitude > $maxX) $maxX = $latitude;
		if ($longitude < $minY) $minY = $longitude;
		if ($longitude > $maxY) $maxY = $longitude;
		
		if (!strlen($gw_metric)) $gw_metric = 0;
		
		$ctime = getdate();
		$ctime = $ctime[0];
		$up = $ctime-strtotime($time);
		$LastCheckin = humantime($time);
	
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
$html = addslashes('<form name="basicEdit" method="POST">'.
			'<h3>Basic Information</h3>'.
				'<table id="node">'.
				'<tr>'.
				  '<td class="style1">Name:</td>'.
				  '<td><input type="text" size="32" name="node_name" value="'.$name.'"></td>'.
				'</tr>'.
				'<tr>'.
				  '<td><span class="style1">MAC:</span><span class="style2">&nbsp;&nbsp;</span></td>'.
				  '<td><input type="text" size="32" name="mac" value="'.$mac.'" readonly></td>'.
				'</tr>'.
				'<tr>' .
				  '<td><span class="style1">Description:</td>' .
				  '<td><input type="text" size="32"  name="description" value="' . $description . '"></td>' .
				'</tr>'.
				'<tr>' .
				  '<td><span class="style1">Latitude:</span></td>' .
				  '<td><input type="text" size="32"  name="latitude" value="' . $latitude . '" readonly></td>' .
				'</tr>'.
				'<tr>' .
				  '<td><span class="style1">Longitude:&nbsp;</span></td>' .
				  '<td><input type="text" size="32"  name="longitude" value="' . $longitude . '" readonly></td>' .
				'</tr>'.
				'<tr>' .
				  '<td><input type="hidden" name="net_name" value="' . $net_name . '"></td>' .
				  '<td><input type="hidden" name="user_type" value="' . $utype . '"></td>' .
				  '<td><input type="hidden" name="form_name" value="basicEdit"></td>'.
				'</tr>' .
		      	'<tr>' .
					'<td>'.
						'<input type="submit" name="submit" value="Update" onClick="addNode(this.form)">' .
	  	    			'<input type="button" name="Delete" value="Delete" onClick="deleteNode(this.form)">'.
					'</td>' .
				'</tr>' .
				'</table></form>');

$owner = addslashes('<form name="ownerEdit" method="POST">'.
			'<h3>Node Owner Information</h3>'.
			'<table class="infoWindow">'.
			'<tr>'.
				'<td>Owner Name:</td>'.
				'<td><input type="text" size="32" name="owner_name" value="'.$owner_name.'"></td>'.
			'</tr>'.
			'<tr>'.
				'<td><a href="mailto:'.$owner_email.'">Owner Email:</a></td>'.
				'<td><input type="text" size="32" name="owner_email" value="'.$owner_email.'"></td>'.
			'</tr>'.
			'<tr>'.
				'<td>Owner Phone:</td>'.
				'<td><input type="text" size="32" name="owner_phone" value="'.$owner_phone.'"></td>'.
			'</tr>'.
			'<tr>'.
				'<td>Owner Address:</td>'.
				'<td><input type="text" size="32" name="owner_address" value="'.$owner_address.'"></td>'.
			'</tr>'.
			'<tr>'.
				'<td><input type="hidden" name="net_name" value="' . $net_name . '">' .
				'<input type="hidden" name="mac" value="' . $mac . '">' .
				'<td><input type="hidden" name="user_type" value="' . $utype . '"></td>' .
				'<input type="hidden" name="form_name" value="ownerEdit"></td>'.
			'</tr>'.
			'<tr><td><input type="submit" name="submit" value="Update" onClick="addNode(this.form)">' .
  	    		'<input type="button" name="Delete" value="Delete" onClick="deleteNode(this.form)"></td>'.
  	    	'</tr>' .
			'</table></form>');

$html_string = '<h3>Node Info: '.$name.'</h3>'.'<table class="infoWindow">'.
			'<tr>'.
				'<td>Node Owner:</td>'.
				'<td>'.$owner_name.'</td>'.
			'</tr>'.
			'<tr>'.
				'<td>Owner Email:</td>'.
				'<td>'.$owner_email.'</td>'.
			'</tr>'.
			'<tr>' .
				'<td colspan=2><i>Owner phone number and address hidden.</i></td>'.
			'</tr>'.
			'<tr>'.
				'<td>Last Checkin:</td>'.
				'<td>'.$LastCheckin.'</td>'.
			'</tr>'.
			'</table>';
$status = addslashes($html_string);

echo <<<END
 		
	point = new GPoint($longitude, $latitude);
	var marker = new nodeMarker(map, "$net_name", point, "$name", "$notes", "$mac", "$gateway", "$gw_metric", "$up", "$draggable", "$users");	
END;
	if($utype=='admin'){
		echo 'marker.addTab("Basic Info","'.$html.'");';
		echo 'marker.addTab("Basic Info","'.$owner.'");';
	} else {
		echo 'marker.addTab("Status","'.$status.'");';
	}
		
echo <<<END
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
<body bgcolor="#FFFFFF" align="center" onload="onLoad()" onResize="setMapSizePos()" onunload="GUnload()" >
<?php include '../lib/menu.php'; ?>

<div align="center" id="top">
  <input name="net_name" id="net_name" type=hidden value=<?php print $net_name?>>
</div>
<div class=note id=tip>Click anywhere on the map to add a new node to this network. <br>
<?if($utype == "admin") echo "Drag an existing node to a new location, or click on it to change its settings.";?>
 <a href=javascript:close()>hide</a>
</div>
<div id="map" style="width: 100%; height: 70%" text-align="center"></div>
</body>
</html>
