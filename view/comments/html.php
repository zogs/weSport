 <?php 
    header ('Content-type: text/html; charset=utf-8');


    function show_comments($coms,$user,$config){

        $html='';
        if(!is_array($coms)) $coms = array($coms);
        foreach ($coms as $com) {            

            if(isset($com->thread )){

                if($com->thread == 'manifNews'){

                    $html .= html_comment( $com , $user);
                }

                if($com->thread == 'joinProtest'){

                    $html .= html_joinProtest( $com , $user);
                }
            }

            else {

                $html.= html_comment($com, $user, $config);

               

                    $html.= show_replies($com,$user,$config);
                
            }
        }

        return $html;
    }


    function show_replies($com,$user,$config){
        
        $html = '<div class="replies">'; 

        //if display reply if there are replies
        if($config->displayReply && !empty($com->replies)){

            $replyshowed = array_slice($com->replies, 0, $config->repliesDisplayPerComment);
            $replyhidden = array_slice($com->replies, $config->repliesDisplayPerComment);

            $html .= show_comments($replyshowed,$user,$config);

            if(!empty($replyhidden)){
               
            $html .= '<div class="showHiddenReplies">';
            if($config->repliesDisplayPerComment==0){
                $html .= '<a href="#" class="showReplies">Afficher les '.count($replyhidden).' réponse(s)</a>';
            }
            else {
                $html .= '<a href="#" class="showReplies">Afficher '.count($replyhidden).' autres réponse(s)</a>';
            }
            $html .= '</div>';
            $html .='<div class="hiddenReplies">';
            $html .= show_comments($replyhidden,$user,$config);
            $html .= '</div>';
            }
        }
       
        //Reply form
        if($config->showFormReply ){

            $html.= "<form class='formCommentReply' action='".Router::url('comments/reply')."' method='POST'>                
                        <img class='userAvatarCommentForm' src='".Router::webroot($user->getAvatar())."' />
                    ";
                if($user->user_id!=0){
                $html .= "<textarea name='content' class='formComment' placeholder='Reply to ".$com->user->getLogin()."'></textarea> 
                            <input class='btn btn-small' type='submit' name='' value='Send'>";
                }
                else {
                $html .= "<textarea disabled='disabled' name='content' placeholder='Log in to comment'></textarea>
                            <input disabled='disabled' class='btn btn-small' type='submit' name='' value='Send'>";
                }
            
            $html .= "  <input type='hidden' name='context' value='".$config->context."' />
                        <input type='hidden' name='context_id' value='".$config->context_id."'/>
                        <input type='hidden' name='type' value='com' />
                        <input type='hidden' name='reply_to' value='".$com->id."' />                            
                        
                    </form>" ;
        }

        $html .= '</div>';  

        return $html;

    }


    //Renvoi le html d'un commenaitre
    //@param $com {objet} du com
    //@param $cuser {objet} de l'user
    function html_comment($com,$cuser,$config){
        
        ob_start();
        ?>
            <div class="thread post <?php echo ($com->reply_to!=0)? 'reply':'';?> <?php echo 'type_'.$com->type;?> <?php echo (!empty($com->title))? 'type_news':'';?>" id="<?php echo 'com'.$com->id; ?>">  
                <img class="logo" src="<?php echo Router::webroot($com->user->getAvatar()) ?>" alt="image avatar" />
            
                <div>   
                    <?php if(!empty($com->title)): ?>                 
                    <div class="title"><?php echo $com->title; ?></div>                                
                    <?php endif; ?>
                    <div class="user"><?php echo $com->user->getLogin();?></div>
                    <abbr class="date" title="<?php echo $com->date;?>"><?php echo $com->date;?></abbr>
                    
                    <div class="content comment_<?php echo $com->type;?>">    
                                            
                        <?php if($com->isModerate() ): ?>
                        <span class="commentIsModerate"><?php echo $com->isModerate('msg'); ?> <a href="#"> Afficher quand même </a></span>
                        <?php endif; ?>

                        <div>
                        <?php

                        switch ($com->type) {
                            case 'com':
                                $content = $com->content;
                                break;
                            
                            case 'video':
                                $content = $com->media . $com->content ;
                                break;

                            case 'img':
                                $content = $com->media . $com->content ;
                                break;

                            case 'link':
                                $content = $com->media . $com->content ;
                                break;

                            default:
                                $content = $com->content;
                                break;
                           
                        }

                         echo str_replace("\\",'',$content);

                        ?>
                        </div>

                        <?php if ($cuser): ?>
                        <div class="actions">                                 
                            <div class="btn-group pull-left">
                                <?php if($cuser->user_id!=0): ?>

                                    <?php if($config->allowVoting): ?>
                                    <a class="btn-vote bubbtop" title="Like this comment" data-url="<?php echo Router::url('comments/vote/'.$com->id); ?>" >                      
                                            <span class="badge badge-info" <?php if ($com->note == 0): ?>style="display:none"<?php endif ?>><?php echo $com->note; ?></span>
                                        Like                         
                                    </a> 
                                    <?php endif; ?>

                                    <?php if($config->allowReply): ?>             
                                    <a class="btn-comment-reply" data-comid="<?php echo $com->id; ?>" data-comlogin="<?php echo $com->user->getLogin();?>" href="<?php echo $com->id;?>">Reply</a>                                
                                    <?php endif; ?>

                                    <a href="<?php echo Router::url('comments/view/'.$com->id); ?>" target="_blank">Share</a>

                                    <a href="">Alert</a>
                                <?php else: ?>
                                    <span>Log in to reply</span>
                                <?php endif;?>
                            </div>                    
                        </div>
                        <?php endif; ?>
                    </div> 
                </div>             
            </div>
        <?php
        $html = ob_get_contents();
        ob_end_clean();

        return $html;
    }


?>