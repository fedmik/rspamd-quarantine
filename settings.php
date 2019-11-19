<?php
	if ( !empty( $_SERVER['SCRIPT_FILENAME'] ) && 'settings.php' == basename( $_SERVER['SCRIPT_FILENAME'] ) ) {
		header("HTTP/1.0 500 Internal Server Error"); exit();
	}
	
	/*
	* Mysql authentication parameters
	*/
	$servername = "mysql-host";
	$username = "mysql-user";
	$password = "mysql-password";
	
	/*
	* IP Addresses of rspamd hosts which are allowed to store messages in the quarantine
	* If your Quarantine service is hosted on the same host as the rspamd, put here its loopback ip address
	*/
	$allowedhosts = [
		"rspamd_ip_1",
		"rspamd_ip_2",
	];
?>