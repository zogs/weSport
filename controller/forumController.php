<?php
class ForumController extends Controller{


	public function board(){


	}


	public function index($type = null, $id = null){

		$this->loadModel('Forum');

		if($type=="category" && !empty($id)) $this->indexTopics($id);
		elseif($type=="category" && !isset($id)) $this->indexCategories();
		elseif($type=="topic" && !empty($id)) $this->indexReplies($id);
		elseif($type=="topic" && !isset($id)) $this->indexTopics();
		else $this->indexCategories();

	}

	public function indexCategories(){

		$catz = $this->Forum->findCategories();
		
		$this->set('categories',$catz);
		$this->set('display','category');

	}

	public function indexTopics( $cid = null ){

		$params = array();
		if(isset($cid) && is_numeric($cid)) $params['category'] = $cid;

		$topics = $this->Forum->findTopics($params);

		$this->set('topics',$topics);
		$this->set('display','topic');

	}

	public function indexReplies( $tid = null ){

		$params = array();
		if(isset($tid) && is_numeric($tid)) $params['topic'] = $tid;

		$replies = $this->Forum->findReplies($params);

		$this->set('replies',$replies);
		$this->set('display','reply');		
		
	}

	public function edit( $type = null, $id = null){

		if($type=='category') $this->editCategory($id);
		elseif($type=='topic') $this->editTopic($id);
		elseif($type=='reply') $this->editReply($id);
		else $this->e404();
	}

	public function editCategory(){

	}

	public function editTopic(){

	}

	public function editReply(){

	}

	
}
?>