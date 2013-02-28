<div class="view_event">
	<?php echo $this->session->flash(); ?>
	<div class="event">	

		<div class="main-content">
			
			<div class="sport"><img src="" alt=""/></div>

			<div class="date">
				<div class="time"><?php echo str_replace(':','h',substr($event->time,0,5));?></div>
				<h2>
				<?php

				if($event->date == date("Y-m-d"))
					echo ''.day('fr','Today').'';				
				else 
					echo 'Le '.day('fr',date('D',strtotime($event->date))).' ';
				echo ''.datefr($event->date).'';

				?>
				</h2>
			</div>

			

			<div class="title"><h1><?php echo $event->title;?></h1></div>

			<div class="content">					
				<div class="location">
					<span class="city"><small>à: </small><?php echo $event->city;?></span>
					<span class="address"><small>Lieu: </small><?php echo stripcslashes($event->address);?></span>
				</div>
				<div class="description">
					<span class="description-prefix">Commentaires :</span>
					<span class="description-content"><?php echo $event->description;?></span>
				</div>
			</div>

			<div class="actions">			
				
				<?php if($this->session->isLogged()): ?>
					<?php if(!$event->isAdmin($this->session->user_id())): ?>
						<?php if(isset($event->UserParticipation)): ?>
							<a class="btn btn-large btn-success" > Vous participez </a>
							<a class="btn btn-large btn-inverse" href="<?php echo Router::url('events/removeParticipant?event_id='.$event->id.'&user_id='.$this->session->user_id());?>"><i class="icon-remove icon-white"></i> Je ne veux plus.</a>
						<?php else: ?>
							<form action="<?php echo Router::url('events/addParticipant');?>" method="GET">
								<?php echo $this->Form->input("user_id","hidden",array("value"=>$this->session->user_id())) ;?>
								<?php echo $this->Form->input("event_id","hidden",array("value"=>$event->id)) ;?>
								<?php echo $this->Form->input("Je viens !","submit",array("class"=>"btn btn-large btn-primary")) ;?>				
							</form>
						<?php endif; ?>
					
					<?php else: ?>	
					<a class="btn btn-large" href="<?php echo Router::url('events/create/'.$event->id);?>">Modifier mon annonce</a>					
					<?php endif;?>
				<?php else: ?>		
					Vous devez <a href="<?php echo Router::url('users/login');?>">vous connecter</a> pour participer à cet événement
				<?php endif; ?>
			</div>

			<div class="comments">			
				<?php $this->request('comments','show',array('events',$event->id));?>
			</div>

		</div>

		
		<div class="right-content">
				
			<div class="right-block orga">
				<h2>Organisé par:</h2>
				<img class="avatar"src="<?php echo Router::webroot($event->avatar);?>" alt="">					
				<a href=""><?php echo $event->login;?></a>
				<small>(<?php echo ageFromBY($event->age);?> ans )</small>
			</div>
			

			<div class="right-block participants">
				<h3><?php echo count($event->participants);?> participants</h3>
				<ul>
					<?php foreach ($event->participants as $participant):?>
						<li><img src="<?php echo Router::webroot($participant->avatar);?>"/>
							<a href=""><?php echo $participant->login;?></a>
							<small>( <?php echo ageFromBY($participant->age);?> ans )</small>
					<?php endforeach;?>
				</ul>
			</div>
		</div>
	
	</div>


</div>
