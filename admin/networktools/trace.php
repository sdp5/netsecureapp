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
<?php
ob_start();
global $Topt;
if(phpversion() >= "4.2.0"){
   extract($_POST);
   extract($_GET);
   extract($_SERVER);
   extract($_ENV);
}
?>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<script>
function m(el) {
  if (el.defaultValue==el.value) el.value = ""
}
</script>
<title></title>
<link href="../../style.css" rel="stylesheet" type="text/css" />
</head>
<body>
<form action="<?php $var=md5("trace"); echo "?key=$var"; ?>" method="post">
<table width="500px" border="0" align="center" bgcolor="#EEEEEE">
  <tr>
    <td colspan="3"><div align="right" class="phptextforms">Trace Route</div></td>
  </tr>
  <tr><td colspan="3">&nbsp;</td></tr>
  <tr>
    <td width="20%">&nbsp;</td>
    <td width="50%" align="center"><input type="text" name="target" value="Enter Host or IP" onFocus="m(this)" class="phptextforms"></td>
    <td width="30%" align="left"><input type="submit" name="trace" value="Go" class="phptextforms"></td>
  </tr>
  <tr><td colspan="3">&nbsp;</td></tr>
  <tr><td colspan="3" align="center" class="phptextforms">
  <?php
  if(isset($_POST['trace']))
  {
	  	$target = $_POST['target'];
		
		$ntarget = "";

		function message($msg)
		{
			echo "<TABLE cellpadding=2><TR align=\"left\"><TD>";
			echo "<font face=\"verdana,arial\" class=\"phptextforms\">$msg</font>";
			echo "</TD></TR></TABLE></CENTER>";
			flush();
		}

		function tr($target,$Topt)
		{
			message("<p class=\"phptextforms\">Traceroute Results:<blockquote>");
			
			if(stristr($_SERVER['SERVER_SOFTWARE'], "win")) $UNIX = "false";
			else $UNIX = "true";
			
			if ($UNIX="true"){$TR="/usr/sbin/traceroute ".$Topt." ".$target;}
			if ($UNIX="false"){$TR="tracert ".$Topt." ".$target;}

			exec($TR, $result, $rval);
			for ($i = 0; $i < count($result); $i++)
			{
				$rt.=$result[$i]."<br />";
			}
			if (! $msg .= trim(nl2br($rt))) 
  			$msg .= "Traceroute failed. Server permissions may need to be configured.";
			$msg .= "</blockquote></p>";
			message($msg);
		}
		
		if( (!$target) || (!preg_match("/^[\w\d\.\-]+\.[\w\d]{1,4}$/i",$target)) )
		{ 
  			message("Error: Valid Host or IP didnot specified.");
  			exit;
  		}
		else 
		{
			tr($target,"-h 10");
		}
}
  ?></td></tr>
</table>
</form>
</body>
</html>
<?php } ?>