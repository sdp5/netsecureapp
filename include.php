<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>netsecureapp</title>
</head>
<?php

class db
{
	
	public $conn;
	
	
	function conn_database()
	{
		// **** LOADING XML FILE FOR DB VARIABLES ****
		
		$xmldb = @simplexml_load_file("db.xml");
				
		$i = 0;
		foreach($xmldb->children() as $child[$i])
  		{
  			$child[$i++];
  		}
		
		//echo $child[0]."<br />".$child[1]."<br />".$child[2]."<br />".$child[3]."<br />".$child[4];
		

		// **** DATABASE SETTINGS **** VARIABLES **** 
	
		$host = $child[0];
		$GLOBALS["database"] = $child[1];
		$user = $child[2];
		$password = $child[3];
		$GLOBALS["tableprefix"] = $child[4];
		
		// **** CONNECTING DATABASE ****
		
		$this->conn = @mysql_connect($host, $user, $password) or die(mysql_error());
		//if($this->conn) echo "Working $this->conn ... $host ... $user";
	}
	
	function select_db()
	{
		$this->conn_database();
		@mysql_select_db($GLOBALS["database"], $this->conn) or die(mysql_error());	
	}
	
	function shut_db()
	{
		@mysql_close($this->conn);
	}
	
}

class dos extends db
{
	
	function dos()
	{
		$GLOBALS["nowsec"] = time();
		$GLOBALS["rip"] = $_SERVER['REMOTE_ADDR'];
		$GLOBALS["agent"] = $_SERVER['HTTP_USER_AGENT'];
		$GLOBALS["wanted"] = $_SERVER['REQUEST_URI'];
	}
	
	function logcurrent_request()
	{
		$nowsec =  $GLOBALS["nowsec"];
		$rip =  $GLOBALS["rip"];
		$agent =  $GLOBALS["agent"];
		$wanted = $GLOBALS["wanted"];
		
		$this->select_db();
		$table = $GLOBALS["tableprefix"]."dos_recent";
		$result = @mysql_query("INSERT INTO $table (tstamp, remoteaddr, agent, calledfor) VALUES ('$nowsec','$rip','$agent','$wanted')", $this->conn);	
		if(!$result) $this->start();
		else $this->track_attempt();
	}
	
	function track_attempt()
	{
		$this->select_db();
		$table = $GLOBALS["tableprefix"]."dos_settings";
		$result = @mysql_query("SELECT * FROM $table", $this->conn);
		$data = @mysql_fetch_array($result);
		
		$nowsec =  $GLOBALS["nowsec"];
		$keeptime = $data[0];
		$hurdle = $data[1];
		$GLOBALS["alarm"] = $data[2];
		
		$rip =  $GLOBALS["rip"];
		$agent =  $GLOBALS["agent"];
		$wanted = $GLOBALS["wanted"];
		
		$nn = $GLOBALS["nowsec"] - $keeptime;
		$table1 = $GLOBALS["tableprefix"]."dos_recent";
		$exec = @mysql_query("SELECT count(tstamp) FROM $table1 WHERE remoteaddr = '$rip' AND agent = '$agent' AND tstamp>$nn");
		$res = @mysql_fetch_row($exec);
		//echo "sending.."; $this->send_sms();
				
		$balloon = $res[0];
		//echo "balloon".$balloon;
		
		if($balloon>$hurdle)
		{
			//echo "working";
			
			$table2 = $GLOBALS["tableprefix"]."dos_warned";
			$date = date('d-M-Y');
			$fire = mysql_query("INSERT INTO $table2 (tstamp, attemptdate, remoteaddr, agent, calledfor) VALUES ('$nowsec', '$date', '$rip','$agent','$wanted')", $this->conn);
			$this->send_sms();
			echo '<META HTTP-EQUIV=refresh content="0; URL=error.php">';
			
		}
	}
	
