<?php
	require_once($_SERVER["DOCUMENT_ROOT"]."/settings.php");

	//check if a host is allowed to request the script
	if(!in_array($_SERVER['REMOTE_ADDR'], $allowedhosts))
	{
		die("disallowed");
	}

	//connect to the mysql server
	$conn = new mysqli($servername, $username, $password);
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	} 
	$conn->select_db($dbname);
	
	//building query string
	$qinsert = "insert into data set timestamp = now()";
	if(isset($_SERVER["HTTP_X_RSPAMD_QID"]) && $_SERVER["HTTP_X_RSPAMD_QID"])
	{
		$qinsert .= ", qid = '".$_SERVER["HTTP_X_RSPAMD_QID"]."'";
	}
	if(isset($_SERVER["HTTP_X_RSPAMD_FROM"]) && $_SERVER["HTTP_X_RSPAMD_FROM"])
	{
		$qinsert .= ", sender = '".$conn->real_escape_string($_SERVER["HTTP_X_RSPAMD_FROM"])."'";
	}
	if(isset($_SERVER["HTTP_X_RSPAMD_SCORE"]) && $_SERVER["HTTP_X_RSPAMD_SCORE"])
	{
		$qinsert .= ", score = '".$_SERVER["HTTP_X_RSPAMD_SCORE"]."'";
	}
	if(isset($_SERVER["HTTP_X_RSPAMD_RCPT"]) && $_SERVER["HTTP_X_RSPAMD_RCPT"])
	{
		$qinsert .= ", rcpt = '".$conn->real_escape_string($_SERVER["HTTP_X_RSPAMD_RCPT"])."'";
	}
	if(isset($_SERVER["HTTP_X_RSPAMD_IP"]) && $_SERVER["HTTP_X_RSPAMD_IP"])
	{
		$qinsert .= ", ip = '".$_SERVER["HTTP_X_RSPAMD_IP"]."'";
	}
	if(isset($_SERVER["HTTP_X_RSPAMD_ACTION"]) && $_SERVER["HTTP_X_RSPAMD_ACTION"])
	{
		$qinsert .= ", action = '".$_SERVER["HTTP_X_RSPAMD_ACTION"]."'";
	}
	$data = file_get_contents( 'php://input' );
	if(isset($data) && $data)
	{
		$qinsert .= ", data = '".$conn->real_escape_string($data)."'";
	}
	if($qinsert != "insert into data set timestamp = now()")
	{
		$conn->query($qinsert);
	}

	$conn->close();
?>
