<?php
		session_start();
	//CONNECT TO THE DATABASE
	$host = "localhost";
	$user = "bcuser";
	$password = "goodbyeWorld";
	$database = "bitcamp";
	$table = "user_events";
	$db = connectToDB($host, $user, $password, $database);


	function connectToDB($host, $user, $password, $database) {
		$db = mysqli_connect($host, $user, $password, $database);
		if (mysqli_connect_errno()) {
			echo "Connect failed.\n".mysqli_connect_error();
			exit();
		}
		return $db;
	}


	if(isset($_POST['logout'])){
		session_destroy(); //If they logouts
		header("Location: main.php");
	}

	if(isset($_POST['home'])){
		session_destroy(); //If they logouts
		header("Location: home_page.php");
	}

	if(isset($_POST['profile'])){
		session_destroy(); //If they logouts
		header("Location: peofile.php");
	}

	$body = <<<EOBODY
		<div id="container">
		<img id="logo" src="logo\happening_logo.png" alt="happening_logo">
			<!--NAVIGATION-->
		<ul id="navigator" class="nav navbar-nav" >
			<li><a class="menu1" href="home_page.php"><strong>Home</strong><span class="sr-only">(current)</span></a></li>
			<li><a class="menu1" href="profile.php"><strong>My Profile</strong></a></li>
			<li><a class="menu1" href="createevent.php"><strong>Create An Event</strong></a></li>
			<form action="home_page.php" method="post"><input class="menu" class="submit" type="submit" name="logout" value="Logout"></form>
		</ul>

		<hr />
		<hr/>

		<div class="title">
			  <h2><strong>My Events</strong></h2> <!--Make a Push Down-->
		</div>

EOBODY;

	$sqlQuery = sprintf("select * from $table where user_name = '%s'", $_SESSION["userNameValue"]);
	$result = mysqli_query($db, $sqlQuery);

	$events= array();

	while ($recordArray = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
		$events[] = $recordArray['event_name'];
	}

	foreach($events as $key) {
		$sqlQuery = sprintf("select * from events where name = '%s'", $key);
		$result = mysqli_query($db, $sqlQuery);

		$recordArray = mysqli_fetch_array($result, MYSQLI_ASSOC);

		$host =  $recordArray["host"];
		$_SESSION["name"] = $name = $recordArray["name"];
		$date = $recordArray["event_date"];
		$time = $recordArray["event_time"];
		$location = $recordArray["location"];
		$description = $recordArray["description"];
		$image = $recordArray["image"];

		$body .= <<<BOD
			<div id="event">
				<img src="images\\$image" alt="eventImage">
			</div>

			<div class="description">
				<h4><strong>$name</strong></h4>
				<strong>$date, $time</strong><br/>
				<strong>Hosted by: $host</strong><br/>
				<strong>$location</strong>
			</div>

			<div class="description">
			<hr/>
				  <p>$description</p> <!--Make a Push Down-->
			</div>

			<br/>
			
			<hr/>
BOD;

	}



	$body .= "</div>";
?>

<!doctype html>
<html>
<head>
<meta charset="utf-8">
	<link rel="stylesheet" type="text/css"  href="css/bootstrap.css"/>
	<link rel="stylesheet" href="css/profile.css" />
<title>Profile</title>
</head>

<body>
	<?php echo $body ?>
</body>
</html>
