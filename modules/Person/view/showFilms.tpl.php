<?php
echo '<h2>'.$person['fullname'].'</h2>';

$img = '';
if(file_exists(library\Image::getUrl('person', $person['personid'])))
    $img = '<img src="'.library\Image::getUrl('person', $person['personid']).'" width="150px" title="'.$person['fullname'].'" class="img-rounded" />';


if(!empty($filmActeur)) {
?>
<h3>A joué dans les films :</h3>
<div class="row">
	<?php
	if($img != '')
		echo "\n\t".'<div class="col-xs-4 col-sm-3 col-md-2">'.$img.'</div>';

	foreach($filmActeur AS $cur)
		echo "\n\t".'<div class="col-xs-4 col-sm-3 col-md-2"><a href="film/'.$cur['movieid'].'"><img src="'.library\Image::getUrl('movie', $cur['movieid']).'" alt="Affiche du film" title="'.$cur['titrevf'].' - '.$cur['role'].'" class="img-rounded"></a></div>';
	?>
</div>
<?php
}

if(!empty($filmReal)) {
?>
<h3>A réalisé les films :</h3>
<div class="row">
	<?php
	if($img != '')
		echo "\n\t".'<div class="col-xs-4 col-sm-3 col-md-2">'.$img.'</div>';

	foreach($filmReal AS $cur)
		echo "\n\t".'<div class="col-xs-4 col-sm-3 col-md-2"><a href="film/'.$cur['movieid'].'"><img src="'.library\Image::getUrl('movie', $cur['movieid']).'" alt="Affiche du film" title="'.$cur['titrevf'].' - '.$cur['role'].'" class="img-rounded"></a></div>';
	?>
</div>
<?php
}
?>