<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<script>
function m(el) {
  if (el.defaultValue==el.value) el.value = ""
}
</script>
<title></title>
<style type="text/css">
<!--
body,td,th {
	font-family: Georgia, "Times New Roman", Times, serif;
	font-size: x-large;
	color: #333;
}

.phptext2 {
	font-family: Verdana, Geneva, sans-serif;
	font-size: x-small;
	font-style: normal;
	color: #333;
}
.phptext1 {
	font-family: Verdana, Geneva, sans-serif;
	font-size: small;
	font-style: normal;
	color: #333;
}
.phptext {
	font-family: Georgia, "Times New Roman", Times, serif;
	font-size: x-large;
	font-style: normal;
	color: #333;
}
a:link {
	color: #333;
	text-decoration: none;
}
a:visited {
	text-decoration: none;
	color: #333;
}
a:hover {
	text-decoration: underline;
	color: #333;
}
a:active {
	text-decoration: none;
	color: #333;
}

-->
</style>
</head>
<body>
<form action="" method="post" enctype="application/x-www-form-urlencoded" name="configdb" class="phptext1">
<table width="600" border="0" align="center" bgcolor="#EEEEEE" class="phptext1">
  <tr>
    <td colspan="3" class="phptext1">&nbsp;</td>
  </tr>
  <tr>
    <td class="phptext1">&nbsp;</td>
    <td class="phptext1"><strong>Database Configuration</strong></td>
    <td class="phptext1">&nbsp;</td>
  </tr>
  <tr>
    <td colspan="3" class="phptext1">&nbsp;</td>
  </tr>
  <tr>
    <td width="76" class="phptext1">&nbsp;</td>
    <td width="214" class="phptext1">Database Name</td>
    <td width="296" class="phptext1"><input name="dbname" type="text" class="phptext1" id="dbname" onFocus="m(this)" value="netsecureapp" /></td>
  </tr>
  <tr>
    <td width="76" class="phptext1">&nbsp;</td>
    <td width="214" class="phptext1">Database User</td>
    <td width="296" class="phptext1"><input name="dbuser" type="text" class="phptext1" id="dbuser" onFocus="m(this)" value="dbuser" /></td>
  </tr>
  <tr>
    <td width="76" class="phptext1">&nbsp;</td>
    <td width="214" class="phptext1">Connect Password</td>
    <td width="296" class="phptext1"><input name="dbpassword" type="text" class="phptext1" id="dbpassword" onFocus="m(this)" value="dbpassword" /></td>
  </tr>
  <tr>
    <td width="76" class="phptext1">&nbsp;</td>
    <td width="214" class="phptext1">Table Prefix</td>
    <td width="296" class="phptext1"><input name="tableprefix" type="text" class="phptext1" id="tableprefix" onFocus="m(this)" value="nsa_"/> 
      </td>
  </tr>
  <tr>
    <td colspan="3" class="phptext1"></td>
  </tr>
  <tr>
    <td colspan="3" class="phptext1">&nbsp;</td>
  </tr>
  <tr>
    <td colspan="3" class="phptext1">
