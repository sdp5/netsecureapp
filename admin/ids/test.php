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
<style type="text/css">
<!--
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
</style></head>
<body>
<table width="900px" border="0" align="center" bgcolor="#EEEEEE">
  <tr>
    <td width="318" class="phptextforms"><p align="right" class="phptext">Netsecureapp | Test IDS</p></td>
  </tr>
  <tr><td>&nbsp;</td></tr>
  <tr>
    <td align="center" class="phptextforms">&nbsp;</td>
  </tr>
    <tr>
    <td class="phptextforms" align="center">Log On to <a class="phptextforms" href="http://ha.ckers.org/xss.html">http://ha.ckers.org/xss.html</a> for XSS (Cross Site Scripting) Cheat Sheet</td>
  </tr>
  <tr>
    <td align="center" class="phptextforms">&nbsp;</td>
  </tr>
   <tr>
    <td align="center" class="phptextforms"><div><p class="phptextforms">
    <?php

// set the include path properly for IDS
set_include_path(
    get_include_path()
    . PATH_SEPARATOR
    . ''
);

if (!session_id()) {
    session_start();
}

require_once 'lib/Init.php';

try {

    $request = array(
        'REQUEST' => $_REQUEST,
        'GET' => $_GET,
        'POST' => $_POST,
        'COOKIE' => $_COOKIE
    );

    $init = IDS_Init::init(dirname(__FILE__) . '/lib/Config/Config.ini.php');

 
    $init->config['General']['base_path'] = dirname(__FILE__) . '/lib/';
    $init->config['General']['use_base_path'] = true;

    $ids = new IDS_Monitor($request, $init);
    $result = $ids->run();

    if (!$result->isEmpty()) {
        echo $result;

        require_once 'lib/Log/File.php';
        require_once 'lib/Log/Composite.php';

        $compositeLog = new IDS_Log_Composite();
        $compositeLog->addLogger(IDS_Log_File::getInstance($init));

        require_once 'lib/Log/Email.php';
        require_once 'lib/Log/Database.php';

        $compositeLog->addLogger(
            IDS_Log_Email::getInstance($init),
            IDS_Log_Database::getInstance($init)
        );

		$compositeLog->execute($result);
        

    } else { echo 'No attack detected!!&nbsp;&nbsp;<a href="?test=%22><script>eval(window.name)</script>">Issue a simple attack</a>';
    }
} catch (Exception $e) {
    printf(
        'An error occured: %s',
        $e->getMessage()
    );
}

?>    
    </p></div></td>
  </tr>
  <tr>
    <td align="center" class="phptextforms">&nbsp;</td>
  </tr>
</table>
</body>
</html>
<?php } ?>