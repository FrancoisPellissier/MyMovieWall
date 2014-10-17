<div class="row">
    <div class="col-xs-4 col-sm-4 col-md-3">
    	<?php echo '<img src="'.library\Image::getUrl('movie', $curFiche['movieid']).'" alt="Affiche du film" title="'.$curFiche['titrevf'].'" class="img-rounded" />'; ?>
    </div>
    <div class="col-xs-8 col-sm-8 col-md-7 filminfos">
    	<h1><?php echo $curFiche['titrevf']; ?></h1>
        
        <p><strong>Date de sortie :</strong> <?php echo $curFiche['datesortie']; ?></p>
    	<p><strong>Réalisateur :</strong> <?php echo $curFiche['realisateur']; ?></p>
    	<p><strong>Acteurs principaux :</strong> <?php echo $curFiche['acteur']; ?></p>
        <p><strong>Durée :</strong> <?php echo gmdate("G\hi", $curFiche['duree']); ?></p>
    	<p><strong>Synopsis :</strong> <?php echo $curFiche['synopsis']; ?></p>
    </div>
    <div class="col-md-2">
        <?php
        if(!$user['is_guest']) {
        ?>
        <p>Vous le possédez en :<br />
            <?php
            foreach(array('bluray' => 'Blu-Ray', 'dvd' => 'DVD') AS $type => $typename) {
                if($user['hasFilm'][$type] == 0)
                    echo "\n\t\t\t".'<a title="'.$type.'" href="film/'.$curFiche['movieid'].'/addBiblio/'.$type.'" class="addBiblio"><button type="button" class="btn btn-default" id="'.$type.'">'.$typename.'</button></a>';
                else
                    echo "\n\t\t\t".'<a title="'.$type.'" href="film/'.$curFiche['movieid'].'/delBiblio/'.$type.'" class="delBiblio"><button type="button" class="btn btn-success" id="'.$type.'">'.$typename.'</button></a>';
            }
            ?>
        </p>
        <?php
        if(empty($user['hasViewFilm']))
            echo '<p>Vous ne l\'avez jamais vu</p>';
        else {
            echo "\n\t\t".'<p>Vous l\'avez vu :';

            foreach($user['hasViewFilm'] AS $id => $vue) {
                echo '<br />- le '.\library\Date::formatDate($vue['viewdate'], 'J mois annee').($vue['type'] == '1' ? ' au cinéma' : ' à la télé');
            }
            echo '</p>';
        }
        
        ?>
        <form action="film/<?php echo $curFiche['movieid']; ?>/addView" method="post">
            <p>
                <label><input type="radio" name="type" value="1" />Cinéma</label> <label><input type="radio" name="type" value="2" /> Télé</label>
                <br /><input type="date" name="viewdate" id="viewdate" />
                <br /><input type="submit" name="addView" value="Enregistrer" /></p>
        </form>
        <?php
            }
        ?>
    </div>

</div>

<div class="row">
	<?php
	foreach($curFiche['acteurs'] AS $acteur)
		echo "\n\t".'<div class="col-xs-4 col-sm-3 col-md-2"><img src="'.library\Image::getUrl('person', $acteur['personid']).'" title="'.$acteur['fullname'].' - '.$acteur['role'].'" class="img-rounded" /></div>';
	?>
</div>