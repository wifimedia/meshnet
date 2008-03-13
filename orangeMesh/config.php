<?php
/* Name: config.php
 * Purpose: dashboard configuration settings.
 * Written By: Shaddi Hasan
 * Last Modified: March 8, 2008
 * 
 * (c) 2008 Orange Networking.
 *  
 * This file is part of OrangeMesh.
 *
 * OrangeMesh is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * OrangeMesh is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with OrangeMesh.  If not, see <http://www.gnu.org/licenses/>.
 * 
 */

$sourceDBhost;
$sourceDBuser = "root";
$sourceDBpwd = "";

$destDBhost;
$destDBuser = "root";
$destDBpwd = "";

$table = "animals";

function setConfig($source,$dest){
	$sourceDBhost = $source;
	$destDBhost = $dest;
	
	echo "I got called!<BR>";
	echo "source: $sourceDBhost<BR>";
	echo "dest: $destDBhost<BR>";
}
function setConfigAll($source,$sourceU,$sourceP,$dest,$destU,$destP){
	$sourceDBhost = $source;
	$sourceDBuser = $sourceU;
	$sourceDBpwd = $sourceP;

	$destDBhost = $dest;
	$destDBuser = $destU;
	$destDBpwd = $destP;
	echo "I got called!<BR>";
	echo "source: $sourceDBhost<BR>";
	echo "dest: $destDBhost<BR>";
}

function getSourceHost(){
	return $sourceDBhost;
}
function getSourceUser(){
	return $sourceDBuser;
}
function getSourcePwd(){
	return $sourceDBpwd;
}

function getDestHost(){
	return $destDBhost;
}
function getDestUser(){
	return $destDBuser;
}
function getDestPwd(){
	return $destDBpwd;
} 

function getTable(){
	return $table;
}
?>