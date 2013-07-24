<div class="connexion">
	<div class="top-banner">
		<div class="void"></div>
		<div class="flash">
			<?php echo $this->session->flash() ;?>			
		</div>		
	</div>
	
	<div class="container">
		<div class="white-sheet">
			<div class="fresque fresque-mini"></div>
			<div class="head-sheet"><h1 class="title-sheet">CONNEXION</h1></div>		

			<form class="form form-ws label-hidden w50 fleft form-register" action="<?php echo Router::url('users/login'); ?>" method='post'>
			
					<p class="intro"></p>
					<?php echo $this->Form->input('login','Pseudo ou email',array('required'=>'required','placeholder'=>'Pseudo ou E-mail','icon'=>'icon-user')); ?>
					<?php echo $this->Form->input('password','Mot de passe',array('type'=>'password','required'=>'required','placeholder'=>'Mot de passe','icon'=>'icon-lock')); ?>							
					<?php echo $this->Form->input('token','hidden',array('value'=>$this->session->token())) ;?>			
					<?php echo $this->Form->input('previous_url','hidden',array('value'=>(isset($_SERVER['HTTP_REFERER']))? $_SERVER['HTTP_REFERER']:'')); ?>
					<?php echo $this->Form->checkbox('remember','',array(0=>'Se souvenir de moi ?'),array('default'=>1)) ;?>
					
					<div class="control-group control-submit">
						<label for="" class="control-label"></label>
						<input type="submit" class="btn-ws" value="Se connecter"/>
						<p class="help help-center">
							<a href="<?php echo Router::url('users/register');?>">Pas encore inscrit ?</a>	    			
						</p>	
						<p class="help help-center">
							<a href="<?php echo Router::url('users/recovery');?>">Mot de passe oublié ?</a>	    			
						</p>

					</div>
			</form>	

			<form class="form-wp form-facebook">
				<p class="intro"></p>
				<a href="<?php echo $this->request('users','link_register_with_facebook');?>">
					<img src="<?php echo Router::webroot('img/connect_with_facebook.png');?>"/>
				</a>				
			</form>

		</div>	
		<div class="fresque"></div>
	</div>	
</div>
