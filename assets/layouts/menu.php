<?php
$navs = array();

$navs[] = array('url' => '/film', 'title' => 'Films');

// Cas des visiteurs
if($user['[is_guest']) {
  

}
// Cas des utilisateurs connectés
else {
  // $navs = array('Films', 'Ma vidéothèque', 'Mes visionnages', 'Ajouter');
  $navs[] = array('url' => '/film/add', 'title' => 'Ajouter');
}

$navs[] = array('url' => '/movie/about', 'title' => 'A propos');

?>

<div class="navbar navbar-inverse navbar-fixed-top" role="navigation">
  <div class="container">
    <a class="navbar-brand" href="/movie">Movie</a>
    <ul class="nav navbar-nav">
    <?php
    foreach ($navs as $id => $value) {
      echo "\n\t\t".'<li'.($id == 0 ? ' class="active"' : '').'><a href="/movie'.$value['url'].'">'.$value['title'].'</a></li>';
    }
    ?>
  </ul>

  <?php
  if($user['is_guest']) {
    ?>
    <div class="navbar-collapse collapse">
    <form class="navbar-form navbar-right" role="form" method="post" action="login.php?action=in">
      <input type="hidden" name="form_sent" value="1" />
      <input type="hidden" name="redirect_url" value="http://localhost/movie/" />
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
  else
    echo '<p class="navbar-text navbar-right">'.$user['username'].' <a href="login.php?action=out&amp;id='.$pun_user['id'].'&amp;csrf_token='.pun_hash($pun_user['id'].pun_hash(get_remote_address())).'"><span class="glyphicon glyphicon-off"></span></a></p>';
  ?>
  
  </div>
</div>
<div class="container">
