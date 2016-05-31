<?php
$action = array(
    'movie_add' => 'a créé le film <strong>%s</strong>',
    'biblio_add' => 'a ajouté le film <strong>%s</strong> dans sa bibliothèque',
    'view_add' => 'a vu le film <strong>%s</strong>',
    'wish_add' => 'a ajouté le film <strong>%s</strong> dans sa whislist',
    'towatch_add' => 'a ajouté le film <strong>%s</strong> dans son agenda',
    'rate_add' => 'a noté le film <strong>%s</strong>'
    );

?>
<div class="row">
    <?php
    foreach($timelines AS $cur) {
        if($cur['realname'] == '')
            $cur['realname'] = 'Inconnu';
        
        echo "\n\t".'<div class="col-xs-6 col-sm-4 col-md-3">';
            echo "\n\t".'<div class="row">';
            echo "\n\t".'<div class="col-xs-6 col-sm-6 col-md-6"><a href="film/'.$cur['movieid'].'"><img src="'.library\Image::getUrl('movie', $cur['movieid']).'" alt="Affiche du film" title="'.$cur['titrevf'].'" class="img-rounded"></a></div>';
            echo "\n\t".'<div class="col-xs-6 col-sm-6 col-md-6">'. $cur['realname'].' '.sprintf($action[$cur['action']], $cur['titrevf']).'</div>';
            echo "\n\t".'</div>';
        echo '</div>';            
    }
    ?>
</div>