<?php
if($error) {
  ?>
<div class="alert alert-warning" role="alert">
  <p>L'adresse email que vous avez renseignée n'est pas présente dans la base utilisateur. Assurez-vous de l'avoir renseignée correctement.</p>
</div>
  <?php
}
else if($complete) {
	?>
<div class="alert alert-success" role="alert">
	<p>Votre demande de nouveau mot de passe a bien été prise en compte. Un email vous a été envoyé avec vos nouveaux identifiants de connexion.</p>
  <p>Vous pourrez changer votre mot de passe une fois connecté.</p>
</div>
	<?php
}
else {
?>
  <form class="form-signin" role="form" method="post" action="">
    <h3 class="form-signin-heading">Mot de passe oublié</h3>
    <input type="hidden" name="form_sent" value="1" />
    <input type="text" class="form-control" placeholder="Adresse email" name="email" required autofocus>
    <button class="btn btn-lg btn-primary btn-block" type="submit">Valider</button>
  </form>
<?php
}
?>