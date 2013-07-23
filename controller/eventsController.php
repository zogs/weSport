<?php 
class EventsController extends Controller{

	public $primaryKey = 'id';


	public function calendar($action = 'now',$date = null){

		$this->view = 'events/index';
		$this->layout = 'none';
		$this->loadModel('Events');
		$this->loadModel('Worlds');
		
		//Parameter of the sql query
		$query = array();

		//GET Parameter
		$params =(array) $this->request->get();

		//check validity of the date
		if(isset($date))
			if(!Date::is_valid_date($date,'yyyy-mm-dd')) exit('date is not valid');
		
		//Number of day of the week
		if(!isset($params['dayperweek']))
			$numDaysPerWeek = 7;		
		else
			$numDaysPerWeek = $params['dayperweek'];
		
		
		if(!empty($action)){

			if(is_string($action)) {

				//day param			
				if($action=='now') $day = date('Y-m-d');
				elseif($action=='date') $day = $date;
				elseif($action=='prev'){
					if(isset($date)) $day = date('Y-m-d',strtotime($date.' - '.$numDaysPerWeek.' days'));
					elseif($this->cookieEventSearch->read('date')) $day = date('Y-m-d',strtotime($this->cookieEventSearch->read('date').' - '.$numDaysPerWeek.' days'));
					else $day = date('Y-m-d',strtotime(date('Y-m-d').' - '.$numDaysPerWeek.' days'));					
				}
				elseif($action=='next'){
					if(isset($date)) $day = date('Y-m-d',strtotime($date.' + '.$numDaysPerWeek.' days'));
					elseif($this->cookieEventSearch->read('date')) $day = date('Y-m-d',strtotime($this->cookieEventSearch->read('date').' + '.$numDaysPerWeek.' days'));
					else $day = date('Y-m-d',strtotime(date('Y-m-d').' + '.$numDaysPerWeek.' days'));	
				}				
				else $day = date('Y-m-d');

				if(!isset($day)) exit('no good url');

				$query['date'] = $day;

				//set cookie date
				$arr = $this->cookieEventSearch->arr();
				$arr['date'] = $day;
				$this->cookieEventSearch->write($arr);			
						
			}
		}			

		//if city is entered
		if(!empty($params['cityID'])){
			//set location for the model
			$query['location'] = array('cityID'=>$params['cityID']);
		}

		//if extend to city arroud
		if(!empty($params['extend']) && $params['extend'] != ' '){			
			if(!empty($params['cityID'])){

				//find latitude longitude of the city
				$city = $this->Worlds->findFirst(array('table'=>'world_cities','fields'=>array('LATITUDE','LONGITUDE'),'conditions'=>array('UNI'=>$params['cityID'])));
				//and set params for the model
				$query['cityLat'] = $city->LATITUDE;
				$query['cityLon'] = $city->LONGITUDE;
			}			
		}

		//
		$query['fields'] = 'E.id, E.user_id, E.cityID, E.cityName, E.sport, E.date, E.time, E.title, E.slug, E.confirmed';

		//if some sport are selected
		if(!empty($params['sports'])){
			$query['sports'] = $params['sports'];
		}
		//initialize variable for days loop
		//first day of the week
		$firstday = $day;
		$weekday = $day;		
		$events = array();
		$dayevents = array();

		//for each days , get the events
		for($i=1; $i<= $numDaysPerWeek; $i++){
 
			//set date param
			$query['date'] = array('day'=> $weekday) ;
			//find events in db
			$dayevents = $this->Events->findEvents($query);			
			$dayevents = $this->Events->joinSports($dayevents,$this->getLang());	
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
		$this->loadModel('Users');	

		if(!isset($id) || !is_numeric($id)) return false;

		$event = $this->Events->findEventById($id);	
		
		if(empty($event)) $this->e404("Cet événement n'existe pas");

		if($event->slug != $slug) $this->redirect('events/view/'.$event->id.'/'.$event->slug);

		//events
		$event = $this->Worlds->JOIN_GEO($event);
		$event = $this->Events->joinSport($event,$this->getLang());		
		$event = $this->Events->joinUserParticipation($event,$this->session->user()->getID());		
		
		//Participants
		$event->participants = $this->Events->eventsParticipants($event->id,1);
		$event->uncertains = $this->Events->eventsParticipants($event->id,0);

		//review
		$event->reviews = $this->Events->findReviewByOrga($event->user_id);
		$event->reviews = $this->Users->joinUser($event->reviews,'login,user_id,avatar');
	
		//google map API
		require(LIB.DS.'GoogleMap'.DS.'GoogleMapAPIv3.class.php');
		$gmap = new GoogleMapAPI();
		$gmap->setDivId('eventmap');
		$gmap->setSize('100%','250px');
		$gmap->setLang('fr');
		$gmap->setEnableWindowZoom(true);
		$fullAddress = $event->address.' , '.$event->getCityName().', '.$event->firstRegion().', '.$event->getCountry();
		$gmap->addMarkerByAddress( $fullAddress, $event->title, "<img src='".$event->getSportLogo()."' width='40px' height='40px'/><strong>".$event->title."</strong> <p>sport : <em>".$event->getSportName()."<em><br />Adresse: <em>".addslashes($event->address)."<br />Ville : <em>".$event->getCityName()."</em></p><p><small>".$event->description."</small></p>",$event->getSportName());
		$gmap->setCenter($fullAddress);
		$gmap->setZoom(12);
		$gmap->generate();		
		$d['gmap'] = $gmap;
		
		//setet exact Lat & Lon from googlemap
		$event->addressCoord = array('lat'=>$gmap->centerLat,'lng'=>$gmap->centerLng);

		//set OpenGraph Object
		$this->OpenGraphObject = $this->request('events','getOpenGraphEventMarkup',array($event));		

		//debug($event);

		//titre de la page
		$d['title_for_layout'] = 'weSport - '.$event->getSportName().' à '.$event->getCityName().' le '.$event->getDate($this->getLang());
		$d['description_for_layout'] = $event->author->getLogin().' organise un match de '.$event->getSportName().' près de la ville de '.$event->getCityName().' le '.$event->getDate($this->getLang()).' - via Wesport - '.$event->getTitle();
		$d['keywords_for_layout'] = 'Sport : '.$event->getSportName();
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
			$event = $this->Events->findEventByID($data->event_id);
			if(!$event->exist()) throw new zException("L'évenement n'existe pas",1);

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
				
				//Set flash				
				$this->session->setFlash("C'est cool on va bien s'éclater :) ","success",5);
				//If facebook user post to OG:
				if($user->isFacebookUser()) $this->fb_openGraph_JoinSport($event,$user);

				//On préviens l'organisateur
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
					$this->session->setFlash("Tanpis, à une prochaine fois!","success",3);					
					//On vérifie si le nombre min nest pas atteint
					//nombre actuel de participants
					$nbparticip = $this->Events->countParticipants($event->id);
					//Si ne nombre est atteint, on annule l'evenement
					if( $nbparticip == $event->nbmin-1 ) {
						//on annule l'événement
						if($this->Events->cancelEvent($event->id)){
							//on previens
							$this->session->setFlash("L'événement est suspendu...","warning",5);
							//on envoi un mailing  aux participants
							if($this->sendEventCanceled($event)) {

								$this->session->setFlash("Les participants ont été prévenues","info",7);	
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
				
			if($res = $this->Events->saveReview($data)){
				
				if($res==='already') {
					$this->session->setFlash("Désolé mais vous avez déjà donné votre avis","warning");
				}
				else {

					$this->session->setFlash("Merci d'avoir donné votre avis !","success");	
				}
				
			}
		}
		$this->redirect('events/view/'.$eid);
	}

	public function confirm($eid){

		if(!isset($eid) ||!is_numeric($eid)) exit();

		$this->loadModel('Events');
		$evt = $this->Events->findEventById($eid);

		if($evt->isAdmin($this->session->user()->getID())){
			//Confirm event
			$this->Events->confirmEvent($eid);			
			//set Flash
			$this->session->setFlash("L'activité est confirmée ! Amusez-vous bien !");
		}

		$this->redirect('events/view/'.$evt->id.'/'.$evt->slug);

	}

	public function create($event_id = 0){

		$this->loadModel('Events');
		$this->loadModel('Users');
		$this->loadModel('Worlds');

		// if user is logged
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
				$this->session->setFlash('Cette activité a été confirmé, vous ne pouvez la modifier !','danger');
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
			$new = $this->request->post();
			
			// if cityID is not defined			
			 if(empty($new->cityID)){
				//find cityID with cityName
				if(!empty($new->cityName)){
					$c = $this->Worlds->suggestCities(array('CC1'=>'FR','prefix'=>$new->cityName));
					
					if(!empty($c)){
						$new->cityID = $c[0]->city_id;
						$new->cityName = $c[0]->name;
					}
					else {
						$new->cityName = '';
					}
				}
			}

			//init var
			$new->slug = slugify($new->title);


				if($this->Events->validates($new)){
					
					//Si l'evt existe déjà
					if($evt->exist()){

						//On regarde quel sont les changements , dans le but d'avertir les participants
						$changes = array();
						$silent_changes = array('slug','nbmin','cityID');
						foreach ($new as $key => $value) {
							if( $new->$key!=$evt->$key && !in_array($key,$silent_changes)) $changes[$key] = $new->$key;
						}


						//On regarde si le nombre minimum est réduit, pour le confirmer si jamais
						if( $new->nbmin < $evt->nbmin ) {
							//On recupere le nombre de participants
							$nbparticipants = $this->Events->countParticipants($evt->id);
							//Si il y a plus de participants que nombre_min, on confirme l'événement
							if($nbparticipants >= $new->nbmin){

								$new->confirmed = 1;
								$this->session->setFlash("Le nombre de participants est atteint ! L'activité est confirmée !");
							}
						}

					}
					


					//save event
					if($event_id = $this->Events->saveEvent($new)){

						$this->session->setFlash("L'annonce a bien été enregistrée, elle est visible dès maintenant");
						
						//get event
						$evt = $this->Events->findEventById($event_id);
					
						//save organizator participation		
						$u = $this->Users->findFirstUser(array('fields'=>'user_id','conditions'=>array('user_id'=>$this->session->user()->getID())));
						$this->Events->saveParticipants($u,$evt);						
						
					
						//email the changes 
						//if there are changes and event is not finished
						if(!empty($changes)&&$evt->timingSituation()!='past'){
							
							if($this->sendEventChanges($new,$changes)){

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
					$evt = new Event($new);					
					$this->session->setFlash("Veuillez revoir votre formulaire",'error');
					
				}		
		
		}
		
		$d['sports_available'] = $this->Events->findSportsList($this->getLang());
		$d['user_events_in_futur'] = $this->Events->findEvents(array('date'=>'futur','conditions'=>array('user_id'=>$this->session->user()->getID())));
		$d['user_events_in_futur'] = $this->Events->joinSports($d['user_events_in_futur'],$this->getLang());
		$d['user_events_in_past'] = $this->Events->findEvents(array('date'=>'past','order'=>'E.date DESC','conditions'=>array('user_id'=>$this->session->user()->getID())));
		$d['user_events_in_past'] = $this->Events->joinSports($d['user_events_in_past'],$this->getLang());
		
		if($evt->exist()) 
			$evt = $this->Events->joinSport($evt,$this->getLang());

		$this->request->data = $evt;//send data to form class

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
		

	public function fb_openGraph_JoinSport($event,$user){

		if(!$user->isFacebookUser()) return false;

		//find english ACTION SPORT
		$this->loadModel('Events');
		$sport = $this->Events->findSport(array('slug'=>$event->sport,'lang'=>'en'));
		$sport_action = $sport->action;
		if($sport_action=='go') $sport_action = ''; // "Go" is the default verb , no need to pass it in the og API call

		//url & params to POST to facebook open graph
		$url = '/me/we-sport-:go_to?';
		$params = array(
			'access_token'=>$user->getFacebookToken(),
			'sport'=>Conf::getSiteUrl().$event->getUrl(),
			'sport_action'=>$sport_action,
			'end_time'=>$event->getDate('en').' '.$event->getTime()
			);
		//create api call url
		foreach($params as $key=>$value) { $url .= $key.'='.urlencode($value).'&'; }
		rtrim($url, '&');

		//facebook SDK
		require_once LIB.'/facebook-php-sdk-master/src/facebook.php';
		$facebook = new Facebook(array('appId'=>Conf::$facebook['appId'],'secret'=>Conf::$facebook['secret'],'cookie'=>true));
		
		debug($url);
		$id = $facebook->api($url);

		debug($id);
		
		exit();
		//return 
		if(!empty($fb_return->id) && is_numeric($fb_return->id)) return true;
		else {
			$this->session->setFlash('Erreur OpenGraph','error');			
		}
	}

	public function getOpenGraphEventMarkup($event){
		//debug($event);
		$head = "prefix='og: http://ogp.me/ns# fb: http://ogp.me/ns/fb# event: http://ogp.me/ns/event#'";
		$metas = '<meta property="fb:app_id"                content="'.Conf::$facebook['appId'].'" /> 
				  	<meta property="og:url"                   content="'.Conf::getSiteUrl().$event->getUrl().'" /> 
				  	<meta property="og:type"                  content="we-sport-:sport" /> 
				  	<meta property="og:title"                 content="'.$event->title.' - '.$event->getSportName().'" /> 
				  	<meta property="og:image"                 content="http://'.Conf::$websiteURL.''.$event->getSportLogo().'" /> 
				  	<meta property="og:description"			content="'.substr($event->getDescription(),0,100).'" />
				  	<meta property="og:street-address" content="'.$event->address.'" />
					<meta property="og:locality" content="'.$event->cityName.'" />
					<meta property="og:country-name" content="'.$event->CC1.'" />
					<meta property="we-sport-:name"      content="'.$event->getSportName().'" />
					<meta property="we-sport-:title"     content="'.$event->getTitle().'" />
					<meta property="we-sport-:description" content="'.substr($event->getDescription(),0,100).'" /> 
					<meta property="we-sport-:action"    content="'.$event->getSportAction().'" />
					<meta property="we-sport-:datetime"            content="'.$event->getTimestamp().'" /> 
					<meta property="we-sport-:participants" content="'.count($event->participants).'" /> 
					<meta property="we-sport-:confirmed"     content="'.(($event->isConfirm())? 'true' : 'false').'" />
				  	<meta property="we-sport-:latitude"  content="'.$event->addressCoord['lat'].'" /> 
				  	<meta property="we-sport-:longitude" content="'.$event->addressCoord['lng'].'" />
				  	<meta property="we-sport-:adresse" content="'.$event->address.'" />
					<meta property="we-sport-:ville" content="'.$event->cityName.'" />
					<meta property="we-sport-:pays" content="'.$event->CC1.'" />
				';

		return array('head'=>$head,'metas'=>$metas);
	}


	private function findEmailsParticipants($event,$mailingName=false,$withAuthor=false){

		$emails = array();

		$this->loadModel('Events');
		$this->loadModel('Users');

		//get emails of participants  	
		$sporters = $this->Events->findParticipants($event->id);	
		
		//pour chaque participants on cherche son email dans la bdd
		foreach ($sporters as $sporter) {

			$user = $this->Users->findFirstUser(array('fields'=>'user_id,email','conditions'=>array('user_id'=>$sporter->user_id)));			
			
			//si l'utilisateur nexiste pas on saute
			if(empty($user) || !$user->exist()) continue;

			if($withAuthor===false && $user->user_id==$event->user_id) continue; //on saute l'organisateur de l'événement 
			
			if($mailingName){
				//on recupere les preferences mailing de l'utilisateur
				$setting = $this->Users->findFirst(array('table'=>'users_settings_mailing','fields'=>$mailingName,'conditions'=>array('user_id'=>$sporter->user_id)));				
				//si l'utilisateur prefere ne pas recevoir ce mail on saute
				if(!empty($setting) && $setting->$mailingName == 0) continue;
			}

			
			//on push l'email dans un tableau
			$emails[] = $user->email;
			 
				
		}
		//retourne le tableau
		return $emails;
	}
	

	public function sendEventDeleting($event = null)
    {

    	$subject = "L'activité à laquelle vous participez a été supprimée - ".Conf::$website;

    	//get emails participants
    	$emails = $this->findEmailsParticipants($event,'eventCanceled');        	        

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
    	$emails = $this->findEmailsParticipants($event,'eventChanged');

        //Récupère le template 
        $body = file_get_contents('../view/email/eventChanges.html');

        //if time has been changed       
        if(isset($changes['hours']) || isset($changes['minutes'])) {
        	$changes['time'] = $event->time;
        	if(isset($changes['hours'])) unset($changes['hours']);
        	if(isset($changes['minutes'])) unset($changes['minutes']);
        }

        //Traduction
        $trad = array('title'=>'Titre','sport'=>'Sport','cityName'=>'Ville','address'=>'Adresse','date'=>'Date','time'=>'Heure','description'=>'Descriptif','phone'=>'Téléphone');

        //init variable
        $content = "";
        foreach ($changes as $key => $value) {
        	$content .= $trad[$key]." : <strong>".$value."</strong><br />";
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
    	
    	//user mailing setting
    	$setting = $this->Events->findFirst(array('table'=>'users_settings_mailing','fields'=>'eventUserQuestion','conditions'=>array('user_id'=>$event->author->user_id)));
    	if(!empty($setting) && $setting->eventUserQuestion==0) return false;
    
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

    	//user mailing setting
    	$setting = $this->Events->findFirst(array('table'=>'users_settings_mailing','fields'=>'eventOrgaReply','conditions'=>array('user_id'=>$comment->user->user_id)));
    	if(!empty($setting) && $setting->eventOrgaReply==0) return false;   	

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

    	$emails = $this->findEmailsParticipants($event,'eventConfirmed',true);

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

    	$emails = $this->findEmailsParticipants($event,'eventCanceled',true);

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

    	//user mailing setting
    	$setting = $this->Events->findFirst(array('table'=>'users_settings_mailing','fields'=>'eventNewParticipant','conditions'=>array('user_id'=>$author->user_id)));
    	if(!empty($setting) && $setting->eventNewParticipant==0) return false;   

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

    	if(get_class($this->request)!='Cron') exit();

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


    	foreach ($sporters as $key => $sporter) {
    			
    		//find user
    		$sporter->user = $this->Users->findFirstUser(array('conditions'=>array('user_id'=>$sporter->user_id)));    		
    		//if user dont exist jump out
    		if(empty($sporter->user) || !$sporter->user->exist()) continue;

    		//find event
    		$sporter->event = $this->Events->findEventById($sporter->event_id);
    		$sporter->event->numParticipants = $this->Events->countParticipants($sporter->event->id);

    		//if event dont exist jump out
    		if(!$sporter->event->exist()) continue;
    			
    		//jump out if the user dont want the mail
    		$setting = $this->Users->findFirst(array('table'=>'users_settings_mailing','fields'=>'eventOpinion','conditions'=>array('user_id'=>$sporter->user->getID())));
    		if(!empty($setting) && $setting->eventOpinion==0){
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

	        	//increment events particpants						
		$this->Users->increment(array('table'=>'users_stat','key'=>'user_id','id'=>$sporter->event->user_id,'field'=>'events_participants','number'=>$sporter->event->numParticipants));
		//increment sporters encourter
		$this->Users->increment(array('table'=>'users_stat','key'=>'user_id','id'=>$sporter->user_id,'field'=>'sporters_encounted','number'=>$sporter->event->numParticipants));
		//Set sport practiced for stat
		$this->Events->setSportPracticed($sporter->user_id,$sporter->event->getSportSlug());

	        }
	        else $nb_mail_error++;
    	}
    	
    	$timer = round(microtime(true) - $debut,5).'s';
    	$log = 'Mail sended:'.$nb_mail_sended.', error:'.$nb_mail_error.' , silent:'.$nb_mail_silent.'  total:'.$nb_sporters.'  '.$timer;
    	$this->Events->saveLog('cron mail','events/sendMailUserEventOpinion',$log);
    	exit($log);

    }



} ?>