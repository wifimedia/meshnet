<?php
$on_index = (boolean)strpos($_SERVER['PHP_SELF'],'index.php');
?>
<LINK REL=STYLESHEET HREF="<?if(!$on_index){echo '../';}?>lib/style.css" TYPE="text/css">