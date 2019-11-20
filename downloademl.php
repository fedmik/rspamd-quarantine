<?php
	if(empty($_POST["emlid"]))
	{
		die();
	}
	$emlid = $_POST["emlid"];
	require_once($_SERVER["DOCUMENT_ROOT"]."/settings.php");
	
	$conn = new mysqli($servername, $username, $password);

	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	} 
	$conn->select_db($dbname);
	
	$row = $conn->query("select qid, data from data where id = '".$emlid."'")->fetch_row();

	header('Content-Disposition: attachment; filename="message'.$row[0].'.eml"');
	header('Content-Type: text/plain');
	header('Content-Length: ' . strlen($row[1]));
	header('Connection: close');


	echo $row[1];
?>