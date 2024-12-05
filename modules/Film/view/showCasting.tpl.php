<?php
include('show.tpl.php');
?>
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