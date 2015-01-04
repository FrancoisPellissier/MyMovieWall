 <div class="row">
 <?php
 $boucle = array(
 	array('title' => 'Liste d\'amis', 'array' => $friends),
 	array('title' => 'Ami de', 'array' => $isfriend)
 	);

foreach($boucle AS $iteration) {
	echo "\n\t".'<div class="col-sm-12 col-md-6">';
    echo "\n\t\t".'<h3>'.$iteration['title'].'</h3>';

	if(!empty($iteration['array'])) {
		echo "\n\t\t".'<table class="table table-striped">';
		echo "\n\t\t".'<thead>';
		echo "\n\t\t\t".'<tr>'."\n\t\t\t\t".'<th>Nom</th>';
		echo "\n\t\t\t\t".'<th>Bibliothèque</th>';
		echo "\n\t\t\t\t".'<th>Vus</th>'."\n\t\t\t".'</tr>';
		echo "\n\t\t".'</thead>';
		echo "\n\t\t".'<tbody>';
		foreach($iteration['array'] AS $friend) {
			echo "\n\t\t\t".'<tr>';
			echo "\n\t\t\t\t".'<td><a href="'.WWW_ROOT.'user/'.$friend['id'].'">'.$friend['realname'].'</a></td>';	
			echo "\n\t\t\t\t".'<td>'.(isset($infos[$friend['id']]['biblio']) ? $infos[$friend['id']]['biblio'] : 0).'</td>';
			echo "\n\t\t\t\t".'<td>'.(isset($infos[$friend['id']]['view']) ? $infos[$friend['id']]['view'] : 0).'</td>';
			echo "\n\t\t\t".'</tr>';
		}
		echo "\n\t\t".'</tbody>';
		echo "\n\t\t".'</table>';
		}
    echo "\n\t".'</div>';
	}
?>
</div>
