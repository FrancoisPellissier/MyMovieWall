<?php
include('show.tpl.php');

if(!empty($seances)) {
    // On parcourt les cinémas
    foreach($seances AS $cine => $versions) {
        echo "\n\t".'<h2>'.$cine.'</h2>';

        if(empty($versions)) {
            echo "\n\t".'<p>Pas de séances dans ce cinéma.</p>';
        }
        else {
            ksort($versions);
            // On parcourt les langues
            foreach($versions AS $version => $dates) {
                echo "\n\t".'<h4>'.$version.'</h4>';

                ?>
        <table class="table table-striped table_width">
        <tbody>
                <?php
                // Tri des dates
                ksort($dates);
                // On parcourt les jours
                foreach($dates AS $jour => $heures) {            
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
    echo "\n\t".'<br /><p class="alert alert-info">Il n\'y a aucune séance prévue pour ce film dans les cinémas que vous avez sélectionnés et pour la période en cours.<br /><br />Vous devez avoir sélectionné au moins un cinéma dans votre profil pour accéder à ses horaires : <a href="theater">ajouter un cinéma</a>.</p>';
}
