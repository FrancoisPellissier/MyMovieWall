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
// Initialisation des items de menu
$navs = array();
$navs[] = array('guest' => true, 'me' => true, 'url' => 'user/'.$userid.'/biblio', 'title' => '<span class="glyphicon glyphicon-film"></span> Mes films', 'item' => 'biblio');
$navs[] = array('guest' => true, 'me' => true, 'url' => 'user/'.$userid.'/towatchlist', 'title' => '<span class="glyphicon glyphicon-play"></span>  Films à voir', 'item' => 'towatchlist');
$navs[] = array('guest' => true, 'me' => true, 'url' => 'user/'.$userid.'/lastview/cinema', 'title' => '<span class="glyphicon glyphicon-eye-open"></span> Derniers vus', 'item' => 'lastview');
$navs[] = array('guest' => false, 'me' => false, 'url' => 'user/'.$userid.'/wishlist', 'title' => '<span class="glyphicon glyphicon glyphicon-gift"></span> Wishlist', 'item' => 'wishlist');
$navs[] = array('guest' => false, 'me' => false, 'url' => 'user/'.$userid.'/stats', 'title' => '<span class="glyphicon glyphicon glyphicon-signal"></span> Statistiques', 'item' => 'stats');

?>

<div class="container-fluid">
  <div class="row">
    <div class="col-sm-3 col-md-2 sidebar">
      <?php
      if(isset($curUser))
        echo "\n\t\t".'<p><a href="'.WWW_ROOT.'user/'.$curUser['id'].'">'.$curUser['realname'].'</a></p>';
      else
        echo "\n\t\t".'<p><a href="'.WWW_ROOT.'user/'.$user['id'].'">'.$user['realname'].'</a></p>';
      ?>
      <ul class="nav nav-sidebar">
        <?php
        foreach ($navs as $id => $value) {
          // Invite : test guest / Ami : tout / Moi / me
          if((!$user['is_guest'] OR $value['guest']) AND ($curUser['id'] != $user['id'] OR $value['me']))
            echo "\n\t\t".'<li'.($value['item'] == $menu_actif ? ' class="active"' : '').'><a href="'.WWW_ROOT.$value['url'].'">'.$value['title'].'</a></li>';
        }
        ?>
      </ul>
      <?php
      if(isset($curUser) && !$user['is_guest'] && $user['id'] != $curUser['id']) {
        echo "\n\t\t".'<p><a href="'.WWW_ROOT.'user/'.$user['id'].'">'.$user['realname'].'</a></p>';
        ?>
        <ul class="nav nav-sidebar">
        <?php
        foreach ($navs as $id => $value) {
            if($value['me'])
            echo "\n\t\t".'<li><a href="'.WWW_ROOT.str_replace('user/'.$userid, 'user/'.$user['id'], $value['url']).'">'.$value['title'].'</a></li>';
          }
        ?>
        </ul>
        <?php
      }
      ?>
    </div>
    <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">