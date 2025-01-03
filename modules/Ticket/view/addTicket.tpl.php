<div class="row">
	<div class="col-xs-8 col-sm-8 col-md-8">
		<div class="panel panel-default">
			<div class="panel-heading">Ouvrir un ticket</div>
			<div class="panel-body">
				<form method="post" action="ticket/add/post">
					<input type="text" class="form-control" id="ticketname" name="ticketname" placeholder="Titre du ticket">

					<p><br />Type : <select name="typeid">
						<?php
						foreach($type AS $key=>$name)
							echo "\n\t\t\t\t\t\t".'<option value="'.$key.'">'.$name.'</option>';
						?>
						</select></p>
					
					<textarea name="message" class="form-control" rows="8" placeholder="Votre message ici"></textarea>
					<p>Vous pouvez mettre en forme votre ticket en utilisant le Markdown : <a href="syntaxe" onclick="window.open(this.href); return false;">Syntaxe</a>.</p>
					<div class="checkbox">
					   <label><input type="checkbox" name="subscribe"<?php echo ($user['notif_ticket'] ? ' checked' : ''); ?>> Être prévenu par email des mises à jour du ticket.</label>
					 </div>
					<p><input class="btn btn-primary" type="submit" value="Créer" /></p>
				</form>
			</div>
		</div>
	</div>
</div>