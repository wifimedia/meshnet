<?php 

session_start();
if (!isset($_SESSION['netid'])) 
	header("Location: ../entry/select.php");

include '../lib/menu.php';

$_SESSION['netid']
?>
