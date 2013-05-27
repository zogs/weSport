<div class="container">
	<div class="formulaire">
		
		<?php echo $this->session->flash(); ?>

		<div class="form-block">
			<form class="form" action="<?php echo Router::url('users/login'); ?>" method='post'>
			
					
					<?php echo $this->Form->input('login','Identifiant',array('required'=>'required','placeholder'=>'Pseudo ou E-mail','icon'=>'icon-user')); ?>
					<?php echo $this->Form->input('password','Mot de passe',array('type'=>'password','required'=>'required','placeholder'=>'Mot de passe','icon'=>'icon-lock')); ?>		
					<?php echo $this->Form->input('token','hidden',array('value'=>$this->session->token())) ;?>			
					<?php echo $this->Form->checkbox("remember","Se souvenir de moi?",array(0=>''),array('default'=>1)) ;?>
					<input type="submit" class="btn btn-large btn-inverse" value="Se connecter"/>	
					<a href="<?php echo Router::url('users/recovery');?>">Forgot your password ?</a>	    			
			</form>	
		</div>
	</div>	
</div>
