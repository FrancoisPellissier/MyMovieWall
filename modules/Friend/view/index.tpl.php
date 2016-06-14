 <div class="row">
 <?php
/*
La colonne action contiendra pour les attentes :
-- Réponse de l'ami
-- Accepter / Refuser

Pour les amis actuels
-- Supprimer le liens ==> double validation, pas ajax
*/

 $boucle = array(
    array('list' => 'friend', 'title' => 'Liste d\'amis', 'array' => $friends),
    array('list' => 'tovalidate', 'title' => 'En attente de validation', 'array' => $toValidated)
    );

foreach($boucle AS $id => $iteration) {
    echo "\n\t".'<div class="col-sm-12 col-md-6">';
    echo "\n\t\t".'<h3>'.$iteration['title'].'</h3>';

    if(!empty($iteration['array'])) {
        echo "\n\t\t".'<table class="table table-striped">';
        echo "\n\t\t".'<thead>';
        echo "\n\t\t\t".'<tr>';
        echo "\n\t\t\t\t".'<th>Nom</th>';
        echo "\n\t\t\t\t".'<th>Action</th>';
        echo "\n\t\t\t\t".'<th>Bibliothèque</th>';
        echo "\n\t\t\t\t".'<th>Vus</th>';
        echo "\n\t\t\t".'</tr>';
        echo "\n\t\t".'</thead>';
        echo "\n\t\t".'<tbody>';
        
        // On parcourt les résultats
        foreach($iteration['array'] AS $friend) {
            $action = '';
            $id = '';

            // Génération des actions
            if($iteration['list'] == 'friend') {
                $action = '<a href="friend/delete/'.$friend['id'].'">Supprimer</span>';
            }
            else if($friend['asked'] == 'ask') {
                $action = '<em>En attente</em>';
            }
            else if($friend['asked'] == 'answer') {
                $action = '<a href="friend/validate/'.$friend['id'].'">Accepter</a> / <a href="friend/decline/'.$friend['id'].'">Refuser</span>';
            }

            echo "\n\t\t\t".'<tr'.$id.'>';
            echo "\n\t\t\t\t".'<td><a href="'.WWW_ROOT.'user/'.$friend['id'].'">'.$friend['realname'].'</a></td>';
            echo "\n\t\t\t\t".'<td>'.$action.'</td>';
            echo "\n\t\t\t\t".'<td>'.(isset($infos[$friend['id']]['biblio']) ? $infos[$friend['id']]['biblio'] : 0).'</td>';
            echo "\n\t\t\t\t".'<td>'.(isset($infos[$friend['id']]['view']) ? $infos[$friend['id']]['view'] : 0).'</td>';
            echo "\n\t\t\t".'</tr>';
        }

        echo "\n\t\t".'</tbody>';
        echo "\n\t\t".'</table>';
        }

        if($id == 0)
            echo "\n\t\t".'<p><a href="friend/search">Rechercher un utilisateur</a></p>';   
    echo "\n\t".'</div>';
    }
?>
</div>