<p class="phptext2" align="center">And put same database values in <b>db.xml</b> which would be at [RootFolder]/db.xml&nbsp; &nbsp;<input name="dbconfig" type="submit" class="phptext1" id="dbconfig" value="GO" /></p>
</td>
  </tr>
  <tr>
    <td colspan="3" class="phptext1">&nbsp;</td>
  </tr>
  <tr>
    <td colspan="3" class="phptext2"><div align="center">
    <?php
	if(isset($_POST['dbconfig']))
	{
		$host = "localhost";
		$user = $_POST['dbuser'];
		$password = $_POST['dbpassword'];
		$dbname = $_POST['dbname'];
		$tableprefix = $_POST['tableprefix'];
		
		$xmldb = @simplexml_load_file("../db.xml");
				
		$i = 0;
		foreach($xmldb->children() as $child[$i])
  		{
  			$child[$i++];
  		}
		
		$host1 = $child[0];
		$dbname1 = $child[1];
		$user1 = $child[2];
		$password1 = $child[3];
		$tableprefix1 = $child[4];
			
		$str1 = "$host$dbname$user$password$tableprefix";
		$str2 = "$host1$dbname1$user1$password1$tableprefix1";
		
		//echo $host." ".$user." ".$password." ".$dbname." ".$tableprefix;
		$linkid = @mysql_connect($host,$user,$password);
		$dbconnect = @mysql_select_db($dbname,$linkid);
		
		if(!$linkid) echo "Connection Failed. Kindly Reissue Database Variables.";
		elseif(!$dbconnect) echo "Incorrect Database Name or Database Doesnot Exist.";  
		elseif($str1!=$str2) echo "Values of db.xml seems to be Incorrect";
		else
		{
			echo "Database Connected Successfully";
			echo "<br />Installing Netsecureapp.. ";
			
			$table = "$tableprefix"."dos_recent";
			
			$sql = "
				CREATE TABLE IF NOT EXISTS $table (
  				`rid` bigint(20) NOT NULL AUTO_INCREMENT,
  				`tstamp` bigint(20) NOT NULL,
  				`remoteaddr` text NOT NULL,
  				`agent` text NOT NULL,
  				`calledfor` text NOT NULL,
  				PRIMARY KEY (`rid`)
				) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ";

			$table1 = "$tableprefix"."dos_settings";
			
			$sql1 =	"
				CREATE TABLE IF NOT EXISTS $table1 (
  				`keeptime` int(5) NOT NULL DEFAULT '120',
  				`hurdle` int(5) NOT NULL DEFAULT '50',
  				`alarmat` bigint(10) NOT NULL DEFAULT '9000000000'
				) ENGINE=MyISAM DEFAULT CHARSET=latin1 ";

			$sql2 = "INSERT INTO $table1 (`keeptime`, `hurdle`, `alarmat`) VALUES (120, 50, 9000000000)";

			$table2 = "$tableprefix"."dos_warned";
			
			$sql3 = "
				CREATE TABLE IF NOT EXISTS $table2 (
  				`rid` bigint(20) NOT NULL AUTO_INCREMENT,
  				`tstamp` bigint(20) NOT NULL,
  				`attemptdate` varchar(11) NOT NULL,
  				`remoteaddr` text NOT NULL,
  				`agent` text NOT NULL,
  				`calledfor` text NOT NULL,
  				PRIMARY KEY (`rid`)
				) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ";
			
			$table3 = "$tableprefix"."users";
			
			$sql4 = "
				CREATE TABLE IF NOT EXISTS $table3 (
  				`uid` int(10) NOT NULL AUTO_INCREMENT,
  				`username` varchar(50) NOT NULL,
  				`password` varchar(50) NOT NULL,
  				`role` varchar(10) NOT NULL,
  				`signature` varchar(40) NOT NULL,
  				`phone` varchar(15) NOT NULL,
  				`status` varchar(40) NOT NULL,
  				`lastlogin` varchar(30) DEFAULT NULL,
  				PRIMARY KEY (`uid`),
  				UNIQUE KEY `username` (`username`)
				) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ";
				
			$table4 = "$tableprefix"."intrusions";
			
			$sql5 = "
				CREATE TABLE IF NOT EXISTS $table4 (
      			`id` int(11) unsigned NOT null auto_increment,
      			`name` varchar(128) NOT null,
      			`value` text NOT null,
      			`page` varchar(255) NOT null,
      			`ip` varchar(15) NOT null,
      			`impact` int(11) unsigned NOT null,
      			`origin` varchar(15) NOT null,
      			`created` datetime NOT null,
      			PRIMARY KEY  (`id`)
    			) ENGINE=MyISAM ";

			$result = @mysql_query($sql,$linkid) or die(mysql_error());
			if(!$result) echo "Unexpected Error Occurred. Kindly Retry";
			else echo " 20%..";
			
			$result1 = @mysql_query($sql1,$linkid) or die(mysql_error());
			if(!$result1) echo "Unexpected Error Occurred. Kindly Retry";
			else echo " 40%..";
			
			$result2 = @mysql_query($sql2,$linkid) or die(mysql_error());
			if(!$result2) echo "Unexpected Error Occurred. Kindly Retry";
			else echo " 55%..";
			
			$result3 = @mysql_query($sql3,$linkid) or die(mysql_error());
			if(!$result3) echo "Unexpected Error Occurred. Kindly Retry";
			else echo " 68%..";
			
			$result4 = @mysql_query($sql4,$linkid) or die(mysql_error());
			if(!$result4) echo "Unexpected Error Occurred. Kindly Retry";
			else echo " 80%..";
			
			$result5 = @mysql_query($sql5,$linkid) or die(mysql_error());
			if(!$result5) echo "Unexpected Error Occurred. Kindly Retry";
			else echo " Done.";
			
			echo "<br /><br /><b>Netsecureapp Installation Phase I Completed.</b> <a href='?key=createuser'>Phase II</a>";
		}
	}
	?>
    </div></td>
  </tr>
    <tr>
    <td colspan="3" class="phptext1">&nbsp;</td>
  </tr>
</table>
</form>

</body>
</html>