<?php
	require_once("support.php");
	require_once("init_database.php");
	session_start();

	$title = "Login";
	$style = "loginStyle.css";


	$topPart = <<<EOBODY
			<form action="{$_SERVER['PHP_SELF']}" method="post">
			<img id="logo" src="logo\happening_logo.png" alt="happening_logo">
			<input type="text" name="login" placeholder="Username" required="true"/><br/>
          	<input type="password" name="password" placeholder="Password" required="true" /><br/>
	      	<input class="submit" type="submit" name="submit" value="Sign In">
			<a href="createaccount.php"><button class="submit" type="button">Sign Up</button></a><br>
EOBODY;


		if (isset($_POST["submit"])) {
			$passwordValue = trim($_POST["password"]);
			$nameValue = trim($_POST["login"]);

			$host = "localhost";
			$user = "bcuser";
			$password = "goodbyeWorld";
			$database = "bitcamp";
			$table = "userinfo";
			$db = connectToDB($host, $user, $password, $database);

			$sqlQuery = sprintf("select * from %s", $table);
			$result = mysqli_query($db, $sqlQuery);
			if ($result) {
				while ($recordArray = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
					if ($recordArray["username"] == $nameValue && password_verify($passwordValue, $recordArray["password"])) {
						$_SESSION["passwordValue"] = $passwordValue;
						$_SESSION["userNameValue"] = $nameValue;
						header("Location: home_page.php");
					}
				}
				$bottomPart = "<h3>Invalid login information provided.</h3>";
				$passwordValue = "";
				$nameValue = "";
				$body = "<div class=\"login\">".$topPart.$bottomPart."</form></div>";
				$page = generatePage($body, $title, $style);
				echo $page;
			} else {
				$body = "Retrieving records failed.".mysqli_error($db);
			}
		} else {
			$body = "<div class=\"login\">".$topPart."</form></div>";
			$page = generatePage($body, $title, $style);
			echo $page;
		}

	function connectToDB($host, $user, $password, $database) {
		$db = mysqli_connect($host, $user, $password, $database);
		if (mysqli_connect_errno()) {
			echo "Connect failed.\n".mysqli_connect_error();
			exit();
		}
		return $db;
	}
?>
