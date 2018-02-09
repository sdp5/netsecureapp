<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Installation | netsecureapp</title>
<style type="text/css">
<!--
body,td,th {
	font-family: Georgia, "Times New Roman", Times, serif;
	font-size: x-large;
	color: #333;
}
body {
	background-color: #CCC;
}
.phptext2 {
	font-family: Verdana, Geneva, sans-serif;
	font-size: x-small;
	font-style: normal;
	color: #333;
}
.phptext1 {
	font-family: Verdana, Geneva, sans-serif;
	font-size: small;
	font-style: normal;
	color: #333;
}
.phptext {
	font-family: Georgia, "Times New Roman", Times, serif;
	font-size: x-large;
	font-style: normal;
	color: #333;
}

-->
</style>
</head>
<body>
<table width="800" border="0" align="center" bgcolor="#FFFFFF">
  <tr style="background-image:url(../images/header.jpg)">
    <td height="90"><p align="center" class="phptext">Netsecureapp Installer</p></td>
  </tr>
  <tr>
<td height="400" class="phptext1">
	<?php
	
	switch($_GET['key'])
	{
		case "configuredb" : include("dbconfig.php"); break;
		case "createuser"  : include("createuser.php"); break;
	}
	
	?>
</td>
  </tr>
  <tr style="background-image:url(../images/footer.jpg)">
    <td height="90" ><p>&nbsp;</p><p align="center" class="phptext1">© Copyright 2010. netsecureapp [IN]. Online Network Security and Analysis Suite.</p></td>
  </tr>
</table>
</body>
</html>