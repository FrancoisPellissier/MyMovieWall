<div class="row film_show">
    <div class="col-xs-4 col-sm-4 col-md-3">
    <?php
        echo '<img src="'.library\Image::getUrl('movie', $curFiche['movieid']).'" alt="Affiche du film" title="'.$curFiche['titrevf'].'" class="img-rounded" />';

        if(!$user['is_guest'])
            echo '<p><a href="film/'.$curFiche['movieid'].'/maj">Mettre à jour la fiche</a></p>';
    ?>
    </div>
    <div class="col-xs-8 col-sm-8 col-md-7 filminfos">
    	<h1><?php echo $curFiche['titrevf']; ?></h1>
        
        <p><strong>Date de sortie :</strong> <?php echo library\Date::formatDate($curFiche['datesortie'], 'J mois annee'); ?></p>
    	<p><strong>Genere :</strong> 
        <?php
        foreach($curFiche['genres'] AS $id => $genre) {
            echo ($id != 0 ? ', ' : '').'<a href="film/genre/'.$genre['genreid'].'">'.$genre['genrename'].'</a>'; 
            }
        ?></p>

        <p><strong>Réalisateur :</strong> 
        <?php
        foreach($curFiche['realisateurs'] AS $id => $real)
           echo ($id != 0 ? ', ' : '').'<a href="person/'.$real['personid'].'">'.$real['fullname'].'</a>'; 

        ?></p>
    	<p><strong>Acteurs :</strong> 
        <?php
        foreach($curFiche['acteurs'] AS $id => $acteur) {
            echo ($id != 0 ? ', ' : '').'<a href="person/'.$acteur['personid'].'">'.$acteur['fullname'].'</a>'; 
            }
        ?></p>
        <p><strong>Durée :</strong> <?php echo gmdate("G\hi", $curFiche['duree']); ?></p>
    	<p><strong>Synopsis :</strong> <?php echo $curFiche['synopsis']; ?></p>
    </div>
    <div class="col-md-2">
        <?php
        if(!$user['is_guest']) {
        ?>
        <p>Vous souhaitez :<br />
            <?php
            foreach(array('view' => 'Le voir', 'buy' => 'L\'acheter') AS $type => $typename) {
                if($user['wishFilm'][$type] == 0)
                    echo "\n\t\t\t".'<a title="'.$type.'" href="film/'.$curFiche['movieid'].'/addWish/'.$type.'" class="addWish"><button type="button" class="btn btn-default" id="'.$type.'">'.$typename.'</button></a>';
                else
                    echo "\n\t\t\t".'<a title="'.$type.'" href="film/'.$curFiche['movieid'].'/delWish/'.$type.'" class="delWish"><button type="button" class="btn btn-success" id="'.$type.'">'.$typename.'</button></a>';
            }
            ?>
        </p>

        <p>Vous le possédez en :<br />
            <?php
            foreach(array('bluray' => 'Blu-Ray', 'dvd' => 'DVD', 'numerique' => 'Numérique') AS $type => $typename) {
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
                echo '<br />- le '.\library\Date::formatDate($vue['viewdate'], 'J mois annee').($vue['type'] == '1' ? ' au cinéma' : ' à la télé'). ' <a href="film/'.$curFiche['movieid'].'/delView/'.$vue['viewid'].'" title="Supprimer"><span class="glyphicon glyphicon-remove"></span></a>';
            }
            echo '</p>';
        }
        
        ?>
        <form action="film/<?php echo $curFiche['movieid']; ?>/addView" method="post">
            <p>
                <label><input type="radio" name="type" value="1" checked />Cinéma</label> <label><input type="radio" name="type" value="2" /> Télé</label>
                <br /><input type="text" name="viewdate" id="viewdate"  class="datepicker" data-provide="datepicker" />
                <br /><input type="submit" name="addView" value="Enregistrer" /></p>
        </form>
        <?php
            }
        if(!empty($user['friendHasFilm'])) {
            echo "\n\t\t".'<p>Ces amis le possèdent :<br />';            
            foreach($user['friendHasFilm'] AS $friend) {
                echo "\n\t\t".'- <a href="user/'.$friend['id'].'">'.$friend['realname'].'</a> (';

                $types = array();
                foreach(array('bluray' => 'Blu-Ray', 'dvd' => 'DVD', 'numerique' => 'Numérique') AS $type => $typename) {
                    if($friend[$type] == '1')
                        $types[] = $typename;
                }
                echo implode(' / ', $types);
            }
            echo ')'."\n\t\t".'</p>';
        }
        ?>
    </div>

</div>

<div class="row">
	<?php
	foreach($curFiche['acteurs'] AS $acteur) {
		echo "\n\t".'<div class="col-xs-4 col-sm-3 col-md-2">';

        if(file_exists(library\Image::getUrl('person', $acteur['personid'])))
            echo '<a href="person/'.$acteur['personid'].'"><img src="'.library\Image::getUrl('person', $acteur['personid']).'" title="'.$acteur['fullname'].' - '.$acteur['role'].'" class="img-rounded" /></a>';
        else
             echo '<a href="person/'.$acteur['personid'].'"><img src="img/empty.jpg" title="'.$acteur['fullname'].' - '.$acteur['role'].'" class="img-rounded" /></a>';

        echo '</div>';
    }
	?>
</div>