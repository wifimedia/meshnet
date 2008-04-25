var gmarker = null;
var count=0;
var j=0;
var polyline = new Array();
var markers = new Array();
var macs = new Array();
		
	function isNumeric(strString)
	{
	   var strValidChars = "0123456789.-";
	   var strChar;
	   var blnResult = true;

		if (strString.length == 0) return false;

	   //  test strString consists of valid characters listed above
		for (i = 0; i < strString.length && blnResult == true; i++)
		{
	      strChar = strString.charAt(i);
	      if (strValidChars.indexOf(strChar) == -1)
	      {
				blnResult = false;
	      }
		}
		return blnResult;
   }
   
	//
	// Called from Add Node Form (add.php)
	//
	function addNode(form)
	{
		if(form.user_type.value=="user"){
			var text;
			if(form.node_name.value=="" || form.mac.value==""){
				text = "You must enter at least a node name and MAC address in format xx:xx:xx:xx:xx:xx. This number can be found on the back of your node.";
			}
			if(form.owner_name.value=="" || form.owner_email.value=="" || form.owner_phone.value=="" || form.owner_address.value==""){
				text += "You must provide your name, email, phone number, and address so the network administrator can verify and approve your node.";
			}
			if(text){
				alert(text);
				return;
			}
		}
		else {
			if(form.node_name.value=="" || form.mac.value==""){
				alert("You must enter at least a node name and MAC address in format xx:xx:xx:xx:xx:xx. This number can be found on the back of your node.");
				return;
			}
		}
		
		var req;

		//
		// check the IP/MAC field to see if user entered an IP or MAC address
		//
      	var items = form.mac.value.split(".");
      	var items2 = form.mac.value.split(":");


		if (items.length != 4 && items2.length != 6)
		{
			var mac="00";
			
			//
			// user entered MAC no colons (FON, Accton) so convert it...
			//
			for (var i = 2; i <12; i+=2)
			{
				var hex = form.mac.value.substr(i, 2);
				mac = mac + ':' + hex;
			}

		} else if (items2.length == 6) {			
			//
			// User entered MAC with colons (Meraki Mini)
			//
			for (var i = 9; i < 17; i+=3)
			mac = form.mac.value;
			
		} 
//		else if (items2.length == 3) {
//			//
//			// User entered only 3-digit MAC (NetEquality Wallplug)
//			// Note from Shaddi: I got rid of the references to ip here in favor
//			// of mac, but I don't know how to properly generate the mac. for now,
//			// we leave this out.
//			mac = "00:18:0A" // always Meraki
//			for (var i = 0; i < 8; i+=3)
//			{
//				mac = mac + ':' + hex;
//			}
//		}

		if (window.XMLHttpRequest)
			req = new XMLHttpRequest();
		else if (window.ActiveXObject)
			req = new ActiveXObject("Microsoft.XMLHTTP");
		req.open("POST", "c_addnode.php", false);
		req.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
		req.setRequestHeader('Cache-Control', 'private');
		var encoded = "mac=" + mac + "&net_name=" + form.net_name.value + "&form_name=" + form.form_name.value;
		if(form.node_name){encoded += "&name=" + form.node_name.value;}
		if(form.description){encoded += "&description=" + form.description.value;}
		if(form.latitude){encoded += "&latitude=" + form.latitude.value;}
		if(form.longitude){encoded += "&longitude=" +form.longitude.value;}
		if(form.owner_name){encoded += "&owner_name=" + form.owner_name.value;}
		if(form.owner_email){encoded += "&owner_email=" + form.owner_email.value;}
		if(form.owner_phone){encoded += "&owner_phone=" +	form.owner_phone.value;}
		if(form.owner_address){encoded += "&owner_address=" + form.owner_address.value;}
		req.send(encoded);
		if (req.status != 200) {
  			alert("There was a communications error: " + req.responseText);
		} else if (req.responseText.search("Error:") == 0){
  			alert(req.responseText);
		}else if(form.form_name.value == "addNode")
		{
			// good, so add	
			var point = new GPoint(form.longitude.value, form.latitude.value);

			var marker = new nodeMarker(map, form.net_name.value, point, form.name.value, form.description.value, form.mac.value, "0", "1700", "1", "true", "0");
			map.addOverlay(marker.get());
		}
		
		map.closeInfoWindow();
	}

	//
	// No popups on nodes in this map, so this isn't called right now...
	//
	function deleteNode(form)
	{
		var req;
		if (window.XMLHttpRequest)
			req = new XMLHttpRequest();
		else if (window.ActiveXObject)
			req = new ActiveXObject("Microsoft.XMLHTTP");
		req.open("POST", "c_deletenode.php", false);
		req.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
		req.setRequestHeader('Cache-Control', 'private');
		var encoded = "mac=" + form.mac.value + "&net_name=" + form.net_name.value;
		req.send(encoded);
		map.closeInfoWindow();
		if (req.status != 200) {
  			alert("There was a communications error: " + req.responseText);
		}

		// Must remove the marker now too!
		map.removeOverlay(gmarker);  // global - last clicked marker
	}

	//
	// Select the right icon based upon # users and 
	//
	function createIcon(metric, gateway, up, users)
	{   
		var baseIcon = new GIcon();
		
		if (!isNumeric(users))
		  users = 0;
		if (users > 9) users = 10;
			  
		switch (users)
		{
			case "0":
				baseIcon.iconSize = new GSize(24, 24);
				break;
						
			case "1":
			case "2":
			case "3":
				baseIcon.iconSize = new GSize(26, 26);
				break;
											
			case "4":
			case "5":
			case "6":
				baseIcon.iconSize = new GSize(28, 28);
				break;
											
			case "7":
			case "8":
			case "9":
				baseIcon.iconSize = new GSize(30, 30);
				break;

			case 10:
				baseIcon.iconSize = new GSize(34, 34);
				break;
				
			default:
				baseIcon.iconSize = new GSize(24, 24);
				break;						
		}

		//
		// The order of these is important!
		//
		// DOWN is no data is last 25 minutes 	
		if (up > 1800 || metric < 0)
		{
			baseIcon.image = "../lib/images/rr-gy-" + users + ".png";
			baseIcon.transparent = "../lib/images/rr-gy-" + users + ".png";				
		}
		
		// BAD is outage in last hour or high metric/hops
		else if (metric > 7000)
		{
			baseIcon.image = "../lib/images/rr-rd-" + users + ".png";
			baseIcon.transparent = "../lib/images/rr-rd-" + users + ".png";				
		}
		
		// CAUTION is high daily outage, high hops, or moderately high metric/hops
		else if (metric > 4000)
		{
			baseIcon.image = "../lib/images/rr-yw-" + users + ".png";
			baseIcon.transparent = "../lib/images/rr-yw-" + users + ".png";				
		}
		
		// GOOD
		else
		{
			baseIcon.image = "../lib/images/rr-gr-" + users + ".png";
			baseIcon.transparent = "../lib/images/rr-gr-" + users + ".png";				
		}
	
		baseIcon.shadow=null;
		
		baseIcon.iconAnchor = new GPoint(10, 10);
		baseIcon.infoWindowAnchor = new GPoint(10, 1);

		var icon = new GIcon(baseIcon);
		return icon;
	}
	
	//set the color of the route
	function setRouteColor(metric)
	{
		var RGB;
		if (metric > 180) 
			RGB = "#00FF00";  // green
		else if (metric > 120)
			RGB = "#F39C04";  // yellow
		else
			RGB = "E01D49";  // red
//		else RGB = 0; // flag for no polyline 
	
		return RGB;
	}
	
	//draw the node route lines for a marker
	function drawRoutePolyline(latitude, longitude, lat2, long2, metric)
	{
		var RGB="#1e2f10"; //setRouteColor(metric);
		var width;

		if (metric < 10)
			return 0;
					
		if (metric < 14)
			width = 1;
		if (metric < 22)
		  width = 3;
		else
		  width = 7;

//		alert (metric + " " + width);
		 
		var polyline = (new GPolyline([new GLatLng(latitude, longitude), new GLatLng(lat2, long2)], RGB, width, 0.1));	
		
		return polyline;
	}

	//    
	// Creates a node marker
	//
	function nodeMarker(map, net_name, point, name, description, mac, gateway, metric, up, draggable, users) 
	{		
		var icon = createIcon(metric, gateway, up, users);
		var marker = new GMarker(point, {icon:icon, draggable:draggable, title:name});
		var infoTabs = new Array();
		
		markers[j] = marker;
		gmarker = marker;
    	macs[j++] = mac;
		
		this.point = point;
		
    	function addTab(label,content){
			infoTabs[infoTabs.length] = new GInfoWindowTab(label,content);
			return true;
    	};

    	function addListeners(){
	   		// Show this marker's info when it is clicked	
			GEvent.addListener(marker, "click", function() 
			{
				gmarker = marker;
				if(infoTabs.length>0){
					marker.openInfoWindowTabsHtml(infoTabs);
				} else {
					//nothing!
				}
				
			});
	
			GEvent.addListener(marker, "dragstart", function() {
				map.closeInfoWindow();
			});
			
			//handles the behavior for dragging
			var req;
			GEvent.addListener(marker, "dragend", function() {
				var pDrop = marker.getPoint();
				if (window.XMLHttpRequest)
					req = new XMLHttpRequest();
				else if (window.ActiveXObject)
					req = new ActiveXObject("Microsoft.XMLHTTP");
				req.open("POST", "c_addnode.php", false);
				req.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
				req.setRequestHeader('Cache-Control', 'private');
				var encoded = "mac=" + mac + "&net_name=" + net_name + "&name=" + name + "&description=" + description + "&latitude=" + pDrop.y + "&longitude=" + pDrop.x;
				req.send(encoded);
				if (req.status != 200) {
		  			alert("There was a communications error: " + req.responseText);
				}
	
			});
			
			GEvent.addListener(marker, "mouseout", function() {
	      		var items = gw_route.split("+");
	     		 
	      		for (var i=0; i<count; i++) {
				//map.removeOverlay(polyline[i]);
	      		}
			});
	
			GEvent.addListener(marker, "mouseover", function() {
	      		var items = gw_route.split("+");
	      
	      		for (var i=0; i<count; i++) {
	          		map.removeOverlay(polyline[i]);
	      		}
	
	      		if (gateway != "true" && items.length >= 6)	{
	         	// remove old polylines
	        	count = -1;
	        
		 			for (var i=0; i<(items.length-1); i+=5)	{
						if (i > 0) {
							var RGB=setRouteColor(items[i+1]);
							polyline[count] = (new GPolyline([new GLatLng(mpoint.y, mpoint.x), new GLatLng(items[i+2], items[i+3])], RGB, 5, 0.85));	
							map.addOverlay(polyline[count]);  // on map, this fails and aborts the loop!  Works on add!
						}
						var mpoint = new GPoint(items[i+3], items[i+2]);
		          		count++;
					}
				}
	
			});
		}
		
		function get(){return marker;}
		
		this.addTab = addTab;
		this.addListeners = addListeners;
		this.get = get;
	}
	
	//FROM rnmap.js -- NOT YET RELEASED
	function myClick(mac)
	{
	  for (var i=0; i<macs.length; i++) 
	  {
	      s = macs[i].replace(/\./g, "0");
	      if (s == mac)
	        GEvent.trigger(markers[i],"click");
	  }
	}

	function setMapSizePos() 
	{
	  var hBody = document.body.clientHeight;
		var hTop = document.getElementById("top").offsetHeight;	
		var left = document.getElementById("top").offsetLeft;
				
		document.getElementById("map").style.height = hBody - hTop + "px";		
		document.getElementById("map").style.marginLeft = 0 + "px";
	}

  function showAddress(address) 
  {
    if (geocoder) 
    {
      geocoder.getLatLng(address, function(point) 
      {
          if (!point)
          {
            //alert("We weren't able to find your default location, so this is our best guess.");
          }
          else
          {
            map.setCenter(point, 15);
          }
      }
      );
    }
  }


	function myCenterAndZoom(map, minY, maxY, minX, maxX, nodeloc) 
	{
		setMapSizePos();
		if ((minX + maxX + minY + maxY) != 0)
		{
			var rectBounds = new GLatLngBounds(new GLatLng(minY, minX), new GLatLng(maxY, maxX));
			map.setCenter(rectBounds.getCenter(), map.getBoundsZoomLevel(rectBounds));  //-1
		}
		else
		{
			showAddress(nodeloc);
		}
	}

