<?php
/* Name: select.php
 * Purpose: select network page.
 * Written By: Shaddi Hasan
 * Last Modified: March 27, 2008
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
include '../lib/menu.php';
?>
<html>
<head>
<title>Select Network</title>
</head>
<body>
Select the network you'd like to see.
<form method="POST" action='c_select.php' name="select">
	<?php if (isset($_SESSION['error'])) echo "Network doesn't exist.  Please try again.<br>";?>
	Network Name <input name="net_name"><br>
  	<input name="select" value="View Network" type="submit">
</form>
</body>
</html>