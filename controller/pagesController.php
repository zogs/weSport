<?php

class PagesController extends Controller {

		public function __construct($request = null){

			parent::__construct($request);

			if(isset($request)) $this->pagename = $request->action;
		}

		public function home( $day = null ){					

			$this->loadModel('Events');
			$this->loadModel('Worlds');
			$this->loadJS = array('js/jquery/jquery.touchSwipe.min.js','js/jquery/jquery.flowslider.js');
			$this->view = 'pages/home';
			
			//date
			if($day === null ){

					$params = $this->cookieEventSearch->arr();
					$params['date'] = date('Y-m-d');				

			}
			else {
				$params['date'] = $day;
				$cookie = $this->cookieEventSearch->arr();			
				$cookie['date'] = $day;						
				$this->cookieEventSearch->write($cookie);				
			}

			//On utilise par default les données du cookie
			$params['sports'] = $this->cookieEventSearch->read('sports');
			$params['cityID'] = $this->cookieEventSearch->read('cityID');
			$params['cityName'] = $this->cookieEventSearch->read('cityName');
			$params['extend'] = $this->cookieEventSearch->read('extend');
			$params['location'] = $this->cookieEventSearch->read('location');

			//Ou on utilise les données de la requete
			if($this->request->get()){
				
				//on recupere les parametres
				foreach ($this->request->get() as $key => $value) {					
					$params[$key] = $value;
				}	

				//If sport not defined set sport to zero
				if(!$this->request->get('sports')) $params['sports'] = '';

				//si l'ID de la ville n'est pas fourni, on cherche une ville par son nom
				if(empty($params['cityID'])) {					
					if(isset($params['cityName'])){
						$cities = $this->Worlds->suggestCities(array('CC1'=>'FR','prefix'=>$params['cityName'])); //on recupere les villes qui correspondent						
						if(!empty($cities)){
							$params['cityID'] = $cities[0]->city_id; //et on choisi la premiere ville
							$params['cityName'] = $cities[0]->name;
						}
					}
				}

				//if extend is not defined set it to zero
				if(!$this->request->get('extend')) $params['extend'] = '';


			}

			//On recupere le nom des regions
			$params['location'] = $this->Worlds->findCityById($params['cityID'],'CC1,ADM1,ADM2,ADM3,ADM4');
			$params['location'] = $this->Worlds->findStatesNames($params['location']);
			$params['location'] = (array) $params['location'];

			//on réécrit le cookie avec les nouveaux parametres
			$this->cookieEventSearch->write($params);		
			
			$d['params'] = $params;
			$d['sports_available'] = $this->Events->find(array('table'=>'sports','fields'=>array('sport_id','slug',$this->getLang().' as name')));			
			
			$this->set($d);

		}

		//===================
		// Permet de rentre une page
		// $param $id id du post dans la bdd
		public function view($slug){
		
			//Si le slug correspond a une page particuliere
			if(method_exists($this, $slug)){
				$method = $slug;
				$this->$method(); //lance la methode particuliere du controller
				return;
			}

			//On charge le model
			$this->loadModel('Pages');			
				
			//On cherche la page		
			if(!$this->request->get('lang'))
				$page = $this->Pages->findPageBySlug($slug);
			else {
				$page = $this->Pages->findPageBySlugAndLang($slug,$this->request->get('lang'));
			}

			//Si la page n'existe pas on redirige sur 404
			if(!$page->exist()){
				$this->e404('Page introuvable');
			}
			
			//On cherche le contenu
			$page = $this->Pages->JOIN_i18n($page,$page->lang);

			//Si la page a une methode particuliere
			if(method_exists($this, $page->slug)){
				$method = $page->slug;
				$this->$method();
				return;
			}

			//Si la traduction demandé n'existe pas on cherche la langue par default , si n'existe pas redirege 404
			if(!$page->isTraductionExist() || !$page->isTraductionValid()){
				$this->session->setFlash("La traduction demandé n'est pas disponible... <a href=".Router::url('pages/view/'.$page->slug.'/?lang='.$page->langDefault).">Cliquez ici</a> pour voir la page dans sa langue d origine ","warning");
				$this->e404('Page introuvable');
			}


			//Atttribution de l'objet $page a une variable page
			$this->set('page',$page);				
		}

		//Permet de recuperer les pages pour le menu
		public function getMenu($menu){

			$this->loadModel('Pages');

			//get requested lang
			$lang = $this->getLang();
			//search all pages to appears in menu
			$pages = $this->Pages->findMenu($menu);
			//debug($pages);			
			//find all traduction for requested language
			$pages = $this->Pages->JOINS_i18n($pages, $lang);
			
			//Unset page that have no traduction for requested lang
			foreach ($pages as $k => $page) {				
				if(!$page->isTraductionExist() || !$page->isTraductionValid() )  unset($pages[$k]);
			}

			//return pages if exist
			if(!empty($pages))
				return  $pages;	
			else 
				return array();				
		}


