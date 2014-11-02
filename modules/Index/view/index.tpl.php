<h3>Dernières sorties cinéma</h3>
<div class="row">
	<?php
	foreach($lastViewCine AS $cur)
		echo "\n\t".'<div class="col-xs-4 col-sm-3 col-md-2"><a href="film/'.$cur['movieid'].'"><img src="'.library\Image::getUrl('movie', $cur['movieid']).'" alt="Affiche du film" title="'.$cur['titrevf'].'" class="img-rounded"></a></div>';
	?>
</div>
<p><a href="user/<?php echo $user['id']; ?>/lastview/cinema">Voir la liste complète</a></p>
<h3>Dernières soirées télé</h3>
<div class="row">
	<?php
	foreach($lastViewTele AS $cur)
		echo "\n\t".'<div class="col-xs-4 col-sm-3 col-md-2"><a href="film/'.$cur['movieid'].'"><img src="'.library\Image::getUrl('movie', $cur['movieid']).'" alt="Affiche du film" title="'.$cur['titrevf'].'" class="img-rounded"></a></div>';
	?>
</div>
<p><a href="user/<?php echo $user['id']; ?>/lastview/tele">Voir la liste complète</a></p>
<h3>Dernières sessions shopping</h3>
<div class="row">
	<?php
	foreach($lastBiblio AS $cur)
		echo "\n\t".'<div class="col-xs-4 col-sm-3 col-md-2"><a href="film/'.$cur['movieid'].'"><img src="'.library\Image::getUrl('movie', $cur['movieid']).'" alt="Affiche du film" title="'.$cur['titrevf'].'" class="img-rounded"></a></div>';
	?>
</div>