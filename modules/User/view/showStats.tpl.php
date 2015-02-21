</p>
<?php
$liens = array();
$liens[] = array('type' => 'all', 'url' => 'user/'.$curUser['id'].'/stats', 'title' => 'Tout');
$liens[] = array('type' => '1', 'url' => 'user/'.$curUser['id'].'/stats/cinema', 'title' => 'Cinéma');
$liens[] = array('type' => '2', 'url' => 'user/'.$curUser['id'].'/stats/tele', 'title' => 'Télé');

foreach($liens AS $lien) {
	echo "\n\t".'<a href="'.$lien['url'].'"><button type="button" class="button '.($lien['type'] == $type ? 'button-success' : 'button-primary').'">'.$lien['title'].'</button></a> ';
}
?>
<p>

<table class="table table-striped">
<thead>
	<tr>
		<th>Année</th>
		<th>Jan</th>
		<th>Fev</th>
		<th>Mars</th>
		<th>Avr</th>
		<th>Mai</th>
		<th>Juin</th>
		<th>Juil</th>
		<th>Août</th>
		<th>Sept</th>
		<th>Oct</th>
		<th>Nov</th>
		<th>Dec</th>
		<th>Total</th>
	</tr>
</thead>
<tbody>
<?php
foreach($stats AS $annee => $stat) {
	$tot = 0;
	echo "\n\t".'<tr>';
	echo "\n\t".'<td>'.$annee.'</td>';
	
	for($i=1;$i<=12;$i++) {
		echo "\n\t".'<td>'.(isset($stat[$i]) ? $stat[$i] : '-').'</td>';
	$tot += isset($stat[$i]) ? $stat[$i] : 0;
	}
	echo "\n\t".'<td>'.$tot.'</td>';
	echo "\n\t".'</tr>';
}
?>
</tbody>
</table>


<div class="row">
<?php
$liste = array(
	array('titre' => 'Genre', 'array' => 'statsGenre', 'url' => 'film/genre/'),
	array('titre' => 'Acteurs', 'array' => 'statsActeur', 'url' => 'person/'),
	array('titre' => 'Réalisateurs', 'array' => 'statsRealisateur', 'url' => 'person/')
	);
foreach($liste AS $infos) {
?>
	<div class="col-xs-12 col-sm-4 col-md-4">
		<h3><?php echo $infos['titre']; ?></h3>
		<table class="table table-striped">
		<thead>
			<tr>
				<th><?php echo $infos['titre']; ?></th>
				<th>Nb vus</th>
			</tr>
		</thead>
		<tbody>
		<?php
		foreach(${$infos['array']} AS $stat) {
			echo "\n\t\t\t".'<tr>';
			echo "\n\t\t\t\t".'<td><a href="'.WWW_ROOT.$infos['url'].$stat['id'].'">'.$stat['libelle'].'</a></td>';
			echo "\n\t\t\t\t".'<td>'.$stat['nb'].'</td>';
			echo "\n\t\t\t".'</tr>';
		}
		?>
		</tbody>
		</table>

	</div>
<?php
}
?>
</div>