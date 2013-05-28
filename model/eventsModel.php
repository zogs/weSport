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
		'cityName' => array(
			"rules"=>array(
				array(
					'rule'=>'notEmpty',
					'message'=>"Une ville doit être renseigné"
				),
				array('rule'=>'notNull','message'=>"Une ville doit être renseigné1")
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

			if(!empty($params['cityLat']) && !empty($params['cityLon']) && !empty($params['extend']) 
				&& is_numeric($params['cityLon']) && is_numeric($params['cityLat']) && is_numeric($params['extend'])){

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

				$extend_zone = ", $earthradius * 2 * ASIN(SQRT( POWER(SIN(($cityLat - C.LATITUDE) *  pi()/180 / 2), 2) +COS($cityLat * pi()/180) * COS(C.LATITUDE * pi()/180) * POWER(SIN(($cityLon - C.LONGITUDE) * pi()/180 / 2), 2) )) as distance";
				
			}
			else {

			}
		}


		//values send to pdo prepare()
		$values = array();

		//Beginning og the query
		$sql = 'SELECT ';

		if(!empty($fields))
			$sql .= $this->sqlfields($fields);
		else
			$sql .= $this->sqlfields('*');

		//add distance field
		if(!empty($extend_zone))
			$sql .= $extend_zone;

		//FROM SQL TABLE
		$sql .= ' FROM events as E					

				';

		//add CITIES TABLE
		if(!empty($extend_zone))
			$sql .= " JOIN world_cities as C ON C.UNI = E.cityID ";


		$sql .= ' WHERE 1=1 ';


		//generic conditions
		if(isset($conditions))
			$sql .= ' AND '.$this->sqlConditions($conditions);


		//date
		if(!empty($date)){
			if(is_string($date)){

				if($date=='past')
					$sql .= ' AND E.date < CURDATE()';
				elseif($date=='futur')
					$sql .= ' AND E.date >= CURDATE()';
				elseif($date=='today')
					$sql .= ' AND E.date = CURDATE()';
				else
					$sql .= ' AND '.$date;
			}				
			elseif(is_array($date)){
				if(isset($date['day'])){

					$sql .= ' AND E.date = :date';
					$values[':date'] = $date['day'];
				}
			}
			$sql .=' ';
		}
		

		//location
		if(!empty($location)){

			$arr = array('cityID','CC1','ADM1','ADM2','ADM3','ADM4');
			$ADM = array();
			foreach ($arr as $key) {

				if(!empty($location[$key]) && trim($location[$key])!=''){

					if(!empty($extend) && $key=='cityID') continue; // useful for extend cities to a radius
					
					$ADM[] = $key.'=:'.$key;
					$values[':'.$key] = $location[$key];
					//$ADM[] = $key.'="'.$location[$key].'" ';
				}								
			}
			if(count($ADM)>0)
				$sql .= ' AND '.implode(' AND ',$ADM).' ';
		}

		//extend beetween the box
		if(!empty($extend_zone)){

			$sql .= 'AND C.LONGITUDE BETWEEN :lon1 AND :lon2 AND C.LATITUDE BETWEEN :lat1 AND :lat2 ';

			$values[':lon1'] = $lon1;
			$values[':lon2'] = $lon2;
			$values[':lat1'] = $lat1;
			$values[':lat2'] = $lat2;

		}

		//get only sports of
		if(!empty($sports)){

			$sql .= 'AND ';

			if(is_array($sports)){

				if(count($sports)>1){
					$arr = array();
					foreach ($sports as $sport) {
					
						if(is_numeric($sport) && $sport!=0)
							$arr[] = ' E.sport='.$sport;
						else
							throw new zException("Sport parameter must be an integer", 1);
							
					}
					$sql .= '( '.implode(' OR ',$arr).' )';
				}
				else {
					if(is_numeric($sports[0])){

						if($sports[0]!=0)
							$sql .= ' sport='.$sports[0];
						else
							$sql .= ' sport!=0';
					}
					else
						throw new zException("Sport parameter must be an integer", 1);
					
				}
			}
			else{

				if(is_numeric($sports)){

					if($sports!=0){
						$sql .= 'E.sport='.$sport;		
					}
					else {
						$sql .= ' E.sport!=0 ';
					}
				}
				else
					throw new zException("Sport parameter must be an integer", 1);
			}
					
		}		

		//get only cities where distance to chosen city < $distance
		if(!empty($distance) && is_numeric($distance)){
			$sql .= " having distance < ".$distance;
		}

		//Order by default or perso
		if(!empty($order)){

			$sql .= ' ORDER BY :order';
			$values[':order'] = $order;
		}
		else {
			$sql .= ' ORDER BY E.date ASC, E.time ASC';
		}

		//set limit
		if(!empty($limit) && is_numeric($limit)){

			$sql .= ' LIMIT '.$limit;
		}

		//add string to the end of the query
		if(!empty($end)){

			$sql .= ' '.$end;
		}

		  // debug($sql);
		  // debug($values);
		$results = $this->query($sql,$values);

		$events = array();
		foreach ($results as $event) {
			
			$events[] = new Event($event);
		}

		$events = $this->joinEventsAuthor($events);
		
		return $events;
	}

	public function findEventByID($event_id, $fields = '*'){

		$res = $this->findEvents(array('conditions'=>array('id'=>$event_id)));

		if(!empty($res))
			return $res[0];
		else
			return new Event();
		
	}
	/**
	 * return events in the futur
	 *
	 * @param string $CC1 country code
	 * @param int $number number of events to return
	 * @param int $sport type of sport to return
	 */
	public function getEventsToCome($CC1,$count,$sport=''){

		$sql = 'SELECT * ';
		$sql .= 'FROM events WHERE CC1=:CC1 ';
		$val = array();
		$val['CC1'] = $CC1;

		if(!empty($sport)) {
			$sql .= ' AND sport=:sport';
			$val['sport'] = $sport;
		}

		//day is superior or equal as current day and time is superior as current time
		$sql.= ' AND ( ( date = CURDATE() AND time > CAST("'.date("H:i:s").'" AS time) )';
		//OR following days
		$sql.= ' OR date >= CURDATE() )';
		//number of event to return
		$sql.= ' LIMIT '.$count;
		
		$res = $this->query($sql,$val);
		$events = array();
		foreach ($res as $event) {
			
			$events[] = new Event($event);
		}

		return $events;
	}

	public function joinEventsAuthor($events){

		if(empty($events)) return $events;
		if(is_array($events)){
			foreach ($events as $key => $event) {
				$author = $this->findFirst(array('table'=>'users','conditions'=>array('user_id'=>$event->user_id)));
				//if no author, remove event and jump to the next
				if(empty($author)) {
					unset($events[$key]);
					continue;
				}
				$event->author = new User($author);
			}
			return $events;
		}

		if(is_object($events)){
			$author = $this->findFirst(array('table'=>'users','conditions'=>array('user_id'=>$events->user_id)));
			if(empty($author)) return false;
			$events->author = new User($author);
			return $events;
		}

	}

	public function joinEventsParticipants($events, $proba = 1){

		if(is_object($events)) {
			$events = array($events);
			$is_object = true;
		}

		//Pour chaque evenement on cherche les participants
		foreach ($events as $event) {
					
			if(is_array($event)) $event = (object) $event;

			$participants = $this->findParticipants($event->id, $proba);

			$users = array();
			foreach ($participants as $participant) {
				
				$user = $this->findFirst(array('table'=>'users','conditions'=>array('user_id'=>$participant->user_id)));
				if(!empty($user)) $users[] = new User( $user );
				
			}

			$event->participants = $users;

		}

		if(isset($is_object))
			return $events[0];
		else
			return $events;

	}
	public function eventsParticipants($event_id,$proba){

		$participants = $this->findParticipants($event_id, $proba);

		$users = array();
		foreach ($participants as $participant) {
			$user = $this->findFirst(array('table'=>'users','conditions'=>array('user_id'=>$participant->user_id)));
			if(!empty($user))
				$users[] = new User( $user );
		}

		return $users;
	}

	public function saveEvent($event){


		$event->timestamp = strtotime($event->date.' '.$event->time);

		if($id = $this->save($event)) return $id;

		return false;
	}

	public function saveParticipants($users,$event,$proba = 1){
		
		if(is_object($users)){
			$users = array($users);
			$is_unique = true;
		}

		foreach ($users as $user) {
			
			//debug($event);
			$check = $this->find(array('table'=>'sporters','conditions'=>array('event_id'=>$event->id,'user_id'=>$user->user_id)));

			if(empty($check)){

				$s = new stdClass();
				$s->event_id = $event->id;
				$s->user_id = $user->user_id;
				$s->date = date('Y-m-d');
				$s->date_event = $event->date;
				$s->table = 'sporters';	
				$s->proba = $proba;			
				$this->save($s);			
			}	
				
		}

		return true;
	}

	public function cancelParticipation($user_id){

		$p = new StdClass();
		$p->table = "sporters";
		$p->key = 'id';
		$p->id = $user_id;

		if($this->delete($p)) return true;

		return false;
	}

	public function findParticipants($event_id, $proba = 1){

		if(!is_numeric($event_id)) return false;

		$participants = $this->find(array('table'=>'sporters',
			'fields'=>'user_id',
			'conditions'=>array('event_id'=>$event_id,'proba'=>$proba)));

		return $participants;
	}

	public function countParticipants($event_id){

		$p = $this->findParticipants($event_id);

		return count($p);
	}

	public function confirmEvent($event_id){

		$s = new stdClass();
		$s->table = 'events';
		$s->id = $event_id;
		$s->confirmed = 1;

		if($this->save($s)) return true;

		return false;
	}

	public function cancelEvent($event_id){

		$s = new stdClass();
		$s->table = 'events';
		$s->id = $event_id;
		$s->confirmed = 0;

		if($this->save($s)) return true;

		return false;
	}

	public function joinUserParticipation($events,$user_id){

		if($user_id==0) return $events;

		if(is_object($events)) {
			$events = array($events);
			$is_unique = true;
		}

		foreach ($events as $event) {

			$check = $this->findFirst(array('table'=>'sporters',"fields"=>"*",'conditions'=>array('event_id'=>$event->id,'user_id'=>$user_id)));
			if(!empty($check)){
				$event->UserParticipation = $check;
			}
		}
		
		if(isset($is_unique))
			return $events[0];
		else
			return $events;
	}


	public function findUserFuturParticipations($uid){
		
		$sql = "SELECT * FROM sporters as S
				JOIN events as E ON E.id=S.event_id
				WHERE S.user_id=$uid AND CURDATE() > E.timestamp ";
		$res = $this->query($sql);

		$events = array();
		foreach ($res as $event) {
			$events[] = new Event($event);
		}
		return $events;
	}

	public function findUserPastParticipations($uid){
		
		$sql = "SELECT * FROM sporters as S 
				JOIN events as E ON E.id=S.event_id
				WHERE S.user_id=$uid AND CURDATE() < E.timestamp ";
		$res = $this->query($sql);

		$events = array();		
		foreach ($res as $event) {
			$events[] = new Event($event);
		}
		return $events;
	}

	public function findEventsUserOrganized($uid){

		$sql = "SELECT * FROM events WHERE user_id=$uid ";
		$res = $this->query($sql);

		$events = array();
		foreach ($res as $event) {
			
			$event->reviews = $this->findReviewByEventId($event->id);

			$events[] = new Event($event);
		}		
		return $events;
	}
	
	public function findReviewByEvents($events){

		$reviews = array();
		foreach ($events as $k=>$event) {
			
			if($review = $this->findReviewByEventId($event->id)) $reviews[] = $review;

		}
		return $reviews;
	}

	public function findReviewByEventId($event_id){
		
		$res = $this->find(array('table'=>'events_review','conditions'=>array('event_id'=>$event_id)));
		
		return $res;
	}	

	public function deleteEvent( $event ){
			
			if(!isset($event->id)) throw new zException("id must be defined to $event object", 1);

			$event->table ="events";

			if($this->delete($event))
				return true;
			else
				return false;
			


	}

	public function findReviewByEvent($event_id){

	}

	public function findReviewByUser($user_id){

	}	

	public function saveReview($event_id,$user_id,$review_tx,$lang){

		//if exist
		$exist = $this->findFirst(array('table'=>'events_review','conditions'=>array('event_id'=>$event_id,'user_id'=>$user_id)));
		if(!empty($exist)) return 'already';
		
		$review = new stdClass();
		$review->table = 'events_review';
		$review->event_id = $event_id;
		$review->user_id = $user_id;
		$review->review = $review_tx;
		$review->lang = $lang;

		if($this->save($review))
			return true;
		else
			return false;

	}

	public function testcron($action){

		$s = new stdClass();
		$s->table = 'log';
		$s->type = 'cron';
		$s->log = 'result';
		$s->action = $action;

		if($this->save($s)) {
			exit($action);
			return true;
		}
		return false;
	}

} 

