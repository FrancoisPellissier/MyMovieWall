<?php
namespace library;

class Application {
	protected $httpRequest;


	public function __construct() {
		 $this->httpRequest = new HTTPRequest();
	}

	public function run() {
		$this->migrate();

		$controller = $this->getController();
    	$controller->execute();
	}

	private function migrate() {
		global $db;

		$tables = array();
		/*
		$tables['users_rate'] = "CREATE TABLE `users_rate` (`userid` int(11) NOT NULL, `movieid` int(11) NOT NULL DEFAULT '0', `rate` TINYINT NOT NULL DEFAULT 0, `created_at` datetime DEFAULT NULL, `updated_at` datetime DEFAULT NULL, PRIMARY KEY (`userid`,`movieid`)) ENGINE=MyISAM DEFAULT CHARSET=utf8;";
		
 		foreach($tables AS $table => $sql) {
 			if(!$db->table_exists($table))
 				$db->query($sql);
 		}
 		*/
	}

	public function getController() {
	    $router = new \library\Router;
	     
	    $xml = new \DOMDocument;
	    $xml->load(__DIR__.'/../assets/config/routes.xml');

	    // echo $this->httpRequest->requestURI();
	     
	    $routes = $xml->getElementsByTagName('route');
	     
	    // On parcourt les routes du fichier XML.
	    foreach ($routes as $route) {
	      $vars = array();
	       
	      // On regarde si des variables sont présentes dans l'URL.
	      if ($route->hasAttribute('vars')) {
	        $vars = explode(',', $route->getAttribute('vars'));
	      }
	       
	      // On ajoute la route au routeur.
	      $router->addRoute(new Route($route->getAttribute('url'), $route->getAttribute('module'), $route->getAttribute('action'), $vars));
	    }
	     
	    try {
	      // On récupère la route correspondante à l'URL.
	      $matchedRoute = $router->getRoute($this->httpRequest->requestURI());
	    }
	    catch (\RuntimeException $e) {
	      if ($e->getCode() == \Library\Router::NO_ROUTE) {
	        // Si aucune route ne correspond, c'est que la page demandée n'existe pas.
	         $matchedRoute = new Route($url, 'Error', 'error_404', array());
	        // $this->httpResponse->redirect404();
	      }
	    }
	     
	    // On ajoute les variables de l'URL au tableau $_GET.
	    $_GET = array_merge($_GET, $matchedRoute->vars());

	    // On instancie le contrôleur.
	    $controllerClass = '\modules\\'.$matchedRoute->module().'\\'.$matchedRoute->module().'Controller';

	    return new $controllerClass($this->httpRequest, $matchedRoute->module(), $matchedRoute->action());
	  }

	public function httpRequest() {
		return $this->httpRequest;
	  }
	   
	public function httpResponse() {
		return $this->httpResponse;
	  }
}