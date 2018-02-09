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
<title></title>
<link href="../../style.css" rel="stylesheet" type="text/css" />
</head>
<body>
<table width="500px" border="0" align="center" bgcolor="#EEEEEE">
  <tr>
    <td><div align="right" class="phptextforms">View Intrusion Logs</div></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
  </tr>
  <tr><td><div align="center"><span class="phptextforms">
    <?php
  	
	$xmldb = @simplexml_load_file("../../db.xml");
				
	$i = 0;
	foreach($xmldb->children() as $child[$i])
  	{
  		$child[$i++];
  	}
		
	$host = $child[0];
	$dbname = $child[1];
	$dbuser = $child[2];
	$password = $child[3];
	$tableprefix = $child[4];
	
	$linkid = @mysql_connect($host,$dbuser,$password);
	@mysql_select_db($dbname,$linkid);
	$table = "$tableprefix"."intrusions";
	$result = @mysql_query("SELECT * FROM $table WHERE name LIKE 'GET%' ORDER By id DESC", $linkid);
	$rows = @mysql_num_rows($result);
	
	if(!$result) echo "Error Connecting Database. Check with IDS Configuration";
	elseif(!$rows>0) echo "No attack has been registered yet.";
	else
	{
		echo "Total Attempts: <b>".$rows."</b>...<br /><br />"; 
		echo '<table width="560px" class="phptextforms" align="center">';
		echo '<tr><th class="phptextforms"><b>Request</b></th><th class="phptextforms"><b>Value</b></th><th class="phptextforms"><b>Page</b></th><th class="phptextforms"><b>Origin</b></th><th class="phptextforms"><b>Dated</b></th></tr><tr><td colspan="5"><hr /></td></tr>';
		$i=0;
		while($rows>$i)
		{
			$request = mysql_result($result,$i,name);
			$value = mysql_result($result,$i,value);
			$page = mysql_result($result,$i,page);
			$origin = mysql_result($result,$i,origin);
			$on = mysql_result($result,$i,created);
			$i++;
			echo "<tr><td class='phptextforms'>$request</td><td class='phptextforms'>$value</td><td class='phptextforms'>$page</td><td class='phptextforms'>$origin</td><td class='phptextforms'>$on</td></tr>";
		}
		echo '</table>';
	}
	
	
	?>
  </span></div></td></tr>
  <tr>
    <td align="center" class="phptextforms">&nbsp;</td>
  </tr>
</table>
</body>
</html>
<?php } ?>