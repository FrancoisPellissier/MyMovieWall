<div class="alert alert-info" role="alert">Cette page vous donne accès à l'ensemble des propositions d'évolution et rapports de bug soumis par les membres du site.</div>

<?php
if(!$user['is_guest']) {
	echo "\n\t".'<p><a class="btn btn-primary" href="ticket/add" role="button"><span class="glyphicon glyphicon-plus"></span> Ouvrir un ticket</a></p>';
}
?>

<table class="table table-striped">
<thead>
	<tr>
		<th>Titre</th>
		<th>Type</th>
		<th>Statut</th>
		<th>Posté par</th>
		<th>Créé le</th>
		<th>Mis à jour le</th>
	</tr>
</thead>
<tbody>
<?php
if(empty($tickets)) {
	?>
	<tr>
		<td colspan="6">Il n'y a aucun ticket pour le moment.</td>
	</tr>
	<?php
}
else {
	foreach($tickets AS $ticket) {
		switch ($ticket['statusid']) {
		    case 2:
		        $class = "info";
		        break;
		    case 3:
		        $class = "success";
		        break;
		    default:
		       $class = "";
		}

		echo "\n\t".'<tr class="'.$class.'">';
		echo "\n\t\t".'<td><a href="'.WWW_ROOT.'ticket/'.$ticket['ticketid'].'">'.pun_htmlspecialchars($ticket['ticketname']).'</a></td>';
		echo "\n\t\t".'<td>'.$ticket['typename'].'</td>';
		echo "\n\t\t".'<td>'.$ticket['statusname'].'</td>';
		echo "\n\t\t".'<td>'.$ticket['realname'].'</td>';
		echo "\n\t\t".'<td>'.$ticket['created_at'].'</td>';
		echo "\n\t\t".'<td>'.$ticket['updated_at'].'</td>';
		echo "\n\t".'</tr>';
	}
}
?>
</tbody>
</table>