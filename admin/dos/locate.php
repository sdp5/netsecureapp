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
    <td colspan="3"><div align="right" class="phptextforms">DoS Attempt IP Location</div></td>
  </tr>
  <tr>
    <td colspan="3">&nbsp;</td>
  </tr>
  <tr>
    <td class="phptextforms">&nbsp;</td>
    <td class="phptextforms" align="left">Select Attempt:</td>
    <td class="phptextforms">
	<?php
    
	require_once("index.php");
	
	$dbconn = new db();
	$dbconn->select_db();
	
	$table = $GLOBALS["tableprefix"]."dos_warned";
	
	$result = @mysql_query("SELECT * FROM $table", $dbconn->conn) or die(mysql_error());
	$rows = mysql_num_rows($result);
	if($rows)
	{
		?> 
        <form action="<?php $var=md5("locate"); echo "?key=$var"; ?>" method="post" enctype="application/x-www-form-urlencoded" name="form1" class="phptestforms">
        <?php
		echo '<select name="victim" class="phptextforms">';
		
		$i = 0;
		while($rows>$i)
		{
			$var = mysql_result($result,$i,remoteaddr);
			if($var) echo "<option>$var</option>";
			$i++;
		}
				
		echo '</select>&nbsp;&nbsp;';
		echo '<input name="govictim" type="submit" class="phptextforms" value="GO" /></form>';
		
	}
	else echo "No Dos Attack Registered Yet!!";
	?>
    
  
  <?php
  if(isset($_POST['govictim']))
				  {
					  ?>
  <tr><td colspan="3"><div align="center"><span class="phptextforms">
    <?php
  		
	include("include/geoipcity.inc");
	include("include/geoipregionvars.php");
	
	// uncomment for Shared Memory support
	// geoip_load_shared_mem("/usr/local/share/GeoIP/GeoIPCity.dat");
	// $gi = geoip_open("/usr/local/share/GeoIP/GeoIPCity.dat",GEOIP_SHARED_MEMORY);
	$gi = geoip_open("include/GeoLiteCity.dat",GEOIP_STANDARD);
	
	//$queryip = $_SERVER['REMOTE_ADDR'];
	$queryip = $_POST["victim"];
	
	$record = geoip_record_by_addr($gi, $queryip);
	
    ?>
  </span></div></td></tr>
  <tr>
    <td width="87" class="phptextforms">&nbsp;</td>
    <td width="155" class="phptextforms">&nbsp;</td>
    <td width="244" class="phptextforms">&nbsp;</td>
   </tr>
  <tr>
    <td width="87" class="phptextforms">&nbsp;</td>
    <td width="155" class="phptextforms">Country Code:</td>
    <td width="244" class="phptextforms"><?php print $record->country_code ." ". $record->country_code3; ?></td>
   </tr>
  <tr>
    <td width="87" class="phptextforms">&nbsp;</td>
    <td width="155" class="phptextforms">Country Name:</td>
    <td width="244" class="phptextforms"><?php print $record->country_name; ?></td>
   </tr>
  <tr>
    <td width="87" class="phptextforms">&nbsp;</td>
    <td width="155" class="phptextforms">Region Code:</td>
    <td width="244" class="phptextforms"><?php print $record->region; ?></td>
   </tr>
   <tr>
    <td width="87" class="phptextforms">&nbsp;</td>
    <td width="155" class="phptextforms">Region Name:</td>
    <td width="244" class="phptextforms"><?php print $GEOIP_REGION_NAME[$record->country_code][$record->region]; ?></td>
   </tr>
   <tr>
    <td width="87" class="phptextforms">&nbsp;</td>
    <td width="155" class="phptextforms">City:</td>
    <td width="244" class="phptextforms"><?php print $record->city; ?></td>
   </tr>
   <tr>
    <td width="87" class="phptextforms">&nbsp;</td>
    <td width="155" class="phptextforms">Postal Code:</td>
    <td width="244" class="phptextforms"><?php print $record->postal_code; ?></td>
   </tr>
   <tr>
    <td width="87" class="phptextforms">&nbsp;</td>
    <td width="155" class="phptextforms">Latitute:</td>
    <td width="244" class="phptextforms"><?php print $record->latitude; ?></td>
   </tr>
   <tr>
    <td width="87" class="phptextforms">&nbsp;</td>
    <td width="155" class="phptextforms">Longitute:</td>
    <td width="244" class="phptextforms"><?php print $record->longitude; ?></td>
   </tr>
   <tr>
    <td width="87" class="phptextforms">&nbsp;</td>
    <td width="155" class="phptextforms">Metro Code:</td>
    <td width="244" class="phptextforms"><?php print $record->metro_code; ?></td>
   </tr>
   <tr>
    <td width="87" class="phptextforms">&nbsp;</td>
    <td width="155" class="phptextforms">Area Code:</td>
    <td width="244" class="phptextforms"><?php print $record->area_code; ?></td>
   </tr>
   <?php geoip_close($gi);} ?>
  <tr><td colspan="3" align="center" class="phptextforms">&nbsp;</td></tr>
</table>
</body>
</html>
<?php  } ?>