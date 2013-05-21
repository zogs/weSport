<?php

class PagesController extends Controller {


		public function home( $day = null ){					

			$this->loadModel('Events');
			$this->loadModel('Worlds');
			$this->loadJS = 'js/jquery/jquery.autocomplete.js';
		

			if($this->request->get()){
				
				foreach ($this->request->get() as $key => $value) {
					
					$params[$key] = $value;
				}				

			}
			else {
				if($day === null )
					$params['date'] = date('Y-m-d');
				else
					$params['date'] = $day;
				
				$params['sports'] = $this->cookieEventSearch->read('sports');
				$params['cityID'] = $this->cookieEventSearch->read('cityID');
				$params['cityName'] = $this->cookieEventSearch->read('cityName');
				$params['extend'] = $this->cookieEventSearch->read('extend');
				$params['location'] = array(
										'city' => $this->cookieEventSearch->read('city'),
										'CC1'=> $this->cookieEventSearch->read('CC1'),
										'ADM1'=> $this->cookieEventSearch->read('ADM1'),
										'ADM2'=> $this->cookieEventSearch->read('ADM2'),
										'ADM3'=> $this->cookieEventSearch->read('ADM3'),
										'ADM4'=> $this->cookieEventSearch->read('ADM4')
										);

			}

			$this->cookieEventSearch->write($params);		

			$d['params'] = $params;
			$d['sports_available'] = $this->Events->find(array('table'=>'sports','fields'=>array('sport_id','slug as name')));
			
			$this->set($d);

		}

		//===================
		// Permet de rentre une page
		// $param $id id du post dans la bdd
		public function view($id){

				//On charge le model
				$this->loadModel('Posts');
				//On utlise la methode findFirst du model
				$page = $this->Posts->findFirst(array(
					'conditions'=> array('id'=>$id,'online'=>1,'type'=>'page') //En envoyant les parametres
					));
				//Si le resultat est vide on dirige sur la page 404
				if(empty($page)){
					$this->e404('Page introuvable');
				}
				//Atttribution de l'objet $page a une variable page
				$this->set('page',$page);

				
		}

		//Permet de recuperer les pages pour le menu
		public function getMenu(){

			$this->loadModel('Posts');
			return $this->Posts->find(array(
				'conditions'=> array('online'=>1,'type'=>'page')

				));
		}


		public function blog(){

			$this->loadModel('Events');


			//EVENTS TO COME
			$eventsToCome = $this->Events->getEventsToCome('FR',10);

			//CREATE GOOGLE MAP
			//include google map API class
			require(LIB.DS.'GoogleMap'.DS.'GoogleMapAPIv3.class.php');

			$gmap = new GoogleMapAPI();
			$gmap->setDivId('eventsToCome');
			$gmap->setSize('100%','250px');
			$gmap->setLang('FR');
			$gmap->setZoom(5);
			$gmap->setCenter('FR');
			$gmap->setEnableWindowZoom(true);
			$gmap->setDisplayDirectionFields(true);
			$gmap->setDefaultHideMarker(false);			

			//CREATE MARKERS
			foreach ($eventsToCome as $event) {
				$event = $this->Events->JOIN('sports','slug as sport',array('sport_id'=>':sport'),$event);
				$gmap->addMarkerByAddress($event->address.' , '.$event->getCityName(), $event->title, "<img src='".$event->getSportLogo()."' width='40px' height='40px'/><strong>".$event->title."</strong> <p>sport : <em>".$event->sport."<em><br />Adresse: <em>".addslashes($event->address)."<br />Ville : <em>".$event->city."</em></p><p><small>".$event->description."</small></p>",$event->sport,$event->getSportLogo());
			}			
			$gmap->generate();
			$d['gmap'] = $gmap;
			

			$this->set($d);
		}
}

?>