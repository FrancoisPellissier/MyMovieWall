<p>
<?php
$liens = array();
$liens['all'] = array('type' => 'all', 'url' => 'user/'.$curUser['id'].'/lastview/'.$annee, 'title' => 'Tout');
$liens['1'] = array('type' => '1', 'url' => 'user/'.$curUser['id'].'/lastview/cinema/'.$annee, 'title' => 'Cinéma');
$liens['2'] = array('type' => '2', 'url' => 'user/'.$curUser['id'].'/lastview/tele/'.$annee, 'title' => 'Télé');


foreach($liens AS $lien) {
	echo "\n\t".'<a href="'.$lien['url'].'"><button type="button" class="button '.($lien['type'] == $type ? 'button-success' : 'button-primary').'">'.$lien['title'].'</button></a> ';
}
?>
</p>
<p>
<?php
if($annee != 0) {
    for($i = 2014;$i <= date('Y');$i++) {
        echo "\n\t".'<a href="'.str_replace($annee, $i, $liens[$type]['url']).'"><button type="button" class="button '.($i == $annee ? 'button-success' : 'button-primary').'">'.$i.'</button></a> ';
    }
}
?>
</p>
<?php
if(!empty($lastView)) {
    $last = '';
    // Parcourt des films
    echo "\n".'<div class="row">';
	foreach($lastView AS $film) {
        // Nouveau mois ?
        /*
        if($last != \library\Datetime::formatDateTime($film['viewdate'], 'm-Y', '')) {
            // 1er mois ?
            if($last != '') {
                echo "\n".'</div>';
            }
            $last = \library\Datetime::formatDateTime($film['viewdate'], 'm-Y', '');
            echo "\n".'<h3>'.ucwords(\library\Datetime::formatDateTime($film['viewdate'], 'mois Y', '')).'</h3>';
            echo "\n".'<div class="row">';
        }
        */
		echo "\n\t".'<div class="col-xs-4 col-sm-3 col-md-2"><a href="film/'.$film['movieid'].'"><img src="'.library\Image::getUrl('movie', $film['movieid']).'" alt="Affiche du film" title="'.$film['titrevf'].($film['viewdate'] != null ? ' - '.\library\Date::UStoFr($film['viewdate']) : '').'" class="img-rounded"></a><br />'.$film['rate'].'</div>';
	}
    echo "\n".'</div>';
}
?>
</div>