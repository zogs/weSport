<?php 
class EventsModel extends Model{

	public $validates = array(
		'title' => array(
			'rule'    => 'notEmpty',
			'message' => 'Vous devez préciser un titre'		
		),
		'sport' => array(
			"rules"=>array(
				array(
					'rule'=>'notEmpty',
					'message'=>"Un sport doit être renseigné"
				),
				array(
					'rule'=>'notNull',
					'message'=>"Un sport doit être reseigné"
				)
			)
		),
		'address' => array(
			"rules"=>array(
				array(
					'rule'=>'notEmpty',
					'message'=>"Une adresse doit être renseigné"
				)
			)
		),
		'city' => array(
			"rules"=>array(
				array(
					'rule'=>'notEmpty',
					'message'=>"Une ville doit être renseigné"
				),
				array(
					'rule'=>'notNull',
					'message'=>"Une ville doit être reseigné"
				)
			)
		),
		'date' => array(
			"rules"=>array(
				array(
					'rule'=>'notEmpty',
					'message'=>"Une date doit être renseigné"
				)
			)
		),
		'time' => array(
			"rules"=>array(
				array(
					'rule'=>'notEmpty',
					'message'=>"Un horaire doit être renseigné"
				)
			)
		),
		'nbmin' => array(
			"rules"=>array(
				array(
					'rule'=>'([0-9]+)',
					'message'=>"Nbmin doit être un nombre"
				)
			)
		),
		'user_id' => array(
			"rules"=>array(
				array(
					'rule'=>'([0-9]+)',
					'message'=>"User_id doit être un nombre"
				)
			)
		)

	);

	public function findEvents($params){		

		extract($params);
		

		//If extend arround a city
		// set params to modified the query
		if(!empty($extend)){

			if(!empty($params['cityLat']) && !empty($params['cityLon'])){

				$cityLat = $params['cityLat'];
				$cityLon = $params['cityLon'];
				$distance= $params['extend'];
				//default in km
				$onedegree = 111.045;
				$earthradius = 6366.565;
				if(!empty($km) && $km == true){ // in km
					$onedegree = 111.045;
					$earthradius = 6366.565;
				}
				if(!empty($miles) && $miles==true) {
					$onedegree = 69;
					$earthradius = 3956;
				}			;				

				//calcul of the box
				$lon1 = $cityLon-$distance/abs(cos(deg2rad($cityLat))*$onedegree);
				$lon2 = $cityLon+$distance/abs(cos(deg2rad($cityLat))*$onedegree);
				$lat1 = $cityLat-($distance/$onedegree);
				$lat2 = $cityLat+($distance/$onedegree);

				$distance_field = ", $earthradius * 2 * ASIN(SQRT( POWER(SIN(($cityLat - C.LATITUDE) *  pi()/180 / 2), 2) +COS($cityLat * pi()/180) * COS(C.LATITUDE * pi()/180) * POWER(SIN(($cityLon - C.LONGITUDE) * pi()/180 / 2), 2) )) as distance";
				
			}
			else debug('city missing');
		}


		//Beginning og the query
		$sql = 'SELECT ';

		if(!empty($fields))
			$sql .= $this->sqlfields($fields);
		else
			$sql .= $this->sqlfields('*');

		//add distance field
		if(!empty($extend))
			$sql .= $distance_field;

		//FROM SQL TABLE
		$sql .= ' FROM events as E';

		//add CITIES TABLE
		if(!empty($extend))
			$sql .= " JOIN world_cities as C ON C.UNI = E.city ";


		$sql .= ' WHERE 1=1 ';


		//date
		if(!empty($date)){
			if(is_string($date))
				$sql .= 'AND '.$date;
			elseif(is_array($date)){
				if(isset($date['day']))
					$sql .= ' AND E.date = "'.$date['day'].'"';
			}
			$sql .=' ';
		}
		else {
			$sql .= ' AND E.date >= CURDATE() ';
		}

		//location
		if(!empty($location)){

			$arr = array('city','CC1','ADM1','ADM2','ADM3','ADM4');
			$ADM = array();
			foreach ($arr as $key) {

				if(!empty($location[$key]) && trim($location[$key])!=''){

					if(!empty($extend) && $key=='city') continue; // useful for extend cities to a radius
					
					$ADM[] = $key.'="'.$location[$key].'" ';
				}								
			}
			if(count($ADM)>0)
				$sql .= ' AND '.implode(' AND ',$ADM);
		}

		//extend beetween the box
		if(!empty($extend)){

			$sql .= 'AND C.LONGITUDE BETWEEN '.$lon1.' AND '.$lon2.' AND C.LATITUDE BETWEEN '.$lat1.' AND '.$lat2.' ';

		}

		//get only sports of
		if(!empty($sports)){

			$sql .= 'AND ';

			if(is_array($sports)){

				if(count($sports)>1){
					$arr = array();
					foreach ($sports as $sport) {
					
						if($sport!=0)
							$arr[] = ' E.sport='.$sport;
					}
					$sql .= '( '.implode(' OR ',$arr).' )';
				}
				else {
					if($sports[0]!=0)
						$sql .= ' sport='.$sports[0];
					else
						$sql .= ' sport!=0';
				}
			}
			elseif(is_numeric($sports)){

				if($sports!=0){
					$sql .= 'E.sport='.$sport;		
				}
				else {
					$sql .= ' E.sport!=0 ';
				}
			}


					
		}		

		//get only cities where distance to chosen city < $distance
		if(!empty($extend)){
			$sql .= " having distance < ".$distance;
		}

		//Order by default or perso
		if(!empty($order)){

			$sql .= ' ORDER BY '.$order;
		}
		else {
			$sql .= ' ORDER BY E.date ASC, E.time ASC';
		}

		//set limit
		if(!empty($limit)){

			$sql .= ' LIMIT '.$limit;
		}

		//add string to the end of the query
		if(!empty($end)){

			$sql .= ' '.$end;
		}

		// debug($sql);
		$pre = $this->db->prepare($sql);
		$pre->execute();
		$events = $pre->fetchAll(PDO::FETCH_OBJ);

		
		return $events;
	}

