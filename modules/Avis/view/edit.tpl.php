<?php
echo "\n".'<h2>Modifiez votre avis sur "'.$film['titrevf'].'"</h2>';
?>

<div class="row">
	<div class="col-xs-8 col-sm-8 col-md-8">
		<form method="post" action="avis/edit/<?php echo $avis['avisid'] ?>">
			<textarea name="message" class="form-control" rows="20"><?php echo pun_htmlspecialchars($avis['message']) ?></textarea>
			<p>Vous pouvez mettre en forme votre ticket en utilisant le Markdown : <a href="syntaxe" onclick="window.open(this.href); return false;">Syntaxe</a>.</p>
			<p><input class="btn btn-primary" type="submit" value="Enregistrer" /></p>
		</form>
	</div>
</div>
