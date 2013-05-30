<?php 
class UsersModel extends Model{

	public $primaryKey = 'user_id';
	public $table = 'users';

	public $validates = array(
		'register' => array(
			'login' => array(
				'rule'    => 'notEmpty',
				'message' => 'Vous devez choisir un pseudo'		
				),
			'email' => array(
				'rule' => '[_a-zA-Z0-9-+]+(\.[_a-zA-Z0-9-]+)*@[a-zA-Z0-9-]+(\.[a-zA-Z0-9-]+)*(\.[a-z]{2,4})',
				'message' => "L'adresse email n'est pas valide"
				),
			'password' => array(
				'rule' => '.{5,20}',
				'message' => "Votre mot de passe doit etre entre 5 et 20 caracteres"
				),
			'confirm' => array(
				'rule' => 'confirmPassword',
				'message' => "Vos mots de passe ne sont pas identiques"
				),
			'prenom' => array(
				'rule'=> 'optional',
				),
			'nom' => array(
				'rule' => 'optional',
				),
			'age' => array(
				'rules'=> array(
							array(
								'rule'=> '19[0-9]{1}[0-9]{1}',
								'message'=> "Between 1900 and 1999..."
							),
							array(
								'rule'=>'optional',
								'message'=>''
							)
						)
				)
		),
		'account_info' => array(
			'login' => array(
				'rules'=> array(
							array(
								'rule' => 'notEmpty',
								'message' => 'Your login is empty'
									),
							array('rule' => '.{5,20}',
								'message' => 'Login between 5 and 20 caracters'
								)
							)
				),
			'email' => array(
				'rule' => '[_a-zA-Z0-9-+]+(\.[_a-zA-Z0-9-]+)*@[a-zA-Z0-9-]+(\.[a-zA-Z0-9-]+)*(\.[a-z]{2,4})',
				'message' => "L'adresse email n'est pas valide"
				)
		),
		'account_profil' => array(
			
		),
		'recovery_mdp' => array(
			'password' => array(
				'rule' => '.{5,20}',
				'message' => "Votre mot de passe doit etre entre 5 et 20 caracteres"
				),
			'confirm' => array(
				'rule' => 'confirmPassword',
				'message' => "Vos mots de passe ne sont pas identiques"
				)
		),
		'account_password' => array(
			'oldpassword' => array(
				'rule' => 'notEmpty',
				'message' => "Votre mot de passe doit contenir entre 5 et 20 caracteres"
				),
			'password' => array(
				'rule' => '.{5,20}',
				'message' => "Votre mot de passe doit etre entre 5 et 20 caracteres"
				),
			'confirm' => array(
				'rule' => 'confirmPassword',
				'message' => "Vos mots de passe ne sont pas identiques"
				)
		),
		'account_delete' => array(
			'password' => array(
				'rule' => 'notEmpty',
				'message' => "Enter your password"
				)
		),
		'account_avatar'=>array(
			'avatar'=>array(
				'rule'=> 'file',
				'params'=>array(
					'destination' => 'media/users/avatar',
					'extentions'=>array('png','gif','jpg','jpeg','JPG','bmp'),
					'extentions_error'=>'Your avatar is not an image file',
					'max_size'=>100000,
					'max_size_error'=>'Your image is too big',
					'ban_php_code'=>true
					)
			)
		),
	);


	public function saveUser($user,$user_id = null){

		//unset action value
		if(isset($user->action)) unset($user->action);
		//set user_id for updating
		if(isset($user_id)) $user->user_id = $user_id;
		//if there is nothing to save
		$tab = (array) $user;
		if( (count($tab)==0 ) || (count($tab)==1 && isset($tab['user_id'])))
			return true;	

		if($this->save($user))
			return true;
		else
			return false;

	}

	public function saveUserAvatar($user_id){

 		//Les vérifications sont faites dans model/validates

 		$tmp = $_FILES['avatar'];
 		$ext = $extention = substr(strrchr($tmp['name'], '.'),1);
 
 		$newname = 'u'.$user_id.'.'.$ext;
 		$directory = 'media/user/avatar';
 		$destination = $directory.'/'.$newname;

 		
 		if(move_uploaded_file($tmp['tmp_name'], $destination)){
		
 			$user = new StdClass();
 			$user->user_id = $user_id;
 			$user->avatar = $destination;

 			$this->table = 'users';
 			if($this->save($user)){
 				return true;
 			}
 		}
 		else return false;

	}

