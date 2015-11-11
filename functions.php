<?php
$db = NULL;
$error = NULL;

error_reporting(E_ALL);
ini_set('display_errors', 1);

function db_connect(){
	$hostname 	= 'localhost';//$_ENV['OPENSHIFT_MYSQL_DB_HOST'];
	$database 	= $_ENV['OPENSHIFT_GEAR_NAME'];
	$username 	= $_ENV['OPENSHIFT_MYSQL_DB_USERNAME'];
	$password 	= $_ENV['OPENSHIFT_MYSQL_DB_PASSWORD'];

	connect($hostname, $username, $password, $database);
}

function connect($hostname, $username, $password, $database){
	global $error, $db;

	$db = new mysqli($hostname, $username, $password, $database);
	if ($db->connect_error){
		$error = "Unable to connect to MySQL: " . $db->connect_error;
	}
}

function select($query){
	global $error, $db;

	$result = $db->query($query);
	$results = [];

	if ($result == FALSE){
		$error = "MySQL query error: " . mysql_error();
		trigger_error('MySQL query error: ' . $query . " - " . mysql_error(), E_USER_ERROR);
	}
	else{
		while($row = $result->fetch_assoc()){
			$results[] = $row;
		}
	}
	return $results;
}

function insert($query){
	global $error, $db;

	$result = $db->query($query);

	if ($result == FALSE){
		trigger_error('MySQL query error: ' . $query . " - "  . mysql_error(), E_USER_ERROR);
	}
}

function disconnect(){
	global $db;
	$db->close();
}
?>
