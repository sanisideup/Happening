<?php
	session_start();
?>

<?php
	$auth = "";

	if (isset($_POST["signin"])) {
		header("Location: main.php");
	}

	if (isset($_POST["submitButton"])) {
		if ($_POST["password"] == $_POST["confirmpass"]) {
			$host = "localhost";
			$user = "bcuser";
			$password = "goodbyeWorld";
			$database = "bitcamp";
			$table = "userinfo";
			$db = connectToDB($host, $user, $password, $database);

			$_SESSION["userNameValue"] = $_POST["name"];
			$_SESSION["passwordValue"] = $_POST["password"];
			$name = trim($_POST["name"]);
			$password = password_hash(trim($_POST["password"]), PASSWORD_DEFAULT);

			$sqlQuery = sprintf("insert into $table values ('%s', '%s')", $name, $password);
			$result = mysqli_query($db, $sqlQuery);
			if ($result) {
				echo "Account creation successful";
				header("Location: main.php");

			} else {
				$auth = "<h3>Username Already In Use</h3><br/>";
			}
			mysqli_close($db);
		}
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

<!doctype html>
<html>
	<head>
		<title>Create Account</title>
          <link rel="stylesheet" type="text/css"  href="css/signUpStyle.css"/>
		  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
		  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

		  	<script>
			//This var is used to determine which submit button was clicked
			var clicked;
			function validateForm() {

				if(clicked) {
					return;
				}

			    var x = document.forms["myForm"]["name"].value;
			    if (x == "") {
			        alert("Username must be filled out");
			        return false;
			    }

			    var x = document.forms["myForm"]["password"].value;
			    if (x == "") {
			        alert("Password must be filled out");
			        return false;
			    }

			    var x = document.forms["myForm"]["confirmpass"].value;
			    if (x == "") {
			        alert("Confirm password must be filled out");
			        return false;
			    }

			    var x = document.forms["myForm"]["password"].value;
			    var y = document.forms["myForm"]["confirmpass"].value;
			    if (x != y) {
			    	alert("Passwords do not match");
			    	return false;
			    }

			}
			</script>
			<script>
				function samePassword(pass1, pass2) {
					if (pass1 != pass2) {
						alert("Passwords do not match");
						return false;
					}
					return true;
				}
			</script>
	</head>

	<body>
		<div class="login">
			<form action = "<?php echo $_SERVER['PHP_SELF']; ?>" name="myForm" method="post" onsubmit= "return validateForm()">
							<img id="logo" src="logo\happening_logo.png" alt="happening_logo">
							<h1>Create Account</h1>
							<input type="text" name="name" placeholder="Username"/><br /><br />
							<input type="password" name="password" placeholder="Password"/><br /><br />
							<input type="password" name="confirmpass" placeholder="Confirm Password"/><br /><br />
                <input class="submit" type="submit" name="submitButton" onClick="clicked=0" value="Create Account">
                <input class="submit" type="submit" name="signin" onClick="clicked=1" value="Sign In">
                <?php echo $auth ?>
			</form>
		</div>
	</body>
</html>
