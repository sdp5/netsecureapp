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
<form action="<?php $var=md5("whois"); echo "?key=$var"; ?>" method="post">
<table width="500px" border="0" align="center" bgcolor="#EEEEEE">
  <tr>
    <td colspan="3"><div align="right" class="phptextforms">Whois Look Up</div></td>
  </tr>
  <tr><td colspan="3">&nbsp;</td></tr>
  <tr>
    <td width="20%">&nbsp;</td>
    <td width="50%" align="center"><input type="text" name="target" value="Enter Host or IP" onFocus="m(this)" class="phptextforms"></td>
    <td width="30%" align="left"><input type="submit" name="whois" value="Go" class="phptextforms"></td>
  </tr>
  <tr><td colspan="3">&nbsp;</td></tr>
  <tr><td colspan="3" align="center" class="phptextforms">
  <?php
  if(isset($_POST['whois']))
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
		
		function wwwhois($target)
		{
			global $ntarget;
			$server = "whois.crsnic.net";
			
			message("<p class=\"phptext\">WWWhois Results:<blockquote>");

			#Determine which WHOIS server to use for the supplied TLD
			if((eregi("\.com\$|\.net\$|\.edu\$", $target)) || (eregi("\.com\$|\.net\$|\.edu\$", $ntarget)))
  			$server = "whois.crsnic.net";
			else if((eregi("\.info\$", $target)) || (eregi("\.info\$", $ntarget)))
  			$server = "whois.afilias.net";
			else if((eregi("\.org\$", $target)) || (eregi("\.org\$", $ntarget)))
  			$server = "whois.corenic.net";
			else if((eregi("\.name\$", $target)) || (eregi("\.name\$", $ntarget)))
  			$server = "whois.nic.name";
			else if((eregi("\.biz\$", $target)) || (eregi("\.biz\$", $ntarget)))
  			$server = "whois.nic.biz";
			else if((eregi("\.us\$", $target)) || (eregi("\.us\$", $ntarget)))
  			$server = "whois.nic.us";
			else if((eregi("\.cc\$", $target)) || (eregi("\.cc\$", $ntarget)))
  			$server = "whois.enicregistrar.com";
			else if((eregi("\.ws\$", $target)) || (eregi("\.ws\$", $ntarget)))
  			$server = "whois.nic.ws";
			else if((eregi("\.jobs\$", $target)) || (eregi("\.jobs\$", $ntarget)))
  			$server = "jobswhois.verisign-grs.com";
			else if((eregi("\.bz\$", $target)) || (eregi("\.bz\$", $ntarget)))
  			$server = "mhpwhois1.verisign-grs.net";
			else if((eregi("\.gov\$", $target)) || (eregi("\.gov\$", $ntarget)))
  			$server = "whois.dotgov.gov";
			else if((eregi("\.mobi\$", $target)) || (eregi("\.mobi\$", $ntarget)))
  			$server = "whois.dotmobiregistry.net";
			else if((eregi("\.edu\$", $target)) || (eregi("\.edu\$", $ntarget)))
  			$server = "whois.educause.edu";
			else if((eregi("\.ac.in\$", $target)) || (eregi("\.ac.in\$", $ntarget)))
  			$server = "whois.inregistry.net";
			else if((eregi("\.co.in\$", $target)) || (eregi("\.co.in\$", $ntarget)))
  			$server = "whois.inregistry.net";
			else if((eregi("\.ernet.in\$", $target)) || (eregi("\.ernet.in\$", $ntarget)))
  			$server = "whois.inregistry.net";
			else if((eregi("\.gov.in\$", $target)) || (eregi("\.gov.in\$", $ntarget)))
  			$server = "whois.inregistry.net";
			else if((eregi("\.net.in\$", $target)) || (eregi("\.net.in\$", $ntarget)))
  			$server = "whois.inregistry.net";
			else if((eregi("\.res.in\$", $target)) || (eregi("\.res.in\$", $ntarget)))
  			$server = "whois.inregistry.net";
			else if((eregi("\.in\$", $target)) || (eregi("\.in\$", $ntarget)))
  			$server = "whois.inregistry.net";
			else if((eregi("\.mil\$", $target)) || (eregi("\.mil\$", $ntarget)))
  			$server = "whois.nic.mil";
			else if((eregi("\.mn\$", $target)) || (eregi("\.mn\$", $ntarget)))
  			$server = "whois.nic.mn";
			else if((eregi("\.mx\$", $target)) || (eregi("\.mx\$", $ntarget)))
  			$server = "whois.nic.mx";
			else if((eregi("\.travel\$", $target)) || (eregi("\.travel\$", $ntarget)))
  			$server = "whois.nic.travel";
			else if((eregi("\.pro\$", $target)) || (eregi("\.pro\$", $ntarget)))
  			$server = "whois.registrypro.pro";
			else
			{
  				$msg .= "TDLs <br /><hr />.com, .net, .org, .edu, .info, .name, .us, .cc, .ws, .biz, .jobs, .bz, .gov, .mobi, .edu, .in, .ac.in, .co.in, .ernet.in, .gov.in, .net.in, res.in, .mil, .mn, .mx, .travel and .pro  <hr />are supported.</blockquote>";
  				message($msg);
  				return;
			}

			message("Connecting to $server...<br /><br />");
			if (! $sock = @fsockopen($server, 43, $num, $error, 10))
			{
  				unset($sock);
  				$msg .= "Timed-out connecting to $server (port 43)";
			}		
			else
			{
  				fputs($sock, "$target\n");
  				while (!feof($sock))
    			$buffer .= fgets($sock, 10240); 
			}
 			@fclose($sock);
 			if(! eregi("Whois Server:", $buffer))
			{
   				if(eregi("no match", $buffer)) message("NOT FOUND: No match for $target<br>");
   				else   message("Ambiguous query, multiple matches for $target:<br>");
 			}		
 			else
			{
   				$buffer = split("\n", $buffer);
   				for ($i=0; $i<sizeof($buffer); $i++)
				{
     				if (eregi("Whois Server:", $buffer[$i]))
       				$buffer = $buffer[$i];
   				}
   				$nextServer = substr($buffer, 17, (strlen($buffer)-17));
   				$nextServer = str_replace("1:Whois Server:", "", trim(rtrim($nextServer)));
   				$buffer = "";
   				message("Deferred to specific whois server: $nextServer...<br><br>");
   				if(! $sock = @fsockopen($nextServer, 43, $num, $error, 10))
				{
     				unset($sock);
     				$msg .= "Timed-out connecting to $nextServer (port 43)";
   				}
   				else
				{
     				fputs($sock, "$target\n");
     				while (!feof($sock))
       				$buffer .= fgets($sock, 10240);
     				@fclose($sock);
   				}
			}
			$msg .= nl2br($buffer);
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
			wwwhois($target);
		}
}
  ?></td></tr>
</table>
</form>
</body>
</html>
<?php } ?>