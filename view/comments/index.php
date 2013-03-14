<?php 

    //Fichier qui contient la fonction qui cré le code du commentaire
    require('html.php');

    header ('Content-type: text/html; charset=utf-8');

    //If fail
    if(isset($fail)) {
        echo json_encode(array('fail'=>$fail));
        exit();
    }


    //If there are comments
    if(!empty($coms)){
        
        //Create the html 
        $html = show_comments($coms,$this->session->user(),$context,$context_id);
        // $html = utf8_encode($html);
        // $html = htmlentities($html);

        echo json_encode(array(
            'html'=>$html,
            'commentsNumber'=>count($coms),
            'commentsTotal'=>$commentsTotal,
            'commentsDisplayed'=>$commentsDisplayed,
            'commentsLeft'=>$commentsLeft)
        ,JSON_HEX_TAG|JSON_HEX_AMP|JSON_HEX_QUOT
        );
    }
    else {

        echo json_encode(array(
            'html'=>'',
            'commentsNumber'=>0,
            'commentsTotal'=>$commentsTotal,
            'commentsDisplayed'=>$commentsDisplayed,
            'commentsLeft'=>0
            )
        ,JSON_HEX_TAG|JSON_HEX_AMP|JSON_HEX_QUOT
        );
    }
    



?>


