<div class="container">
	<div class="pub">PUB</div>
	<?php echo $this->session->flash(); ?>
	<div class="event">	
		
		<div class="span7">
			
			<div class="event-title">
				<img class="event-logo" src="<?php echo $event->getSportLogo();?>" alt="<?php echo $event->sport;?>">				
				<h1><?php echo $event->title;?></h1></div>

				<div class="event-actions">			
				
					<?php if($this->session->user()->isLog()): ?>
						<?php if(!$event->isAdmin($this->session->user()->getID())): ?>
							<?php if(isset($event->UserParticipation)): ?>
								<a class="btn btn-success" > 
									<i class="icon icon-ok-sign icon-white"></i>
									<?php if($event->UserParticipation->proba==1): ?> Vous participez !<?php endif;?>
									<?php if($event->UserParticipation->proba==0): ?> Peut être !<?php endif;?>
								</a>
								<a class="btn btn-link" href="<?php echo Router::url('events/removeParticipant?event_id='.$event->id.'&user_id='.$this->session->user()->getID());?>"><i class="icon-remove"></i> Annuler</a>
							<?php else: ?>
								<a class="btn btn-primary" href="<?php echo Router::url('events/addParticipant?event_id='.$event->id.'&user_id='.$this->session->user()->getID().'&proba=1');?>">
									<icon class="icon-white icon-ok"></icon>
									Comptez sur moi !
								</a>
								<a class="btn btn-info" href="<?php echo Router::url('events/addParticipant?event_id='.$event->id.'&user_id='.$this->session->user()->getID().'&proba=0');?>">
									<icon class="icon-white icon-asterisk"></icon>
									Peut-être
								</a>
							<?php endif; ?>
						
						<?php else: ?>	
						<a class="btn btn-small" href="<?php echo Router::url('events/create/'.$event->id);?>">Modifier mon annonce</a>					
						<?php endif;?>
					<?php else: ?>		
						Vous devez <a href="<?php echo Router::url('users/login');?>">vous connecter</a> pour participer à cet événement
					<?php endif; ?>
				</div>

			<div class="event-date">
				<i class="icon icon-calendar"></i>	
				<h2 class="event-info">
					<?php

					if($event->date == date("Y-m-d"))
						echo day('fr','Today').' ';				
					else 
						echo day('fr',date('D',strtotime($event->date))).' ';
					echo datefr($event->date).'';

					?>
				</h2>				
			</div>

			<div class="event-time">
				<i class="icon icon-time"></i>
				<h2 class="event-info">
					<?php echo str_replace(':','h',substr($event->time,0,5));?>
				</h2>
			</div>

			<div class="event-location">
				<i class="icon icon-map-marker"></i>
				<h2 class="event-info"><?php echo $event->city;?></h2>
				<h3 class="event-info"><?php echo stripcslashes($event->address);?></h3>
			</div>

			<div class="event-organizer">
				<i class="icon icon-user"></i>					
				<h4 class="event-info">Organisateur :</h4>
				<a class="event-user" href=""><?php echo $event->getLogin();?></a>
				<span class="event-info">(<?php echo $event->getAge();?> ans )<span>
			</div>

			<div class="event-description">
				<img src="<?php echo Router::webroot('img/icon-quote-left.png');?>" alt="" class="lquote">
				<img src="<?php echo Router::webroot('img/icon-quote-right.png');?>" alt="" class="rquote">
				<span><?php echo $event->description;?></span>
			</div>


			<div class="event-participants">
				<i class="icon icon-user"></i>
				<h4 class="event-info">Participants :</h4>
				<div class="event-participants-avatars">
				<?php foreach ($event->participants as $participant):?>										
					<img class="event-avatar event-participant-avatar" src="<?php echo Router::webroot($participant->avatar);?>" data-toggle="tooltip" title="<?php echo $participant->getLogin().' ('.$participant->getAge().' ans)';?>"/>
				<?php endforeach;?>
				<?php foreach ($event->uncertains as $participant):?>										
					<img class="event-avatar event-uncertains-avatar" src="<?php echo Router::webroot($participant->avatar);?>" data-toggle="tooltip" title="<?php echo $participant->getLogin().' ('.$participant->getAge().' ans) (peut être)';?>"/>
				<?php endforeach;?>
					
				</div>
			
			</div>							

	


			<div class="event-discussion">
				<i class="icon icon-comment"></i>			
				<h4 class="event-info">Discussion</h4>

				<div class="event-comments">
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

		</div>

		
		<div class="span4">
			<div>					        
				<?php echo $gmap->getGoogleMap(); ?>
			</div>
		</div>
	
	</div>

</div>

<script type="text/javascript">

	$(document).ready(function(){

		$('.event-avatar').tooltip({placement:'top'});

	});

</script>