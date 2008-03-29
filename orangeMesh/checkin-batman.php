<?php

#checkin-batman.php
#OrangeMesh
#Mac Mollison
#Last Update: Mar. 29
#Known Issues:
#    -Had to mess with /sbin/update to get it to work with non-https server.
#    -Right now the test to set the gateway_bit to 1 is if hops==1; I'm not positive this is correct.

//Establish database connection
mysql_connect("localhost", "root", "");
mysql_select_db("orangemesh");

//Create an array to fill with the values from ROBIN
$keys = array('ip','mac','uptime','robin','batman','memfree','nbs','gateway','gw-qual','routes','users','kbdown','kbup','rank','hops','ssid','pssid');
$robin_vars = array_fill_keys($keys, '');

//Move the ROBIN variables from $_GET to variables of the same names
foreach($robin_vars as $key => $value) { $robin_vars[$key] = $_GET[$key]; }

//We must at least have a MAC address; fail if we don't.
if ($robin_vars["mac"] == '') die("No MAC address.");

//If we don't have a DB row for this MAC address, create one.
//While we're at it, get the memlow and usershi variables to use later.
$query = sprintf("SELECT memlow, usershi FROM node WHERE mac='%s'",$robin_vars["mac"]);
$result = mysql_query($query);
if (mysql_num_rows($result) == 0) {
    $query = sprintf("INSERT INTO node (mac) VALUES ('%s')",$robin_vars["mac"]);
    mysql_query($query);          }
$row = mysql_fetch_array($result);
$memlow = $row['memlow'];
$usershi = $row['usershi'];

//Prepare the update string with the ROBIN vars
$update = "UPDATE node SET ";
foreach($robin_vars as $key => $value) $update .= "`" . $key . "`='" . $value . "', ";

//Add the derivative ROBIN vars to the update string
if ($memlow == '' || $memlow > $robin_vars["memfree"]) $update .= "`memlow`='" . $robin_vars['memfree'] . "', "; 
if ($usershi < $robin_vars["users"]) $update .= "`usershi`='" . $robin_vars['users'] . "', ";
if ($robin_vars['hops']==1) $update .= "`gateway_bit`=1, "; else $update .= "`gateway_bit`=0, ";

//Cap off the update string and make the update
$update = rtrim($update, ", ");
$update .= sprintf(" WHERE mac='%s'",$robin_vars["mac"]);
mysql_query($update);

?>
