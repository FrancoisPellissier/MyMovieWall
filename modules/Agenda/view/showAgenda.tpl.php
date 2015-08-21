<?php
$last = '';

if(!empty($films)) {
	foreach($films AS $film) {
		if($last != $film['datesortie']) {
			if($last != '')
				echo "\n\t".'</div>';

			echo '<h3>'.ucwords(library\Date::formatDate($film['datesortie'], 'J mois annee')).'</h3>';
			$last = $film['datesortie'];
			echo "\n\t".'<div class="row">';
		}

		echo "\n\t".'<div class="col-xs-4 col-sm-3 col-md-2"><a href="film/'.$film['movieid'].'"><img src="'.library\Image::getUrl('movie', $film['movieid']).'" alt="Affiche du film" title="'.$film['titrevf'].'" class="img-rounded"></a>';

		if(!$user['is_guest']) {
			if(isset($towatch[$film['movieid']]))
				echo '<img src="img/icons/play.png" class="toview" />';
			else
				echo '<a title="Indiquer comme à voir" href="film/'.$film['movieid'].'/addWish/view" class="addWish"><img src="img/icons/add.png" class="toview" /></a>';
			}
		echo '</div>';
	}
}
?>
</div>