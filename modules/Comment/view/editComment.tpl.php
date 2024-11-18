<div class="row">
	<div class="col-xs-8 col-sm-8 col-md-8">
		<div class="panel panel-default">
			<div class="panel-heading">Message d'origine
			</div>
			<div class="panel-body">
				<?php
				if($ticket['firstcommentid'] == $comment['commentid']) {
					echo "\n\t\t\t\t".'<p><strong>Titre :</strong> '.pun_htmlspecialchars($ticket['ticketname']).'</p>';
				}
				echo "\n\t\t\t\t".pun_htmlspecialchars($comment['message']);				
				?>
			</div>
		</div>
	</div>
</div>
<div class="row">
	<div class="col-xs-8 col-sm-8 col-md-8">
		<form method="post" action="ticket/<?php echo $ticket['ticketid'] ?>/comment/<?php echo $comment['commentid'] ?>/edit">
			<?php
			if($ticket['firstcommentid'] == $comment['commentid']) {
				echo "\n\t\t".'<p><input type="text" class="form-control" id="ticketname" name="ticketname" placeholder="Titre du ticket" value="'.pun_htmlspecialchars(str_replace('"', '', $ticket['ticketname'])).'"></p>';
			}
			?>
			<textarea name="message" class="form-control" rows="8"><?php echo pun_htmlspecialchars($comment['message']); ?></textarea>
			<p>Vous pouvez mettre en forme votre ticket en utilisant le Markdown : <a href="syntaxe" onclick="window.open(this.href); return false;">Syntaxe</a>.</p>
			<p><input class="btn btn-primary" type="submit" value="Modifier" /></p>
		</form>
	</div>
</div>
