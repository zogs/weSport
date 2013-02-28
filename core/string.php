<?php 

class String {


	static function random($length = 10){

		return substr(str_shuffle(MD5(microtime())), 0, $length);

	}

	static function directorySeparation($string){

		return str_replace(array('/','\\'), DIRECTORY_SEPARATOR, $string);
	}

} ?>