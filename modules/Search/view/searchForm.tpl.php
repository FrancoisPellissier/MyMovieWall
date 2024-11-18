<form action="" method="post" class="form-inline">

	<div class="row">
  		<div class="col-md-3">
  			<label for="titre">Titre</label>
	   		<input type="text" class="form-control" id="titre" name="titre" placeholder="Titre" value="<?php echo str_replace('"', '\"', $params['titre']); ?>">
	   	</div>
	   	<div class="col-md-3">
	   		<label for="acteur">Acteur</label>
	   		<input type="text" class="form-control" id="acteur" name="acteur" placeholder="Acteur" value="<?php echo str_replace('"', '\"', $params['acteur']); ?>">
	   	</div>
	   	<div class="col-md-3">
			<label for="genre">Genre
				<select class="form-control" id="genre" name="genre">
	  			<option>Choisir un genre</option>
	  			<?php
	  			foreach($genres AS $genre) {
	  				echo "\n\t\t\t\t".'<option value="'.$genre['genreid'].'"'.($params['genre'] == $genre['genreid'] ? ' selected="selected"' : '').'>'.$genre['genrename'].'</option>';
	  			}
	  			?>
			</select>
			</label>
		</div>
	</div>
	<div class="row">
		<?php
		if(!$user['is_guest']) {
		?>
		<div class="col-md-3">
			<label for="own">Format
				<select class="form-control" id="own" name="own">
				<?php
				$ordres = array('' => 'Format', 'dvd' => 'DVD', 'bluray' => 'Blu-Ray', 'numerique' => 'Numérique');
	  			foreach($ordres AS $ordreid => $ordre) {
	  				echo "\n\t\t\t\t".'<option value="'.$ordreid.'"'.($params['own'] == $ordreid ? ' selected="selected"' : '').'>'.$ordre.'</option>';
	  			}
	  			?>
			</select>
			</label>
		</div>
		<?php
		}
		?>
		<div class="col-md-3">
			<label for="ordre">Trier par
				<select class="form-control" id="ordre" name="ordre">
				<?php
				$ordres = array('1' => 'Titre', '2' => 'Date de sortie', '3' => 'Mes notes');
	  			foreach($ordres AS $ordreid => $ordre) {
	  				echo "\n\t\t\t\t".'<option value="'.$ordreid.'"'.($params['ordre'] == $ordreid ? ' selected="selected"' : '').'>'.$ordre.'</option>';
	  			}
	  			?>
			</select>
			</label>
		</div>
	 </div>
	 <?php
	 if(!$force_biblio && !$user['is_guest']) {
	 ?>
	 <div class="row">
		<div class="col-md-4 checkbox">
		  <label><input id="biblio" name="biblio" type="checkbox"<?php echo ($params['biblio'] ? ' checked': ''); ?>> Films que je possède</label>
		</div>
	</div>
	<?php
		}
	?>
	<p><button type="submit" class="btn btn-primary" name="search">Chercher</button></p>
</form>