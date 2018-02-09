<?php
@session_save_path("../session");
@session_start();
$_SESSION['live'] = -1;
echo '<META HTTP-EQUIV=refresh content="0; URL=../redirect.php">';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title></title>
<style type="text/css">
<!--
body,td,th {
	font-family: Verdana, Geneva, sans-serif;
	font-size: large;
	color: #333;
}
body {
	background-color: #CCC;
}
-->
</style>
</head>
<body>
</body>
</html>