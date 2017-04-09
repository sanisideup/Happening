<?php
	session_start();
	//CONNECT TO THE DATABASE
	$host = "localhost";
	$user = "bcuser";
	$password = "goodbyeWorld";
	$database = "bitcamp";
	$table = "events";
	$db = connectToDB($host, $user, $password, $database);


	function connectToDB($host, $user, $password, $database) {
		$db = mysqli_connect($host, $user, $password, $database);
		if (mysqli_connect_errno()) {
			echo "Connect failed.\n".mysqli_connect_error();
			exit();
		}
		return $db;
	}

	if(isset($_POST['liked'])){
		$name = $_POST['event_name'];
		$user = $_SESSION["userNameValue"];

		$sqlQuery = sprintf("delete from user_events where event_name = '$name' and user_name = '$user'");
		$result = mysqli_query($db, $sqlQuery);


		unset($_SESSION["name"]);
		unset($_POST['liked']);
	}

	if(isset($_POST['like'])){
		$name = $_POST['event_name'];
		$user = $_SESSION["userNameValue"];


		$sqlQuery = sprintf("insert into user_events values('$name', '$user')");
		$result = mysqli_query($db, $sqlQuery);
		unset($_SESSION["name"]);
		unset($_POST['like']);
	}

	if(isset($_POST['logout'])){
		session_destroy(); //If they logouts
		header("Location: main.php");
	}

	if(isset($_POST['home'])){
		header("Location: home_page.php");
		}

	if(isset($_POST['logout'])){
		session_destroy();
		header("Location: main.php");
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
		
		<br/><br/>
EOBODY;

	$events = array();

	$sqlQuery = sprintf("select * from user_events where user_name = '%s'", $_SESSION["userNameValue"]);
	$result = mysqli_query($db, $sqlQuery);

	while ($recordArray = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
		$events[] = $recordArray['event_name'];
	}


	$sqlQuery = sprintf("select * from $table");
	$result = mysqli_query($db, $sqlQuery);

	if($result){

		while ($recordArray = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
			$host =  $recordArray["host"];
			$_SESSION["name"] = $name = $recordArray["name"];
			$date = $recordArray["event_date"];
			$time = $recordArray["event_time"];
			$location = $recordArray["location"];
			$description = $recordArray["description"];
			$image = $recordArray["image"];

			$body .= <<<BOD
				<div  id="event" >
					<img src="images/$image"  height="auto" width="auto">
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
				
BOD;


			$body .= <<<BO
			<form action="home_page.php" class="description" method="post">

					<input name="event_name" type="hidden" value="$name">
BO;

			$like = FALSE;

			foreach($events as $e) {
				if($name == $e) {
					$like = TRUE;
					break;
				}
			}

			if($like == NULL) {
				$body .= "<input name=\"like\" class=\"vote\" type=\"submit\" value=\"LIKE\">";
			} else {
				$body .= "<input name=\"liked\" id=\"liked\" type=\"submit\" value=\"LIKED\">";
			}

			$body .= "</form><br/><hr/>";
			
			
		}
	}

	//DO A CONDITION WHEN IT IS LIKED
	$body .= "</div>";
?>

<!doctype html>
<html>
<head>
<meta charset="utf-8">
	<link rel="stylesheet" type="text/css"  href="css/bootstrap.css"/>
	<link rel="stylesheet" href="css/mainstyle.css" />
<title>Home</title>
</head>
<body>
	<?php echo $body ?>
</body>
</html>
