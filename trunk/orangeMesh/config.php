<?php
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