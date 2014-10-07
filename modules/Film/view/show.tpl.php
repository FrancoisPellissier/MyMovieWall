<div class="row">
    <div class="col-md-3">
    	<?php echo '<img src="img/movie/0/'.$curFiche['movieid'].'.jpg" alt="Affiche du film" title="'.$curFiche['titrevf'].'" class="img-rounded" />'; ?>
    </div>
    <div class="col-md-9">
    	<h1><?php echo $curFiche['titrevf']; ?></h1>
    	<p><?php echo $curFiche['synopsis']; ?></p>
    </div>
</div>