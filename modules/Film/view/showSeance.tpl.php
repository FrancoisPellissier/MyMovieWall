<?php
include('show.tpl.php');

if(!empty($seances)) {
    // On parcourt les cinémas
    foreach($seances AS $cine) {
        echo "\n\t".'<h2>'.$cine['theater']['theatername'].'</h2>';

        if(empty($cine['horaires'])) {
            echo "\n\t".'<p>Pas de séances dans ce cinéma.</p>';
        }
        else {

            // On parcourt les langues
            foreach($cine['horaires'] AS $version) {
                echo "\n\t".'<h4>'.$version['version'].' - '.$version['format'].'</h4>';

                ?>
        <table class="table table-striped">
        <thead>
            <tr>
                <th>Jour</th>
                <th>Horaires</th>        
            </tr>
        </thead>
        <tbody>
                <?php

                // On parcourt les jours
                foreach($version['date'] AS $jour => $heures) {            
                    echo "\n\t".'<tr><td>'.library\Datetime::formatDateTime($jour, 'jour j mois', '').'</td><td>'.implode($heures, ' | ').'</td></tr>';
                }
                ?>
        </tbody>
        </table>
                <?php
            }
        }
    }
}
else {
    echo "\n\t".'<br /><p class="alert alert-info">Vous devez avoir sélectionné au moins un cinéma dans votre profil pour accéder à ses horaires : <a href="theater">ajouter un cinéma</a>.</p>';
}
