</p>
<?php
foreach($genres AS $genre) {
	echo "\n\t".'<a href="user/'.$curUser['id'].'/biblio/genre/'.$genre['genreid'].'"><button type="button" class="btn '.($genre['genreid'] == $genreid ? 'btn-success' : 'btn-primary').'">'.$genre['genrename'].'</button></a> ';
}
?>
<p>
<div class="row">
	<?php
	foreach($films AS $cur)
		echo "\n\t".'<div class="col-xs-4 col-sm-3 col-md-2"><a href="film/'.$cur['movieid'].'"><img src="'.library\Image::getUrl('movie', $cur['movieid']).'" alt="Affiche du film" title="'.$cur['titrevf'].'" class="img-rounded"></a></div>';
	?>
</div>
