<h2>Gestion des droits d'accès</h2>
<form class="form-profil-right" role="form" method="post" action="">
<input type="hidden" name="form_sent" value="1" />

<table class="table" style="width:500px;">
	<tr>
		<th>Section</th>
		<th>Invités</th>
		<th>Membres</th>
		<th>Amis</th>
	</tr>
<?php
// dump($user['right']);

$droits = array('guest', 'member', 'friend');

$sections = array(
	'biblio' => 'Vidéothèque',
	'towatchlist' => 'Films à voir',
	'lastview' => 'Derniers vus',
	'wishlist' => 'Whishlist',
	'stats' => 'Statistiques');

foreach($sections AS $section => $name) {
	echo "\n\t".'<tr>';
	echo "\n\t\t".'<td>'.$name.'</td>';

	foreach($droits AS $droit) {
		echo "\n\t\t".'<td><input type="checkbox" name="right['.$section.']['.$droit.']"'.($user['right'][$section][$droit] ? ' checked' : '').' /></td>';		
	}

	echo "\n\t".'</tr>';
}
?>
</table>
<button type="submit" class="btn btn-primary">Valider</button>
</form>