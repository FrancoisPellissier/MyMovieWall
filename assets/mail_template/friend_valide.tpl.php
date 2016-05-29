public function getTrailers() {
		$this->infos['trailer'] = array();

		// Si le film est sortie il y a plus d'un mois on attend 15 jours/1 mois
		if($this->infos['datesortie'] != '0000-00-00') {
			$sortie = new \Datetime($this->infos['datesortie'].' 00:00:00');
			dump($sortie->format('Y-m-d H:i:s'));

		}

		$date->add(new \DateInterval('P15D'));
		// Si le film est sortie il y a un mois d'un mois (ou futur), on attend 2 jours
		// On teste la dernière mise à jour des trailers pour les mettre à jour si plus de 2 jours
		$date = new \Datetime($this->infos['trailerdate']);
		$date->add(new \DateInterval('P2D'));

		if($date->format('Y-m-d H:i:s') <= date('Y-m-d H:i:s') || $this->infos['trailerdate'] == null || $this->infos['trailerdate'] == '0000-00-00 00:00:00') {
			$allocine = new \modules\Allocine\Allocine();
			$trailers = $allocine->getFilmTrailer($this->infos['code']);
			$this->assocTrailer($this->infos['movieid'], $trailers);
			
			// On indique la date de mise à jour des trailers
			$this->edit(array('trailerdate' => date('Y-m-d H:i:s')));
		}
		
		$trailers = new \modules\Trailer\Trailer();
		$this->infos['trailers'] = $trailers->getTrailersMovie($this->infos['movieid']);
	}