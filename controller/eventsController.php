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
		}
		else unset($params['extend']); //unset extend for the model

		//
		$params['fields'] = 'E.id, E.user_id, E.city, E.cityName, E.sport, E.date, E.time, E.title, E.slug';


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

		$event = $this->Events->findEventById($id);	
		
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

			if(!$this->session->isLogged()) throw new zException('User must log in before trying to participate',1);

			//Si les donnees sont bien numerique
			if(!is_numeric($data->user_id) || !is_numeric($data->event_id)) throw new zException('user_id or event_id is not numeric',1);

			//Si l'user correspond bien à la session
			if($data->user_id!=$this->session->user_id()) throw new zException("user is different from session's user", 1);
				
			//On vérifie si l'événement existe bien
			$event = $this->Events->findFirst(array('fields'=>'id,date,slug','conditions'=>array('id'=>$data->event_id)));
			if(empty($event)) throw new zException("L'évenement n'existe pas",1);

			//On vérifie si l'user existe bien
			$user = $this->Users->findFirst(array('fields'=>'user_id','conditions'=>array('user_id'=>$data->user_id)));
			if(empty($user)) throw new zException("L'utilisateur n'existe pas",1);

			//On vérifie qu'il ne participe pas déja
			$check = $this->Users->find(array('table'=>'sporters','fields'=>'id','conditions'=>array('user_id'=>$data->user_id,'event_id'=>$data->event_id)));
			if(!empty($check)) {
				$this->session->setFlash("Tu participe déjà !","info");				
			}

			if($this->Events->saveParticipants($user,$event)){
				
				$this->session->setFlash("C'est cool on va bien s'éclater :) !!!","success");
			}
			else
				throw new zException('Unknown error while saving user participation',1);
				
		
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

			if(!$this->session->isLogged()) throw new zException('User must log in before trying to cancel participation',1);

			//Si les donnees sont bien numerique
			if(!is_numeric($data->user_id) || !is_numeric($data->event_id)) throw new zException('user_id or event_id is not numeric',1);

			//Si l'user correspond bien à la session
			if($data->user_id!=$this->session->user_id()) throw new zException("user is different from session's user", 1);
				
			//On vérifie si l'événement existe bien
			$event = $this->Events->findFirst(array('fields'=>'id,date,slug','conditions'=>array('id'=>$data->event_id)));
			if(empty($event)) throw new zException("L'évenement n'existe pas",1);

			//On vérifie si l'user existe bien
			$user = $this->Users->findFirst(array('fields'=>'user_id','conditions'=>array('user_id'=>$data->user_id)));
			if(empty($user)) throw new zException("L'utilisateur n'existe pas",1);

			//On vérifie qu'il participe
			$check = $this->Users->findFirst(array('table'=>'sporters','fields'=>'id','conditions'=>array('user_id'=>$data->user_id,'event_id'=>$data->event_id)));
			if(!empty($check)) {
				
				$p = new StdClass();
				$p->table = "sporters";
				$p->key = 'id';
				$p->id = $check->id;
				$this->Events->delete($p);

				$this->session->setFlash("Tanpis... à une prochaine fois!","warning");		
			}			
						
			$this->redirect('events/view/'.$event->id.'/'.$event->slug);		
		}
	}
	

	public function create($event_id = 0){

		$this->loadModel('Events');
		$this->loadModel('Users');
		$this->loadJS = 'js/jquery/jquery.autocomplete.js';


		//if user is logged
		if(!$this->session->islogged()) {

			$this->session->setFlash("Vous devez vous connecter pour proposer un événement !","info");
			$this->redirect('users/login');
			exit();
		}

		
		//if an event is specifyed
		if($event_id!=0){

			//find event
			$evt = $this->Events->findEventById($event_id);
			//exit if event not exist
			if(empty($evt)) throw new zException("Can not modify - Event not exist", 1);
			
			//redistect if user not exist
			if($evt->user_id != $this->session->user('user_id')){

				$this->session->setFlash("Vous n'êtes pas le créateur de cette annonce","error");				
				$this->redirect('users/login');			
			}
			//else continue
			
		}
		else{
			//else init a empty event
			$evt = new Event();
		}
		
		//if new data are sended
		if($this->request->post()){				

			//data to save		
			$newEvent = $this->request->post();
			$newEvent->city = $newEvent->cityID;
			$newEvent->slug = slugify($newEvent->title);
			unset($newEvent->cityID);

				if($this->Events->validates($newEvent)){
					
					//find changes
					$changes = array();
					foreach ($newEvent as $key => $value) {
						if($newEvent->$key!=$evt->$key) $changes[$key] = $newEvent->$key;
					}

					//save event
					if($this->Events->save($newEvent)){

						$this->session->setFlash("L'annonce a bien été enregistré, elle est visible dès maintenant");

						//get id of the event
						if(isset($this->Events->id))
						$event_id = $this->Events->id;
					
						//save organizator participation
						$check = $this->Events->findParticipants($event_id);
						if(!in_array($this->session->user_id(),$check)){
							
							$u = $this->Users->findFirstUser(array('fields'=>'user_id','conditions'=>array('user_id'=>$this->session->user_id())));
							$this->Events->saveParticipants($u,$newEvent);
						}

						//email the changes 
						if(!empty($changes)){

							$users = $this->Events->findParticipants($event_id);
							$users = $this->Events->JOIN('users','email','user_id=:user_id',$users);
							$emails = array();
							foreach ($users as $user) {
								$emails[] = $user->email;
							}
							if($this->sendEventChanges($emails,$newEvent,$changes)){

								$this->session->setFlash('Les modifications ont été envoyés aux participants','warning');
							}
						}
					}
					else{
						$this->session->setFlash("Il ya une erreur lors de la sauvegarde. Essaye encore","error");
					}

					
					
					//$this->redirect('events/view/'.$event_id);
				}
				else{

					$this->session->setFlash("Veuillez revoir votre formulaire",'error');
				}
			$evt = $this->Events->findEventById($event_id);
		
		}
		else {							
				
				$this->request->data = $evt;//send data to form class
				$this->session->setFlash("Vous pouvez créer une annonce","info");	
		}

		$d['event'] = $evt;

		$this->set($d);
	}

	public function sendEventChanges($emails,$event,$changes)
    {
        //Création d'une instance de swift mailer
        $mailer = Swift_Mailer::newInstance(Conf::getTransportSwiftMailer());
       
       	//Contenu
        $content = "";
        foreach ($changes as $key => $value) {
        	$content .= $key." : <strong>".$value."</strong><br />";
        }
        			
        $lien = Conf::$websiteURL."/events/view/".$event->id;

        //Récupère le template et remplace les variables
        $body = file_get_contents('../view/email/eventChanges.html');
        $body = preg_replace("~{site}~i", Conf::$website, $body);
        $body = preg_replace("~{title}~i", $event->title, $body);
        $body = preg_replace("~{date}~i", Date::datefr($event->date), $body);
        $body = preg_replace("~{lien}~i", $lien, $body);
        $body = preg_replace("~{content}~i", $content, $body);

        //Création du mail
        $message = Swift_Message::newInstance()
          ->setSubject("Un événement auquel vous participez a été modifié - ".Conf::$website)
          ->setFrom('noreply@'.Conf::$websiteDOT, Conf::$website)
          ->setTo($emails)
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




} ?>