
<div class="row">
	<?php

	for($i=1; $i<=12; $i++) {
		echo "\n\t".'<div class="col-xs-4 col-sm-3 col-md-2"><img src="img/movie/0/t'.($i > 12 ? $i-12 : $i).'.jpg" alt="Affiche du film" class="img-rounded"></div>';
	}
	?>
</div>
