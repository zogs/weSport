<?php 
class EventsController extends Controller{

	public $primaryKey = 'id';


	public function index($params = null){

		$this->view = 'events/index';
		$this->layout = 'none';
		$this->loadModel('Events');
		$this->loadModel('Worlds');
		

		
		if(!empty($params)){

			//get params from pagesController home 
			if(is_array($params)){ 

				$day = $params['date'];				
			}
			//get params from get ajax request
			if(is_string($params)) {

				//nombre de jour en plus ou moins
				$delta_days = $params;
				//get date from cookie
				$date = $this->cookieEventSearch->read('date');				
				//set new date
				$day = date('Y-m-d', strtotime($date." ".$delta_days." day"));				
				//get params from cookie
				$params = $this->cookieEventSearch->arr();
				//set new param date
				$params['date'] = $day;
				//rewrite the cookie
				$this->cookieEventSearch->write($params);
			}
		}			

		//if city is entered
		if(!empty($params['cityID'])){
			//set location for the model
			$params['location'] = array('city'=>$params['cityID']);
		}

		//if extend to city arroud
		if(!empty($params['extend']) && $params['extend'] != ' '){			
			if(!empty($params['cityID'])){

				//find latitude longitude of the city
				$city = $this->Worlds->findFirst(array('table'=>'world_cities','fields'=>array('LATITUDE','LONGITUDE'),'conditions'=>array('UNI'=>$params['cityID'])));
				//and set params for the model
				$params['cityLat'] = $city->LATITUDE;
				$params['cityLon'] = $city->LONGITUDE;
			}
			else debug('city missing');
		}
		else unset($params['extend']); //unset extend for the model

		//
		$params['fields'] = 'E.id, E.user_id, E.city, E.sport, E.date, E.time, E.title, E.slug';


		//initialize variable for days loop
		$num_days = 7;
		$nextday = $day;
		$events = array();
		$dayevents = array();
		//for each days , get the events
		for($i=0; $i< $num_days; $i++){
 
			//set date param
			$params['date'] = array('day'=> $nextday) ;
			//find events in db
			$dayevents = $this->Events->findEvents($params);
			$dayevents = $this->Events->JOIN('users','login,avatar,age',array('user_id'=>':user_id'),$dayevents);		
			$dayevents = $this->Worlds->JOIN_GEO($dayevents);
			$dayevents = $this->Events->joinEventsParticipants($dayevents);
			$dayevents = $this->Events->joinUserParticipation($dayevents,$this->session->user_id());
			$events[$nextday] = $dayevents;
			//set next day
			$nextday = date("Y-m-d", strtotime($day. " +".$i." day"));
		}


		$d['events'] = $events;

		$this->set($d);
		$this->render();
	}





	public function view($id = null,$slug = null){

		$this->loadModel('Events');
		$this->loadModel('Worlds');

		if(!isset($id) || !is_numeric($id)) return false;

		$event = $this->Events->findFirst(array('conditions'=>array('id'=>$id)));	

		if(empty($event)) $this->e404("Cet événement n'existe pas");

		if($event->slug != $slug) $this->redirect('events/view/'.$event->id.'/'.$event->slug);

		$event = $this->Events->JOIN('users','login,avatar,age',array('user_id'=>':user_id'),$event);
		$event = $this->Worlds->JOIN_GEO($event);
		$event = $this->Events->joinEventsParticipants($event);
		$event = $this->Events->joinUserParticipation($event,$this->session->user_id());
		 // debug($event);


		$d['event'] = $event;
		$this->set($d);

	}

	public function addParticipant(){

		$this->loadModel('Events');
		$this->loadModel('Users');
		$this->view = 'none';
		$this->layout = 'none';

		if($this->request->get()){

			$data = $this->request->get();

			//Si les donnees sont bien numerique
			if(!is_numeric($data->user_id) || !is_numeric($data->event_id)) debug('!is_numeric');

			//Si l'user correspond bien à la session
			if($data->user_id!=$this->session->user_id()) debug("L'user est différent de session->user");
				
			//On vérifie si l'événement existe bien
			$event = $this->Events->findFirst(array('fields'=>'id,date,slug','conditions'=>array('id'=>$data->event_id)));
			if(empty($event)) debug("L'évenement n'existe pas");

			//On vérifie si l'user existe bien
			$user = $this->Users->findFirst(array('fields'=>'user_id','conditions'=>array('user_id'=>$data->user_id)));
			if(empty($user)) debug("L'user n'existe pas");

			//On vérifie qu'il ne participe pas déja
			$check = $this->Users->findFirst(array('table'=>'sporters','fields'=>'id','conditions'=>array('user_id'=>$data->user_id,'event_id'=>$data->event_id)));
			if(!empty($check)) {
				$this->session->setFlash("Tu participe déjà !","info");				
			}

			if($this->Events->saveParticipants($user,$event)){
				
				$this->session->setFlash(":) viens faire du sport avec nous !!!","success");
			}
			else
				debug('error while saving...');
				
		
			$this->redirect('events/view/'.$event->id.'/'.$event->slug);		
		}
	}

