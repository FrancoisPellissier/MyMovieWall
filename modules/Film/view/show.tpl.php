<div class="row film_show">
    <div class="col-xs-4 col-sm-4 col-md-3">
    <?php
        echo '<img src="'.library\Image::getUrl('movie', $curFiche['movieid'], $curFiche['updated_at']).'" alt="Affiche du film" title="'.$curFiche['titrevf'].'" class="img-rounded" />';

        if(!$user['is_guest']) {
            if($curFiche['tmdbid'] != 0) {
            echo '<p><a href="film/'.$curFiche['movieid'].'/maj">Mettre à jour la fiche</a></p>';
            }
            else if($user['id'] == 2) {
                echo '<p><a href="film/'.$curFiche['movieid'].'/associate">Associer la fiche</a></p>';
            }
        }
    ?>
    </div>
    <div class="col-xs-8 col-sm-8 col-md-7 filminfos">
    	<h1><?php echo $curFiche['titrevf']; ?></h1>
        <p><strong>Titre original :</strong> <?php echo $curFiche['titrevo']; ?></p>
        <p><strong>Date de sortie :</strong> <?php echo library\Date::formatDate($curFiche['datesortie'], 'J mois annee'); ?></p>
    	<p><strong>Genre :</strong> 
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
                    echo "\n\t\t\t".'<button type="button" class="btn btn-default" id="'.$type.'" onClick="addWish('.$curFiche['movieid'].', \''.$type.'\')">'.$typename.'</button>';
                else
                    echo "\n\t\t\t".'<button type="button" class="btn btn-success" id="'.$type.'" onClick="delWish('.$curFiche['movieid'].', \''.$type.'\')">'.$typename.'</button>';
            }
            ?>
        </p>

        <p>Vous le possédez en :<br />
            <?php
            foreach(array('bluray' => 'Blu-Ray', 'dvd' => 'DVD') AS $type => $typename) {
                if($user['hasFilm'][$type] == 0)
                    echo "\n\t\t\t".'<button type="button" class="btn btn-default" id="'.$type.'" onClick="addBiblio('.$curFiche['movieid'].', \''.$type.'\')">'.$typename.'</button>';
                else
                    echo "\n\t\t\t".'<button type="button" class="btn btn-success" id="'.$type.'" onClick="delBiblio('.$curFiche['movieid'].', \''.$type.'\')">'.$typename.'</button>';
            }
            ?>
        </p>
        <?php
        // Espace de notation
        echo "\n\t".'<p id="rateFilm">Note : ';
        for($i=1;$i<=5;$i++) {
            
            // echo '<a href="film/'.$curFiche['movieid'].'/rate/'.$i.'" title="Noter '.$i.'/5">';
            if($i > $user['rateFilm'])
                echo '<span class="glyphicon glyphicon-star-empty" onClick="rate('.$curFiche['movieid'].', '.$i.')" title="Noter '.$i.'/5"></span>';
            else
                echo '<span class="glyphicon glyphicon-star" onClick="rate('.$curFiche['movieid'].', '.$i.')" title="Noter '.$i.'/5"></span>';
            // echo '</a>';
        }
        echo '<p>';

        // Visionnages
        if(empty($user['hasViewFilm']))
            echo '<p>Vous ne l\'avez jamais vu</p>';
        else {
            echo "\n\t\t".'<p>Vous l\'avez vu :';

            foreach($user['hasViewFilm'] AS $id => $vue) {
                if($vue['viewdate'] == null)
                    echo '<br />- ';
                else
                    echo '<br />- le '.\library\Date::formatDate($vue['viewdate'], 'J mois annee');

                 echo ($vue['type'] == '1' ? ' au cinéma' : ' à la télé'). ' <a href="film/'.$curFiche['movieid'].'/delView/'.$vue['viewid'].'" title="Supprimer"><span class="glyphicon glyphicon-remove"></span></a>';
            }
            echo '</p>';
        } 
        ?>
        <form action="film/<?php echo $curFiche['movieid']; ?>/addView" method="post">
            <p>
                <label><input type="radio" name="type" value="1" checked />Cinéma</label> <label><input type="radio" name="type" value="2" /> Télé</label>
                <br /><input type="text" name="viewdate" id="viewdate"  class="datepicker" data-provide="datepicker" />
                <br /><input type="submit" name="addView" value="Ajouter le visionnage" /></p>
        </form>
        <?php
            }
        if(!empty($user['friendToWtach'])) {
            echo "\n\t\t".'<p>Ces amis veulent le voir :';            
            foreach($user['friendToWtach'] AS $friend) {
                echo "\n\t\t".'<br />- <a href="user/'.$friend['userid'].'">'.$friend['realname'].'</a>';
            }
            echo "\n\t\t".'</p>';
        }

        if(!empty($user['friendHasFilm'])) {
            echo "\n\t\t".'<p>Ces amis le possèdent :';            
            foreach($user['friendHasFilm'] AS $friend) {
                echo "\n\t\t".'<br />- <a href="user/'.$friend['userid'].'">'.$friend['realname'].'</a> (';

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
<?php
/*
// Gestion des onglets : acteurs, bandes-annonces
$views = array(
    array('url' => 'casting', 'titre' => 'Casting'),
    array('url' => 'trailer', 'titre' => 'Bandes-annonces')
     );

if(!$user['is_guest'])
    $views[] = array('url' => 'seance', 'titre' => 'Séances');

// $views[] = array('url' => 'avis', 'titre' => 'Avis ('.count($curFiche['avis']).')');

echo "\n".'<ul class="nav nav-tabs">';
foreach($views AS $view) {
    echo "\n\t".'<li role="presentation"'.($view['url'] == $vueActif ? ' class="active"' : '').'><a href="'.WWW_ROOT.'film/'.$curFiche['movieid'].'/'.$view['url'].'">'.$view['titre'].'</a></li>';
    }
echo "\n".'</ul>';
*/