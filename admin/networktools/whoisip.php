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
//global $Topt;
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
<form action="<?php $var=md5("whoisip"); echo "?key=$var"; ?>" method="post">
<table width="500px" border="0" align="center" bgcolor="#EEEEEE">
  <tr>
    <td colspan="3"><div align="right" class="phptextforms">Whois IP Look up</div></td>
  </tr>
  <tr><td colspan="3">&nbsp;</td></tr>
  <tr>
    <td width="20%">&nbsp;</td>
    <td width="50%" align="center"><input type="text" name="target" value="Enter Host or IP" onFocus="m(this)" class="phptextforms"></td>
    <td width="30%" align="left"><input type="submit" name="whoisip" value="Go" class="phptextforms"></td>
  </tr>
  <tr><td colspan="3">&nbsp;</td></tr>
  <tr><td colspan="3" align="center" class="phptextforms">
  <?php
  if(isset($_POST['whoisip']))
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
		
		function arin($target)
		{
			$server = "whois.arin.net";
			message("<p class=\"phptextforms\">Whois IP Results for $target:<blockquote>");
			if (!$target = gethostbyname($target))
  			$msg .= "Can't IP Whois without an IP address.";
			else
			{
  				message("Connecting to $server...<br><br>");
  				if (! $sock = @fsockopen($server, 43, $num, $error, 20))
				{
    				unset($sock);
    				$msg .= "Timed-out connecting to $server (port 43)";
    			}
  				else
				{
    				fputs($sock, "$target\n");
    				while (!feof($sock))
      				$buffer .= fgets($sock, 10240); 
    				@fclose($sock);
    			}
   				if (eregi("RIPE.NET", $buffer))
     			$nextServer = "whois.ripe.net";
   				else if (eregi("whois.apnic.net", $buffer))
     			$nextServer = "whois.apnic.net";
   				else if (eregi("nic.ad.jp", $buffer))
				{
     				$nextServer = "whois.nic.ad.jp";
     			
					#/e suppresses Japanese character output from JPNIC
     				$extra = "/e";
     			}
   				else if (eregi("whois.registro.br", $buffer))
     			$nextServer = "whois.registro.br";
   				if($nextServer)
				{
     				$buffer = "";
     				message("Deferred to specific whois server: $nextServer...<br><br>");
     				if(! $sock = @fsockopen($nextServer, 43, $num, $error, 10))
					{
       					unset($sock);
       					$msg .= "Timed-out connecting to $nextServer (port 43)";
       				}
     				else
					{
       					fputs($sock, "$target$extra\n");
       					while (!feof($sock))
         				$buffer .= fgets($sock, 10240);
       					@fclose($sock);
       				}
     			}
  				$buffer = str_replace(" ", "&nbsp;", $buffer);
  				$msg .= nl2br($buffer);
  			}
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
			arin($target);
		}
}
  ?></td></tr>
</table>
</form>
</body>
</html>
<?php } ?>