	public function removeParticipant(){

		$this->loadModel('Events');
		$this->loadModel('Users');
		$this->view = 'none';
		$this->layout = 'none';

		if($this->request->get()){

			$data = $this->request->get();

			//Si les donnees sont bien numerique
			if(!is_numeric($data->user_id) || !is_numeric($data->event_id)) debug('!is_numeric');

			//Si l'user correspond bien à la session
			if($data->user_id!=$this->session->user_id()) exit("L'user est différent de session->user");
				
			//On vérifie si l'événement existe bien
			$event = $this->Events->findFirst(array('fields'=>'id,date,slug','conditions'=>array('id'=>$data->event_id)));
			if(empty($event)) exit("L'évenement n'existe pas");

			//On vérifie si l'user existe bien
			$user = $this->Users->findFirst(array('fields'=>'user_id','conditions'=>array('user_id'=>$data->user_id)));
			if(empty($user)) exit("L'user n'existe pas");

			//On vérifie qu'il participe
			$check = $this->Users->findFirst(array('table'=>'sporters','fields'=>'id','conditions'=>array('user_id'=>$data->user_id,'event_id'=>$data->event_id)));
			if(!empty($check)) {
				
				$p = new StdClass();
				$p->table = "sporters";
				$p->key = 'id';
				$p->id = $check->id;
				$this->Events->delete($p);

				$this->session->setFlash(":( motive toi !","warning");		
			}			
						
			$this->redirect('events/view/'.$event->id.'/'.$event->slug);		
		}
	}
	

	public function create($event_id = 0){

		$this->loadModel('Events');
		$this->loadJS = 'js/jquery/jquery.autocomplete.js';


		//if user is logged
		if(!$this->session->islogged()) {

			$this->session->setFlash("Vous devez vous connecter pour proposer un événement !","info");
			$this->redirect('users/login');
		}

		
		//if event exist
		if($event_id!=0){

			//check if user is admin
			$evt = $this->Events->findFirst(array('conditions'=>array('id'=>$event_id)));

			if($evt->user_id == $this->session->user('user_id'))
				$isAdmin = true;
			else
				$isAdmin = false;

			if(!$isAdmin){
				
				$this->session->setFlash("Vous n'êtes pas le créateur de cette annonce","error");				
				$this->redirect('users/login');							
			}
		
		}
		else{
			//by default
			$evt = new StdClass();
			$evt->id = 0;
			$evt->sport = 0;
			$evt->city = '';
		}
		
		if($this->request->post()){				

			//data to save		
			$data = $this->request->data;
			$data->city = $data->cityID;
			$data->slug = slugify($data->title);
			unset($data->cityID);
			unset($data->cityName);

				if($this->Events->validates($data)){
													
					//save
					if($this->Events->save($data)){

						$this->session->setFlash("L'annonce a bien été enregistré, elle est visible dès maintenant");
					}
					else{
						$this->session->setFlash("Il ya une erreur lors de la sauvegarde. Essaye encore","error");
					}
					if(isset($this->Events->id))
						$event_id = $this->Events->id;
					
					
					
					//$this->redirect('events/view/'.$event_id);
				}
				else{

					$this->session->setFlash("Veuillez revoir votre formulaire",'error');
				}
			$evt = $this->Events->findFirst(array('conditions'=>array('id'=>$event_id)));
		
		}
		else {							
				
				$this->request->data = $evt;//send data to form class
				$this->session->setFlash("Vous pouvez créer une annonce","info");	
		}
		
		$d['event'] = $evt;

		$this->set($d);
	}




} ?>