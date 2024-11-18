<h1>Mes cinémas</h1>
<p><a class="btn btn-primary" href="theater/search" role="button">Chercher un cinéma</a></p>
<?php
if(count($theaters)) {

	echo "\n".'<div class="row">';
	foreach($theaters AS $item) {
		echo "\n\t".'<div class="col-xs-4 col-sm-4 col-md-4">';
		echo "\n\t\t".'<div class="panel panel-default">';
  		echo "\n\t\t\t".'<div class="panel-heading">'.$item['theatername'].'</div>';
  		echo "\n\t\t\t".'<div class="panel-body">';
    	echo "\n\t\t\t\t".'<p>'.$item['adress'].'</p>';
    	echo "\n\t\t\t\t".'<p>'.$item['zipcode'].' '.$item['city'].'</p>';
    	echo "\n\t\t\t\t".'<p><a class="btn btn-danger" href="theater/del/'.$item['theaterid'].'" role="button">Supprimer</a></p>';

  		echo "\n\t\t\t".'</div>';
		echo "\n\t\t".'</div>';
		echo "\n\t".'</div>';

	}
	echo "\n".'</div>';
	echo "\n".'<p><a class="btn btn-primary" href="theater/search" role="button">Chercher un cinéma</a></p>';
}
?>