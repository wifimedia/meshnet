<?php
/* Name: menu.php
 * Purpose: main menu for dashboard.
 * Written By: Mike Burmeister-Brown, Shaddi Hasan
 * Last Modified: March 19, 2008
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
?>

<div id="menu">
<ul id="nav">
<li id="home" class="first"><a href="index.php">Home</a></li>
<li id="create"><a href="create.php">Add Network</a></li>
<li id="edit"><a href="edit.php">Edit Network</a></li>
<li id="view"><a href="view.php">Network Status</a></li>
<li id="logout"><a href="logout.php">Log Out</a></li>
<?php
/* if ($_SERVER["SERVER_NAME"] == "www.open-mesh.com" || $_SERVER["SERVER_NAME"] == "open-mesh.com")
 * echo <<<END
 * <li id="contact"><a href="supportform.php">Contact</a></li>
 * <li id="contact"><a href="store">Store</a></li>
 * <li id="Docs"><a href="wiki/doku.php?id=open-mesh">Mesh Network HowTo</a></li>
 * END;
 */
?>
</ul>
<p>
</div>

