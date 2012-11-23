<?php 
class Events extends Model{

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

		$sql = 'SELECT ';

		if(!empty($fields))
			$sql .= $this->sqlfields($fields);
		else
			$sql .= $this->sqlfields('*');

		$sql .= ' FROM events as E';


		$sql .= ' WHERE 1=1 ';

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

		if(!empty($location)){

			$arr = array('city','CC1','ADM1','ADM2','ADM3','ADM4');
			$ADM = array();
			foreach ($arr as $key) {

				if(!empty($location[$key]) && trim($location[$key])!=''){

					$ADM[] = $key.'="'.$location[$key].'" ';
				}								
			}
			if(count($ADM)>0)
				$sql .= ' AND '.implode(' AND ',$ADM);
		}

		if(!empty($date)){

			$sql .= ' AND E.date >= '.$date;
		}
		else {
			$sql .= ' AND E.date >= CURDATE() ';
		}

		if(!empty($order)){

			$sql .= ' ORDER BY '.$order;
		}
		else {
			$sql .= ' ORDER BY E.date ASC, E.time ASC';
		}

		if(!empty($limit)){

			$sql .= ' LIMIT '.$limit;
		}

		if(!empty($end)){

			$sql .= ' '.$end;
		}

		// debug($sql);
		$pre = $this->db->prepare($sql);
		$pre->execute();
		return $pre->fetchAll(PDO::FETCH_OBJ);
	}



} ?>