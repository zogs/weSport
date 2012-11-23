<?php 
class EventsController extends Controller{

	public $primaryKey = 'id';


	public function index(){

		$this->view = 'events/index';
		$this->layout = 'none';
		$this->loadModel('Events');
		$this->loadModel('Worlds');


		$evts = $this->Events->findEvents(array(

					'sports'=>$this->request->post('sports'),
					'location'=>array(
						'city'=> $this->request->post('city'),
						'CC1'=> $this->request->post('CC1'),
						'ADM1'=> $this->request->post('ADM1'),
						'ADM2'=> $this->request->post('ADM2'),
						'ADM3'=> $this->request->post('ADM3'),
						'ADM4'=> $this->request->post('ADM4')
						),
					'date'=>$this->request->post('date')
					
				));

		$evts = $this->Events->JOIN('users','login,avatar,age',array('user_id'=>':user_id'),$evts);		
		$evts = $this->Worlds->JOIN_GEO($evts);


		$d['events'] = $evts;

		$this->set($d);
		$this->render();
	}

	public function view($id = null,$slug = null){

		$this->loadModel('Events');
		$this->loadModel('Worlds');

		if(!isset($id) || !is_numeric($id)) return false;

		$event = $this->Events->findFirst(array('conditions'=>array('id'=>$id)));
		$event = $this->Events->JOIN('users','login,avatar,age',array('user_id'=>':user_id'),$event);
		$event = $this->Worlds->JOIN_GEO($event);


		if(!empty($event)){

			if($event->slug != $slug) $this->redirect('events/view/'.$event->id.'/'.$event->slug);
		}

		$d['event'] = $event;

		$this->set($d);

	}
	public function create($event_id = 0){

		$this->loadModel('Events');


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
		}
		
		if($this->request->post()){				

				if($this->Events->validates($this->request->data)){
					//data to save
					$data = $this->request->data;
					$data->slug = slugify($data->title);
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
				$this->session->setFlash("Vous pouvez modifier votre annonce","info");	
		}
		
		$d['event'] = $evt;

		$this->set($d);
	}




} ?>