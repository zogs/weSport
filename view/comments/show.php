<?php

                    if(!empty($flash)){
                        $this->session->setFlash($flash['message'],$flash['type']);
                        echo $this->session->flash();
                    }
                    ?>


                    <?php 

                    //Si les commentaires sont authorisé OU si c'est l'admin on affiche le formulaire
                    if(true == $this->allowComment ): ?>
                    <form id="commentForm" action="<?php echo Router::url('comments/add'); ?>" method="POST">                        

                        <?php if ($this->session->user()->isLog()): ?>    

                        <img class="userAvatarCommentForm" src="<?php echo Router::url($this->session->user()->getAvatar()); ?>" />
                        <div class="commentFormFields">
                            <?php 
                                //Si c'est l'admin , il peut mettre un titre a son commentaire
                                if($this->allowTitle):?>
                                <input type="text" name="title" id="commentTitle" placeholder="<?php echo $this->titlePlaceholder;?>" />
                                <?php endif; ?>

                            <textarea name="content" id="commentTextarea" class="formComment" data-url-preview="<?php echo Router::url('comments/preview'); ?>" placeholder="<?php echo $this->textareaPlaceholder;?>"></textarea>
                            <input type="hidden" name="context" value="<?php echo $context; ?>" />
                            <input type="hidden" name="context_id" value="<?php echo $context_id; ?>" /> 
                            <input type="hidden" name="config" value='<?php echo json_encode($this->config);?>' />                       
                            <input type="hidden" name="type" id="type" value='com' /> 
                            <input type="hidden" name="lang" value="<?php echo $this->getLang();?>" />           
                            <input type="hidden" name="media" id="media" value='' /> 
                            <input type="hidden" name="media_url" id="media_url" value='' /> 
                            <div class="btn-group" id="commentTextareaButtons">
                                <input type="submit" id="submitComment" class="btn btn-small" value="Envoyer">
                                <a class="btn btn-small dropdown-toggle" data-toggle="dropdown" href="#">              
                                <span class="caret"></span>
                                </a>
                                <ul class="dropdown-menu">                           
                                <li><a href="">Créer une nouvelle discussion</a></li>                
                                </ul>
                            </div>                              
                            <div id="commentPreview"></div>
                        </div>
                        <?php else: ?>
                        <img class="userAvatarCommentForm" src="<?php echo Router::url($this->session->user()->getAvatar()); ?>" />
                        <textarea disabled="disabled" name="content" data-url-preview="<?php echo Router::url('comments/preview'); ?>" placeholder="<?php echo $this->noLoggedPlaceholder;?>"></textarea>
                        <?php endif; ?>
                    </form>                                   
                    <?php endif; ?>
                    
                    <div style="float:left;width:100%; height:0px;"></div>  

                <?php if($this->displayRenderButtons): ?>
                    <div id="tri" class="btn-toolbar">

                        <div class="btn-group pull-right">
                            <a class="btn  btn-mini dropdown-toggle bubble-bottom" title="Type of comments" data-toggle="dropdown" href="#">
                            Type
                            <span class="caret"></span>
                            </a>
                            <ul class="dropdown-menu">
                            <li><a class="type_com" href="?type=com">Commentaires</a></li>
                            <li><a class="type_com" href="?type=img">Images</a></li>
                            <li><a class="type_com" href="?type=video">Vidéo</a></li>
                            <li><a class="type_com" href="?type=all">Tout</a></li>
                            </ul>
                        </div>
                        <div class="btn-group pull-right">
                            <a class="btn btn-mini dropdown-toggle bubble-bottom" title="Ordering comments" data-toggle="dropdown">
                            Ordre
                            <span class="caret"></span>
                            </a>
                            <ul class="dropdown-menu">
                            <li><a class="type_com" href="?order=datedesc">+ récent</a></li>
                            <li><a class="type_com" href="?order=dateasc">+ ancien</a></li>
                            <li><a class="type_com" href="?order=notedesc">mieux noté</a></li>
                            <li><a class="type_com" href="?order=noteasc">moins bien noté</a></li>
                            </ul>
                        </div>  
                        <div class="btn-group pull-right">
                            <a class="btn btn-mini bubble-bottom" title="Display new comments" href="<?php echo Router::url('comments/index/'.$context.'/'.$context_id); ?>" id="refresh_com" data-url-count-com="<?php echo Router::url('comments/tcheck/'.$context.'/'.$context_id.'/'); ?>">
                                <i class="icon-repeat"></i>  Actualiser <span class="badge badge-inverse hide" id="badge"></span>
                            </a>
                            <span id="ajaxLoader" style="display:none"><img src="<?php echo Router::webroot('img/ajax-loader.gif');?>" alt="Loading" /></span>
                            <a class="btn btn-mini dropdown-toggle hide" data-toggle="dropdown" href="#">              
                            <span class="caret"></span>
                            </a>
                            <ul class="dropdown-menu">
                            <li><a class="set_refresh" href="600">Toutes les 10 min</a></li>
                            <li><a class="set_refresh" href="300">Toutes les 5 min</a></li>
                            <li><a class="set_refresh" href="120">Toutes les 2 min</a></li>
                            <li><a class="set_refresh" href="60">Toutes les 1 min</a></li>
                            </ul>
                        </div>      
                    </div>
                <?php endif; ?>

                    <div id="comments" data-start="0" data-comments-url="<?php echo Router::url('comments/index/'.$context.'/'.$context_id); ?>" data-url-count-com="<?php echo Router::url('comments/tcheck/'.$context.'/'.$context_id.'/'); ?>">
                        <?php 
                        // load in ajax 
                        ?>                    
                    </div>
                    <div id="bottomComments">
                        <a  id="showMoreComments" href="" ><span class="icon-arrow-down"></span> Afficher plus de commentaires</a>
                        <div id='loadingComments'><span class="ajaxLoader"></span> Chargement des commentaires ...</div>
                        <div id='noMoreComments'>Fin des commentaires</div>
                        <div id="noCommentYet">Pas encore de commentaires</div>                        
                    </div>

                    <?php if($this->allowReply): ?>
                    <div id="hiddenFormReply">
                         <?php if($this->session->user()->isLog()):?>
                         <div class="replies" id="formCommentReply" >
                            <form class="formCommentReply" action="<?php echo Router::url('comments/reply'); ?>" method="POST">                
                                <img class="userAvatarCommentForm" src="<?php echo Router::url($this->session->user()->getAvatar()); ?>" />
                                <?php if($this->session->user()->isLog()):?>
                                <textarea name="content" class="formComment" placeholder="Reply here"></textarea> 
                                <input class="btn btn-small" type="submit" name="" value="Send">
                                <?php else: ?>
                                 <textarea disabled='disabled' name="content" placeholder="Log in to comment"></textarea> 
                                <input disabled='disabled' class="btn btn-small" type="submit" name="" value="Send">
                                <?php endif;?>
                                <input type="hidden" name="context" value="<?php echo $context; ?>"  />
                                <input type="hidden" name="context_id" value="<?php echo $context_id; ?>"/>
                                <input type="hidden" name="lang" value="<?php echo $this->getLang();?>" />
                                <input type="hidden" name="type" value="com" />                            
                                <input type="hidden" name="reply_to" />                                                       
                            </form>
                         </div>                        
                        <?php endif ;?>
                    </div>
                    <?php endif; ?>
                   