	function send_sms()
	{
		$number = $GLOBALS["alarm"];
		$server = $_SERVER['SERVER_NAME'];
		$rip = $GLOBALS["rip"];
		$message = "$server is under denial of service attempts from IP $rip [Netsecureapp Alert]";
				
		//USING CURL METHOD.................
		
		/*$url = "http://api.webstarindia.com/smsv3.asp?userid=sundeep&apikey=46c2d62b54d9d9b080482ea23c648589&message=$message&senderid=INITMAIL&sendto=$number";
		
		echo $url;
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url); //set the url
		curl_setopt($ch, CURLOPT_HEADER, true);
		curl_exec($ch);
		curl_close($ch);*/
		
		//echo "send sms";
		
		// USING SOCKETS METHOD..........
		
		/*$host = "api.webstarindia.com";
		$script = "smsv3.asp";
		$request = "userid=sundeep&apikey=46c2d62b54d9d9b080482ea23c648589&message=$message&senderid=INITMAIL&sendto=$number";
		$request_length = strlen($request);
		$method = "GET"; // must be POST if sending multiple messages
		if ($method == "GET")
		{
  			$script .= "?$request";
		}
		//Now comes the header which we are going to post.
		$header = "$method $script HTTP/1.1\r\n";
		$header .= "Host: $host\r\n";
		$header .= "Content-Type: text/html\r\n";
		$header .= "Content-Length: $request_length\r\n";
		$header .= "Connection: close\r\n\r\n";
		//$header .= "$request\r\n";

		//Now we open up the connection
		$socket = @fsockopen($host, 80, $errno, $errstr);
		if ($socket) //if its open, then...
		{
  			//echo $header;
			fputs($socket, $header); // send the details over
  			while(!feof($socket))
  			{
    			$output[] = fgets($socket); //get the results
  			}
  			fclose($socket);
		}*/
		
		// USING JAVASCRIPT METHOD
		
		?>
        <script>window.open("http://api.webstarindia.com/smsv3.asp?userid=sundeep&apikey=46c2d62b54d9d9b080482ea23c648589&message=<?php echo $message; ?>&senderid=INITMAIL&sendto=<?php echo $number ?>",'DoS Alarm','width=0,height=0')</script>
        <?php
						
	}
	
	function authenticate()
	{
		$this->select_db();
		$table = $GLOBALS["tableprefix"]."dos_warned";
		$ip = $_SERVER['REMOTE_ADDR'];
		
		$test = @mysql_query("SELECT * FROM $table WHERE remoteaddr = '$ip'", $this->conn) or die(mysql_error());
		$rows1 = @mysql_num_rows($test);
		if($rows1) echo '<META HTTP-EQUIV=refresh content="0; URL=error.php">';
		else
		{
			$this->logcurrent_request();
			
			//PUT THE LINK OF DEFAULT WEBSITE HERE
			echo '<META HTTP-EQUIV=refresh content="0; URL=login.php">';
		}
	}
	
	function start()
	{
		$xmldb = @simplexml_load_file("db.xml");
				
		$i = 0;
		foreach($xmldb->children() as $child[$i])
  		{
  			$child[$i++];
  		}
		
		$host = $child[0];
		$database = $child[1];
		$user = $child[2];
		$password = $child[3];
		$tableprefix = $child[4];
		
		$conn = @mysql_connect($host,$user,$password);
		//echo $conn;
		$dbstatus = @mysql_select_db($database,$conn);
		
		$table = $tableprefix."users";
		$result1 = mysql_query("SELECT * FROM $table", $conn);
		if($result1) $rows1 = mysql_num_rows($result1);
		
		$table2 = $tableprefix."intrusions";
		$result2 = mysql_query("SELECT * FROM $table2", $conn);
		if($result2) $rows2 = mysql_num_rows($result2);
		
		$table3 = $tableprefix."dos_recent";
		$result3 = mysql_query("SELECT * FROM $table3", $conn);
		if($result3) $rows3 = mysql_num_rows($result3);
		
		$table4 = $tableprefix."dos_warned";
		$result4 = mysql_query("SELECT * FROM $table4", $conn);
		if($result4) $rows4 = mysql_num_rows($result4);
		
		$table5 = $tableprefix."dos_settings";
		$result5 = mysql_query("SELECT * FROM $table5", $conn);
		if($result5) $rows5 = mysql_num_rows($result5);
		
		if(!$conn) echo '<META HTTP-EQUIV=refresh content="0; URL=install/?key=configuredb">';
		elseif(!$dbstatus) echo '<META HTTP-EQUIV=refresh content="0; URL=install/?key=configuredb">';
		elseif(!$result1) echo '<META HTTP-EQUIV=refresh content="0; URL=install/?key=configuredb">';
		elseif(!$result2) echo '<META HTTP-EQUIV=refresh content="0; URL=install/?key=configuredb">';
		elseif(!$result3) echo '<META HTTP-EQUIV=refresh content="0; URL=install/?key=configuredb">';
		elseif(!$result4) echo '<META HTTP-EQUIV=refresh content="0; URL=install/?key=configuredb">';
		elseif(!$result5) echo '<META HTTP-EQUIV=refresh content="0; URL=install/?key=configuredb">';
		elseif($rows1>0)$this->authenticate();
		else echo '<META HTTP-EQUIV=refresh content="0; URL=install/?key=createuser">';
	}
}

?>
<body>
</body>
</html>