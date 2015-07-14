<div class="row">
	<div class="col-xs-8 col-sm-8 col-md-8">
		<div class="panel panel-default">
			<div class="panel-heading">Message d'origine, le 
			</div>
			<div class="panel-body">
				<p><?php echo pun_htmlspecialchars($comment['message']); ?></p>
			</div>
		</div>
	</div>
</div>
<div class="row">
	<div class="col-xs-8 col-sm-8 col-md-8">
		<form method="post" action="ticket/<?php echo $ticket['ticketid'] ?>/comment/<?php echo $comment['commentid'] ?>/edit">
			<textarea name="message" class="form-control" rows="8"><?php echo pun_htmlspecialchars($comment['message']); ?></textarea>
			<p><br /><input class="btn btn-primary" type="submit" value="Modifier" /></p>
		</form>
	</div>
</div>
