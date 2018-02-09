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
<link href="file:///C|/xampp/htdocs/netsecureapp/style.css" rel="stylesheet" type="text/css" />
</head>
<body>
<table width="500px" border="0" align="center" bgcolor="#EEEEEE">
  <tr>
    <td colspan="3"><div align="right" class="phptextforms">List DoS Attempts</div></td>
  </tr>
  <tr>
    <td colspan="3">&nbsp;</td>
  </tr>
  <tr>
    <td colspan="3">
      <div align="center" class="phptextforms">
        <?php
    
	require_once("index.php");
	
	$dbconn = new db();
	$dbconn->select_db();
	
	$table = $GLOBALS["tableprefix"]."dos_warned";
	$table1 = $GLOBALS["tableprefix"]."dos_recent";
	
	$result = @mysql_query("SELECT * FROM $table", $dbconn->conn) or die(mysql_error());
	$rows = mysql_num_rows($result);
	if($rows)
	{
		echo '<table width="450px" border="0" class="phptestforms" align="center">';
		echo '<th><div class="phptextforms"><b>ID</b></div></th><th><div class="phptextforms"><b>Date</b></div></th><th><div class="phptextforms"><b>IP Address</b></div></th><th><div class="phptextforms"><b>Attempted</b></div></th>';
		
		$i = 0;
		while($rows>$i)
		{
			$id = mysql_result($result,$i,rid);
			$date = mysql_result($result,$row,attemptdate);
			$ip = mysql_result($result,$i,remoteaddr);
			$calledfor = mysql_result($result,$i,calledfor);
			echo "<tr><td><div class=".'phptextforms'." align=".'center'.">$id</div></td><td><div class=".'phptextforms'." align=".'center'.">$date</div></td><td><div class=".'phptextforms'." align=".'center'.">$ip</div></td><td><div class=".'phptextforms'." align=".'center'.">$calledfor</div></td></tr>";
			$i++; $flag = -1;
		}
 		echo '<tr><td>&nbsp;</td></tr></table>';
	
	}
	else echo "No Dos Attack Registered Yet!!";
	?>
    </div></td>
  </tr>
  <tr><td colspan="3" align="center" class="phptextforms">&nbsp;</td></tr>
  <?php if($flag == -1) { ?>
  <tr><td colspan="3" align="center" class="phptextforms"><form action="<?php $var=md5("list"); echo "?key=$var"; ?>" method="post" enctype="application/x-www-form-urlencoded" name="list" class="phptextforms"><select name="forgive" class="phptextforms">
  <?php
  $i = 0;
	while($rows>$i)
	{
		$var = mysql_result($result,$i,remoteaddr);
		if($var) echo "<option>$var</option>";
		$i++;
	}
	?>
  </select><input name="release" type="submit" class="phptextforms" value="Release" /></form>
  <?php
  if(isset($_POST['release']))
  {
	  $ip = $_POST['forgive'];
	  @mysql_query("DELETE FROM $table WHERE remoteaddr = '$ip'", $dbconn->conn) or die(mysql_error());
	  @mysql_query("DELETE FROM $table1 WHERE remoteaddr = '$ip'", $dbconn->conn) or die(mysql_error());
	  echo "<br><b>$ip released!!</></br>";
  }
  ?>
  </td></tr> <?php } ?>
  <tr><td colspan="3" align="center" class="phptextforms">&nbsp;</td></tr>
</table>
</body>
</html>
<?php  } ?>