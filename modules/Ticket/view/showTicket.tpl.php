<?php
$Parsedown = new \library\Parsedown();

foreach($comments AS $comment) {
	?>
<div class="row">
	<div class="col-xs-8 col-sm-8 col-md-8">
		<div class="panel panel-default">
			<?php
			$edit = '';
			if($user['id'] == $comment['userid'])
				$edit = ' <a href="ticket/'.$ticket['ticketid'].'/comment/'.$comment['commentid'].'/edit" title="Modifier"><span class="glyphicon glyphicon-pencil"></span></a>';

			// Gestion d'une entête et d'un corps de message distinct pour le premier message
			if($ticket['firstcommentid'] == $comment['commentid'])
				echo "\n\t\t\t".'<div class="panel-heading" id="'.$comment['commentid'].'">#'.$ticket['ticketid'].' : '.pun_htmlspecialchars($ticket['ticketname']).' ('.\library\Datetime::formatDateTime($comment['created_at'], 'd/m/Y', 'H:i:s').')'.$edit.'</div>';
			else
				echo "\n\t\t\t".'<div class="panel-heading" id="'.$comment['commentid'].'">'.$comment['realname'].' ('.\library\Datetime::formatDateTime($comment['created_at'], 'd/m/Y', 'H:i:s').')'.$edit.'</div>';
			?>
			<div class="panel-body">
			<?php
			if($ticket['firstcommentid'] == $comment['commentid']) {
				$statusedit = '';
				if($user['id'] == 2)
					$statusedit = ' <a href="ticket/'.$ticket['ticketid'].'/status" title="Modifier"><span class="glyphicon glyphicon-pencil"></span></a>';

				echo "\n\t\t\t".'<p><strong>Créé par :</strong> '.$comment['realname'].' | <strong>Type :</strong> '.$type[$ticket['typeid']].' | <strong>Statut :</strong> '.$status[$ticket['statusid']].$statusedit.'</p>';
				echo "\n\t\t\t".'<hr />';
			}
			
			echo $Parsedown->text(pun_htmlspecialchars($comment['message']));
			?>
			</div>
		</div>
	</div>
</div>
<?php
}
?>
<div class="row">
	<div class="col-xs-8 col-sm-8 col-md-8">
		<div class="panel panel-default">
			<div class="panel-heading">Ajouter un commentaire
			</div>
			<div class="panel-body">
				<form method="post" action="ticket/<?php echo $ticket['ticketid'] ?>/comment">
					<textarea name="message" class="form-control" rows="8"></textarea>
					<p><br /><input class="btn btn-primary" type="submit" value="Poster" /></p>
				</form>
			</div>
		</div>
	</div>
</div>
