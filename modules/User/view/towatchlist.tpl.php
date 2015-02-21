<p>
<?php
$liens = array();
$liens[] = array('type' => '1', 'url' => 'user/'.$curUser['id'].'/towatchlist/cinema', 'title' => 'Cinéma');
$liens[] = array('type' => '2', 'url' => 'user/'.$curUser['id'].'/towatchlist/tele', 'title' => 'Télé');

foreach($liens AS $lien)
	echo "\n\t".'<a href="'.$lien['url'].'"><button type="button" class="button '.($lien['type'] == $type ? 'button-success' : 'button-primary').'">'.$lien['title'].'</button></a> ';
?>
</p>
<?php

// Affichage normal pour les films à voir à la télé
if($type == 2) {
	echo "\n\t".'<div class="row">';
	foreach($films AS $film)
		echo "\n\t".'<div class="col-xs-4 col-sm-3 col-md-2"><a href="film/'.$film['movieid'].'"><img src="'.library\Image::getUrl('movie', $film['movieid']).'" alt="Affiche du film" title="'.$film['titrevf'].'" class="img-rounded"></a></div>';
	echo "\n\t".'</div>';
}
// Affichage plus détaillé pour les films à voir au cinéma
else {
	$curdate = '';
	foreach($films AS $film) {
		// Films déjà sortis
		if($film['datesortie'] <= date('Y-m-d')) {			
			if($curdate == '') {
			echo "\n\t".'<h3>Déjà en salle</h3>';
			echo "\n\t".'<div class="row">';
			$curdate = $film['datesortie'];
			}
		}
		else {
			// On change de semaine
			if($curdate != $film['datesortie']) {
				// Si on n'itère pas pour la première fois
				if($curdate != '')
					echo "\n\t".'</div>';
				
				echo "\n\t".'<h3>'.library\Date::formatDate($film['datesortie'], 'J mois annee').'</h3>';
				echo "\n\t".'<div class="row">';
				$curdate = $film['datesortie'];
			}
		}
		echo "\n\t".'<div class="col-xs-4 col-sm-3 col-md-2"><a href="film/'.$film['movieid'].'"><img src="'.library\Image::getUrl('movie', $film['movieid']).'" alt="Affiche du film" title="'.$film['titrevf'].'" class="img-rounded"></a></div>';
	}
	echo "\n\t".'</div>';	
}
?>
