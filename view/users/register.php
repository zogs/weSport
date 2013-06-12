<div class="register">
	<div class="container">
		<?php echo $this->session->flash();?>	
		
		<div class="span6">
			<form class="form no-label" id="form_register" autocomplete="on" action="<?php echo Router::url('users/register'); ?>" method="post" <?php echo (isset($Success))? 'class="hide"':''; ?>>
				<h1>Sign in</h1>

				<?php echo $this->Form->input('login','',array('icon'=>'icon-user','required'=>'required','placeholder'=>"Pseudo",'data-url'=>Router::url('users/check'))) ?>
				<?php echo $this->Form->input('email',"",array('type'=>'email', 'icon'=>"icon-envelope","required"=>"required","placeholder"=>"Email",'data-url'=>Router::url('users/check'))) ?>
				<?php echo $this->Form->input('password','',array('type'=>"password",'icon'=>'icon-lock','required'=>'required','placeholder'=>'Mot de passe')) ?>
				<?php echo $this->Form->input('confirm','', array('type'=>'password','icon'=>'icon-lock','required'=>'required','placeholder'=>'Confirmer le mot de passe')) ?>		
				<?php echo $this->Form->select('sexe','Sexe',array('h'=>'Homme','f'=>'Femme'),array('placeholder'=>'Sexe','icon'=>'icon-star-empty')); ?>
				<div class="control-group" id="control-birthday">
					<label for="birthday" class="control-label"></label>
					<div class="controls">
						<?php echo $this->Form->_select('day',Form::MonthDays(),array('default'=>'1','style'=>'width:20%;float:left','icon'=>'icon-gift')); ?>
						<?php echo $this->Form->_select('month',Form::Months(),array('default'=>'01','style'=>'width:30%;float:left')); ?>
						<?php echo $this->Form->_selectNumber('year',2003,1940,array('default'=>1980,'style'=>'width:30%;float:left')) ?>
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
					<input class="btn btn-large btn-inverse" type="submit" value="C'est parti !" />
					<p>En validant l'inscription j'accepte les conditions d'utilisations</p>
				</div>

			</form>
		</div>

		<div class="span4 offset1">

			<form class="form">
			<a class="btn" href="<?php echo UsersController::link_register_with_facebook();?>">S'inscrire avec facebook</a>
				
			</form>
			
		</div>
	</div>
</div>	

<script type="text/javascript">
	

	
</script>