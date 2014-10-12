
<div class="row">
	<?php
	foreach($last AS $cur)
		echo "\n\t".'<div class="col-xs-4 col-sm-3 col-md-2"><a href="film/'.$cur['movieid'].'"><img src="'.library\Image::getUrl('movie', $cur['movieid']).'" alt="Affiche du film" title="'.$cur['titrevf'].'" class="img-rounded"></a></div>';
	?>
</div>
