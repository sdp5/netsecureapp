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
<?php
$xmldb = @simplexml_load_file("../db.xml");
				
$i = 0;
foreach($xmldb->children() as $child[$i])
{
	$child[$i++];
}
		
$host = $child[0];
$dbname = $child[1];
$user = $child[2];
$password = $child[3];
$tableprefix = $child[4];
?>
<body>
<form action="" method="post" enctype="application/x-www-form-urlencoded" name="configdb" class="phptext1">
<table width="600" border="0" align="center" bgcolor="#EEEEEE" class="phptext1">
  <tr>
    <td colspan="3" class="phptext1">&nbsp;</td>
  </tr>
  <tr>
    <td class="phptext1">&nbsp;</td>
    <td class="phptext1"><strong>Create User</strong></td>
    <td class="phptext1">&nbsp;</td>
  </tr>
  <tr>
    <td colspan="3" class="phptext1">&nbsp;</td>
  </tr>
  <tr>
    <td width="76" class="phptext1">&nbsp;</td>
    <td width="214" class="phptext1">User Name</td>
    <td width="296" class="phptext1"><input name="username" type="text" class="phptext1" id="username" onFocus="m(this)" value="try@demo.user" /></td>
  </tr>
  <tr>
    <td width="76" class="phptext1">&nbsp;</td>
    <td width="214" class="phptext1">Password</td>
    <td width="296" class="phptext1"><input name="pass1" type="password" class="phptext1" id="pass1" onFocus="m(this)" value="password" /></td>
  </tr>
  <tr>
    <td width="76" class="phptext1">&nbsp;</td>
    <td width="214" class="phptext1">Retype Password</td>
    <td width="296" class="phptext1"><input name="pass2" type="password" class="phptext1" id="pass2" onFocus="m(this)" value="password" /></td>
  </tr>
  <tr>
    <td width="76" class="phptext1">&nbsp;</td>
    <td width="214" class="phptext1">Contact No.</td>
    <td width="296" class="phptext1"><input name="phone" type="text" class="phptext1" id="phone" onFocus="m(this)" value="9000000000"/>
      <span class="phptext2">
      <input name="createuser" type="submit" class="phptext1" id="createuser" value="GO" />
      </span></td>
  </tr>
  <tr>
    <td colspan="3" class="phptext1"></td>
  </tr>
  <tr>
    <td colspan="3" class="phptext1">&nbsp;</td>
  </tr>
  <tr>
    <td colspan="3" class="phptext2"><div align="center">
      <?php
	
	$linkid = @mysql_connect($host,$user,$password);
	@mysql_select_db($dbname,$linkid);
	
	if(isset($_POST['createuser']))
	{
		if(!preg_match("/^[a-zA-Z0-9._]*[@][a-z0-9A-Z.]*[.][a-zA-Z]{2,4}$/",$_POST['username'])) echo "Use Email Format for Creating Username.";
		elseif(!strstr($_POST['pass1'], $_POST['pass2'])) echo "Provided Passwords Donot Match.";
		elseif(!preg_match("/^[0-9]{10}$/",$_POST['phone'])) echo "10 Digits Contact Number is Required.";
		else
		{
			$user = $_POST['username'];
			$password = md5($_POST['pass1']);
			$signature = md5($user);
			$phone = $_POST['phone'];
			$status = md5("active");
			
			$table = $tableprefix."users";
			$result = @mysql_query("INSERT INTO $table (username, password, role, signature, phone, status) VALUES ('$user', '$password', 'admin', '$signature', '$phone', '$status')", $linkid);
																												
			if(!$result) echo "User Already Exists.";
			else echo "<br /><br /><b>Netsecureapp Installation Phase II Completed.</b> <a href='../.'>Login</a>";
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