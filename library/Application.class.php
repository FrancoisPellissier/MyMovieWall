<?php
namespace library;

class Application {
	protected $httpRequest;


	public function __construct() {
		 $this->httpRequest = new HTTPRequest();
	}

	public function run() {
		// echo $this->httpRequest->requestURI();

		$this->migrate();

		$controller = $this->getController();
    	$controller->execute();
	}

	private function migrate() {
		global $db;

		$tables = array();

		$tables['genre'] = "CREATE TABLE IF NOT EXISTS `genre` ( `genreid` int(11) NOT NULL AUTO_INCREMENT, `genrename` varchar(255) NOT NULL, `code` int(11) NOT NULL, `created_at` datetime DEFAULT NULL, `updated_at` datetime DEFAULT NULL, PRIMARY KEY(genreid) ) ENGINE=InnoDB DEFAULT CHARSET=utf8";
 		$tables['movie'] = "CREATE TABLE IF NOT EXISTS `movie` ( `movieid` int(11) NOT NULL AUTO_INCREMENT, `titrevo` varchar(255) NOT NULL DEFAULT '', `titrevf` varchar(255) NOT NULL DEFAULT '', `datesortie` date DEFAULT NULL, `duree` int(11) NOT NULL DEFAULT '0', `synopsis` text, `realisateur` varchar(255) NOT NULL DEFAULT '', `acteur` varchar(255) NOT NULL DEFAULT '', `code` int(11) NOT NULL, `created_at` datetime DEFAULT NULL, `updated_at` datetime DEFAULT NULL, PRIMARY KEY(movieid) ) ENGINE=MyISAM DEFAULT CHARSET=utf8";
 		$tables['movie_genre'] = "CREATE TABLE IF NOT EXISTS `movie_genre` ( `movieid` int(11) NOT NULL DEFAULT '0', `genreid` int(11) NOT NULL DEFAULT '0', PRIMARY KEY (movieid, genreid) ) ENGINE=MyISAM DEFAULT CHARSET=utf8";
 		$tables['movie_person'] = "CREATE TABLE IF NOT EXISTS `movie_person` ( `roleid` int(11) NOT NULL AUTO_INCREMENT, `movieid` int(11) NOT NULL DEFAULT '0', `personid` int(11) NOT NULL DEFAULT '0', `type` enum('1','2') DEFAULT '1' COMMENT '1 : acteur, 2 : realisateur', `role` varchar(255) NOT NULL DEFAULT '', PRIMARY KEY(roleid) ) ENGINE=MyISAM DEFAULT CHARSET=utf8";
 		$tables['person'] = "CREATE TABLE IF NOT EXISTS `person` ( `personid` int(11) NOT NULL AUTO_INCREMENT, `fullname` varchar(255) NOT NULL, `code` int(11) NOT NULL, `created_at` datetime DEFAULT NULL, `updated_at` datetime DEFAULT NULL, PRIMARY KEY (personid) )ENGINE=InnoDB DEFAULT CHARSET=utf8";
 		$tables['stats_log'] = "CREATE TABLE IF NOT EXISTS `stats_log` ( `logid` int(11) NOT NULL AUTO_INCREMENT, `tablename` varchar(255) NOT NULL DEFAULT '', `tableid` int(11) NOT NULL DEFAULT '0', `ip` varchar(255) NOT NULL DEFAULT '', `userid` int(11) NOT NULL DEFAULT '0', `created_at` datetime DEFAULT NULL, `updated_at` datetime DEFAULT NULL, PRIMARY KEY(logid) ) ENGINE=MyISAM DEFAULT CHARSET=utf8";

 		foreach($tables AS $table => $sql) {

 			if(!$db->table_exists($table))
 				$db->query($sql);
 		}
 
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