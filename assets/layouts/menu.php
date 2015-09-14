<?php
// Initialisation des items de menu
$navs = array();
// $navs[] = array('guest' => true, 'url' => '', 'title' => 'Accueil', 'texte' => '<span class="glyphicon glyphicon-home"></span> Accueil', 'item' => 'index');
$navs[] = array('guest' => true, 'url' => 'film', 'title' => 'Tous les films', 'texte' => 'Tous les films', 'item' => 'film_index');
$navs[] = array('guest' => true, 'url' => 'agenda', 'title' => 'Agenda', 'texte' => 'Agenda', 'item' => 'agenda_index');
// $navs[] = array('guest' => false, 'url' => 'ticket', 'title' => 'Tickets', 'texte' => 'Tickets', 'item' => 'ticket_index');

?>

<div class="navbar navbar-inverse navbar-fixed-top" role="navigation">
  <div class="container">
    <ul class="nav navbar-nav">
    <?php
    foreach ($navs as $id => $value) {
      if(!$user['is_guest'] OR $value['guest'])
        echo "\n\t\t".'<li'.($value['item'] == $menu_actif ? ' class="active"' : '').'><a href="'.WWW_ROOT.$value['url'].'" title="'.$value['title'].'">'.$value['texte'].'</a></li>';
    }
    ?>
  </ul>
  <?php
  if($user['is_guest']) {
     echo '<p class="navbar-text navbar-right"><a href="login">Connexion <span class="glyphicon glyphicon-off"></span></a></p>';
  }
  else {
    $icons = array();
    $icons[] = array('url' => 'logout/'.$user['id'].'/'.pun_hash($user['id'].pun_hash(get_remote_address())), 'title' => 'Se déconnecter', 'texte' => '<span class="glyphicon glyphicon-off"></span>');
    $icons[] = array('url' => 'user/'.$user['id'].'/edit', 'title' => 'Profil', 'texte' => '<span class="glyphicon glyphicon-cog"></span> '.$user['realname']);

    echo '<p id="navbar-icons" class="navbar-text navbar-right">';
    foreach($icons AS $icon) {
      echo "\n\t\t".'<span class="icon"><a href="'.$icon['url'].'" title="'.$icon['title'].'">'.$icon['texte'].'</a></span>';
    }
    echo '</p>';
  }
    ?>
    <form class="navbar-form navbar-right" role="form" method="post" action="film/search">
      <input type="text" class="form-control" name="keyword" id="keyword" placeholder="Mots-clé" value="<?php echo (isset($keyword) ? str_replace('"', '', $keyword) : ''); ?>">
     <button type="submit" class="btn btn-success">Chercher</button>
    </form>
    </div>
</div>
