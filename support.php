<?php

function generatePage($body, $title="DontDoDrugs", $style="css/bootstrap.css") {
    $page = <<<EOPAGE
	<!doctype html>
	<html>
		<head> 
			<meta charset="utf-8">
			<title>$title</title>		
			<link rel="stylesheet" type="text/css"  href="css/$style"/>
			<script src="http://ajax.googleapis.com/ajax/jquery/3.2.0/jquery.min.js"></script>
		</head>
				
		<body>
				$body
		</body>
	</html>
EOPAGE;

    return $page;
}
?>