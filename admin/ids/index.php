<?php
session_save_path("../../session");
session_start();

if(($_SESSION['live'] == 1 AND $_SESSION['signature'] == md5($_SERVER['HTTP_USER_AGENT']) AND $_SESSION['role'] == md5("admin")) != 1)
{
	$_SESSION['live'] = -1; echo '<META HTTP-EQUIV=refresh content="0; URL=../../redirect.php">';
	echo '<script>alert("A Session Error has been Reported. Kindly Re-Login!!")</script>'; 
} 
else {
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>IDS [netsecureapp]</title>
<style type="text/css">
<!--
body,td,th {
	font-family: Verdana, Geneva, sans-serif;
	font-size: small;
	color: #333;
}
body {
	background-color: #F2F0EE;
}
#apDiv1 {
	position:absolute;
	width:154px;
	height:140px;
	z-index:1;
	left: 758px;
	top: 49px;
}
body table tr td table tr td p {
	font-size: large;
	font-family: Georgia, "Times New Roman", Times, serif;
}
.phptext1 {
	font-family: Verdana, Geneva, sans-serif;
	font-size: small;
	font-style: normal;
	color: #333;
}
a {
	font-size: small;
	color: #333;
}
a:link {
	text-decoration: none;
}
a:visited {
	text-decoration: none;
	color: #666;
}
a:hover {
	text-decoration: underline;
	color: #333;
}
a:active {
	text-decoration: none;
	color: #999;
}
-->
</style>
<link href="../../style.css" rel="stylesheet" type="text/css" />
</head>

<body link="#FFFFFF" vlink="#FFFFFF" alink="#FFFFFF">
<table width="800" align="center" bgcolor="#FFFFFF">
  <tr>
    <td width="968"><table width="800" border="0" align="center">
      <tr style="background-image:url(../../images/header.jpg)">
        <td height="85" colspan="3">
          <p>
          <p align="right">netsecureapp.in | Online Network Security and Analysis Suite | v1.0</p></td>
      </tr>
      <tr>
        <td height="4" colspan="3"><hr /></td>
        </tr>
      <tr>
        <td height="24" colspan="3"><table width="100%" border="0">
          <tr>
            <td width="50%"><?php echo "Welcome <b>".$_SESSION['user']."</b>!!"; ?></td>
            <td width="38%">Logged In IP: <b><?php echo $_SERVER['REMOTE_ADDR']; ?></b></td>
            <td width="12%">[ <a href="../logout.php"><b>Logout</b></a> ]</td>
          </tr>
        </table></td>
        </tr>
      <tr>
        <td height="4" colspan="3"><hr /></td>
        </tr>
      <tr>
        <td width="29%" height="34"><div align="left"><a href="."><img src="../../images/ids.jpg" /></a></div></td>
        <td width="64%" height="34">&nbsp;</td>
        <td width="7%"><div align="right"><a href="../."><img src="../../images/home.jpg" alt="home" /></a></div></td>
      </tr>
      <tr>
        <td height="84" colspan="3" background="../../images/menubg.jpg">&nbsp;</td>
        </tr>
      <tr>
        <td height="220" colspan="3" align="center">
<br />
<table width="755" border="1" align="center" cellpadding="2" cellspacing="2" bordercolor="#CCCCCC">
  <tr>
    <td><table width="750px" height="212" align="center" cellpadding="2" cellspacing="2">
      <tr>
        <td colspan="2">
          <table width="100%" height="60px" align="center" cellpadding="5" cellspacing="5" style="vertical-align:top">
              <tr>
                <td><div align="right"><img src="images/test.gif" width="20" height="20" /></div></td>
                <td><div align="left"><a href="<?php $key=md5("test"); echo "?key=".$key; ?>">Test IDS</a></div></td>
              </tr>
              <tr>
                <td><div align="right"><img src="images/view.gif" width="20" height="20" /></div></td>
                <td><div align="left"><a href="<?php $key=md5("view"); echo "?key=".$key; ?>">View Logs</a></div></td>
              </tr>
            </table>
          </td>
        <td width="8" background="images/line.jpg">&nbsp;</td>
        <td width="506"><div align="left">
          <?php
    $val1 = md5("test");
	$val2 = md5("view");
		
	switch($_GET['key'])
	{
		case "$val1"	:	include("default.php");
							echo '<script>window.open("test.php")</script>';
							break;		
		case "$val2"	:	include("view.php");
							break;
		default			:	include("default.php");
							break;
	}
	?>
          </div></td>
      </tr>
      </table></td>
    </tr>
</table></td>
        </tr>
      <tr style="background-image:url(../../images/footer.jpg)">
        <td height="111" colspan="3"><p>&nbsp;</p><p align="center" class="phptext1">
          <?php echo "Last Login IP: <b>".$_SESSION['lastlogin']."</b>"; ?></p>
          <p align="center" class="phptext1">© Copyright 2010. netsecureapp [IN]. Online Network Security and Analysis Suite.</p></td>
      </tr>
    </table></td>
  </tr>
</table>
</body>
</html>
<?php } ?>