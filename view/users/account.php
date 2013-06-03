<div class="container">
	<div id="account">
		
		<?php echo $this->session->flash();?>

		<div class="span3">		
			<div class="dashboard">		
				<div class="module module-rounded">		
					<a class="" href="/">
					<div class="fleft"><img src="<?php echo Router::webroot($this->session->user()->getAvatar());?>" class="avatar size32" alt="<?php echo $user->login;?>"></div>
					<div class="fleft"><b><?php echo $user->login;?></b></div>
				</div>
				<div class="module module-rounded ">
					<ul class="dashboard-links">
						<?php
							$links = array(										
											'account'=>'Mon Compte',
											'profil'=>'Mon Profil',
											'avatar'=>'Mon Avatar',									
											'password'=>'Mon mot de passe',
											'delete'=>'Suppression'
										);

							foreach ($links as $key => $value) {
								
								if($key==$action)
									echo '<li class="active">';
								else
									echo '<li class="">';
								echo '<a class="" href="'.Router::url('users/account/'.$key).'">'.$value.'</a>';
								echo '</li>';
							}
						?>
					</ul>
				</div>
			</div>
		</div>

		
		<div class="span8">
			<div class="module module-rounded account-form">
				<form class="form" id="account-form" autocomplete="off" action="<?php echo Router::url('users/account/'.$action); ?>" method="post" enctype="multipart/form-data">
					<?php echo $this->Form->_input('action','hidden',array('value'=>$action)); ?>
					<?php echo $this->Form->_input('token','hidden',array('value'=>$this->session->token())) ;?>
					<?php echo $this->Form->_input('user_id','hidden',array('value'=>$this->session->user()->getID())) ;?>

					<?php //=========PROFIL================ ?>
					<?php if($action=='profil'): ?>

						<div class="module-header">
							<h2>Profil</h2>
						</div>
										
						
						<?php echo $this->Form->input('prenom','Prenom',array('icon'=>'icon-user','placeholder'=>'Prenom')); ?>
						<?php echo $this->Form->input('nom','Nom',array('icon'=>'icon-user','placeholder'=>'Nom')); ?>					
						<?php echo $this->Form->SelectNumber('age','Birth year',2006,1950,array('default'=>$user->age,'icon'=>'icon-gift','placeholder'=>"( Votre année de naissance )")) ;?>	

						<div class="control-group">	
							<label for="CC1" class="control-label">Localisation</label>			
							<div class="controls">
								<i class='icon-form icon-home'></i>
								<?php $options = $this->request('world','locate',array(array('obj'=>$user,'style'=>'width:100%'))); ?>					
							</div>
						</div>								

						<input type="submit" class="btn btn-large btn-inverse" value="Save profil" />			
					<?php endif ;?>




					<?php //=========ACCOUNT================= ?>
					<?php if($action=='account'||$action==''): ?>

						<div class="module-header">
							<h2>Mon Compte</h2>
							<p class="subheader"></p>
						</div>

						<?php echo $this->Form->input('login','Login',array('icon'=>'icon-user','required'=>'required','data-url'=>Router::url('users/check'))); ?>
						<?php echo $this->Form->input('email','Email',array('icon'=>'icon-envelope','required'=>'required','type'=>'email','data-url'=>Router::url('users/check'))); ?>
						<?php echo $this->Form->Select('lang','Langue',Conf::$languageAvailable,array('default'=>$user->lang,'placeholder'=>'( votre langue )','icon'=>'icon-book')) ;?>				
						<?php echo $this->Form->input('Sauvegarder','submit',array('class'=>'btn btn-large btn-inverse')) ;?>


					<?php endif ;?>



					
					<?php //=========AVATAR=================== ?>
					<?php if($action=='avatar'): ?>

						<div class="module-header">
							<h2>Mon avatar</h2>
							<p class="subheader">Votre image de profil</p>
						</div>

						<div class='avatar'>
							<img src="<?php echo Router::webroot($user->avatar); ?>" />
						</div>
						
							
						<?php echo $this->Form->input('avatar','Avatar',array('icon'=>'icon-bullhorn','type'=>'file')); ?>
							
						<input type="submit" class="btn btn-large btn-inverse" value="Sauvegarder" />		
				
					<?php endif ;?>


					<?php //=========PASSWORD============== ?>			
					<?php if($action=='password'):?>

						<div class="module-header">
							<h2>Mot de passe</h2>
							<p class="subheader">Changer votre mot de passe</p>
						</div>


						<?php echo $this->Form->input('oldpassword','Ancien mot de passe',array('type'=>'password','icon'=>'icon-lock','placeholder'=>'Ancien mot de passe')); ?>
						<?php echo $this->Form->input('password','Nouveau mot de passe',array('type'=>'password','icon'=>'icon-lock','placeholder'=>'Nouveau mot de passe')); ?>
						<?php echo $this->Form->input('confirm','Confirmer mot de passe',array('type'=>'password','icon'=>'icon-lock','placeholder'=>'Confirmer mot de passe')); ?>
						<input class="btn btn-large btn-inverse" type="submit" value="Sauvegarder" />	
						
					<?php endif ;?>


					<?php //=======DELETE================ ?>
					<?php if($action=='delete'): ?>

						<div class="module-header">
							<h2>Delete</h2>
							<p class="subheader">Do you want to delete your account ?</p>
						</div>

				
						<?php echo $this->Form->input('password','Yes i do', array('type'=>'password','icon'=>'icon-lock','placeholder'=>'Entrer votre mot de passe pour supprimer')); ?>
						<?php echo $this->Form->input('token','hidden',array('value'=>$this->session->token())) ;?>
						<input class="btn btn-large btn-inverse" type="submit" value="Supprimer" />

					<?php endif ;?>



				</form>
			</div>
		</div>
		
	</div>
	
</div>