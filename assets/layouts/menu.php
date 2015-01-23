<?php
// Initialisation des items de menu
$navs = array();
$navs[] = array('guest' => true, 'url' => '', 'title' => 'Accueil', 'item' => 'index');
$navs[] = array('guest' => true, 'url' => 'film', 'title' => 'Tous les films', 'item' => 'film_index');
?>

<div class="navbar navbar-inverse navbar-fixed-top" role="navigation">
  <div class="container">
    <!--<a class="navbar-brand" href="<?php echo WWW_ROOT; ?>">Movie</a>-->
    <ul class="nav navbar-nav">
    <?php
    foreach ($navs as $id => $value) {
      if(!$user['is_guest'] OR $value['guest'])
        echo "\n\t\t".'<li'.($value['item'] == $menu_actif ? ' class="active"' : '').'><a href="'.WWW_ROOT.$value['url'].'">'.$value['title'].'</a></li>';
    }
    ?>
  </ul>

  <?php
  if($user['is_guest']) {
     echo '<p class="navbar-text navbar-right"><a href="login">Connexion <span class="glyphicon glyphicon-off"></span></a></p>';
  }
  else {
    echo '<p class="navbar-text navbar-right"><a href="user/'.$user['id'].'/edit" title="Profil">'.$user['realname'].'</a>&nbsp;&nbsp;&nbsp;<a href="friend" title="Amis"><span class="glyphicon glyphicon-user"></span></a>&nbsp;&nbsp;&nbsp;<a href="logout/'.$user['id'].'/'.pun_hash($user['id'].pun_hash(get_remote_address())).'" title="Se déconnecter"><span class="glyphicon glyphicon-off"></span></a></p>';
  }
    ?>
    <form class="navbar-form navbar-right" role="form" method="post" action="film/search">
      <input type="text" class="form-control" name="keyword" id="keyword" placeholder="Mots-clé" value="<?php echo (isset($keyword) ? str_replace('"', '', $keyword) : ''); ?>">
     <button type="submit" class="btn btn-success">Chercher</button>
    </form>
    </div>
</div>
<!--
<div class="container">
-->