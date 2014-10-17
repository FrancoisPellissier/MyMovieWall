<?php
echo '<p>Résultat de la recherche pour : '.$keyword.'</p>';

if(isset($datas)) {

  echo "\n\t".'<div class="row">';

  foreach($datas AS $data) {
    echo "\n\t".'<div class="col-xs-4 col-sm-3 col-md-2"><a href="film/'.$data['movieid'].'"><img src="'.library\Image::getUrl('movie', $data['movieid']).'" alt="Affiche du film" title="'.$data['titrevf'].'" class="img-rounded"></a></div>';

  }
  echo "\n\t".'</div>';
}

if(!$user['is_guest'])
	echo '<p>Vous ne trouvez pas le film que vous cherchez ? <a href="film/searchAllocine/'.str_replace(' ', '+', $keyword).'">Ajoutez le</a></p>';
?>