<h3>Films vus récemment au cinéma</h3>
<div class="row">
	<?php
	foreach($lastViewCine AS $cur)
		echo "\n\t".'<div class="col-xs-4 col-sm-3 col-md-2"><a href="film/'.$cur['movieid'].'"><img src="'.library\Image::getUrl('movie', $cur['movieid']).'" alt="Affiche du film" title="'.$cur['titrevf'].'" class="img-rounded"></a></div>';
	?>
</div>
<p><a href="user/<?php echo $curUser['id']; ?>/lastview/cinema">Voir la liste complète</a></p>
<h3>Films vus récemment à la télé</h3>
<div class="row">
	<?php
	foreach($lastViewTele AS $cur)
		echo "\n\t".'<div class="col-xs-4 col-sm-3 col-md-2"><a href="film/'.$cur['movieid'].'"><img src="'.library\Image::getUrl('movie', $cur['movieid']).'" alt="Affiche du film" title="'.$cur['titrevf'].'" class="img-rounded"></a></div>';
	?>
</div>
<p><a href="user/<?php echo $curUser['id']; ?>/lastview/tele">Voir la liste complète</a></p>
<h3>Dernières ajouts dans la bibliothèque</h3>
<div class="row">
	<?php
	foreach($lastBiblio AS $cur)
		echo "\n\t".'<div class="col-xs-4 col-sm-3 col-md-2"><a href="film/'.$cur['movieid'].'"><img src="'.library\Image::getUrl('movie', $cur['movieid']).'" alt="Affiche du film" title="'.$cur['titrevf'].'" class="img-rounded"></a></div>';
	?>
<p><a href="user/<?php echo $curUser['id']; ?>/biblio">Voir la liste complète</a></p>
</div>