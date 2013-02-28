<?php

    //Fichier qui contient la fonction qui cré le code du commentaire
    require('html.php');


    //If fail
    if(isset($fail)) {
        echo json_encode(array('fail'=>$fail));
        exit();
    }

    //If there are comments
    if(!empty($coms)){

        //Create the html 
        $html = utf8_decode(show_comments($coms,$this->session->user('obj'),$context,$context_id) );
        $html = htmlentities($html);
        
        echo json_encode(array('content'=>$html));
    }
    else {

        echo '';
    }

?>