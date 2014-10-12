<h3>Sorties cinéma</h3>
<div class="row">
	<?php
	foreach($lastViewCine AS $cur)
		echo "\n\t".'<div class="col-xs-4 col-sm-3 col-md-2"><a href="film/'.$cur['movieid'].'"><img src="'.library\Image::getUrl('movie', $cur['movieid']).'" alt="Affiche du film" title="'.$cur['titrevf'].'" class="img-rounded"></a></div>';
	?>
</div>
<h3>Soirées télé</h3>
<div class="row">
	<?php
	foreach($lastViewTele AS $cur)
		echo "\n\t".'<div class="col-xs-4 col-sm-3 col-md-2"><a href="film/'.$cur['movieid'].'"><img src="'.library\Image::getUrl('movie', $cur['movieid']).'" alt="Affiche du film" title="'.$cur['titrevf'].'" class="img-rounded"></a></div>';
	?>
</div>
<h3>Sessions shopping</h3>
<div class="row">
	<?php
	foreach($lastBiblio AS $cur)
		echo "\n\t".'<div class="col-xs-4 col-sm-3 col-md-2"><a href="film/'.$cur['movieid'].'"><img src="'.library\Image::getUrl('movie', $cur['movieid']).'" alt="Affiche du film" title="'.$cur['titrevf'].'" class="img-rounded"></a></div>';
	?>
</div>