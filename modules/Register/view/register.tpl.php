<?php
if($complete) {
	?>
<div class="alert alert-success" role="alert">
	<p>Votre inscription a bien été prise en compte. Un email vous a été envoyé avec vos identifiants de connexion.</p>
  <p>Vous pourrez changer votre mot de passe une fois connecté.</p>
</div>
	<?php
}
else {
?>
  <form class="form-signin" role="form" method="post" action="">
    <h2 class="form-signin-heading">Inscription</h2>
    <input type="hidden" name="form_sent" value="1" />
    <input type="text" class="form-control" placeholder="Adresse email" name="email" required autofocus>
    <input type="text" class="form-control" placeholder="Nom" name="fullname" required autofocus>
    <button class="btn btn-lg btn-primary btn-block" type="submit">Valider</button>
  </form>
<?php
}
?>