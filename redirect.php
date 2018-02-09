<?php
@session_save_path("session");
@session_start();
if($_SESSION['live'] == -1)
{
	@session_destroy();
	echo '<br /><br /><br /><br /><br /><br /><div align="center"><img src="images/ajax-loader.gif" width="56" height="21" />';
	echo "<br /><br />Logout Successful. Redirecting...</div>";
	echo '<META HTTP-EQUIV=refresh content="0; URL=.">';
}
else {
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title></title>
<style type="text/css">
<!--
body,td,th {
	font-family: Verdana, Geneva, sans-serif;
	font-size: large;
	color: #333;
}
body {
	background-color: #CCC;
}
-->
</style>
</head>
<body>
<?php
require_once("include.php");

if(isset($_POST['login']))
{
		  
  		$user = $_POST['usrname'];
  		$pwd = md5($_POST['pwd']);
					  
		$dbconn = new db();
		$dbconn->select_db();

		$table = $GLOBALS["tableprefix"]."users";

		$result = @mysql_query("SELECT * FROM $table WHERE username = '$user' AND password = '$pwd'", $dbconn->conn) or die(mysql_error());
		$rows = mysql_num_rows($result);
		
		if($rows == 1)
		{
			$data = @mysql_fetch_array($result) or die(mysql_error());		
				
			if($user == $data['username'] AND $pwd == $data['password'] AND $data['role'] == "admin" AND $data['status'] == md5("active"))
 			{
			 	$_SESSION['user'] = $data['username'];
		 		$_SESSION['signature'] = md5($_SERVER['HTTP_USER_AGENT']);
		 		$_SESSION['usrname'] = $data['signature'];
		 		$_SESSION['role'] = md5("admin");
		 		$_SESSION['live'] = 1;
				$_SESSION['lastlogin'] = $data['lastlogin'];
						 
		 		$clientip = $_SERVER['REMOTE_ADDR'];
					 
		 		$updateIP = mysql_query("UPDATE $table SET lastlogin = '$clientip'", $dbconn->conn) or die(mysql_error());
		 		if($updateIP)
		 		{
			 		echo '<br /><br /><br /><br /><br /><br /><div align="center"><img src="images/ajax-loader.gif" width="56" height="21" />';
					echo "<br /><br />Login Successful. Redirecting...</div>";
			 		echo '<META HTTP-EQUIV=refresh content="0; URL=admin">';
					$dbconn->shut_db();
		 		}
		 		else 
				{
					echo '<br /><br /><br /><br /><br /><br /><div align="center"><img src="images/ajax-loader.gif" width="56" height="21" />';
					echo "<br /><br />Unknown error occured. Redirecting...</div>";
					echo '<META HTTP-EQUIV=refresh content="0; URL=.">';
					@session_destroy();
				}
			}
			else
			{
				echo '<br /><br /><br /><br /><br /><br /><div align="center"><img src="images/ajax-loader.gif" width="56" height="21" />';
				echo "<br /><br />User Status Inactive. Redirecting...</div>";
				echo '<META HTTP-EQUIV=refresh content="0; URL=.">';
				@session_destroy();
			}
 		}
 		else 
		{
			echo '<br /><br /><br /><br /><br /><br /><div align="center"><img src="images/ajax-loader.gif" width="56" height="21" />';
			echo "<br /><br />Invalid Username and/or Password. Redirecting...</div>";
			echo '<META HTTP-EQUIV=refresh content="0; URL=.">';
			@session_destroy();
		}
}
else 
{
	echo '<META HTTP-EQUIV=refresh content="0; URL=.">';
	@session_destroy();
}
?>
</body>
</html>
<?php } ?>