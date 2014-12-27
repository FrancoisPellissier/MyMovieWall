<?php
// Header du site
require_once('assets/layouts/header.php');

// Menu du site
require_once('assets/layouts/menu.php');

// Sidebar
require_once('assets/layouts/sidebar.php');

// Contenu du site
require_once($view);

// Footer du site
require_once('assets/layouts/footer.php');
?>