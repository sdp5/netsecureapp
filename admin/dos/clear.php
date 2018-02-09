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
    <td colspan="3"><div align="right" class="phptextforms">Reset Visitors Count</div></td>
  </tr>
  <tr>
    <td colspan="3">&nbsp;</td>
  </tr>
  <tr><td colspan="3"><div align="center"><span class="phptextforms">
    <?php
  		
	require_once("index.php");
	
	$dbconn = new db();
	$dbconn->select_db();
	
	$table1 = $GLOBALS["tableprefix"]."dos_recent";
	
	$result1 = @mysql_query("SELECT * FROM $table1", $dbconn->conn) or die(mysql_error());
	$rows1 = mysql_num_rows($result1);
	
			
    ?>
  </span></div></td></tr>
  <tr>
    <td width="87" class="phptextforms">&nbsp;</td>
    <td width="155" class="phptextforms">Visitors Count:</td>
    <td width="244" class="phptextforms"><?php echo "<b>$rows1</b>"; ?></td>
    </tr>
  <tr>
    <td class="phptextforms">&nbsp;</td>
    <td class="phptextforms">Reset Visitors Count</td>
    <td class="phptextforms"><form id="form1" name="reset" method="post" action="<?php $var=md5("clear"); echo "?key=$var"; ?>">
      <input name="reset" type="submit" class="phptextforms" id="reset" value="GO" />
      <?php
	  if(isset($_POST["reset"])) {
	  @mysql_query("TRUNCATE TABLE $table1", $dbconn->conn) or die(mysql_error());
	  echo "<b>Done!!</b>"; }
	  ?>
    </form></td>
  </tr>
  <tr>
    <td colspan="3" align="center" class="phptextforms">&nbsp;</td>
  </tr>
  <tr><td colspan="3" align="center" class="phptextforms">&nbsp;</td></tr>
</table>
</body>
</html>
<?php } ?>