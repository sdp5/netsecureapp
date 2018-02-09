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
<form id="form1" name="form1" method="post" action="">
  <table width="500px" border="0" align="center" bgcolor="#EEEEEE">
    <tr>
      <td colspan="3"><div align="right" class="phptextforms">DoS Module Settings</div></td>
    </tr>
    <tr>
      <td colspan="3">&nbsp;</td>
    </tr>
    <tr>
      <td colspan="3"><div align="center"><span class="phptextforms">
        <?php
  		
	require_once("index.php");
	
	$dbconn = new db();
	$dbconn->select_db();
	
	$table = $GLOBALS["tableprefix"]."dos_settings";
		
	$result = @mysql_query("SELECT * FROM $table", $dbconn->conn) or die(mysql_error());
	$rows = @mysql_num_rows($result);
	$data = @mysql_fetch_row($result);
			
    ?>
      </span></div></td>
    </tr>
    <tr>
      <td width="87" class="phptextforms">&nbsp;</td>
      <td width="155" class="phptextforms">Keep Duration (Seconds):</td>
      <td width="244" class="phptextforms"><b>
        <input name="keeplive" type="text" class="phptextforms" value="<?php echo $data[0]; ?>"/>
      </b></td>
    </tr>
    <tr>
      <td class="phptextforms">&nbsp;</td>
      <td class="phptextforms">Request Times Allowed:</td>
      <td class="phptextforms"><b>
        <input name="hurdle" type="text" class="phptextforms" value="<?php echo $data[1]; ?>"/>
      </b></td>
    </tr>
    <tr>
      <td class="phptextforms">&nbsp;</td>
      <td class="phptextforms">Sent SMS To Number:</td>
      <td class="phptextforms"><b>
        <input name="alarmat" type="text" class="phptextforms" value="<?php echo $data[2]; ?>"/>
        <input name="settings" type="submit" class="phptextforms" id="settings" value="Save" />
      </b></td>
    </tr>
    <tr>
      <td colspan="3" align="center" class="phptextforms">
	  <?php
      if(isset($_POST['settings']))
	  {
		  if(!preg_match("/^[0-9]{3,5}$/", $_POST['keeplive'])) echo "<br />Keep Duration: 3, 4 or 5 digits are expected.";
		  elseif(!preg_match("/^[0-9]{2,4}$/", $_POST['hurdle'])) echo "<br />Times Allowed: 2, 3 or 4 digits are expected.";
		  elseif(!preg_match("/^[0-9]{10}$/", $_POST['alarmat'])) echo "<br />Alarm at: 10 digits are expected";
		  else
		  {
			  $keeplive = $_POST['keeplive'];
			  $hurdle = $_POST['hurdle'];
			  $alarmat = $_POST['alarmat'];
			  echo "<br />";
			  $resultset = @mysql_query("UPDATE $table SET keeptime = '$keeplive', hurdle = '$hurdle', alarmat = '$alarmat'", $dbconn->conn) or die(mysql_error());
			  if($resultset) echo "<b>Preferences Saved</b>";
		  }
	  }
		  
	  ?>
      </td>
    </tr>
    <tr>
      <td colspan="3" align="center" class="phptextforms">&nbsp;</td>
    </tr>
  </table>
</form>
</body>
</html>
<?php } ?>