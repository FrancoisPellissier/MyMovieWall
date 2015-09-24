<?php
if(isset($valid))
	echo '<div class="alert alert-success" role="alert">Les modifications ont bien été prises en compte.</div>';
?>

<h3>Notifications</h3>
<form class="form-profil-notif" role="form" method="post" action="">
	<input type="hidden" name="form_sent" value="1" />
	<div class="form-group">
		<div class="checkbox">
		  <label>
		    <input name="notif_friend" type="checkbox"<?php echo($user['notif_friend'] ? ' checked' : ''); ?>> Être prévenu quand on m'ajoute à une liste d'amis
		  </label>
		</div>
	</div>
	<div class="form-group">
		<div class="checkbox">
		  <label>
		    <input name="notif_ticket" type="checkbox"<?php echo($user['notif_ticket'] ? ' checked' : ''); ?>> S'abonner automatiquement aux tickets que je créé et commente
		  </label>
		</div>
	</div>

	<button type="submit" class="btn btn-primary">Valider</button>
</form>