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
    ?>
    <div class="navbar-collapse collapse">
    <form class="navbar-form navbar-right" role="form" method="post" action="login.php?action=in">
      <input type="hidden" name="form_sent" value="1" />
      <input type="hidden" name="redirect_url" value="<?php echo WWW_ROOT; ?>" />
     <div class="form-group">
       <input type="text" placeholder="Email" name="req_username" class="form-control">
     </div>
     <div class="form-group">
       <input type="password" placeholder="Password" name="req_password" class="form-control">
     </div>
     <button type="submit" class="btn btn-success">Sign in</button>
    </form>
    </div>
    <?php
  }
  else {
    echo '<p class="navbar-text navbar-right"><a href="user/'.$user['id'].'">'.$user['username'].'</a> <a href="login.php?action=out&amp;id='.$user['id'].'&amp;csrf_token='.pun_hash($user['id'].pun_hash(get_remote_address())).'" title="Se déconnecter"><span class="glyphicon glyphicon-off"></span></a></p>';
    ?>
    <form class="navbar-form navbar-right" role="form" method="post" action="film/search">
      <input type="text" class="form-control" name="keyword" id="keyword" placeholder="Mots-clé" value="<?php echo (isset($keyword) ? str_replace('"', '', $keyword) : ''); ?>">
     <button type="submit" class="btn btn-success">Chercher</button>
    </form>
    <?php
  }
  ?>
    </div>
</div>
<div class="container">
