<?php
class ForumModel extends Model{

	public $tableCategories = 'forum_categories';
	public $tableTopics = 'forum_topics';
	public $tableReplies = 'forum_replies';


	public function findCategories($params = array()){

		return $this->find(array('table'=>$this->tableCategories,'conditions'=>$params));
	}

	public function findTopics($params = array()){

		return $this->find(array('table'=>$this->tableTopics,'conditions'=>$params));
	}

	public function findReplies($params = array()){

		return $this->find(array('table'=>$this->tableReplies,'conditions'=>$params));
	}

}
?>