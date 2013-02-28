<?php 

    function show_comments($coms,$user,$context,$context_id){

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

                $html.= html_comment($com, $user);

                if($com->haveReplies() && CommentsController::$displayReply){

                    $html.= show_replies($com,$user,$context,$context_id);
                }
            }
        }

        return $html;
    }


    function show_replies($com,$user,$context,$context_id){
        
        $html = '<div class="replies">'; 

        $comment2show = array_slice($com->replies, 0, CommentsController::$nbDisplayedOnTop);
        $comment2hidden = array_slice($com->replies, CommentsController::$nbDisplayedOnTop);

        $html .= show_comments($comment2show,$user,$context,$context_id);

        if(!empty($comment2hidden)){
           // debug(count($comment2hidden));
        $html .= '<div class="showHiddenReplies">'.count($comment2hidden). ' réponses - <a href="#" class="showReplies">Afficher</a><a href="#" class="hideReplies hide">Cacher</a></div>';  
        $html .='<div class="hiddenReplies">';
        $html .= show_comments($comment2hidden,$user,$context,$context_id);
        }

        if($user->user_id!=0 && $com->replyAllowed() ){

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
            
            $html .= "  <input type='hidden' name='context' value='".$context."' />
                        <input type='hidden' name='context_id' value='".$context_id."'/>
                        <input type='hidden' name='type' value='com' />
                        <input type='hidden' name='reply_to' value='".$com->id."' />                            
                        
                    </form>" ;
        }

        if(!empty($comment2hidden)){
        $html .= '</div>';
        }
        $html .= '</div>';  

        return $html;

    }
    

    function html_joinProtest($protester,$cuser){

        ob_start();
        ?>
        <div class="thread post post_info">
            <img class="logo" src="<?php echo Router::webroot($protester->logo)?>" alt="Logo" />
            <div class="content">
                <abbr class="date" title="<?php echo $protester->date;?>"><?php echo $protester->date;?></abbr>
                <div>
                    <span class="user"><?php echo $protester->login ?> </span>
                    protest
                    <a href="<?php echo Router::url('manifs/view/'.$protester->manif_id.'/'.$protester->slug); ?>"><?php echo $protester->nommanif; ?></a>
                </div>
            </div>
        </div>
        <?php

        $html = ob_get_contents();
        ob_end_clean();

        return $html; 
    }

    //Renvoi le html d'un commenaitre
    //@param $com {objet} du com
    //@param $cuser {objet} de l'user
    function html_comment($com,$cuser){
        
        ob_start();
        ?>
            <div class="thread post <?php echo ($com->reply_to!=0)? 'reply':'';?> <?php echo 'type_'.$com->type;?>" id="<?php echo 'com'.$com->id; ?>">  
                <?php if($com->type=='news'): ?> 
                <img class="logo" src="<?php echo Router::webroot($com->logoManif) ?>" alt="image avatar" />             
                <?php else: ?>
                <img class="logo" src="<?php echo Router::webroot($com->user->getAvatar()) ?>" alt="image avatar" />
                <?php endif; ?>
                <div>                    
                    <div class="user"><?php echo $com->user->getLogin();?></div>
                    <abbr class="date" title="<?php echo $com->date;?>"><?php echo $com->date;?></abbr>
                    <?php if($com->type=='news'): ?>
                    <div class="title"><?php echo $com->head; ?></div>                                
                    <?php endif; ?>
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
                            
                            case 'slogan':
                                $content = $com->content.' <img src="'.Router::webroot('img/megaphone/megaphone'.$com->speaker.'.gif').'" alt="" />';
                                break;

                            case 'video':
                                $content = $com->content . $com->media;
                                break;

                            case 'img':
                                $content = $com->content . $com->media;
                                break;

                            case 'link':
                                $content = $com->content . $com->media;
                                break;

                            case 'news':
                                $content = $com->content . $com->media;
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

                                    <?php if($com->voteAllowed()): ?>
                                    <a class="btn-vote bubbtop" title="Like this comment" data-url="<?php echo Router::url('comments/vote/'.$com->id); ?>" >                      
                                            <span class="badge badge-info" <?php if ($com->note == 0): ?>style="display:none"<?php endif ?>><?php echo $com->note; ?></span>
                                        Like                         
                                    </a> 
                                    <?php endif; ?>

                                    <?php if($com->replyAllowed()): ?>             
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