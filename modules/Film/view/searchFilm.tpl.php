<?php
include('modules/Search/view/searchForm.tpl.php');

if(isset($films))
	echo "\n".'<p>Il y a '.count($films).' résultat(s) à votre recherche.<p>';
?>
<div class="row">
	<?php
	foreach($films AS $cur) {
		echo "\n\t".'<div class="col-xs-4 col-sm-3 col-md-2"><a href="film/'.$cur['movieid'].'"><img src="'.library\Image::getUrl('movie', $cur['movieid']).'" alt="Affiche du film" title="'.$cur['titrevf'].'" class="img-rounded"></a><br />'.$cur['rate'].'</div>';
	}
	?>
</div>
