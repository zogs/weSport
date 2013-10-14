<?php

class MailingController extends Controller {
	
	public function admin_index(){


	}

	public function admin_listmailing(){

		$this->loadModel('Mailing');

		$lists = $this->Mailing->findMailingList();

		$this->set('lists',$lists);

	}

	public function admin_deleteEmail($lid,$eid){

		$this->loadModel('Mailing');

		if($this->Mailing->deleteEmail($eid)){
			$this->session->setFlash('Email supprimé');
		}

		$this->redirect('admin/mailing/editlist/'.$lid);
	}

	public function admin_deletelist($lid){

		$this->loadModel('Mailing');

		if($this->Mailing->deleteList($lid)){
			$this->session->setFlash('Liste supprimé');
		}

		$this->redirect('admin/mailing/listmailing');
	}

	public function admin_editlist($lid = null){

		$this->loadModel('Mailing');

		if($data = $this->request->post()){

			if($this->Mailing->validates($data,'editlist')){

				if($lid = $this->Mailing->saveMailing($data)){

					$this->session->setFlash('La mailing list a bien été enregistré !');
					$this->redirect('admin/mailing/editlist/'.$lid);
				}
				else {
					$this->session->setFlash('Error save mailing list','error');
				}
			}
		}

		if($lid){

			$list = $this->Mailing->getListByID($lid);
			$list->users = $this->Mailing->getEmailsByListID($lid);
			$emails = '';
			foreach ($list->users as $u) {
				$emails .= $u->email.';';				
			}
			$list->emails = $emails;
		}
		else{
			$list = new stdClass();
		}


		$this->request->data = $list;

		$this->set('list',$list);

	}

	public function admin_freemailing(){

		$this->loadModel('Mailing');

		if($data = $this->request->post()){

			if($data = $this->Mailing->validates($data,'freemailing')){

				$emails = array();
				$content = '';
				$title = '';
				$path = '';

				if(!empty($data->list_id)){
					$e = $this->Mailing->getEmailsByListID($data->list_id);
					foreach ($e as $k => $v) {
						$e[$k] = $v->email; 
					}
					$emails = array_merge($emails,$e);
				}

				if(!empty($data->emails)){

					$e = String::findEmailsInString($data->emails);
					$emails = array_merge($emails,$e);
				}

				if(!empty($data->title)){
					$title = $data->title;
				}

				if(!empty($data->content)){

					$content = $data->content;
				}
				
				if(!empty($_FILES['pj']['name'])){

					if($path = $this->Mailing->saveFile('pj')){

						$path = WEBROOT.DS.$path;
					}

				}
				
				$results = array();
				$results['sended'] = array();
				$results['errors'] = array();
				foreach ($emails as $email) {
					
					if($this->send_freemailing($email,$title,$content,$path)){
						$results['sended'][] = $email;
					}
					else{
						$results['errors'][] = $email;
					}
				}

				if(!empty($results['sended'])){
					$this->session->setFlash(count($results['sended']).' emails envoyés ! ','success');
				}

				if(!empty($results['errors'])){
					$this->session->setFlash(count($results['errors']). ' erreurs d\'envoi... ('.implode(' ; ',$results['errors']).')');
				}
				
			}

		}

		$lists = $this->Mailing->findMailingList();
		$selectLists = array();
		foreach ($lists as $key => $l) {
			$selectLists[$l->list_id] = $l->name;
		}

		$this->set('selectLists',$selectLists);
	}

	private function send_freemailing($dest,$title,$content,$pj = null){		

		//Création d'une instance de swift mailer
		$mailer = Swift_Mailer::newInstance(Conf::getTransportSwiftMailer());

		//Récupère le template et remplace les variables
		$body = file_get_contents('../view/email/freeMailing.html');
		$body = preg_replace("~{content}~i", $content, $body);
		$body = preg_replace("~{title}~i", $title, $body);


		//Création du mail
		$message = Swift_Message::newInstance()
		 ->setSubject($title)
		 ->setFrom(Conf::$contactEmail,Conf::getSiteUrl())
		 ->setTo($dest)
		 ->setBody($body, 'text/html', 'utf-8');

		  if(isset($pj)){
		  	$pj = Swift_Attachment::FromPath($pj);
		  	$message->attach($pj);
		  }

		
		//Envoi du message et affichage des erreurs éventuelles
		if (!$mailer->send($message, $failures))
		{
		   return true;
		}

		return false;

	}

	
}