	public function joinEventsParticipants($events){

		if(is_object($events)) {
			$events = array($events);
			$is_object = true;
		}

		//Pour chaque evenement on cherche les participants
		foreach ($events as $event) {
					
					if(is_array($event)) $event = (object) $event;

					$sql = 'SELECT user_id, date FROM sporters WHERE event_id="'.$event->id.'"';
					$pre = $this->db->prepare($sql);
					$pre->execute();
					$participants = $pre->fetchAll(PDO::FETCH_OBJ);

					//Pour chaque participants on va chercher son login , avatar , ect...
					$participants = $this->JOIN('users','login,avatar,age',array('user_id'=>':user_id'),$participants);
		
					//ON associe à l'événement le tableau des participants
					$event->participants = $participants;

		}

		if(isset($is_object))
			return $events[0];
		else
			return $events;

	}


	public function saveParticipants($users,$event){

		if(is_object($users)){
			$users = array($users);
			$is_object = true;
		}

		foreach ($users as $user) {
			
			debug($event);
			$check = $this->find(array('table'=>'sporters','conditions'=>array('event_id'=>$event->id,'user_id'=>$user->user_id)));

			if(empty($check)){

				$s = new stdClass();
				$s->event_id = $event->id;
				$s->user_id = $user->user_id;
				$s->date = date('Y-m-d');
				$s->date_event = $event->date;
				$s->table = 'sporters';

				$this->save($s);

				
			}			
		}

		return true;
	}

	public function joinUserParticipation($events,$user_id){

		if($user_id==0) return $events;

		if(is_object($events)) {
			$events = array($events);
			$is_object = true;
		}

		foreach ($events as $event) {

			$check = $this->findFirst(array('table'=>'sporters',"fields"=>"id",'conditions'=>array('event_id'=>$event->id,'user_id'=>$user_id)));
			if(!empty($check)){
				$event->UserParticipation = $check->id;
			}
		}
		
		if(isset($is_object))
			return $events[0];
		else
			return $events;
	}


} ?>