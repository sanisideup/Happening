<?php
	//Database Variable Initialization	
	$host = "localhost";
	$user = "bcuser";
	$password = "goodbyeWorld";
	$database = "bitcamp";
	$table1 = "userinfo";
	$table2 = "events";
	$table3 = "user_events";
	
	// Create connection to root
	$conn = new mysqli($host,"root","");
	
	//Check if it fails
	if ($conn->connect_error) {
  	  die("Connection failed: " . $conn->connect_error);
	} 
	
	//Check if database - "groupdb" exists
	$sqlQuery = "SHOW DATABASES";
	$result = $conn->query($sqlQuery);
	$found = false;
	while ($recordArray = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
		//"groupdb" already exists
		if($recordArray['Database'] == $database) {
			$found = true;
			break;
		}
	}
	
	//create "groupdb" if it does not exist
	if (!$found) {	
		
		//Create database
		$conn->query("CREATE DATABASE $database");	
		
		//Creater user
		$conn->query("CREATE USER '$user'@'$host' IDENTIFIED BY '$password'");
		
		//Grant user access to DB
		$conn->query( "GRANT ALL PRIVILEGES ON $database.* TO '$user'@'$host'");
		
		//CONNECT TO NEW DATABASE AND USER
		$conn = new mysqli($host, $user, $password, $database);	
		
		//User's events
		$conn->query("CREATE TABLE $table3(event_name varchar(50), user_name varchar(50))");
		
		//Create table for ALL events
		$conn->query("CREATE TABLE $table2(host varchar(50), name varchar(50) primary key, event_date date, event_time time, location varchar(100), description varchar(500), image varchar(100))");
		
		//Create table for userinfo
		$conn->query("CREATE TABLE $table1(username varchar(50) primary key, password varchar(250))");
	}
	
	$conn->close();
?>