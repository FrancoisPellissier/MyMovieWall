<?php
if(isset($datasAPI) && count($datasAPI) > 0) {

  echo "\n\t".'<div class="row">';
  foreach($datasAPI AS $data) {

    echo "\n\t".'<div class="col-xs-12 col-sm-6 col-md-4">';
    ?>
      <div class="row">
        <div class="col-md-4"><?php echo '<img src="'.$data['affiche'].'"" width="160" height="240" alt="Affiche" title="'.$data['titre'].'" class="img-rounded" />'; ?></div>
        <div class="col-md-8">
          <?php echo '<p>'.$data['titre'].'</p><p>'.$data['datesortie'].'</p><p>'.substr($data['pitch'], 0, 200).'</p>'; ?>
          <p><a href="film/<?php echo $curId; ?>/associate/<?php echo $data['tmdbid']; ?>" class="addFilm"><button type="button" class="btn btn-primary">Associer</button></a></p>
      </div>
      </div>
    <?php
    

    echo '</div>';

  }
  echo "\n\t".'</div>';
}
?>