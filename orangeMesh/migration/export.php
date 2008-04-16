<?php
/* Name: export.php
 * Purpose: form to add migration info.
 * Written By: Shaddi Hasan
 * Last Modified: April 5, 2008
 * TODO: allow users to set a new name for their network on the remote server.
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
<title>Migrate Network</title>
<?include "../lib/style.php"; ?>
</head>
<body>
Enter the location of the server to which you want to migrate this network's data.<br>
<br>
Be careful you enter data correctly! This script will be sending your network configuration information,
which will include your network keys. Be sure you don't mistype and enter evilHax0r.com for the host name!
<br>
<br>
Path is the location on the remote server in which you've installed OrangeMesh. Leave this blank if it's placed
in your web server's root directory. It could also be under /orangemesh.
<form method="POST" action="c_export.php" name="export">
	Host: <input name="host"><br>
	Path: <input name="path"><br>
	New Network Name (optional): <input name="new_name"><br>
	<input type="submit" name="submit" value="submit">
</form>
</body>
</html>