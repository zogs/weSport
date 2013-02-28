<?php

class PagesController extends Controller {


		public function home(){					

			$this->loadModel('Events');
			$this->loadModel('Worlds');
			$this->loadJS = 'js/jquery/jquery.autocomplete.js';
		

			if($this->request->post()){
				
				foreach ($this->request->data as $key => $value) {
					
					$params[$key] = $value;
				}				

			}
			else {
				$params['sports'] = $this->cookieEventSearch->read('sports');
				$params['date'] = date("Y-m-d");
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
}

?>