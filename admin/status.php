<?php
session_save_path("../session");
session_start();

if(($_SESSION['live'] == 1 AND $_SESSION['signature'] == md5($_SERVER['HTTP_USER_AGENT']) AND $_SESSION['role'] == md5("admin")) != 1)
{
	$_SESSION['live'] = -1; echo '<META HTTP-EQUIV=refresh content="0; URL=../redirect.php">';
	echo '<script>alert("A Session Error has been Reported. Kindly Re-Login!!")</script>'; 
} 
else {
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title></title>
<link href="../style.css" rel="stylesheet" type="text/css" />
</head>
<body>
<fieldset class="phptextforms" style="width:700px"><legend class="phptext">Environment Variables</legend>
<br /><table width="690px" border="1" cellpadding="4" cellspacing="4" bordercolor="#EEEEEE" align="center">
  <tr bgcolor="#EEEEEE" align="left">
    <td width="146">Operating Domain</td>
    <td width="534"><?php echo $_SERVER['HTTP_HOST']; ?></td>
  </tr>
  <tr align="left">
    <td>Client Agent</td>
    <td><?php echo $_SERVER['HTTP_USER_AGENT']; ?></td>
  </tr>
  <tr bgcolor="#EEEEEE" align="left">
    <td>Access Location</td>
    <td><?php echo $_SERVER['REQUEST_URI']; ?></td>
  </tr>
  <tr align="left">
    <td>Server Signature</td>
    <td><?php echo $_SERVER['SERVER_SIGNATURE']; ?></td>
  </tr>
  <tr bgcolor="#EEEEEE" align="left">
    <td>Server Software</td>
    <td><?php echo $_SERVER['SERVER_SOFTWARE']; ?></td>
  </tr>
  <tr align="left">
    <td>Server IP</td>
    <td><?php echo $_SERVER['SERVER_ADDR']; ?></td>
  </tr>
  <tr bgcolor="#EEEEEE" align="left">
    <td>Server Port</td>
    <td><?php echo $_SERVER['SERVER_PORT']; ?></td>
  </tr>
  <tr align="left">
    <td>Client IP</td>
    <td><?php echo $_SERVER['REMOTE_ADDR']; ?></td>
  </tr>
  <tr bgcolor="#EEEEEE" align="left">
    <td>Client Port</td>
    <td><?php echo $_SERVER['REMOTE_PORT']; ?></td>
  </tr>
  <tr align="left">
    <td>HTTP Connection</td>
    <td><?php echo $_SERVER['HTTP_CONNECTION']; ?></td>
  </tr>
  <tr bgcolor="#EEEEEE" align="left">
    <td>Client Cookie</td>
    <td><?php echo $_SERVER['HTTP_COOKIE']; ?></td>
  </tr>
  <tr align="left">
    <td>Server Protocol</td>
    <td><?php echo $_SERVER['SERVER_PROTOCOL']; ?></td>
  </tr>
  <tr bgcolor="#EEEEEE" align="left">
    <td>Gateway Interface</td>
    <td><?php echo $_SERVER['GATEWAY_INTERFACE']; ?></td>
  </tr>
</table>
<br />
</fieldset>
</body>
</html>
<?php } ?>