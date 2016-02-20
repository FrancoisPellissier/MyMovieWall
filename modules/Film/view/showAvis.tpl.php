<?php
include('show.tpl.php');

if(!$user['is_guest']) {
    echo "\n\t".'<br /><p><a class="btn btn-primary" href="avis/add/'.$curFiche['movieid'].'" role="button"><span class="glyphicon glyphicon-plus"></span> Donner son avis</a></p>';
}

$Parsedown = new \library\Parsedown();
foreach($curFiche['avis'] AS $avis) {
	?>
<div class="row">
	<div class="col-xs-8 col-sm-8 col-md-8">
		<div class="panel panel-default">
			<?php
			$edit = '';
			if($user['id'] == $avis['userid'])
				$edit = ' <a href="avis/edit/'.$avis['avisid'].'" title="Modifier"><span class="glyphicon glyphicon-pencil"></span></a>';

			echo "\n\t\t\t".'<div class="panel-heading" id="'.$avis['avisid'].'">Ecrit par <em>'.$avis['realname'].'</em> le '.\library\Datetime::formatDateTime($avis['created_at'], 'd/m/Y', 'H:i:s').$edit.'</div>';
			?>
			<div class="panel-body">
			<?php
			
			echo $Parsedown->text(pun_htmlspecialchars($avis['message']));
			?>
			</div>
		</div>
	</div>
</div>
<?php
}

if(!$user['is_guest'] && count($curFiche['avis']) > 1) {
    echo "\n\t".'<p><a class="btn btn-primary" href="avis/add/'.$curFiche['movieid'].'" role="button"><span class="glyphicon glyphicon-plus"></span> Donner son avis</a></p>';
}
