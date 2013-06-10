<?php 
class EventsController extends Controller{

	public $primaryKey = 'id';


	public function calendar($params = null){

		$this->view = 'events/index';
		$this->layout = 'none';
		$this->loadModel('Events');
		$this->loadModel('Worlds');
		

		
		if(!empty($params)){

			//if its the first week
			//get params from pagesController home 
			if(is_array($params)){ 

				$day = $params['date'];
				$cookie = $this->cookieEventSearch->arr();	
				$cookie['date'] = $day;			
				$this->cookieEventSearch->write($cookie);
							
			}
			//if its the prev/next week
			//get params from get ajax request
			if(is_string($params)) {

				//day param
				$day = $params;				
				//rewrite cookie parameters
				$params = $this->cookieEventSearch->arr();
				$params['date'] = $day;			
				$this->cookieEventSearch->write($params);
						
			}
		}			

		//if city is entered
		if(!empty($params['cityID'])){
			//set location for the model
			$params['location'] = array('cityID'=>$params['cityID']);
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
		$params['fields'] = 'E.id, E.user_id, E.cityID, E.cityName, E.sport, E.date, E.time, E.title, E.slug, E.confirmed';


		//initialize variable for days loop
		$numDaysPerWeek = 7; //number of days showed
		//find the first day of the week
		$firstday = (isset($day))? $day : $this->cookieEventSearch->read('date');
		
		//loop init
		$weekday = $firstday;		
		$events = array();
		$dayevents = array();

		//for each days , get the events
		for($i=1; $i<= $numDaysPerWeek; $i++){
 
			//set date param
			$params['date'] = array('day'=> $weekday) ;
			//find events in db
			$dayevents = $this->Events->findEvents($params);			
			$dayevents = $this->Events->JOIN('sports','slug as sport',array('sport_id'=>':sport'),$dayevents);		
			$dayevents = $this->Worlds->JOIN_GEO($dayevents);
			$dayevents = $this->Events->joinEventsParticipants($dayevents);
			$dayevents = $this->Events->joinUserParticipation($dayevents,$this->session->user()->getID());
			$events[$weekday] = $dayevents;
			//set next day			
			$weekday = date("Y-m-d", strtotime($weekday. " +1 day"));

		}


		$d['events'] = $events;
		$d['firstday'] = $firstday;
		$d['numDaysPerWeek'] = $numDaysPerWeek;
		
		
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

		$event = $this->Worlds->JOIN_GEO($event);
		$event = $this->Events->JOIN('sports','slug as sport',array('sport_id'=>':sport'),$event);		
		$event = $this->Events->joinUserParticipation($event,$this->session->user()->getID());		
		$event->participants = $this->Events->eventsParticipants($event->id,1);
		$event->uncertains = $this->Events->eventsParticipants($event->id,0);

	
		//google map API
		require(LIB.DS.'GoogleMap'.DS.'GoogleMapAPIv3.class.php');

		$gmap = new GoogleMapAPI();
		$gmap->setDivId('eventmap');
		$gmap->setSize('300px','250px');
		$gmap->setLang('fr');
		$gmap->setEnableWindowZoom(true);

		$fullAddress = $event->address.' , '.$event->getCityName().', '.$event->firstRegion().', '.$event->getCountry();
		
		$gmap->addMarkerByAddress( $fullAddress, $event->title, "<img src='".$event->getSportLogo()."' width='40px' height='40px'/><strong>".$event->title."</strong> <p>sport : <em>".$event->sport."<em><br />Adresse: <em>".addslashes($event->address)."<br />Ville : <em>".$event->getCityName()."</em></p><p><small>".$event->description."</small></p>",$event->sport);
		$gmap->setCenter($fullAddress);
		$gmap->setZoom(12);

		$gmap->generate();
		$d['gmap'] = $gmap;

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


			//Si un utilisateur est loggé
			if(!$this->session->user()->isLog()) throw new zException('User must log in before trying to participate',1);

			//Si les donnees sont bien numerique
			if(!is_numeric($data->user_id) || !is_numeric($data->event_id)) throw new zException('user_id or event_id is not numeric',1);

			//Si l'user correspond bien à la session
			if($data->user_id!=$this->session->user()->getID()) throw new zException("user is different from session's user", 1);
				
			//On vérifie si l'événement existe bien
			$event = $this->Events->findFirst(array('conditions'=>array('id'=>$data->event_id)));
			if(empty($event)) throw new zException("L'évenement n'existe pas",1);

			//On vérifie si l'user existe bien
			$user = $this->Users->findFirstUser(array('conditions'=>array('user_id'=>$data->user_id)));
			if(!$user->exist()) throw new zException("L'utilisateur n'existe pas",1);

			//On vérifie qu'il ne participe pas déja
			$check = $this->Users->find(array('table'=>'sporters','fields'=>'id','conditions'=>array('user_id'=>$data->user_id,'event_id'=>$data->event_id)));
			if(!empty($check)) {
				$this->session->setFlash("Tu participe déjà !","info");				
			}

			//Probabilité de participation ( default=1)
			if(isset($data->proba)) $proba = $data->proba;
			else $proba = 1;

			//Sauver la participations
			if($this->Events->saveParticipants($user, $event, $proba)){
				
				$this->session->setFlash("C'est cool on va bien s'éclater :) !!!","success",4);

				//Prévenir l'organisateur
				if($this->sendNewParticipant($event,$user)){

					//Vérifier si le nombre est atteint
					//nombre actuel de participants
					$nbparticip = $this->Events->countParticipants($event->id);
					//Si ne nombre est atteint
					if($event->nbmin == $nbparticip ) {
						// on confirme l'evenement
						$this->Events->confirmEvent($event->id);
						//Envoi un mailing  aux participants
						$this->sendEventConfirmed($event);
					}
				}

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

			if(!$this->session->user()->isLog()) throw new zException('User must log in before trying to cancel participation',1);

			//Si les donnees sont bien numerique
			if(!is_numeric($data->user_id) || !is_numeric($data->event_id)) throw new zException('user_id or event_id is not numeric',1);

			//Si l'user correspond bien à la session
			if($data->user_id!=$this->session->user()->getID()) throw new zException("user is different from session's user", 1);
				
			//On vérifie si l'événement existe bien
			$event = $this->Events->findFirst(array('conditions'=>array('id'=>$data->event_id)));
			if(empty($event)) throw new zException("L'évenement n'existe pas",1);

			//On vérifie si l'user existe bien
			$user = $this->Users->findFirstUser(array('fields'=>'user_id','conditions'=>array('user_id'=>$data->user_id)));
			if(!$user->exist()) throw new zException("L'utilisateur n'existe pas",1);

			//On vérifie qu'il participe
			$check = $this->Users->findFirst(array('table'=>'sporters','fields'=>'id','conditions'=>array('user_id'=>$data->user_id,'event_id'=>$data->event_id)));
			//Si il participe
			if(!empty($check)) {
				//On annule sa participation
				if($this->Events->cancelParticipation($check->id)){
					//On previens
					$this->session->setFlash("Tanpis, à une prochaine fois!","warning",1);
					//On vérifie si le nombre min nest pas atteint
					//nombre actuel de participants
					$nbparticip = $this->Events->countParticipants($event->id);
					//Si ne nombre est atteint, on annule l'evenement
					if( $nbparticip == $event->nbmin-1 ) {
						//on annule l'événement
						if($this->Events->cancelEvent($event->id)){
							//on previens
							$this->session->setFlash("L'événement est suspendu...","warning",2);
							//on envoi un mailing  aux participants
							if($this->sendEventCanceled($event)) {

								$this->session->setFlash("Les participants ont été prévenues","warning",3);	
							}

						} 
	
					}

				}
				else throw new zException("error cancel participation", 1);
						
			}			
						
			$this->redirect('events/view/'.$event->id.'/'.$event->slug);		
		}
	}


	public function review($eid){

		$this->loadModel('Events');

		if($this->request->post()){

			$data = $this->request->post();
				
			if($res = $this->Events->saveReview($eid,$this->session->user()->getID(),$data->review, $this->getLang() )){
				
				if($res==='already') {
					$this->session->setFlash("Vous avez déjà donné votre avis","warning");
				}
				else
					$this->session->setFlash("Merci d'avoir donné votre avis !","success");	
				
			}
		}
		$this->redirect('events/view/'.$eid);
	}

	public function create($event_id = 0){

		$this->loadModel('Events');
		$this->loadModel('Users');
		$this->loadModel('Worlds');

		//if user is logged
		if(!$this->session->user()->isLog()) {

			$this->session->setFlash("Vous devez vous connecter pour proposer un sport !","info");
			$this->redirect('users/login');
			exit();
		}

		
		//if an event is specifyed
		if($event_id!=0){

			//find event
			$evt = $this->Events->findEventById($event_id);
			//exit if event not exist
			if(!$evt->exist()) throw new zException("Can not modify - Event not exist", 1);
			
			//redistect if user not exist
			if(!$evt->isAdmin($this->session->user()->getID())){
				$this->session->setFlash("Vous n'êtes pas le créateur de cette annonce","error");				
				$this->redirect('users/login');			
			}	
			
			//if event is confirm , dont allow modification
			if($evt->isConfirm()){				
				$this->session->setFlash('Cette activité a été confirmé, vous ne pouvez le modifier...','danger');
				$this->request->data = null;
			}		
			
		}
		else{
			//else init a empty event
			$evt = new Event();

		}

		//if new data are sended
		if($this->request->post()){				

			//data to save		
			$Event = $this->request->post();
			
			// if cityID is not defined			
			 if(empty($Event->cityID)){
				//find cityID with cityName
				if(!empty($Event->cityName)){
					$c = $this->Worlds->suggestCities(array('CC1'=>'FR','prefix'=>$Event->cityName));
					
					if(!empty($c)){
						$Event->cityID = $c[0]->city_id;
						$Event->cityName = $c[0]->name;
					}
					else {
						$Event->cityName = '';
					}
				}
			}

			//init var
			$Event->slug = slugify($Event->title);


				if($this->Events->validates($Event)){
					
					//find if change occurs
					if($evt->exist()){
						$changes = array();
						$silent_changes = array('slug','nbmin');
						foreach ($Event as $key => $value) {
							if( $Event->$key!=$evt->$key && !in_array($key,$silent_changes)) $changes[$key] = $Event->$key;
						}
					}

					//save event
					if($event_id = $this->Events->saveEvent($Event)){

						$this->session->setFlash("L'annonce a bien été enregistrée, elle est visible dès maintenant");
						
						//get event
						$evt = $this->Events->findEventById($event_id);
					
						//save organizator participation		
						$u = $this->Users->findFirstUser(array('fields'=>'user_id','conditions'=>array('user_id'=>$this->session->user()->getID())));
						$this->Events->saveParticipants($u,$evt);
						
					
						//email the changes 
						if(!empty($changes)){
							
							if($this->sendEventChanges($Event,$changes)){

								$this->session->setFlash('Les modifications ont été envoyées aux participants','warning');
							}
						}

						//redirect
						//$this->redirect('events/create/'.$event_id);
					}
					else{
						$this->session->setFlash("Il ya une erreur lors de la sauvegarde. Essaye encore","error");
					}

					
					
					//$this->redirect('events/view/'.$event_id);
				}
				else{
					//if not validate , return a incomplete event fill with the data
					$evt = new Event($Event);					
					$this->session->setFlash("Veuillez revoir votre formulaire",'error');
				}
			
		
		}
		else {							
				
				$this->request->data = $evt;//send data to form class
					
		}

		$d['sports_available'] = $this->Events->find(array('table'=>'sports','fields'=>array('sport_id','slug')));
		$d['user_events_in_futur'] = $this->Events->findEvents(array('date'=>'futur','conditions'=>array('user_id'=>$this->session->user()->getID())));
		$d['user_events_in_past'] = $this->Events->findEvents(array('date'=>'past','order'=>'E.date DESC','conditions'=>array('user_id'=>$this->session->user()->getID())));
		

		$d['event'] = $evt;

		$this->set($d);
	}


	public function delete($eid,$token){

		//tcheck token
		if($token!=$this->session->token()) $this->e404('Merci de vous reconnecter avant d\'effectuer cet opération');

		//find Event
		$this->loadModel('Events');
		$this->view = 'none';
		$evt = $this->Events->findEventById($eid);		

		//check if event exit
		if(!$evt->exist()) $this->e404('Cette activité n\'existe pas');

		//check if user is admin
		if(!$evt->isAdmin($this->session->user()->getID())) $this->e404('Vous ne pouvez pas supprimé cette activité');

		//delete the event
		if($this->Events->deleteEvent($evt)){
			$this->session->setFlash("Activité supprimée !","success");

			//send Mailing to sporters				
			if($this->sendEventDeleting($evt)){
				$this->session->setFlash("Les participants ont été informés de l'annulation !","info");
			}
			
			$this->redirect('events/create');
		} else {
			$this->session->setFlash("Erreur... l'activité n'a pu être supprimée","danger");
			$this->redirect('events/create/'.$eid);
		}

		
	}


	private function findEmailsParticipants($event,$withAuthor=false){

		$emails = array();

		$this->loadModel('Events');
		$this->loadModel('Users');

		//get emails of participants  	
		$sporters = $this->Events->findParticipants($event->id);	
		
		//pour chaque participants on cherche son email dans la bdd
		foreach ($sporters as $sporter) {

			$user = $this->Users->findFirstUser(array('fields'=>'user_id,email','conditions'=>array('user_id'=>$sporter->user_id)));			
			
			if($withAuthor===false && $user->user_id==$event->user_id) continue; //sauf si on veut sauter l'organisateur de l'evt
			
			if($user->exist()) $emails[] = $user->email;
		}
		
		return $emails;
	}
	

	private function sendEventDeleting($event)
    {

    	$subject = "L'activité à laquelle vous participez a été supprimée - ".Conf::$website;

    	//get emails participants
    	$emails = $this->findEmailsParticipants($event);        	        

        //Récupère le template
        $body = file_get_contents('../view/email/eventDeleted.html');

        //Init varaible
        $lien = Conf::getSiteUrl()."/events/create";

        // remplace variables dans la template
        $body = preg_replace("~{site}~i", Conf::$website, $body);
        $body = preg_replace("~{title}~i", $event->title, $body);
        $body = preg_replace("~{date}~i", Date::datefr($event->date), $body);
        $body = preg_replace("~{lien}~i", $lien, $body);
        $body = preg_replace("~{time}~i", $event->time, $body);
        $body = preg_replace("~{ville}~i", $event->cityName, $body);

        if($this->sendEmails($emails,$subject,$body)) return true;
        else return false;
    }

	private function sendEventChanges($event,$changes)
    {
    	//Sujet du mail
    	$subject = "L'activité à laquelle vous participez a été modifiée - ".Conf::$website;    

    	//get emails participatns
    	$emails = $this->findEmailsParticipants($event);

        //Récupère le template 
        $body = file_get_contents('../view/email/eventChanges.html');

        //init variable
        $content = "";
        foreach ($changes as $key => $value) {
        	$content .= $key." : <strong>".$value."</strong><br />";
        }        		
        $lien = Conf::getSiteUrl()."/events/view/".$event->id;

        //remplace les variables dans la template
        $body = preg_replace("~{site}~i", Conf::$website, $body);
        $body = preg_replace("~{title}~i", $event->title, $body);
        $body = preg_replace("~{date}~i", Date::datefr($event->date), $body);
        $body = preg_replace("~{lien}~i", $lien, $body);
        $body = preg_replace("~{content}~i", $content, $body);

      
        if($this->sendEmails($emails,$subject,$body)) return true;
        else return false;
    }

    public function sendMailNewComment($event_id,$comment_id){

    	if(!is_numeric($event_id)) throw new zException("Error Processing Request", 1);
    	if(!is_numeric($comment_id)) throw new zException("Error Processing Request", 1);
    	

    	$this->loadModel('Events');
    	$this->loadModel('Comments');
    	$this->view = 'none';
    	$this->layout = 'none';

    	$event = $this->Events->findEventById($event_id);
    	$email = $event->author->email;

    	$com = $this->Comments->getComment($comment_id);
    	$content = $com->content;

    	if($event->author->user_id==$com->user_id) return false;

    	$user = $com->user;
    	$subject = $user->login.' vous a posé une question !';

    	$body = file_get_contents('../view/email/eventNewComment.html');

    	$lien = Conf::getSiteUrl()."/events/view/".$event->id."/".$event->slug;

        $body = preg_replace("~{site}~i", Conf::$website, $body);
        $body = preg_replace("~{title}~i", $event->title, $body);
        $body = preg_replace("~{user}~i", $user->login, $body);
        $body = preg_replace("~{comment}~i", $content, $body);
        $body = preg_replace("~{subject}~i", $subject, $body);
        $body = preg_replace("~{lien}~i", $lien, $body);

        if($this->sendEmails($email,$subject,$body)) return true;
        else return false;

    }

    public function sendMailNewReply($comment_id,$reply_id){

    	if(!is_numeric($reply_id)) throw new zException("Error Processing Request", 1);
    	if(!is_numeric($comment_id)) throw new zException("Error Processing Request", 1);
    	

    	$this->loadModel('Events');
    	$this->loadModel('Comments');
    	$this->view = 'none';
    	$this->layout = 'none';

    	$comment = $this->Comments->getComment($comment_id);
    	if(!$comment->exist()) exit('comment not exist');

    	$reply = $this->Comments->getComment($reply_id);
    	if(!$reply->exist()) exit('reply not exist');

    	$event = $this->Events->findEventById($comment->context_id);
    	if(!$event->exist()) exit('event not exist');

    	$email = $comment->user->email;    	

    	$content = $reply->content;

    	if($event->author->user_id==$comment->user_id) exit('user reply to himself');
    	

    	$subject = $reply->user->login.' vous a répondu !';

    	$body = file_get_contents('../view/email/eventNewReply.html');

    	$lien = Conf::getSiteUrl()."/events/view/".$event->id."/".$event->slug;

        $body = preg_replace("~{site}~i", Conf::$website, $body);
        $body = preg_replace("~{title}~i", $event->title, $body);
        $body = preg_replace("~{user}~i", $reply->user->login, $body);
        $body = preg_replace("~{comment}~i", $content, $body);
        $body = preg_replace("~{subject}~i", $subject, $body);
        $body = preg_replace("~{lien}~i", $lien, $body);

        if($this->sendEmails($email,$subject,$body)) return true;
        else return false;

    }

    private function sendEventConfirmed($event){

    	$subject = "L'activité ".$event->title." est confirmée !";

    	$emails = $this->findEmailsParticipants($event,true);

    	$body = file_get_contents('../view/email/eventConfirmation.html');

    	$lien = Conf::getSiteUrl()."/events/view/".$event->id;

        $body = preg_replace("~{site}~i", Conf::$website, $body);
        $body = preg_replace("~{title}~i", $event->title, $body);
        $body = preg_replace("~{date}~i", Date::datefr($event->date), $body);
        $body = preg_replace("~{time}~i", $event->time, $body);
        $body = preg_replace("~{ville}~i", $event->cityName, $body);
        $body = preg_replace("~{lien}~i", $lien, $body);

        if($this->sendEmails($emails,$subject,$body)) return true;
        else return false;

    }


    private function sendEventCanceled($event){

    	$subject = "Un sportif s'est désisté, l'activité est suspendue...";

    	$emails = $this->findEmailsParticipants($event,true);

    	$body = file_get_contents('../view/email/eventAnnulation.html');

    	$lien = Conf::getSiteUrl()."/events/view/".$event->id;

        $body = preg_replace("~{site}~i", Conf::$website, $body);
        $body = preg_replace("~{title}~i", $event->title, $body);
        $body = preg_replace("~{date}~i", Date::datefr($event->date), $body);
        $body = preg_replace("~{time}~i", $event->time, $body);
        $body = preg_replace("~{ville}~i", $event->cityName, $body);
        $body = preg_replace("~{lien}~i", $lien, $body);
        

        if($this->sendEmails($emails,$subject,$body)) return true;
        else return false;

    }

    private function sendNewParticipant($event,$user){

    	$subject = $user->login." participe à votre activité !";

    	$this->loadModel('Users');
    	$author = $this->Users->findFirstUser(array('conditions'=>array('user_id'=>$event->user_id)));
    	$email = $author->email;

    	$body = file_get_contents('../view/email/eventNewParticipant.html');

    	$eventLink = Conf::getSiteUrl()."/events/view/".$event->id;
    	$userLink = Conf::getSiteUrl()."/users/view/".$user->getID().'/'.$user->getLogin();

        $body = preg_replace("~{site}~i", Conf::$website, $body);
        $body = preg_replace("~{title}~i", $event->title, $body);
        $body = preg_replace("~{author}~i", $author->getLogin(), $body);
        $body = preg_replace("~{sporter}~i", $user->getLogin(), $body);
        $body = preg_replace("~{eventlink}~i", $eventLink, $body);
        $body = preg_replace("~{sporterlink}~i", $userLink, $body);

        if($this->sendEmails($email,$subject,$body)) return true;
        else return false;
    }

    public function sendMailUserEventOpinion(){

    	//if(get_class($this->request)!='Cron') exit();

    	$debut = microtime(true);

    	$this->view = 'none';
    	$this->layout = 'none';
    	$this->loadModel('Events');
    	$this->loadModel('Users');

    	$sporters = $this->Events->findSportersNotYetMailed();

    	$nb_sporters = 0;
    	$nb_mail_sended = 0;
    	$nb_mail_silent = 0;
    	$nb_mail_error = 0;
    	$mail_content = file_get_contents(ROOT.'/view/email/eventPastEventReminder.html');

    	debug($sporters);

    	foreach ($sporters as $key => $sporter) {
    			
    		//find user
    		$sporter->user = $this->Users->findFirstUser(array('conditions'=>array('user_id'=>$sporter->user_id)));
    		//if user dont exist jump out
    		if(!$sporter->user->exist()) continue;

    		//find event
    		$sporter->event = $this->Events->findEventById($sporter->event_id);
    		//if event dont exist jump out
    		if(!$sporter->event->exist()) continue;
    			
    		//jump out if the user dont want the mail
    		if(empty($sporter->user->mailOpinion)) {
    			$nb_mail_silent++;
    			$this->Events->mailReminderSended($sporter->id); //set the mailing to done
    			continue;
    		}

    		//jump out if the user is the organisator
    		if($sporter->user_id==$sporter->event->user_id){    			
    			$this->Events->mailReminderSended($sporter->id); //set the mailing to done
    			continue;
    		}

    		$nb_sporters++;


    		//emailing
	    	$subject = 'Alors c\'était comment ?!';
	    	$eventLink = Conf::getSiteUrl()."/events/view/".$sporter->event->id.'/'.$sporter->event->title;
	    	$userLink = Conf::getSiteUrl()."/users/view/".$sporter->user->getID().'/'.$sporter->user->getLogin();
	    	$body = $mail_content;
	    	$body = preg_replace("~{site}~i", Conf::$website, $body);
	        $body = preg_replace("~{title}~i", $sporter->event->title, $body);
	        $body = preg_replace("~{subject}~i", $subject, $body);
	        $body = preg_replace("~{eventlink}~i", $eventLink, $body);

	        
	        if($this->sendEmails($sporter->user->email,$subject,$body)){

	        	$this->Events->mailReminderSended($sporter->id);
	        	$nb_mail_sended++;

	        }
	        else $nb_mail_error++;
    	}
    	
    	$timer = round(microtime(true) - $debut,5).'s';
    	$log = 'Mail sended:'.$nb_mail_sended.', error:'.$nb_mail_error.' , silent:'.$nb_mail_silent.'  total:'.$nb_sporters.'  '.$timer;
    	$this->Events->saveLog('cron mail','events/sendMailUserEventOpinion',$log);
    	exit($log);

    }



} ?>