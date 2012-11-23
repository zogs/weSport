<?php /**
* 
*/
class Worlds extends Model
{
	

	public function findCountry($CC1 = ''){

 		$res = $this->find(array(
 			'table' => "world_country",
 			'fields'=>array('CC1 as code','FULLNAME as name'),
 			'conditions'=>$CC1,
 			'order'=>'FULLNAME'
 			));

 		return array(
 			'lvl'=>'CC1',
 			'title'=>'Select a country',
 			'list'=>$res
 			);

 	}

 	public function findAllStates($ADM){

 		$states = array();

 		foreach ($ADM as $key => $value) {
			if($key=='CC1'){
				$CC1 = $value;
				$states[$key] = $this->findCountry();
			}
			elseif($key=='ADM1') {

				
				$states[$key] = $this->findStates(array('CC1'=>$CC1,'ADM'=>$key,'ADM_PARENT'=>'useless'));
 				
 			}
 			elseif($key=='city' && $value != 0){

 				$states['city'] = $this->findCities($CC1,$this->formatADMArray($ADM) );

 			} 
 			else {
 						 				
	 				$states[$key] = $this->findStates(array('CC1'=>$CC1,'ADM'=>$key,'ADM_PARENT'=>$parent));
 				 			
			}

			$parent = $value;

 		}

 		return $states; 	

 		
 	}

 	//Renvoi les regions/states/departements/cantons suivant son parent
 	//$params array $data (
 	//				CC1 => code du pays
 	//				ADM => niveau de la region
 	//				ADM_PARENT => code de la region pere
 	public function findStates($data){

 		extract($data);

 		$sql = "SELECT WR.ADM_CODE as code, WR.FULLNAME as name 
 				FROM world_states as WR
 				JOIN world_country as WC ON WC.CC1=WR.CC1";


 		if ($ADM == 'ADM1')
		    $sql .= "	WHERE WR.CC1='" . $CC1 . "' AND WR.DSG='" . $ADM . "' AND WR.LC=WC.LO";
		else
		    $sql .= "	WHERE WR.CC1='" . $CC1 . "' AND WR.ADM_PARENT='" . $ADM_PARENT . "' AND WR.DSG='" . $ADM . "' AND WR.LC=WC.LO";


		$sql .= " GROUP BY WR.FULLNAME ORDER BY WR.FULLNAME";

		 //debug($sql);
 		$res = $this->query($sql);

 		return array(
 			'lvl'=>$ADM,
 			'title'=>'Select a state',
 			'list'=>$res
 			);

 	}
 	
 	//Renvoi les villes suivant la/les regions
 	//$params string $CC1
 	//$params array $ADM (
 	//				ADM1 => code region 1
 	//				ADM2 => code region 2
 	//				ADM3 => 
 	//				ADM..=>
 	public function findCities($CC1, $ADM){

	 	extract($ADM);
 		$sql = "SELECT C.UNI as code, C.FULLNAME as name
				FROM world_cities as C	
				JOIN world_country as WC ON C.CC1=WC.CC1				
				WHERE C.CC1='".$CC1."' ";


			$sql .= " AND (";
			$cond = array();
 			foreach ($ADM as $k => $v) {
 				if(isset($v) && $v != ''){
	 				$v = '"'.mysql_escape_string($v).'"';	 						 		
	 				$cond[] = "C.$k=$v";
	 			}	 				 				 			
 			}
 			$sql .= implode(' AND ',$cond);
 			$sql .= " ) ";
			
		$sql .=" AND ( C.LC=WC.LO OR C.LC='') ORDER BY C.FULLNAME";
		//debug($sql);
		$res = $this->query($sql);

		return array(
			'lvl'=>'city',
			'title'=>'Select a city',
			'list'=>$res
			);
 	}

