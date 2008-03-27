<?php

include '../lib/menu.php';
?>
<html>
<head>
<title>Select Network</title>
</head>
<body>
Select the network you'd like to see.
<form method="POST" action='c_select.php' name="select">
	<?php if (isset($_SESSION['login_error'])) echo "Network doesn't exist.  Please try again.<br>";?>
	Network Name <input name="net_name"><br>
  	<input name="select" value="View Network" type="submit">
</form>
</body>
</html>