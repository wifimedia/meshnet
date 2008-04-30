<?php
/* Name: mapkeys.php
 * Purpose: selects the proper google map api key for the host server.
 * Written By: Mike Burmeister-Brown, Shaddi Hasan
 * Last Modified: April 12, 2008
 * 
 * (c) 2008 Open Mesh Inc. and Orange Networking.
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

//just edit these two lines!
$myAddress = "your.address.here";	//the address you type into your browser to get to your orangemesh server, without the "http://"
$myKey = "your google map key goes here";	//see http://code.google.com/apis/maps/signup.html for a key


$host = $_SERVER['HTTP_HOST'];



//
// generate your google map API keys (http://code.google.com/apis/maps/index.html) and paste them here.
// you'll need two:  One for your domain with the www and one without.
//

if ($host == "localhost")
	echo '<script src="http://maps.google.com/maps?file=api&v=2&key=ABQIAAAAOblURTDowPDJJzfEXmiWIBT2yXp_ZAY8_ufC3CFXhHIE1NvwkxQbL_rhHeHuGFVXrVCUAJB7anr10w" type="text/javascript"></script>'."\n" ;
else if ($host == "omnis.hopto.org")
	echo '<script src="http://maps.google.com/maps?file=api&v=2&key=ABQIAAAAOblURTDowPDJJzfEXmiWIBTZjxrB_pn9jGmMh-RwF7AKb9MO5BSCsu6kbvCWascCDdDG4x87WRQixA" type="text/javascript"></script>'."\n" ;
else if ($host == "open-mesh.com")
	echo '<script src="http://maps.google.com/maps?file=api&v=2&key=ABQIAAAAOblURTDowPDJJzfEXmiWIBTZjxrB_pn9jGmMh-RwF7AKb9MO5BSCsu6kbvCWascCDdDG4x87WRQixA" type="text/javascript"></script>'."\n" ;
else if ($host == $myAddress)
	echo '<script src="http://maps.google.com/maps?file=api&v=2&key='.$myKey.'" type="text/javascript"></script>'."\n" ;  
else{
	include '../lib/menu.php';
	die('<div class=error>This OrangeMesh server does not have a Google Map key, which is needed to display the maps on this page. Contact the administrator, or see the owner\'s manual for information on how to get this key.</div>');
}
?>