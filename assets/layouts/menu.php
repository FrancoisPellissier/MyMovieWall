<?php
$navs = array();

$navs[] = array('url' => '/film', 'title' => 'Films', 'item' => 'film_index');

// Cas des visiteurs
if($user['[is_guest']) {
  

}
// Cas des utilisateurs connectés
else {
  // $navs = array('Films', 'Ma vidéothèque', 'Mes visionnages', 'Ajouter');
  // $navs[] = array('url' => '/film/add', 'title' => 'Ajouter', 'item' => 'film_add');
}

// $navs[] = array('url' => '/about', 'title' => 'A propos', 'item' => 'about');

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
    ?>
    <form class="navbar-form navbar-right" role="form" method="post" action="film/add">
      <input type="text" class="form-control" name="keyword" id="keyword" placeholder="Mots-clé">
     <button type="submit" class="btn btn-success">Chercher</button>
    </form>
    <?php
    echo '<p class="navbar-text navbar-right">'.$user['username'].' <a href="login.php?action=out&amp;id='.$pun_user['id'].'&amp;csrf_token='.pun_hash($pun_user['id'].pun_hash(get_remote_address())).'"><span class="glyphicon glyphicon-off"></span></a></p>';
  }
  ?>
  
  </div>
</div>
<div class="container">
