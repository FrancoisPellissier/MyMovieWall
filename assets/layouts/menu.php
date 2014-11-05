<?php
$navs = array();

$navs[] = array('url' => 'film', 'title' => 'Films', 'item' => 'film_index');

// Cas des visiteurs
if($user['[is_guest']) {
  

}
// Cas des utilisateurs connectés
else {
  // $navs = array('Films', 'Ma vidéothèque', 'Mes visionnages', 'Ajouter');
  // $navs[] = array('url' => 'film/add', 'title' => 'Ajouter', 'item' => 'film_add');
}

// $navs[] = array('url' => 'about', 'title' => 'A propos', 'item' => 'about');

?>

<div class="navbar navbar-inverse navbar-fixed-top" role="navigation">
  <div class="container">
    <a class="navbar-brand" href="<?php echo WWW_ROOT; ?>">Movie</a>
    <ul class="nav navbar-nav">
    <?php
    foreach ($navs as $id => $value) {
      echo "\n\t\t".'<li'.($value['item'] == $menu_actif ? ' class="active"' : '').'><a href="'.WWW_ROOT.$value['url'].'">'.$value['title'].'</a></li>';
    }
    ?>
  </ul>

  <?php
  if($user['is_guest']) {
     echo '<p class="navbar-text navbar-right"><a href="login">Connexion <span class="glyphicon glyphicon-off"></span></a></p>';
  }
  else {
    echo '<p class="navbar-text navbar-right"><a href="user/'.$user['id'].'">'.$user['username'].'</a> <a href="connexion.php?action=out&amp;id='.$user['id'].'&amp;csrf_token='.pun_hash($user['id'].pun_hash(get_remote_address())).'" title="Se déconnecter"><span class="glyphicon glyphicon-off"></span></a></p>';
  }
    ?>
    <form class="navbar-form navbar-right" role="form" method="post" action="film/search">
      <input type="text" class="form-control" name="keyword" id="keyword" placeholder="Mots-clé" value="<?php echo (isset($keyword) ? str_replace('"', '', $keyword) : ''); ?>">
     <button type="submit" class="btn btn-success">Chercher</button>
    </form>
    </div>
</div>
<div class="container">
