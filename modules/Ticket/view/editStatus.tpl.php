<div class="row">
	<div class="col-xs-8 col-sm-8 col-md-8">
		<div class="panel panel-default">
			<div class="panel-heading">Modifier le statut du ticket #<?php echo $ticket['ticketid'].' - '.pun_htmlspecialchars($ticket['ticketname']); ?></div>
			<div class="panel-body">
				<form method="post" action="ticket/<?php echo $ticket['ticketid']; ?>/status">
					<p><br />Type : <select name="statusid">
						<?php
						foreach($status AS $key=>$name)
							echo "\n\t\t\t\t\t\t".'<option value="'.$key.'"'.($key == $ticket['statusid'] ? ' selected="selected"': '').'>'.$name.'</option>';
						?>
						</select></p>
					<p><br /><input class="btn btn-primary" type="submit" value="Modifier" /></p>
				</form>
			</div>
		</div>
	</div>
</div>