 	/**
 	* SuggestCities
 	* Find city from the autocompletion
 	* params array(CC1,limit,prefix)
 	*/
 	public function suggestCities($params){

 		

 			(isset($params['CC1']))? $CC1 = $params['CC1'] : $CC1 = Conf::$pays;
 			(isset($params['limit']))? $nbResult = $params['limit'] : $nbResult = 10;
 			if(isset($params['prefix'])) $queryString = $params['prefix'];
 			else return false;

 			$sql = "SELECT DISTINCT City.UNI as city_id, City.FULLNAME as name, City.CC1, City.ADM1, City.ADM2, City.ADM3, City.ADM4 
 								FROM world_cities as City
								LEFT JOIN world_country as Pays ON Pays.CC1=City.CC1
								WHERE City.CC1='" . $CC1 . "' AND (City.LC=Pays.LO OR City.LC='') AND City.FULLNAME LIKE '" . $queryString . "%' LIMIT " . $nbResult;

			$cities = $this->query($sql);

			$array=array();
			foreach ($cities as $city) {
				
				if($city->ADM4=='') unset($city->ADM4);
				if($city->ADM3=='') unset($city->ADM3);
				if($city->ADM2=='') unset($city->ADM2);
				if($city->ADM1=='') unset($city->ADM1);

				$city = $this->JOIN_GEO($city);
				$array[] = $city;
			}
						
			return $array;

 		
 	}

 	public function formatADMArray($array){

 		//Delete first row
 		array_shift($array);

 		$i = 1;
 		$new = array();
 		//Create new array with ADM1,ADM2,ADM3... keys
 		foreach ($array as $key => $value) {

 			if($key!='city') {
 			$new['ADM'.$i] = $value;
 			}
 			$i++; 			
 		}

 		return $new;
 	}

 	/**
 	*	JOIN_GEO
 	* join geo informations to an object
 	* param obj OR array(objs)
 	* obj should have ( $obj->CC1, $obj->ADM1, $obj->AMD2, ...)
	*/
 	public function JOIN_GEO($object){
 		
 		//if object set new array
 		if(is_object($object)) $objs = array($object);

 		else if(is_array($object)) $objs = $object;

 		else return false;

		$res = array();
		foreach ($objs as $obj) {
				
				if(isset($obj->ADM1)){

	 			$obj = $this->JOIN('world_states','FULLNAMEND as ADM1',array(
																					'CC1'=>$obj->CC1,
																					'DSG'=>'ADM1',
																					'ADM_CODE'=>$obj->ADM1,
																					'NT'=>'N'

																				),$obj
									);
		 		}

		 		if(isset($obj->ADM2)){

		 			$obj = $this->JOIN('world_states','FULLNAMEND as ADM2',array(
																						'CC1'=>$obj->CC1,
																						'DSG'=>'ADM2',
																						'ADM_CODE'=>$obj->ADM2,
																						'NT'=>'N'

																					),$obj
									);
		 		}

		 		if(isset($obj->ADM3)){

		 			$obj = $this->JOIN('world_states','FULLNAMEND as ADM2',array(
																						'CC1'=>$obj->CC1,
																						'DSG'=>'ADM3',
																						'ADM_CODE'=>$obj->ADM3,
																						'NT'=>'N'

																					),$obj
									);
		 		}

		 		if(isset($obj->ADM4)){

		 			$obj = $this->JOIN('world_states','FULLNAMEND as ADM4',array(
																						'CC1'=>$obj->CC1,
																						'DSG'=>'ADM4',
																						'ADM_CODE'=>$obj->ADM4,
																						'NT'=>'N'

																					),$obj
									);
		 		}

		 		if(isset($obj->city) && $obj->city!=0 && !empty($obj->city)){

		 			$obj = $this->JOIN('world_cities','FULLNAMEND as city',array('UNI'=>$obj->city),$obj);
		 		}
		 		if(isset($obj->CC1)){

		 			$obj = $this->JOIN('world_country','FULLNAME as CC1',array('CC1'=>$obj->CC1),$obj);
		 		}

		 		$res[] = $obj;
		}
		
 		if(is_object($object)) return $res[0];

 		if(is_array($object)) return $res;

 	}

} ?>