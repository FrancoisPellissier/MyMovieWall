<h3>Mes avis</h3>
<?php
// dump($avis);


$Parsedown = new \library\Parsedown();
foreach($avis AS $avi) {
	?>
<div class="row">
	<div class="col-xs-8 col-sm-8 col-md-8">
		<div class="panel panel-default">
			<?php
			$edit = '';
			if($user['id'] == $avi['userid'])
				$edit = ' <a href="avis/edit/'.$avi['avisid'].'" title="Modifier"><span class="glyphicon glyphicon-pencil"></span></a>';

			echo "\n\t\t\t".'<div class="panel-heading" id="'.$avi['avisid'].'"><a href="film/'.$avi['movieid'].'/avis#'.$avi['avisid'].'">'.$avi['titrevf'].'</a> - Posté le '.\library\Datetime::formatDateTime($avi['created_at'], 'd/m/Y', 'H:i:s').$edit.'</div>';
			?>
			<div class="panel-body">
			<?php
			
			echo $Parsedown->text(pun_htmlspecialchars(substr($avi['message'], 0, 150).'...').' <a href="film/'.$avi['movieid'].'/avis#'.$avi['avisid'].'">Lire la suite</a>');
			?>
			</div>
		</div>
	</div>
</div>
<?php
}