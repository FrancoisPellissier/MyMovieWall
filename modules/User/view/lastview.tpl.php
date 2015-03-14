<p>
<?php
$liens = array();
$liens[] = array('type' => 'all', 'url' => 'user/'.$curUser['id'].'/lastview', 'title' => 'Tout');
$liens[] = array('type' => '1', 'url' => 'user/'.$curUser['id'].'/lastview/cinema', 'title' => 'Cinéma');
$liens[] = array('type' => '2', 'url' => 'user/'.$curUser['id'].'/lastview/tele', 'title' => 'Télé');


foreach($liens AS $lien) {
	echo "\n\t".'<a href="'.$lien['url'].'"><button type="button" class="button '.($lien['type'] == $type ? 'button-success' : 'button-primary').'">'.$lien['title'].'</button></a> ';
}
?>
</p>
<?php
$mois = '';

if(!empty($lastView)) {
	foreach($lastView AS $film) {
		if($mois != substr($film['viewdate'], 0, 7)) {

			if($mois != '')
				echo "\n\t".'</div>';

			echo '<h3>'.ucwords(library\Date::formatDate($film['viewdate'], 'mois annee')).'</h3>';
			$mois = substr($film['viewdate'], 0, 7);

			echo "\n\t".'<div class="row">';
		}

		echo "\n\t".'<div class="col-xs-4 col-sm-3 col-md-2"><a href="film/'.$film['movieid'].'"><img src="'.library\Image::getUrl('movie', $film['movieid']).'" alt="Affiche du film" title="'.$film['titrevf'].'" class="img-rounded"></a><br />'.$film['rate'].'</div>';
	}
}
?>
</div>