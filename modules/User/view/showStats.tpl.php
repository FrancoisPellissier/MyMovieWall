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
