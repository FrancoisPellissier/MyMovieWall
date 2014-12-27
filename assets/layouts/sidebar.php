<?php
// ID à utiliser en fonction du visiteur
// dump($curUser['id']);

$userid = $curUser['id'];

// Initialisation des items de menu
$navs = array();
$navs[] = array('guest' => false, 'url' => 'user/'.$userid.'/edit', 'title' => 'Profil', 'item' => 'user_edit');
$navs[] = array('guest' => true, 'url' => 'user/'.$userid.'/biblio', 'title' => 'Mes films', 'item' => 'biblio');
$navs[] = array('guest' => true, 'url' => 'user/'.$userid.'/towatchlist', 'title' => 'Films à voir', 'item' => 'towatchlist');
$navs[] = array('guest' => true, 'url' => 'user/'.$userid.'/lastview/cinema', 'title' => 'Derniers vus', 'item' => 'lastview');
$navs[] = array('guest' => false, 'url' => 'user/'.$userid.'/wishlist', 'title' => 'Wishlist', 'item' => 'wishlist');
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
          if(!$user['is_guest'] OR $value['guest'])
            echo "\n\t\t".'<li'.($value['item'] == $menu_actif ? ' class="active"' : '').'><a href="'.WWW_ROOT.$value['url'].'">'.$value['title'].'</a></li>';
        }
        ?>
      </ul>
      <?php
      if(isset($curUser) && !$user['is_guest'] && $user['id'] != $curUser['id'])
        echo "\n\t\t".'<p><a href="'.WWW_ROOT.'user/'.$user['id'].'">'.$user['realname'].'</a></p>';
      ?>
    </div>
    <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">