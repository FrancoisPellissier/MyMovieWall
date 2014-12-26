<!DOCTYPE html>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<base href="<?php echo WWW_ROOT; ?>" />
		<link href="assets/css/bootstrap.css" rel="stylesheet">
		<link href="assets/css/tuto.css" rel="stylesheet">
		<title><?php echo $titre_page; ?> - Movie</title>
		<link rel="stylesheet" href="//code.jquery.com/ui/1.11.2/themes/smoothness/jquery-ui.css">
		<script src="//code.jquery.com/jquery-1.11.0.min.js"></script>
		<script src="//code.jquery.com/jquery-migrate-1.2.1.min.js"></script>
		<script src="//code.jquery.com/ui/1.11.2/jquery-ui.js"></script>
		<?php
		if($jsfile != '')
			echo "\n\t".'<script type="text/javascript" src="assets/js/'.$jsfile.'.js"></script>';
		?>
	</head>
<body>