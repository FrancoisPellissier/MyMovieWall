</p>
<?php
echo "\n\t".'<div class="row">';

foreach($wishlist AS $film) {

	echo "\n\t".'<div class="col-xs-4 col-sm-3 col-md-2"><a href="film/'.$film['movieid'].'"><img src="'.library\Image::getUrl('movie', $film['movieid']).'" alt="Affiche du film" title="'.$film['titrevf'].'" class="img-rounded"></a></div>';
}
?>
</div>