<?php 

class String {


	static function random($length = 10){

		return substr(str_shuffle(MD5(microtime())), 0, $length);

	}

	static function directorySeparation($string){

		return str_replace(array('/','\\'), DIRECTORY_SEPARATOR, $string);
	}

	static function slugify($text)
	{
		    // replace non letter or digits by -
		    $text = preg_replace('~[^\\pL\d]+~u', '-', $text);
		 
		    // trim
		    $text = trim($text, '-');
		 
		    // transliterate
		    if (function_exists('iconv'))
		    {
		        $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);
		    }
		 
		    // lowercase
		    $text = strtolower($text);
		 
		 
		    // remove unwanted characters
		    $text = preg_replace('~[^-\w]+~', '', $text);
		 
		    if (empty($text))
		    {
		        return 'n-a';
		    }
		 
	    return $text;
	}

	static function br2nl($foo) {
		return preg_replace("/\<br\s*\/?\>/i", "\n", $foo);
	}

	static function parse_url($_url = null){
    
	    try{
	    	
	    	//add http if missing
	    	if(strpos($_url,'http://')!==0) $_url = 'http://'.$_url;	    	
	    	
			$parsed = parse_url($_url);
			
			$arr = array();
			$arr['www']='';
			$arr['protocol']='';
			$arr['domain']='';
			$arr['subdomain']='';
			$arr['extension']='';
			$arr['path']='';
			$arr['query']='';
			$arr['all']='';

			if(strpos($parsed['host'],'www.')===0){				
				$arr['www'] = 'www.';
				$parsed['host'] = str_replace('www.','',$parsed['host']);
			}			
			$host = explode('.',$parsed['host']);
			$ln = count($host);
			$arr['extension'] = $host[$ln-1];
			$arr['domain'] = $host[$ln-2];
			$arr['subdomain'] = ($ln>2)? $host[$ln-3] : '';
			
			if(!empty($parsed['path'])) $arr['path'] = $parsed['path'];
			if(!empty($parsed['query'])) $arr['query'] = $parsed['query'];
			if(!empty($parsed['scheme'])) $arr['protocol'] = $parsed['scheme'].'://';
			
			$arr['all'] = $_url;

			return $arr;
			}
	    catch(Exception $e){
	    	return false;
	    }
	}

} ?>