<?php
// ID à utiliser en fonction du visiteur / page
// On visite le profile de quelqu'un ?
if(isset($curUser))
  $userid = $curUser['id'];
// Sinon, est-on visiteur non connecté ?
else if($user['is_guest']) {
  $curUser = array('id' => 2, 'realname' => 'François');
  $userid = $curUser['id'];
}
// Cas normal, c'est nous
else {
  $curUser = $user;
  $userid = $user['id'];
}


$side = array();

// Gestion du profil
$nav = array();
$nav[] = array('guest' => false, 'me' => true, 'url' => 'user/'.$user['id'].'/edit', 'title' => '<span class="glyphicon glyphicon-cog"></span> Général', 'item' => 'general');
$nav[] = array('guest' => false, 'me' => true, 'url' => 'theater', 'title' => '<span class="glyphicon glyphicon glyphicon-facetime-video"></span> Cinémas', 'item' => 'theater');
$nav[] = array('guest' => false, 'me' => true, 'url' => 'user/'.$user['id'].'/notification', 'title' => '<span class="glyphicon glyphicon-envelope"></span> Notifications', 'item' => 'notification');
$nav[] = array('guest' => false, 'me' => true, 'url' => 'user/'.$user['id'].'/right', 'title' => '<span class="glyphicon glyphicon-eye-close"></span> Droits', 'item' => 'right');
$titre = $user['realname'];
$url = 'user/'.$user['id'].'/edit';
$side['profil'] = array('url' => $url,'titre' => $titre, 'navs' => $nav);

// Menu pour les tickets
$nav = array();
$nav[] = array('guest' => true, 'me' => true, 'url' => 'ticket', 'title' => 'Activité récente', 'item' => 'recent');
$nav[] = array('guest' => true, 'me' => true, 'url' => 'ticket/list', 'title' => 'Tous les tickets', 'item' => 'all');

$titre = 'Ticket';
$url = 'ticket';
$side['ticket'] = array('url' => $url, 'titre' => $titre, 'navs' => $nav);

// Reste du site
$nav = array();
$nav[] = array('guest' => false, 'me' => true, 'url' => 'user/'.$userid.'/biblio', 'title' => '<span class="glyphicon glyphicon-film"></span> Vidéothèque', 'item' => 'biblio');
$nav[] = array('guest' => true, 'me' => true, 'url' => 'user/'.$userid.'/towatchlist', 'title' => '<span class="glyphicon glyphicon-play"></span>  Films à voir', 'item' => 'towatchlist');
$nav[] = array('guest' => true, 'me' => true, 'url' => 'user/'.$userid.'/lastview/cinema', 'title' => '<span class="glyphicon glyphicon-eye-open"></span> Derniers vus', 'item' => 'lastview');
$nav[] = array('guest' => false, 'me' => true, 'url' => 'user/'.$userid.'/wishlist', 'title' => '<span class="glyphicon glyphicon glyphicon-gift"></span> Wishlist', 'item' => 'wishlist');
// $nav[] = array('guest' => false, 'me' => false, 'url' => 'friend', 'title' => '<img src="img/icons/friend.png" height="12" /> Amis', 'item' => 'friend');
$nav[] = array('guest' => false, 'me' => false, 'url' => 'friend', 'title' => '<span class="glyphicon glyphicon-user"></span> Amis', 'item' => 'friend');
$nav[] = array('guest' => false, 'me' => true, 'url' => 'user/'.$userid.'/stats', 'title' => '<span class="glyphicon glyphicon-signal"></span> Statistiques', 'item' => 'stats');

$titre = (isset($curUser) ? $curUser['realname'] : $curUser['realname']);
$url = 'user/'.$userid;
$side['site'] = array('url' => $url, 'titre' => $titre, 'navs' => $nav);

// Il faut afficher un menu ?
if(isset($side[$side_section])) {
?>
<div class="container-fluid">
  <div class="row">
    <div class="col-sm-3 col-md-2 sidebar">
      <p><a href="<?php echo $side[$side_section]['url'] ?>"><?php echo $side[$side_section]['titre'] ?></a></p>
      <ul class="nav nav-sidebar">
        <?php
        foreach($side[$side_section]['navs'] as $id => $value) {
          // Soit on est connecté / soit la section est accessible aux invités
          // Soit on visite un autre propre profil / soit la section ne s'affiche que pour moi
          if((!$user['is_guest'] OR $value['guest']) AND ($curUser['id'] == $user['id'] OR $value['me']))
            echo "\n\t\t".'<li'.($value['item'] == $side_item ? ' class="active"' : '').'><a href="'.WWW_ROOT.$value['url'].'">'.$value['title'].'</a></li>';
        }
          if($user['id'] == 2 && $curUser['id'] == 2)
            echo "\n\t\t".'<li><a href="'.WWW_ROOT.'film/cron/update"><span class="glyphicon glyphicon-refresh"></span> Mise à jour (Admin)</a></li>';
        ?>
      </ul>
      <?php
      // Section site et on regarde un autre profil ?
      if($side_section == 'site' && isset($curUser) && !$user['is_guest'] && $user['id'] != $curUser['id']) {
        echo "\n\t\t".'<p><a href="'.WWW_ROOT.'user/'.$user['id'].'">'.$user['realname'].'</a></p>';
        /*
        ?>
        <ul class="nav nav-sidebar">
        <?php
        foreach ($side[$side_section]['navs'] as $id => $value) {
          echo "\n\t\t".'<li><a href="'.WWW_ROOT.str_replace('user/'.$userid, 'user/'.$user['id'], $value['url']).'">'.$value['title'].'</a></li>';
          }
        ?>
        </ul>
        <?php
        */
      }
      ?>
      <footer>
        <p>
        <?php
        if(!$user['is_guest'])
          echo '<a href="ticket">Bugs / Suggestions</a><br />';
        ?>
          <a href="about" title="A propros">A propos</a> - <a href="mentions" title="Mentions légales">Mentions légales</a>
        </p>
      </footer>
    </div>
  <?php
}
?>
    <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">

<?php
  if($_SERVER['SERVER_NAME'] != 'mymoviewall.com' && $_SERVER['SERVER_NAME'] != 'localhost') {
    echo "\n\t".'<div class="alert alert-info" role="alert">Le site a changé d\'adresse, vous pouvez y accéder via <a href="http://www.mymoviewall.com">www.MyMovieWall.com</a>. Si vous voyez ce message, c\'est qu\'il est temps de mettre à jour vos favoris !</div>';
  }