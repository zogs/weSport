<?php

class Cron{

	public $url; 
	public $page = 1; 
	public $prefix =false; 
	public $data = false; 

	//Permet de récupérer la requete url demandé
	function __construct( $url ) {
		
		$this->url = $url;
		//$this->url = str_replace(BASE_URL."/", "", $_SERVER['REQUEST_URI']); //Recuperation du PATH_INFO 		
	}

	public function get(){

	}

	public function post(){

	}

}