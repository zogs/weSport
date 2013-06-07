<?php

class PagesModel extends Model {
	
	public $table = 'pages';
	public $table_i18n = 'pages_i18n';
	public $key_i18n = 'id_i18n';

	public $validates = array(
		'title' => array(
			'rule'    => 'notEmpty',
			'message' => 'Vous devez préciser un titre'		
		),
		'content'=>array(
			'rule' => 'notEmpty',
			'message' => 'Vous devez remplir le contenu'
		),


	);


	public function getContent($id){

		$content = $this->findFirst(array('conditions'=>array('id'=>$id)));
		return new Page($content);
	}

	public function getPage($id){

		$page = $this->findFirst(array('conditions'=>array('id'=>$id,'type'=>'page')));
		return new Page($page);
	}

	public function findPages(){

		$pages = $this->find(array('conditions'=>array('type'=>'page')));				
		foreach ($pages as $key => $page) {
			$pages[$key] = new Page($page);
		}
		return $pages;
	}

	public function countContent( $type , $lang){

		return $this->findCount('type="'.$type.'"');
	}

	public function findTraduction($page_id){

		$sql = "SELECT lang FROM $this->table_i18n WHERE page_id=$page_id";
		$res = $this->query($sql);

		$langs = array();
		foreach ($res as $r) {
			$langs[] = $r->lang;
		}		
		return $langs;
	}
	public function findTraductions($pages){

		$trad = array();
		foreach ($pages as $key => $page) {
			
			$trad[$page->id] = $this->findTraduction($page->id);
		}
		return $trad;
	}

	public function countPagesTraduction($pages){
		$traductions = array();
		foreach ($pages as $key => $page) {
			
			$t = $this->findTraduction($page->id);

			foreach ($t as $lang) {
				
				if(isset($traductions[$lang])) $traductions[$lang]++;
				else $traductions[$lang] = 1;
			}
		}
		return $traductions;
	}


	public function JOIN_i18n( $page, $lang = '' ){
	
		$page = $this->JOIN($this->table_i18n,'*',array('page_id'=>$page->id,'lang'=>$lang),$page);
		return new Page($page);					
	}

	public function JOINS_i18n($contents,$lang){

		if(empty($contents)) return array();
		if(!is_array($contents)) $contents = array($contents);

		foreach ($contents as $k => $c) {			
			$contents[$k] = $this->JOIN_i18n($c,$lang);
		}

		return $contents;	
	}

	public function i18nExist( $page_id , $lang){

		$c = $this->findFirst(array('table'=>$this->table_i18n,'fields'=>'id_i18n as id','conditions'=>array('page_id'=>$page_id,'lang'=>$lang)));
		
		if(!empty($c)) return $c->id;
		else return false;
	}

	public function saveContent($data){

		
		//Champs des tables
		$page_fields = array('id','position','type','date','online','menu','langDefault');
		$i18n_fields = array('id_i18n','page_id','lang','content','title','date','valid','slug');
		
		//On sauvegarde les métadata de la page
		$c = new stdClass();		
		foreach ($page_fields as $key) {			
			if(!empty($data->$key)) $c->$key = $data->$key;
		}
		if($this->save($c)) 
			$pageID = $this->id; //On recupere l'ID de la page
		else
			throw new zException("Error saving page metadata", 1);

		
		//On sauvegarde la traduction de la page
		$i18n = new stdClass();
		$i18n->table = $this->table_i18n;
		$i18n->key = 'id_i18n';
		$i18n->page_id = $pageID;
		foreach ($i18n_fields as $key) {			
			if(isset($data->$key)) $i18n->$key = $data->$key;
		}	
		
		 if($this->save($i18n)) 
		 	return $pageID;
		 else
		 	throw new zException("Error saving i18n content", 1);

	}

	public function savePage($data){

		$p = new stdClass();
		$p->online = $data->online;
		$p->menu = $data->menu;
		$p->type = 'page';

		if(!empty($data->id)){
			$p->id = $data->id;
		}

		$p->table = $this->table;
		$p->key = 'id';

		if($this->save($p)){
			return true;
		}
	}

	public function deleteContent($id){

		if($this->delete($id)) return true;
		else return false;
	}

	public function deletei18nContents($id){

		$sql = "DELETE FROM ".$this->table_i18n." WHERE page_id=:page_id ";
		$val = array('page_id'=>$id);
		
		if($this->query($sql,$val)){
			return true;
		}
		
	}	
}

class Page {

	public $id;
	public $type;
	public $position;
	public $date;
	public $langDefault;
	public $valid;

	public function __construct($params = array()){
		if(empty($params)) return true;
		foreach ($params as $key => $param) {
			$this->$key = $param;
		}
	}

	public function getTitle(){
		return $this->title;
	}

	public function isOnline(){
		return $this->online;
	}

	public function isOnMenu(){
		return $this->menu;
	}

	public function isTraductionExist(){
		if(!empty($this->lang)) return true;
		return false;
	}

	public function isTraductionValid(){
		if($this->valid==1) return true;
		return false;
	}
}
?>