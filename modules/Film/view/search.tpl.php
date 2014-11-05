<?php
echo '<p>Résultat de la recherche pour : '.$keyword.'</p>';

if(isset($datas)) {

  echo "\n\t".'<div class="row">';

  foreach($datas AS $data) {
    echo "\n\t".'<div class="col-xs-4 col-sm-3 col-md-2"><a href="film/'.$data['movieid'].'"><img src="'.library\Image::getUrl('movie', $data['movieid']).'" alt="Affiche du film" title="'.$data['titrevf'].'" class="img-rounded"></a></div>';

  }
  echo "\n\t".'</div>';
}

if(!$user['is_guest'] && !empty($datasAPI)) {

	echo '<p>Vous ne trouvez pas le film que vous cherchez ? Ajoutez le :</p>';
	foreach($datasAPI AS $data) {

	  echo "\n\t".'<div class="col-xs-12 col-sm-6 col-md-4">';
	  ?>
	    <div class="row">
	      <div class="col-md-4"><?php echo '<img src="'.$data['affiche'].'"" width="160" height="240" alt="Affiche" title="'.$data['titre'].'" class="img-rounded" />'; ?></div>
	      <div class="col-md-8">
	        <?php echo '<p>'.$data['titre'].'</p><p>'.$data['realisateur'].'</p><p>'.$data['acteur'].'</p>'; ?>
	        <p><a href="film/add/<?php echo $data['code']; ?>" class="addFilm"><button type="button" class="btn btn-primary">Ajouter</button></a></p>
	    </div>
	    </div>
	  <?php
	  echo '</div>';
	}
	echo "\n\t".'</div>';
}
?>