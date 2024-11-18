<?php
echo '<h2>Ajouter des films de/avec '.$person['fullname'].'</h2>';
?>
<div id="results">
<?php

if(!$user['is_guest'] && !empty($filmActeur)) {
    ?>
    <h3>A joué dans les films :</h3>
    <div class="row">
	<?php

	foreach($filmActeur AS $data)
		echo "\n\t".'<div class="col-xs-4 col-sm-3 col-md-2"><a href="film/add/'.$data['tmdbid'].'" class="addFilm"><img src="'.$data['affiche'].'" alt="Ajouter '.$data['titre'].' - '.$data['datesortie'].'" title="Ajouter \''.$data['titre'].'\' - '.$data['datesortie'].'" class="img-rounded" /></a></div>';
	?>
    </div>
<?php
}
	
if(!$user['is_guest'] && !empty($filmReal)) {
    ?>
    <h3>A réalisé les films :</h3>
    <div class="row">
	<?php

	foreach($filmReal AS $data)
		echo "\n\t".'<div class="col-xs-4 col-sm-3 col-md-2"><a href="film/add/'.$data['tmdbid'].'" class="addFilm"><img src="'.$data['affiche'].'" alt="Ajouter '.$data['titre'].' - '.$data['datesortie'].'" title="Ajouter \''.$data['titre'].'\' - '.$data['datesortie'].'" class="img-rounded" /></a></div>';
	?>
    </div>
<?php
}
?>
</div>