class Event{

	public $id = 0;
	public $sport = 0;
	public $cityID = '';
	public $ADM1 = '';
	public $ADM2 = '';
	public $ADM3 = '';
	public $ADM4 = '';
	public $CC1 = '';

	public function __construct( $fields = array() ){

		foreach ($fields as $field => $value) {
			
			$this->$field = $value;
		}
	}

	public function exist(){

		if($this->id==0) return false;

		return true;
	}

	public function getAuthor(){

		if(isset($this->author->login)) return $this->author->login;
		return '';
	}

	public function getLinkAuthor(){
		return Router::url('users/view/'.$this->user_id.'/'.$this->getAuthor());
	}

	public function isAdmin($user_id){

		if($this->user_id===$user_id) return true;
		return false;
	}

	public function getTime(){

		return $this->time;
	}

	public function getTitle(){

		return $this->title;
	}

	public function getSportLogo(){

		if(file_exists(WEBROOT.'/img/sport_icons/icon_'.$this->sport.'.png')) return Router::webroot('img/sport_icons/icon_'.$this->sport.'.png');
		return Router::webroot('img/sport_icons/icon_curling.png');
	}

	public function getAvatar(){

		return $this->avatar;
	}

	public function getID(){

		return $this->id;
	}

	public function getSlug(){

		return $this->slug;
	}

