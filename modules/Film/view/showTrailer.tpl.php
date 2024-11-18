<?php
include('show.tpl.php');

$last = '';
?>
<div class="row film_show">
    <div class="col-xs-4 col-sm-4 col-md-4">
    	<?php
    	foreach($trailers AS $trailer) {
    		echo "\n\t".'<p>'.$trailer['titre'].'<br /><a href="#" onclick="changeTrailer(\''.str_replace("'", "\'", $trailer['video']).'\');"><img src ="'.$trailer['img'].'" width="180px" title="'.$trailer['titre'].'" /></a></p>';

    		if($last == '')
    			$last = $trailer['video'];
    	}
    	?>
    </div>
    <div class="col-xs-8 col-sm-8 col-md-8" id="trailer">
    	<?php
    	echo $last;
    	?>
    </div>
</div>
