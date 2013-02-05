<?php

                    if(!empty($flash)){
                        $this->session->setFlash($flash['message'],$flash['type']);
                        echo $this->session->flash();
                    }
                    ?>

                    <?php if ($this->session->isLogged()): ?>
                    <form id="smartForm" class="form-ajax" action="<?php echo Router::url('comments/add'); ?>" method="POST">                        

                        <?php 

                        //Si les commentaires sont authorisé OU si c'est l'admin
                        if(
                            !empty($commentsAllow) 
                            ||
                            !empty($isadmin)
                        ): ?>

                        <img class="userAvatarCommentForm" src="<?php echo Router::url($this->session->user('avatar')); ?>" />
                        <textarea name="content" id="smartTextarea" class="formComment" data-url-preview="<?php echo Router::url('comments/preview'); ?>" placeholder="Poser une question ici"></textarea>
                        <input type="hidden" name="context" value="<?php echo $context; ?>" />
                        <input type="hidden" name="context_id" value="<?php echo $context_id; ?>" />                        
                        <input type="hidden" name="type" id="type" value='com' />            
                        <input type="hidden" name="media" id="media" value='' /> 
                        <input type="hidden" name="media_url" id="media_url" value='' /> 
                        <div class="btn-group" id="smartSubmitGroup">
                            <a id="smartSubmit" class="btn btn-small">
                                <i class="icon-envelope"></i> Envoyer
                            </a>                            
                        </div>  
                        <?php 
                        //Si c'est l'admin , il peut mettre un titre a son commentaire
                        if(!empty($isadmin)):
                        ?>
                        <input type="text" name="title" id="title" placeholder="You can put a title to your comment. (will broadcast a NEWS)." />
                        <?php 
                        endif; 
                        ?>

                        <div id="commentSmartPreview"></div>
                        <?php endif; ?>
                    </form>
                    

                    <?php else: ?>
                    <img class="userAvatarCommentForm" src="<?php echo Router::url($this->session->user('avatar')); ?>" />
                    <textarea disabled="disabled" name="content" id="smartTextarea" data-url-preview="<?php echo Router::url('comments/preview'); ?>" placeholder="You must log in to post comment"></textarea>
                    <?php endif; ?>
                    
                    <div style="float:left;width:100%; height:0px;"></div>  

                    <div id="tri" class="btn-toolbar">
                                                            
                        <div class="btn-group pull-right">
                            <span id="ajaxLoader" style="display:none"><img src="<?php echo Router::webroot('img/ajax-loader.gif');?>" alt="Loading" /></span>
                            <a class="btn btn-mini bubble-bottom" title="Display new comments" href="<?php echo Router::url('comments/index/'.$context.'/'.$context_id); ?>" id="refresh_com" data-url-count-com="<?php echo Router::url('comments/tcheck/'.$context.'/'.$context_id.'/'); ?>">
                                <i class="icon-repeat"></i>  Actualiser <span class="badge badge-inverse hide" id="badge"></span>
                            </a>
                        </div>      
                    </div>

                    <div id="comments" data-start="0">
                        <?php
                            //load by jquery
                        ?>
                        
                    </div>
                    <div id="bottomComments">
                        <a  id="showMoreComments" href="" ><span class="icon-arrow-down"></span> Afficher plus de commentaires (<span id="commentsLefts"></span> restants)</a>
                        <div id='loadingComments'><span class="ajaxLoader"></span> Chargement des commentaires ...</div>
                        <div id='noMoreComments'>Pas de commentaires</div>
                        <div id="noCommentYet">Pas encore de questions</div>
                    </div>

                    <div id="hiddenFormReply">
                         <?php if($this->session->isLogged()):?>
                        <form id="formCommentReply" class="formCommentReply" action="<?php echo Router::url('comments/reply'); ?>" method="POST">                
                            <img class="userAvatarCommentForm" src="<?php echo Router::url($this->session->user('avatar')); ?>" />
                            <?php if($this->session->isLogged()):?>
                            <textarea name="content" class="formComment" placeholder="Reply here"></textarea> 
                            <input class="btn btn-small" type="submit" name="" value="Send">
                            <?php else: ?>
                             <textarea disabled='disabled' name="content" placeholder="Log in to comment"></textarea> 
                            <input disabled='disabled' class="btn btn-small" type="submit" name="" value="Send">
                            <?php endif;?>
                            <input type="hidden" name="context" value="<?php echo $context; ?>"  />
                            <input type="hidden" name="context_id" value="<?php echo $context_id; ?>"/>
                            <input type="hidden" name="type" value="com" />
                            <input type="hidden" name="reply_to" />                                                       
                        </form>
                        <?php endif ;?>
                    </div>
                   