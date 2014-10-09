<form role="form form-inline" method="post">
  <div class="form-group">
    <label for="keyword"></label>
    <input type="text" class="form-control" name="keyword" id="keyword" placeholder="Mots-clé">
  </div>
  <button type="submit" class="btn btn-default">Submit</button>
</form>

<?php
if(isset($datas)) {

  echo "\n\t".'<div class="row">';
  foreach($datas AS $data) {

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