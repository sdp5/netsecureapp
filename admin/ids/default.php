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
    <td colspan="4"><div align="right" class="phptextforms">Configure IDS</div></td>
  </tr>
  <tr><td colspan="4"><div align="center"><span class="phptextforms">
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
	?>
  </span></div></td></tr>
  <tr>
    <td colspan="4" align="center" class="phptextforms">&nbsp;</td>
  </tr>
    <tr>
    <td colspan="4" class="phptextforms" align="center">Provide Writable Permission to <b>tem</b> folder which can be located at [webroot]/admin/ids/lib/tmp</td>
  </tr>
  <tr>
    <td colspan="4" align="center" class="phptextforms">&nbsp;</td>
  </tr>
   <tr>
    <td colspan="4" align="center" class="phptextforms">Update <b>Config.ini.php</b> file at [webroot]/admin/ids/lib/Config/Config.ini.php with following details:</td>
  </tr>
  <tr>
    <td colspan="4" align="center" class="phptextforms">&nbsp;</td>
  </tr>
  <tr>
    <td width="87" class="phptextforms">&nbsp;</td>
    <td width="155" class="phptextforms"><b>[Email Logging]</b></td>
    <td colspan="2" class="phptextforms">&nbsp;</td>
  </tr>
<tr>
    <td width="87" class="phptextforms">&nbsp;</td>
    <td colspan="2" class="phptextforms"><hr /></td>
    <td width="76" class="phptextforms">&nbsp;</td>
  </tr>
  <tr>
    <td class="phptextforms">&nbsp;</td>
    <td class="phptextforms">recipients[]</td>
    <td colspan="2" class="phptextforms">Provide Alert Email Address </td>
  </tr>
  <tr>
    <td colspan="4" align="center" class="phptextforms">&nbsp;</td>
  </tr>
  <tr>
    <td width="87" class="phptextforms">&nbsp;</td>
    <td width="155" class="phptextforms"><b>[Database Logging]</b></td>
    <td colspan="2" class="phptextforms">&nbsp;</td>
  </tr>
  <tr>
    <td width="87" class="phptextforms">&nbsp;</td>
    <td colspan="2" class="phptextforms"><hr /></td>
    <td class="phptextforms">&nbsp;</td>
  </tr>
  <tr>
    <td class="phptextforms">&nbsp;</td>
    <td class="phptextforms">wrapper (dbname)</td>
    <td colspan="2" class="phptextforms"><?php echo $dbname; ?></td>
  </tr>
  <tr>
    <td class="phptextforms">&nbsp;</td>
    <td class="phptextforms">user</td>
    <td colspan="2" class="phptextforms"><?php echo $dbuser; ?></td>
  </tr>
  <tr>
    <td class="phptextforms">&nbsp;</td>
    <td class="phptextforms">password</td>
    <td colspan="2" class="phptextforms"><?php echo $password; ?></td>
  </tr>
  <tr>
    <td class="phptextforms">&nbsp;</td>
    <td class="phptextforms">table</td>
    <td colspan="2" class="phptextforms"><?php echo $tableprefix."intrusions"; ?></td>
  </tr>
  <tr><td colspan="4" align="center" class="phptextforms">&nbsp;</td></tr>
</table>
</body>
</html>
<?php } ?>