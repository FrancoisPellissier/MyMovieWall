<table class="table table-striped">
<thead>
	<tr>
		<th>Nom</th>
		<th>Bibliothèque</th>
		<th>Vus</th>
		<th>Action</th>
	</tr>
</thead>
<tbody>
<?php
foreach($users AS $id => $cur) {
	if($user['id'] != $cur['id']) {
		echo "\n\t\t\t".'<tr>';
		echo "\n\t\t\t\t".'<td><a href="'.WWW_ROOT.'user/'.$cur['id'].'">'.$cur['realname'].'</a></td>';	
		echo "\n\t\t\t\t".'<td>'.(isset($infos[$cur['id']]['biblio']) ? $infos[$cur['id']]['biblio'] : 0).'</td>';
		echo "\n\t\t\t\t".'<td>'.(isset($infos[$cur['id']]['view']) ? $infos[$cur['id']]['view'] : 0).'</td>';
		echo "\n\t\t\t\t".'<td>';
		if(isset($friends[$cur['id']]))
			echo '<a href="'.WWW_ROOT.'friend/del/'.$cur['id'].'">Supprimer</a>';
		else
			echo '<a href="'.WWW_ROOT.'friend/add/'.$cur['id'].'">Ajouter</a>';
		echo '</td>';
		echo "\n\t\t\t".'</tr>';
	}
}
?>
</tbody>
</table>
