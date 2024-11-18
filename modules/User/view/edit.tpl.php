<?php
if($modifyRealname)
	echo '<div class="alert alert-success" role="alert">Votre nom a bien été modifié</div>';

if($modifyPassword)
	echo '<div class="alert alert-success" role="alert">Votre mot de passe a bien été modifié</div>';
?>


<form class="form-profil-edit" role="form" method="post" action="">
	<input type="hidden" name="form_sent" value="1" />
	<div class="form-group">
		<label for="realname">Nom</label>
		<input type="text" class="form-control" id="realname" name="realname" placeholder="Nom" value="<?php echo $user['realname'] ?>" length="50" />
	</div>
	<div class="form-group">
		<label for="password1">Nouveau mot de passe</label>
		<input type="password" class="form-control" id="password1" name="password1" placeholder="Mot de passe">
		<label for="password2">Confirmer le nouveau mot de passe</label>
		<input type="password" class="form-control" id="password2" name="password2" placeholder="Mot de passe">
	</div>
	<button type="submit" class="btn btn-primary">Valider</button>
</form>