	public function findUsers($req){

		$sql = 'SELECT ';
 		
 		if(isset($req['fields'])){
			
			$sql .= $this->sqlFields($req['fields']);
 		}
 		else $sql .= ' * ';


 		$sql .= " FROM ";

 		if(isset($req['table'])) $sql .= $req['table'];
 		else $sql .= $this->table;

 		$sql .= " WHERE ";


 		 if(isset($req['conditions'])){ 			
 			
 			$sql .= $this->sqlConditions($req['conditions']);
 			
 		}

 		if(isset($req['order'])){
 			if($req['order'] == 'random') $sql .= ' ORDER BY rand()';
 			else $sql .= ' ORDER BY '.$req['order'];
 		}

 		if(isset($req['limit'])){
			$sql .= ' LIMIT '.$req['limit'];
 		}

 		 // debug($sql);
 		$results = $this->query($sql);
 		

 		if(empty($results)) return array( new User() );

 		$users = array();
 		foreach ($results as $user) {
 					
 			$users[] = new User($user); 
 		}
 		return $users;
	}

	public function findFirstUser($req){

		return current($this->findUsers($req));
	}

	

	// public function findUserThread($user_id){


	// 	$sql=" SELECT P.id,
	//                  'PROTEST' AS TYPE,
	//                  P.date,	                 
	//                  D.name as relatedName,
	//                  D.manif_id as relatedID,
	//                  D.slug     as relatedSlug,
	//                  I.logo     as relatedLogo

 //        		FROM manif_participation as P
 //        		LEFT JOIN manif_descr as D ON D.manif_id=P.manif_id AND D.lang='".$this->session->user()->getLang()."'	
 //        		LEFT JOIN manif_info as I ON I.manif_id=P.manif_id
 //           		WHERE P.user_id = $user_id
 //      		UNION
 //        --   	SELECT `id`,
	//        --           'COMMENT' AS TYPE,
	//        --           `date`
 //        --     	FROM `manif_comment`
	//        --     	WHERE user_id = $user_id AND reply_to=0
 //      		-- UNION
 //       		SELECT C.id,
 //                	'NEWS' AS TYPE,
 //                  	C.date,
 //                  	D.name as relatedName,
 //                  	D.manif_id as relatedID,
 //                  	D.slug     as relatedSlug,
 //                  	I.logo     as relatedLogo
 //              	FROM manif_comment AS C
 //              	LEFT JOIN manif_participation AS P ON P.user_id = $user_id
 //              	LEFT JOIN manif_descr as D ON D.manif_id=P.manif_id AND D.lang='".$this->session->user()->getLang()."'
 //              	LEFT JOIN manif_info as I ON I.manif_id=P.manif_id	
 //             	WHERE C.context_id = P.manif_id AND C.context='manif' AND C.type='news'
	// 		ORDER BY date DESC
	// 		";


	// 	$pre = $this->db->prepare($sql);
 // 		$pre->execute();
 // 		return $pre->fetchAll(PDO::FETCH_OBJ);

	// }

	
}


class User {


	public $user_id = 0;
	public $role  = 'visitor';
	public $avatar  = 'img/logo_yp.png';
	public $account = 'visitor';
	public $email = '';

	public function __construct( $fields = array() ){

		foreach ($fields as $field => $value) {
			
			$this->$field = $value;
		}
	}

	public function getID(){

		return $this->user_id;
	}

	public function exist(){

		if($this->user_id===0) return false;
		return true;
	}

	public function getLogin(){

		if($this->account=='anonym') return 'anonym_'.$this->user_id;
		else return $this->login;
	}

	public function getAvatar(){

		if(isset($this->avatar)&&!empty($this->avatar)&&file_exists(WEBROOT.DS.$this->avatar)) return $this->avatar;
		else return 'img/musclor.jpg';
	}

	public function getLink(){

		return Router::url('users/view/'.$this->getID().'/'.$this->getLogin());
	}

	public function getBonhom(){

		return $this->bonhom;
	}

	public function getRole(){
		return $this->role;
	}

	public function isLog(){
		if($this->user_id!==0) return true;
		return false;
	}
	public function online(){
		if($this->user_id!==0) return true;
		return false;
	}


	public function setLang($lang){
		$this->lang = $lang;
	}

	public function getLang(){
		if(!empty($thus->lang)) return $lang;
		else return Conf::$languageDefault;
	}

	public function isMrZ(){

		if($this->statut=='admin') return true;
		else return false;
	}

	public function getAge(){

		if(isset($this->age))
			return date('Y')-$this->age;
		else 
			return 'N.A';
	}

	public function getBirthyear(){
		return $this->age;
	}

}
 ?>