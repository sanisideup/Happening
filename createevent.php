<?php
	require_once("support.php");
	session_start();

	$title = "Create Event";
	$style = "createevent.css";


	$topPart = <<<EOBODY
		<h1>Create An Event</h1>
		<form action="{$_SERVER['PHP_SELF']}" method="post" enctype="multipart/form-data">
			<input type="text" name="name" placeholder="Event Name" required="true"/><br/><br/>
			<strong>Select Image to Upload:</strong><br /><br />
            <input type="file" name="fileToUpload" id="fileToUpload" required="true"><br /><br />
          	<input type="date" name="date" placeholder="Date" required="true" /><br/><br/>
			<input type="time" name="time" placeholder="Time" required="true" /><br/><br/>
			<input type="text" name="location" placeholder="Location" required="true" /><br/><br/>
			<textarea rows="7" cols="40"  placeholder="Write Description" name="description"></textarea><br /><br />
	      	<input class="submitButton" type="submit" name="submit" value="Submit">
EOBODY;


		if (isset($_POST["submit"])) {
			$host = "localhost";
			$user = "bcuser";
			$password = "goodbyeWorld";
			$database = "bitcamp";
			$table = "events";
			$db = connectToDB($host, $user, $password, $database);

			$host = $_SESSION["userNameValue"];
			$name = trim($_POST["name"]);
			$date = $_POST["date"];
			$time = $_POST["time"];
			$location = trim($_POST["location"]);
			$description = trim($_POST["description"]);

			$sqlQuery = sprintf("insert into $table values('$host', '$name', '$date', '$time', '$location', '$description')");
			$result = mysqli_query($db, $sqlQuery);

			function getExtension($str) {

				 $i = strrpos($str,".");
				 if (!$i) { return ""; }
				 $l = strlen($str) - $i;
				 $ext = substr($str,$i+1,$l);
				 return $ext;
			}

			$errors=0;

			//CHECK THE INPUT IMAGE
			define ("MAX_SIZE","750");
			if(isset($_POST["submit"]) && isset($_FILES["fileToUpload"])) {
				$image =$_FILES["fileToUpload"]["name"];
				$uploadedfile = $_FILES['fileToUpload']['tmp_name'];

				if ($image) {
					$filename = stripslashes($_FILES['fileToUpload']['name']);
					$extension = getExtension($filename);
					$extension = strtolower($extension);
					if (($extension != "jpg") && ($extension != "jpeg")
							&& ($extension != "png") && ($extension != "gif")) {
						echo ' Please upload an image. ';
						$errors=1;
					}else{
						$size=filesize($_FILES['fileToUpload']['tmp_name']);

						if($extension=="jpg" || $extension=="jpeg" ){
							$uploadedfile = $_FILES['fileToUpload']['tmp_name'];
							$src = imagecreatefromjpeg($uploadedfile);
						}else if($extension=="png"){
							$uploadedfile = $_FILES['fileToUpload']['tmp_name'];
							$src = imagecreatefrompng($uploadedfile);
						}else {
						$src = imagecreatefromgif($uploadedfile);
						}

						list($width,$height)=getimagesize($uploadedfile);

						$newwidth=400;
						$newheight=400;

						$tmp=imagecreatetruecolor($newwidth,$newheight);

						imagecopyresampled($tmp,$src,0,0,0,0,$newwidth,$newheight,$width,$height);

						$filename = "images/". $_FILES['fileToUpload']['name'];
						$image = $_SESSION["image"] = $_FILES['fileToUpload']['name'];


						$sqlQuery = sprintf("insert into $table values('$host', '$name', '$date', '$time', '$location', '$description', '$image')");
						$result = mysqli_query($db, $sqlQuery);

						imagejpeg($tmp,$filename,100);

						imagedestroy($src);
						imagedestroy($tmp);
					}
				}
			}

			if(isset($_POST['submit']) && !$errors) {
				$topPart = "<h3>Event Posted.</h3>";
			}
		}

			$nav = <<< NAV
				<img id="logo" src="logo\happening_logo.png" alt="happening_logo">
				<form action="home_page.php" method="post"><input class="menu" type="submit" name="home" value="Home"></form>
				<form action="home_page.php" method="post"><input class="menu" type="submit" name="profile" value="Profile"></form>
				<form action="home_page.php" method="post"><input class="menu" type="submit" name="logout" value="Logout"></form>
NAV;

			$body = "<div id=\"container\">".$nav.$topPart."</form></div>";
			$page = generatePage($body, $title, $style);
			echo $page;


	function connectToDB($host, $user, $password, $database) {
		$db = mysqli_connect($host, $user, $password, $database);
		if (mysqli_connect_errno()) {
			echo "Connect failed.\n".mysqli_connect_error();
			exit();
		}
		return $db;
	}
?>
