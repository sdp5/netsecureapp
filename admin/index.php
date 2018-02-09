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
<title>Admin Dashboard [netsecureapp]</title>
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
<link href="../style.css" rel="stylesheet" type="text/css" />
</head>

<body link="#FFFFFF" vlink="#FFFFFF" alink="#FFFFFF" tracingsrc="../images/home.jpg" tracingopacity="0">
<table width="800" align="center" bgcolor="#FFFFFF">
  <tr>
    <td><table width="100%" border="0" align="center">
      <tr style="background-image:url(../images/header.jpg)">
        <td height="85" colspan="5">
         <p align="right">netsecureapp.in | Online Network Security and Analysis Suite | v1.0</p></td>
      </tr>
      <tr>
        <td height="4" colspan="5"><hr /></td>
        </tr>
      <tr>
        <td height="24" colspan="5"><table width="100%" border="0">
          <tr>
            <td width="50%"><?php echo "Welcome <b>".$_SESSION['user']."</b>!!"; ?></td>
            <td>Logged In IP: <b><?php echo $_SERVER['REMOTE_ADDR']; ?></b></td>
            <td width="10%">[ <a href="logout.php"><b>Logout</b></a> ]</td>
          </tr>
        </table></td>
        </tr>
      <tr>
        <td height="4" colspan="5"><hr /></td>
        </tr>
      <tr>
        <td width="8%" height="34"><div align="center"><a href="."><img src="../images/home.jpg" alt="home" /></a></div></td>
        <td width="24%" height="34"><div align="center"><img src="../images/dblogo.jpg" /></div></td>
        <td width="26%"><div align="center"><a href="<?php $zone=md5("nt"); echo "?zone=".$zone; ?>"><img src="../images/nt.jpg" /></a></div></td>
       <td width="17%"><div align="center"><a href="<?php $zone=md5("ids"); echo "?zone=".$zone; ?>"><img src="../images/ids.jpg" /></a></div></td>
       <td width="25%"><div align="center"><a href="<?php $zone=md5("dos"); echo "?zone=".$zone; ?>"><img src="../images/dos.jpg" /></a></div></td>
      </tr>
      <tr>
        <td height="84" colspan="5" background="../images/menubg.jpg">&nbsp;</td>
        </tr>
      <tr>
        <td height="220" colspan="5"><div align="center">
        <?php
       	$val1 = md5("nt");
	   	$val2 = md5("ids");
		$val3 = md5("dos");
		
		switch($_GET['zone'])
		{
			case "$val1" :  echo '<META HTTP-EQUIV=refresh content="0; URL=networktools">';
							break;
			case "$val2" :  echo '<META HTTP-EQUIV=refresh content="0; URL=ids">';
							break;
			case "$val3" :  echo '<META HTTP-EQUIV=refresh content="0; URL=dos">';
							break;
			default		 :  include("status.php");
							break;
		}
		?></div></td>
        </tr>
      <tr style="background-image:url(../images/footer.jpg)">
        <td height="111" colspan="5"><p>&nbsp;</p><p align="center" class="phptext1">
          <?php echo "Last Login IP: <b>".$_SESSION['lastlogin']."</b>"; ?></p>
          <p align="center" class="phptext1">© Copyright 2010. netsecureapp [IN]. Online Network Security and Analysis Suite.</p></td>
      </tr>
    </table></td>
  </tr>
</table>
</body>
</html>
<?php } ?>