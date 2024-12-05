<?php
echo '<p>Résultat de la recherche pour : '.$keywords.'</p>';
?>
<table class="table table-striped">
<thead>
	<tr>
		<th></th>
		<th>Nom</th>
		<th>Adresse</th>
		<th>Code Postal</th>
		<th>Ville</th>
	</tr>
</thead>
<tbody>
<?php
foreach($theaters AS $id => $cur) {
	echo "\n\t\t\t".'<tr>';

	if(isset($hasTheaters[$cur['code']]))
		echo "\n\t\t\t\t".'<td></td>';	
	else
		echo "\n\t\t\t\t".'<td><a href="theater/add/'.$cur['code'].'" class="addTheater" title="Ajouter ce cinéma aux Favoris"><span class="glyphicon glyphicon-plus"></span></a></td>';
	
	echo "\n\t\t\t\t".'<td>'.$cur['theatername'].'</td>';
	echo "\n\t\t\t\t".'<td>'.$cur['adress'].'</td>';
	echo "\n\t\t\t\t".'<td>'.$cur['zipcode'].'</td>';
	echo "\n\t\t\t\t".'<td>'.$cur['city'].'</td>';
	echo "\n\t\t\t".'</tr>';
}
?>
</tbody>
</table>
