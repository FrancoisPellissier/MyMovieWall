<?php
require_once('assets/config/config.php');

$app = new library\Application();
$app->run();

/*
$person = new modules\Person\Person;
$data = array(
  'fullname' => 'Daniel Radcliff',
  'code' => 61009);
$id = $person->add($data);
$person->exists($id);

$view = new library\View('index', 'index');
$view->with('person', $person->infos);
$view->make();
*/