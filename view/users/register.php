<div class="register">
	<div class="top-banner">
		<div class="void"></div>
		<div class="fresque"></div>
	</div>
	<?php echo $this->session->flash(); ?>
	<div class="container">
		<div class="white-sheet">
			<div class="head-sheet"><h1 class="title-sheet">INSCRIPTION</h1></div>

			<form class="form form-ws label-hidden w50 fleft" id="form_register" autocomplete="on" action="<?php echo Router::url('users/register'); ?>" method="post" <?php echo (isset($Success))? 'class="hide"':''; ?>>
				<p class="intro">En 2min via le formulaire suivant:</p>
				<?php echo $this->Form->input('login','Pseudo',array('icon'=>'icon-user','required'=>'required','placeholder'=>"Pseudo",'data-url'=>Router::url('users/check'))) ?>
				<?php echo $this->Form->input('email',"Email",array('type'=>'email', 'icon'=>"icon-envelope","required"=>"required","placeholder"=>"Email",'data-url'=>Router::url('users/check'))) ?>
				<?php echo $this->Form->input('password','Mot de passe',array('type'=>"password",'icon'=>'icon-lock','required'=>'required','placeholder'=>'Mot de passe')) ?>
				<?php echo $this->Form->input('confirm','Confirmer', array('type'=>'password','icon'=>'icon-lock','required'=>'required','placeholder'=>'Confirmer le mot de passe')) ?>		
				<?php echo $this->Form->select('sexe','Sexe',array('h'=>'Homme','f'=>'Femme'),array('placeholder'=>'Sexe','icon'=>'icon-star-empty')); ?>
				<div class="control-group" id="control-birthday">
					<label for="birthday" class="control-label">Date de naissance</label>
					<div class="controls">
						<?php echo $this->Form->_select('day',Form::MonthDays(),array('default'=>'1','style'=>'width:24%;float:left;margin-right:4%','icon'=>'icon-gift')); ?>
						<?php echo $this->Form->_select('month',Form::Months(),array('default'=>'01','style'=>'width:29%;float:left;margin-right:4%')); ?>
						<?php echo $this->Form->_selectNumber('year',2003,1940,array('default'=>1980,'style'=>'width:29%;float:left')) ?>
					</div>
				</div>
				<div class="control-group">
					<label for="pays" class="control-label"></label>
					<div class="controls">
					
							<?php 
							
							$this->request('world','locate',array(array('style'=>'width:100%')));


							?>	

					</div>
				</div>	
				<div class="actions">
					<?php echo $this->Form->input('token','hidden',array('value'=>$this->session->token())) ;?>			
					<div class="control-group control-submit">
						<label for="" class="control-label"></label>
						<input type="submit" class="btn-ws" value="C'est parti mon kiki !"/>	
						<p class="help help-center">
							En validant l'inscription j'accepte les <a href="<?php echo Router::url('conditions-d-utilisation');?>">conditions d'utilisations</a>	    			
						</p>

					</div>					
				</div>

			</form>


		

			<form class="form-wp form-facebook">
				<p class="intro">Ou en 1 clic seulement !</p>
				<a href="<?php echo UsersController::link_register_with_facebook();?>">
					<img src="<?php echo Router::webroot('img/register_with_facebook.png');?>"/>
				</a>				
			</form>
			
			<div class="clearfix"></div>
			<div class="fresque"></div>
		</div>
	</div>
</div>	

<script type="text/javascript">
	

	
</script>