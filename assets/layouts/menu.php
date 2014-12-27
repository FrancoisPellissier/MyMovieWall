<?php
$navs = array();
$navs[] = array('url' => '', 'title' => 'Accueil', 'item' => 'index');

// Cas des visiteurs
if($user['is_guest']) {
  $navs[] = array('url' => 'user/2/biblio', 'title' => 'Mes films', 'item' => 'biblio');
  $navs[] = array('url' => 'user/2/lastview/cinema', 'title' => 'Vus récemment', 'item' => 'lastview');

}
// Cas des utilisateurs connectés
else {
  $navs[] = array('url' => 'user/'.$user['id'].'/biblio', 'title' => 'Mes films', 'item' => 'biblio');
  $navs[] = array('url' => 'user/'.$user['id'].'/towatchlist', 'title' => 'A voir', 'item' => 'towatchlist');
  $navs[] = array('url' => 'user/'.$user['id'].'/lastview/cinema', 'title' => 'Vus récemment', 'item' => 'lastview');
  $navs[] = array('url' => 'user/'.$user['id'].'/wishlist', 'title' => 'Wishlist', 'item' => 'wishlist');
}

// Liens globaux
$navs[] = array('url' => 'film', 'title' => 'Tous les films', 'item' => 'film_index');
// $navs[] = array('url' => 'about', 'title' => 'A propos', 'item' => 'about');

?>

<div class="navbar navbar-inverse navbar-fixed-top" role="navigation">
  <div class="container">
    <!--<a class="navbar-brand" href="<?php echo WWW_ROOT; ?>">Movie</a>-->
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
    echo '<p class="navbar-text navbar-right"><a href="user/'.$user['id'].'/edit">'.$user['realname'].'</a> <a href="logout/'.$user['id'].'/'.pun_hash($user['id'].pun_hash(get_remote_address())).'" title="Se déconnecter"><span class="glyphicon glyphicon-off"></span></a></p>';
  }
    ?>
    <form class="navbar-form navbar-right" role="form" method="post" action="film/search">
      <input type="text" class="form-control" name="keyword" id="keyword" placeholder="Mots-clé" value="<?php echo (isset($keyword) ? str_replace('"', '', $keyword) : ''); ?>">
     <button type="submit" class="btn btn-success">Chercher</button>
    </form>
    </div>
</div>
<div class="container">
