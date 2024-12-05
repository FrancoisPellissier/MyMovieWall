<h3>Sorties récentes</h3>
<div class="row">
	<?php
	foreach($lastOut AS $cur)
		echo "\n\t".'<div class="col-xs-4 col-sm-3 col-md-2"><a href="film/'.$cur['movieid'].'"><img src="'.library\Image::getUrl('movie', $cur['movieid']).'" alt="Affiche du film" title="'.$cur['titrevf'].' ('.$cur['datesortie'].')" class="img-rounded"></a><br /></div>';
	?>
</div>

<h3>Sorties à venir</h3>
<div class="row">
	<?php
	foreach($nextOut AS $cur)
		echo "\n\t".'<div class="col-xs-4 col-sm-3 col-md-2"><a href="film/'.$cur['movieid'].'"><img src="'.library\Image::getUrl('movie', $cur['movieid']).'" alt="Affiche du film" title="'.$cur['titrevf'].' ('.$cur['datesortie'].')" class="img-rounded"></a><br /></div>';
	?>
</div>

<h3>Derniers ajouts</h3>
<div class="row">
	<?php
	foreach($lastCreated AS $cur)
		echo "\n\t".'<div class="col-xs-4 col-sm-3 col-md-2"><a href="film/'.$cur['movieid'].'"><img src="'.library\Image::getUrl('movie', $cur['movieid']).'" alt="Affiche du film" title="'.$cur['titrevf'].'" class="img-rounded"></a></div>';
	?>
</div>
