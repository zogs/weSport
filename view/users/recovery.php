<div class="formulaire">
	
	<?php echo $this->session->flash(); ?>

	<?php if($action=='' || $action=='show_form_email'): ?>

	<form class="form" action="<?php echo Router::url('users/recovery'); ?>" method="POST">
		<div class="form-block">
			<?php echo $this->Form->input('email','Entrer votre adresse email',array('required'=>'required','icon'=>'icon-envelope','placeholder'=>'Entre ton email')) ;?>
			<?php echo $this->Form->input('token','hidden',array('value'=>$this->session->token())) ;?>
			<input type="submit" class="btn btn-large btn-primary" value="Envoyer" />
		</div>
	</form>	

	<?php endif;?>


	<?php if($action=='show_form_password') : ?>

	<form class="form" action="<?php echo Router::url('users/recovery'); ?>" method="POST">

		<div class="form-block">
			
			<?php echo $this->Form->input('code','hidden',array('value'=>$code)) ;?>
			<?php echo $this->Form->input('user','hidden',array('value'=>$user_id)) ;?>	
			<?php echo $this->Form->input('password','Nouveau mot de passe',array('type'=>'password','icon'=>'icon-lock','placeholder'=>'Nouveau mot de passe')) ;?>
			<?php echo $this->Form->input('confirm','Confirmer mot de passe',array('type'=>'password','icon'=>'icon-lock','placeholder'=>'Confirmer mot de passe')) ;?>
			<?php echo $this->Form->input('token','hidden',array('value'=>$this->session->token())) ;?>
			<input type="submit" class="btn btn-large btn-primary" value="Envoyer" />
		</div>	

	</form>

<?php endif ;?>
	
</div>