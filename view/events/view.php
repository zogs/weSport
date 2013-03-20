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
				
				<?php if($this->session->user()->isLog()): ?>
					<?php if(!$event->isAdmin($this->session->user()->getID())): ?>
						<?php if(isset($event->UserParticipation)): ?>
							<a class="btn btn-large btn-success" > 
								<?php if($event->UserParticipation->proba==1): ?> Vous participez !<?php endif;?>
								<?php if($event->UserParticipation->proba==0): ?> Vous participez peut être...<?php endif;?>
							</a>
							<a class="btn btn-large btn-inverse" href="<?php echo Router::url('events/removeParticipant?event_id='.$event->id.'&user_id='.$this->session->user()->getID());?>"><i class="icon-remove icon-white"></i> Annuler</a>
						<?php else: ?>
							<a class="btn btn-large btn-primary" href="<?php echo Router::url('events/addParticipant?event_id='.$event->id.'&user_id='.$this->session->user()->getID().'&proba=1');?>">
								<icon class="icon-white icon-ok"></icon>
								Comptez sur moi !
							</a>
							<a class="btn btn-large btn-info" href="<?php echo Router::url('events/addParticipant?event_id='.$event->id.'&user_id='.$this->session->user()->getID().'&proba=0');?>">
								<icon class="icon-white icon-asterisk"></icon>
								Peut-être
							</a>
						<?php endif; ?>
					
					<?php else: ?>	
					<a class="btn btn-large" href="<?php echo Router::url('events/create/'.$event->id);?>">Modifier mon annonce</a>					
					<?php endif;?>
				<?php else: ?>		
					Vous devez <a href="<?php echo Router::url('users/login');?>">vous connecter</a> pour participer à cet événement
				<?php endif; ?>
			</div>

			<div class="comments">			
				<?php 

				//Call to comment system
				$this->request('comments','show',array(
															array('context'=>'events',
																	'context_id'=>$event->id
																)
														)
								);

				?>
			</div>

		</div>

		
		<div class="right-content">
				
			<div class="right-block orga">
				<h2>Organisé par:</h2>
				<img class="avatar"src="<?php echo Router::webroot($event->getAvatar());?>" alt="">					
				<a href=""><?php echo $event->getLogin();?></a>
				<small>(<?php echo $event->getAge();?> ans )</small>
			</div>
			

			<div class="right-block participants">
				<h3><?php echo count($event->participants);?> participants</h3>
				<ul>
					<?php foreach ($event->participants as $participant):?>						
						<li><img src="<?php echo Router::webroot($participant->avatar);?>"/>
							<a href=""><?php echo $participant->login;?></a>
							<small>( <?php echo $participant->getAge();?> ans )</small>
						</li>
					<?php endforeach;?>
				</ul>
			</div>

			<div class="right-block participants">
				<h3><?php echo count($event->uncertains);?> peut-être</h3>
				<ul>
					<?php foreach ($event->uncertains as $uncertain):?>						
						<li><img src="<?php echo Router::webroot($uncertain->getAvatar());?>"/>
							<a href=""><?php echo $uncertain->getLogin();?></a>
							<small>( <?php echo $uncertain->getAge();?> ans )</small>
						</li>
					<?php endforeach;?>
				</ul>
			</div>

		</div>
	
	</div>


</div>
