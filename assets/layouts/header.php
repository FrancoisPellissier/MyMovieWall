<!DOCTYPE html>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<base href="http://localhost/movie/" />
		<link href="assets/css/bootstrap.css" rel="stylesheet">
		<link href="assets/css/tuto.css" rel="stylesheet">
		<title><?php echo $titre_page; ?> - Movie</title>
		<script src="assets/js/jquery/jquery.js"></script>
		<?php
		if($jsfile != '')
			echo "\n\t".'<script src="assets/js/'.$jsfile.'.js"></script>';
		?>
	</head>
<body>