		public function admin_index($menu=null){

			$this->loadModel('Pages');

			if($this->request->post()){

				if($this->Pages->savePage($this->request->post())){

					$this->session->setFlash("Page sauvegardé !","success");
				}
				else
					$this->session->setFlash("message","type");
			}

			$lang = $this->getLang();			

			if(!isset($menu))
				$pages = $this->Pages->findPages();
			else
				$pages = $this->Pages->findMenu($menu);

			$traductions = $this->Pages->countPagesTraduction($pages);
			$pages = $this->Pages->JOINS_i18n($pages,$lang);	
			$menus = $this->Pages->findDistinctMenu();		

			if(empty($pages)) $pages = array();

			$d['traductions'] = $traductions;
			$d['menus'] = $menus;
			$d['pages'] = $pages;
			$d['lang'] = $lang;


			$this->set($d);			
		}

		public function admin_delete($id){

			$this->loadModel('Pages');

			if($this->Pages->deleteContent($id)){

				$this->session->setFlash("Page supprimé","success");

				if($this->Pages->deletei18nContents($id)){
					$this->session->setFlash("Traductions supprimés","success");
				}
				else {
					$this->session->setFlash("Error lors de la suppression des traductions","error");
				}				
			}
			else {
				$this->session->setFlash("Error lors de la suppression","error");
			}
			$this->redirect('admin/pages/index');
		}

		public function admin_edit($id = null){

			$this->loadModel('Pages');
			$d['id'] = $id;

			$lang = $this->getLang();		

			if($this->request->data){

				$new = $this->request->data;

				if($this->Pages->validates($new)){
					
					if($page_id = $this->Pages->savePage($new)){

						if($this->Pages->saveTraduction($new,$page_id)){

							$this->session->setFlash('Contenu modifié');
							$this->redirect('admin/pages/edit/'.$page_id.'?lang='.$lang);
						}
					}
				}

				//on recupere la langue des données envoyés
				$lang = $new->lang;
			}
							
			if($id){
				$c = $this->Pages->getContent($id);
				$c = $this->Pages->JOIN_i18n($c,$lang);

				$trad = $this->Pages->findTraduction($id);

				$d['id'] = $id;
				$d['trad'] = $trad;
				$d['content'] = $c;
				$this->request->data = $c;
				
			}

			

			$this->set($d);
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
				
				$event = $this->Events->joinSport($event,$this->getLang());
				$event = $this->Worlds->JOIN_GEO($event);
				
				$full_address = $event->address.', '.$event->getCityName().', '.$event->firstRegion().', '.$event->getCountry();

				$gmap->addMarkerByAddress($event->address.' , '.$event->getCityName(), $event->title, "<img src='".$event->getSportLogo()."' width='40px' height='40px'/><strong>".$event->title."</strong> <p>sport : <em>".$event->getSportName()."<em><br />Adresse: <em>".addslashes($event->address)."<br />Ville : <em>".$event->getCityName()."</em></p><p><small>".$event->description."</small></p>",$event->getSportSlug(),$event->getSportLogo());
			}			
			$gmap->generate();
			$d['gmap'] = $gmap;
			

			$this->set($d);
		}

		public function contact(){

			$this->view = 'pages/contact';
			$this->loadModel('Pages');
			$page = $this->Pages->findPageBySlugAndLang('contact',$this->getLang());
			$page = $this->Pages->JOIN_i18n($page,$this->getLang());
			$d['page'] = $page;


			if($this->request->post()){

				$data = $this->request->post();
				
				//Security against robot
				//Login filed must be empty
				if(!empty($data->login)) throw new zException("Robot used contact form - Login field must be empty", 1);
				if(empty($data->time)) throw new zException("Robot used contact form - Login time must not be empty", 1);
				if(abs(time()-$data->time) < 3) throw new zException("Robot used contact form - form filled too fast for human being", 1);				
				
				

				if($this->sendMailContact($data->name,$data->email,$data->title,$data->message) && $this->Pages->saveContactMessage($data))
					$this->session->setFlash("C'est gentil d'avoir laisser un petit mot !","success");				
				else
					$this->session->setFlash("Erreur lors de la sauvegarde du message",'error');
				
			}

			$this->loadModel('Users');
			$user = $this->Users->findFirstUser(array('conditions'=>array('user_id'=>$this->session->user()->getID())));
			if(empty($user)) $user = new User();
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