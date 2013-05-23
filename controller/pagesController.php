<?php

class PagesController extends Controller {


		public function home( $day = null ){					

			$this->loadModel('Events');
			$this->loadModel('Worlds');
			$this->loadJS = 'js/jquery/jquery.autocomplete.js';
		
			//date
			if($day === null )
				$params['date'] = date('Y-m-d');
			else
				$params['date'] = $day;

			//si une requete est passée
			if($this->request->get()){
				
				//on recupere les parametres
				foreach ($this->request->get() as $key => $value) {
					
					$params[$key] = $value;
				}	
				//si l'ID de la ville n'est pas fourni, on cherche une ville par son nom
				if(!isset($params['cityID'])) {					
					if(isset($params['cityName'])){
						$cities = $this->Worlds->suggestCities(array('CC1'=>'FR','prefix'=>$params['cityName'])); //on recupere les villes qui correspondent						
						if(!empty($cities)){
							$params['cityID'] = $cities[0]->city_id; //et on choisi la premiere ville
							$params['cityName'] = $cities[0]->name;
						}
					}
				}
			}

			//Sinon on utilise les parametres du cookie
			else {

				
				
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

			//on réécrit le cookie avec les nouveaux parametres
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
			$this->loadModel('Worlds');

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
				$event = $this->Worlds->JOIN_GEO($event);
				
				$full_address = $event->address.', '.$event->getCityName().', '.$event->firstRegion().', '.$event->getCountry();

				$gmap->addMarkerByAddress($event->address.' , '.$event->getCityName(), $event->title, "<img src='".$event->getSportLogo()."' width='40px' height='40px'/><strong>".$event->title."</strong> <p>sport : <em>".$event->sport."<em><br />Adresse: <em>".addslashes($event->address)."<br />Ville : <em>".$event->getCityName()."</em></p><p><small>".$event->description."</small></p>",$event->sport,$event->getSportLogo());
			}			
			$gmap->generate();
			$d['gmap'] = $gmap;
			

			$this->set($d);
		}

		public function contact(){


			if($this->request->post()){

				$data = $this->request->post();
				
				//Security against robot
				//Login filed must be empty
				if(!empty($data->login)) throw new zException("Robot used contact form - Login field must be empty", 1);
				if(empty($data->time)) throw new zException("Robot used contact form - Login time must not be empty", 1);
				if(abs(time()-$data->time) < 3) throw new zException("Robot used contact form - form filled too fast for human being", 1);				
				
				if($this->sendMailContact($data->name,$data->email,$data->title,$data->message)){

					$this->session->setFlash("Votre message a bien été envoyé","success");
				}
				
			}

			$this->loadModel('Users');
			$user = $this->Users->findFirstUser(array('conditions'=>array('user_id'=>$this->session->user()->getID())));
			$d['user'] = $user;

			$this->set($d);
		}

		public function sendMailContact($sender_name,$sender_mail,$title,$message){

			//Création d'une instance de swift mailer
	        $mailer = Swift_Mailer::newInstance(Conf::getTransportSwiftMailer());

	        //Récupère le template et remplace les variables
	        $body = file_get_contents('../view/email/contact.html');
	        $body = preg_replace("~{site}~i", Conf::$website, $body);
	        $body = preg_replace("~{title}~i", $title, $body);
	        $body = preg_replace("~{name}~i", $sender_name, $body);
	        $body = preg_replace("~{date}~i", Date::datefr(date('Y-m-d')), $body);
	        $body = preg_replace("~{message}~i", $message, $body);

	        //Création du mail
	        $message = Swift_Message::newInstance()
	          ->setSubject("Contact de - ".$sender_name)
	          ->setFrom('noreply@'.Conf::$websiteDOT, Conf::$website)
	          ->setTo(Conf::$contactEmail)
	          ->setBody($body, 'text/html', 'utf-8');          
	       
	        //Envoi du message et affichage des erreurs éventuelles
	        if (!$mailer->send($message, $failures))
	        {
	            echo "Erreur lors de l'envoi du email à :";
	            print_r($failures);
	            return false;
	        }
	        else return true;
			}
}

?>