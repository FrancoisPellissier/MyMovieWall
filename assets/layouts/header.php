<!DOCTYPE html>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<link rel="icon" type="image/png" href="favicon.png" />
		<base href="<?php echo WWW_ROOT; ?>" />
		<link href="assets/css/bootstrap.css" rel="stylesheet">
		<link href="assets/css/style.css" rel="stylesheet">
		<title><?php echo $titre_page; ?> - My Movie Wall</title>

		<link rel="stylesheet" href="assets/js/jquery/jquery-ui-1.11.2/jquery-ui.css">
		<script src="assets/js/jquery/jquery-1.11.0.min.js"></script>
		<script src="assets/js/jquery/jquery-migrate-1.2.1.min.js"></script>
		<script src="assets/js/jquery/jquery-ui-1.11.2/jquery-ui.js"></script>
		<?php
		// Meta OG
		if(!isset($meta)) {
			$meta['title'] = 'My Movie Wall';
			$meta['url'] = WWW_ROOT.$_SERVER['REQUEST_URI'];
			$meta['description'] = '';
			$meta['image'] = WWW_ROOT.'img/share.jpg';
			$meta['type'] = 'video.movie';
		}
		if(isset($meta)) {
			foreach($meta AS $key=>$value)
				echo "\n\t\t".'<meta property="og:'.$key.'" content="'.str_replace('"', '', $value).'" />';
		}

		// Fichier JS
		if($jsfile != '')
			echo "\n\t".'<script type="text/javascript" src="assets/js/'.$jsfile.'.js"></script>';
		?>
	</head>
<body>
