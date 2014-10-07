
<div class="row">
	<?php
	foreach($last AS $cur)
		echo "\n\t".'<div class="col-xs-4 col-sm-3 col-md-2"><a href="film/'.$cur['movieid'].'"><img src="img/movie/0/'.$cur['movieid'].'.jpg" alt="Affiche du film" title="'.$cur['titrevf'].'" class="img-rounded"></a></div>';
	/*
	for($i=1; $i<=12; $i++) {
		echo "\n\t".'<div class="col-xs-4 col-sm-3 col-md-2"><img src="img/movie/0/t'.($i > 12 ? $i-12 : $i).'.jpg" alt="Affiche du film" class="img-rounded"></div>';
	}
	*/
	?>
</div>
