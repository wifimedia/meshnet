<?php
/* Name: login.php
 * Purpose: creates a user session and logs the user into the config or status page.
 * Written By: Mike Burmeister-Brown, Shaddi Hasan
 * Last Modified: March 26, 2008
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

$rd_page = $_GET['rd'];
unset($_GET['rd']);
session_start();

include '../lib/menu.php';
if(isset($_POST["submit"])){
	//unset variable
	unset($_POST["submit"]);
	//setup connection
	require '../lib/connectDB.php';
	setTable('network');
	
	//generate query
	$net_name = $_POST["net_name"];
	$password = md5($_POST["password"]);
	
	$query = "SELECT * FROM ".$dbTable." WHERE net_name='".$net_name."' AND password='".$password."'";
	$result = mysqli_query($conn,$query);
	
	$num = mysqli_num_rows($result);

	if ( $num >= 1 )
	{   
    	// A matching row was found - the user is authenticated as 'admin'. 
    	$resArray = mysqli_fetch_array($result, MYSQLI_ASSOC);
    	$_SESSION['netid'] = $resArray['id'];
   		$_SESSION['user_type'] = 'admin';
   		$_SESSION['net_name'] = $net_name;
	 	echo "<h1>Success!</h1>";
    	echo "<meta http-equiv=\"Refresh\" content=\"0;url=../$rd_page.php\">"; 
    	
	} else 
	{
		//there was no match found, so the login failed
    	unset($_SESSION['user_type']);
    	$_SESSION['error'] = true;
    	echo "<meta http-equiv=\"Refresh\" content=\"0;url=login.php?rd=$rd_page\">"; 
  	}  
}
//else if (isset($_SESSION['authenticated']) && ($rd_page != "edit")) {
//    // gets here if already logged in and coming from another page.
//    echo "<meta http-equiv=\"Refresh\" content=\"0;url=$rd_page.php\">"; 
	

//}
 else {
?>
<!-- HTML BEGINS HERE -->
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
  <meta content="text/html; charset=ISO-8859-1"
 http-equiv="content-type">
  <title></title>
</head>
<body>
Login to manage your network.<br>
<br>
<form method="POST" action='<?php echo "login.php?rd=$rd_page"; ?>' name="login">
	<?php if (isset($_SESSION['error'])) echo "Account or Password not recognized.  Please try again.<br>";?>
	Network Name <input name="net_name"><br>
	Password <input name="password" type="password"><br>
  	<input name="submit" value="Login" type="submit">
  	<input name="reset" value="Reset" type="reset"><br>
  	<input name="rd" id="rd" type=hidden value=<?php print $rd_page?>>
</form>
</body>
</html>
<!-- END HTML -->
<?
} 
?>