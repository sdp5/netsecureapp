<?php
require_once("include.php");
$obj = new dos();
$obj->logcurrent_request();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Admin Login [netsecureapp]</title>
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
<link href="style.css" rel="stylesheet" type="text/css" />
</head>
<body>
<div align="center">try netsecureapp with try@demo.user | password<br />
  <br /><br /><br />
</div>
<form id="form1" name="form1" method="post" action="redirect.php">
  <table width="400" height="280" border="0" align="center" style="background-image:url(images/loginbg.jpg)">
    <tr>
      <td height="266"><table width="100%" border="0">
        <tr>
          <td colspan="4">&nbsp;</td>
        </tr>
        <tr>
          <td height="27" colspan="4">&nbsp;</td>
        </tr>
        <tr>
          <td height="28" colspan="4">&nbsp;</td>
          </tr>
        <tr>
          <td width="9%">&nbsp;</td>
          <td width="33%">Username</td>
          <td width="50%"><input name="usrname" type="text" class="controls" id="usrname" /></td>
          <td width="8%">&nbsp;</td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td>Password</td>
          <td><input name="pwd" type="password" class="controls" id="pwd" /></td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td colspan="4"><div align="center">
            <input name="login" type="submit" class="controls" id="button" value="Log In" />
            </div></td>
        </tr>
        <tr>
          <td height="24" colspan="4">
		    <div align="center" class="phptext">
		      <?php echo "Client IP:<b>".$_SERVER['REMOTE_ADDR']."</b>&nbsp;&nbsp;&nbsp;Server IP:<b>".$_SERVER['SERVER_ADDR']."</b>"; ?> 
	        </div></td>
        </tr>
      </table></td>
    </tr>
  </table>
</form>
</body>
</html>