	public function getAge(){

		return date('Y')-$this->age;
	}

	public function getCityName(){

		return $this->cityName;
	}

	public function getCityID(){

		return $this->cityID;
	}

	public function getRegions($ADM = null){

		if(!isset($ADM)) return $this->ADM4.' '.$this->ADM3.' '.$this->ADM2.' '.$this->ADM1;
		elseif(isset($this->$ADM)) return $this->$ADM;
		else return 'NA';
	}

	public function lastRegion(){
		if(!empty($this->ADM4)) return $this->ADM4;
		if(!empty($this->ADM3)) return $this->ADM3;
		if(!empty($this->ADM2)) return $this->ADM2;
		if(!empty($this->ADM1)) return $this->ADM1;
	}

	public function firstRegion(){

		return $this->ADM1;
	}

	public function getCountry(){

		return $this->CC1;
	}

	public function getNbParticipants(){
		
		return count($this->participants);
	}

	public function getUserParticipation(){

		if(!empty($this->UserParticipation))
			return true;
		return false;		
	}

	public function timingSituation(){

		if($this->date < date('Y-m-d') ) return 'past';
		if($this->date > date('Y-m-d') ) return 'tocome';
		if($this->date == date('Y-m-d') && $this->time >= date('H:i:s')) return 'tocome';
		if($this->date == date('Y-m-d') && $this->time < date('H:i:s')) return 'past';
		if($this->date == date('Y-m-d') && $this->time == date('H:i:s')) return 'current';
	}

	public function isUserParticipate($user_id){

		if($user_id===0) return false;

		foreach ($this->participants as $participant) {
			
			if($participant->getID() === $user_id) return true;
		}

		return false;
	}

}


?>