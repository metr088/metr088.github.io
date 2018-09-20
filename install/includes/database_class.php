<?php

class Database {

	// Function to the database and tables and fill them with the default data
	function create_database($data)
	{
		// Connect to the database
		$mysqli = new mysqli($data['hostname'],$data['username'],$data['password'],'');

		// Check for errors
		if(mysqli_connect_errno())
			return false;

		// Create the prepared statement
		$mysqli->query("CREATE DATABASE IF NOT EXISTS ".$data['database']);

		// Close the connection
		$mysqli->close();

		return true;
	}

	// Function to create the tables and fill them with the default data
	function create_tables($data)
	{
		// Connect to the database
		$mysqli = new mysqli($data['hostname'],$data['username'],$data['password'],$data['database']);
		$email = $data['useremail'];
		$pass = sha1($data['userpass']);
		if(mysqli_connect_errno())
			return false;

		$query = file_get_contents('assets/install.sql');
		$mysqli->set_charset("utf8");
		$mysqli->multi_query($query);
		while($mysqli->next_result()) $mysqli->store_result();
		$create = "INSERT INTO `users` (`id`, `login`, `password`) VALUES (null, '".$email."', '".$pass."');";
		$mysqli->query($create);
		print_r($mysqli->error);
		$mysqli->close();

		return true;
	}

}