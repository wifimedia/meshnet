<?php
/* Name: checkin-batman.php
 * Purpose: checking script for nodes.
 * Written By: Mac Mollison
 * Last Modified: April 6, 2008
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

//Establish database connection
require 'lib/connectDB.php';

//Create an array to fill with the values from ROBIN
$keys = array('ip','mac','uptime','robin','batman','memfree','nbs','gateway','gw-qual','routes','users','kbdown','kbup','rank','hops','ssid','pssid');
$robin_vars = array_fill_keys($keys, '');

//Move the ROBIN variables from $_GET to variables of the same names
foreach($robin_vars as $key => $value) { $robin_vars[$key] = $_GET[$key]; }

//We must at least have a MAC address; fail if we don't.
if ($robin_vars["mac"] == '') die("No MAC address.");

//If we don't have a DB row for this MAC address, create one.
//While we're at it, get the memlow, usershi, and netid variables to use later.
$query = sprintf("SELECT memlow, usershi, netid FROM node WHERE mac='%s'",$robin_vars["mac"]);
$result = mysqli_query($conn,$query);
if (mysqli_num_rows($result) == 0) {
    $query = sprintf("INSERT INTO node (mac) VALUES ('%s')",$robin_vars["mac"]);
    mysqli_query($query);
}
$row = mysqli_fetch_array($result);
$memlow = $row['memlow'];
$usershi = $row['usershi'];
$netid = $row['netid'];

//Prepare the update string with the ROBIN vars
$update = "UPDATE node SET ";
foreach($robin_vars as $key => $value) $update .= "`" . $key . "`='" . $value . "', ";

//Add the time and derivative ROBIN vars to the update string
$update .= "`time`=CURRENT_TIMESTAMP, ";
if ($memlow == '' || $memlow > $robin_vars["memfree"]) $update .= "`memlow`='" . $robin_vars['memfree'] . "', "; 
if ($usershi < $robin_vars["users"]) $update .= "`usershi`='" . $robin_vars['users'] . "', ";
if (in_array($robin_vars["gateway"],split(";",$robin_vars["nbs"]))) $update .= "`gateway_bit`=0, "; 
else $update .= "`gateway_bit`=1, ";    //If $gateway is in $nbs array, the gateway is a neighbor (i.e. another node), which means this node itself is not a gateway. (For actual gateway nodes, the 'gateway' is the router.)

//Cap off the update string and make the update
$update = rtrim($update, ", ");
$update .= sprintf(" WHERE mac='%s'",$robin_vars["mac"]);
mysqli_query($conn,$update);

//Get the network settings variables
$query = sprintf("SELECT * FROM network WHERE id='%s'",$netid);
$result = mysqli_query($conn,$query);
if (mysqli_num_rows($result) == 0) die("No such network");
$row = mysqli_fetch_array($result);
$fields = array("ap1_essid","ap1_key","ap2_essid","ap2_key","ap1_isolate","ap2_isolate","ap2_enable",
  "node_pwd","download_limit","upload_limit","throttling_enable","lan_block","splash_redirect_url",
  "splash_idle_timeout","splash_force_timeout","test_firmware_enable","splash_enable");
foreach ($fields as $field) $$field = $row[$field];

//Create any other special strings needed for the response
if (strlen($splash_redirect_url) > 0) $splash_redirect_url_string = "RedirectURL " . $splash_redirect_url;
else $splash_redirect_url_string = "";
if ($test_firmware_enable == 1) $base = "trunk";
else $base = "beta";
if ($splash_enable == 1) $authenticate_immediately = 0; 
else $authenticate_immediately = 1;
if ( strlen($ap1_key) >= 8) $ap_psk = 1; 
else $ap_psk = 0;

//Output response to node. This comes from Antonio's sample, with some bug fixes.
echo <<< RESPONSE
#@#config node
general.net orange
#@#config management
enable.base $base
enable.rootpwd $node_pwd
enable.defessid 0
#@#config mesh
ap.up 1
Myap.up $ap2_enable
ap.psk $ap_psk
#@#config wireless
private.ssid $ap2_essid
public.ssid $ap1_essid
private.key $ap2_key

RESPONSE;
if ($ap_psk) echo "public.key $ap1_key\n";
echo <<< RESPONSE
#@#config iprules
LAN_BLOCK $lan_block
AP1_bridge $ap1_isolate
AP2_bridge $ap2_isolate
#@#config secondary
backend.update 0
backend.server
backend.ssl 0
#@#config acl
mac.mode_ap1 0
#@#config nodog
FirewallRuleSet preauthenticated-users {
FirewallRule allow
}
FirewallRuleSet authenticated-users {
FirewallRule allow
}
FirewallRuleSet users-to-router {
FirewallRule allow udp port 53
FirewallRule allow tcp port 53
FirewallRule allow udp port 67
FirewallRule allow tcp port 20
FirewallRule allow tcp port 21
FirewallRule allow tcp port 22
FirewallRule allow tcp port 23
FirewallRule allow tcp port 80
FirewallRule allow tcp port 443
}
GatewayName orange test
RedirectURL http://$splash_redirect_url
ClientIdleTimeout $splash_idle_timeout
ClientForceTimeout $splash_force_timeout
AuthenticateImmediately $authenticate_immediately
TrafficControl $throttling_enable
DownloadLimit $download_limit
UploadLimit $upload_limit
#bogus2 772827811
#@#config splash-HTML
page http://www.open-mesh.com/users/anselmi/splash.txt
file http://www.open-mesh.com/pages/gateway.css
image http://www.open-mesh.com/users/anselmi/images/antonio.GIF
image http://www.open-mesh.com/users/anselmi/images/open-mesh-small.png

RESPONSE;

