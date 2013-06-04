<div class="container">
	<div class="pub">PUB</div>
	<?php echo $this->session->flash(); ?>
	<a href="<?php echo Router::url('pages/home/'.$this->cookieEventSearch->read('date'));?>">Retour au calendrier</a>
	<div class="event">	

		<?php if($event->timingSituation() == 'past'):?>
			<div class="alert alert-danger"><button class="close" data-dismiss="alert">×</button><p>Cette activité est terminée !</p></div>		

			<?php if($event->isUserParticipate($this->session->user()->getID())):?>
				<div class="alert alert-info">
					<form action="<?php echo Router::url('events/review/'.$event->getID());?>" method="POST">
						<button class="close" data-dismiss="alert">×</button>
						<strong>Vous y êtiez ;)</strong> 
						<input type="text" name="review" placeholder="Comment ça s'est passé ?">
						<input type="hidden" name="token" value="<?php echo $this->session->token();?>">
						<input type="submit" value="Envoyer">
					</form>
					
				</div>
			<?php endif;?>	

		<?php endif; ?>

		
		<div class="span7">
			
			<div class="event-title">
				<img class="event-logo" src="<?php echo $event->getSportLogo();?>" alt="<?php echo $event->sport;?>">				
				<h1><?php echo $event->title;?></h1></div>

				<div class="event-confirm">
					<?php if($event->confirmed==1): ?>
					<span class="label label-success">Confirmé</span>
					<?php endif ?>
					<?php if($event->confirmed==0): ?>
					<span class="label">En attente</span> de <?php echo ($event->nbmin-count($event->participants));?> participants
					<?php endif;?>
				</div>

				<div class="event-actions">			
				
					<?php if($this->session->user()->online()): ?>
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
									Compter sur moi !
								</a>
								<a style="display:none" class="btn btn-info" href="<?php echo Router::url('events/addParticipant?event_id='.$event->id.'&user_id='.$this->session->user()->getID().'&proba=0');?>">
									<icon class="icon-white icon-asterisk"></icon>
									Peut-être
								</a>
							<?php endif; ?>
						
						<?php else: ?>	
						<a class="btn btn-small" href="<?php echo Router::url('events/create/'.$event->id);?>">Modifier mon annonce</a>					
						<?php endif;?>
					<?php else: ?>		
						<a class="btn" href="<?php echo Router::url('users/login');?>">Connectez-vous pour participer !</a>
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
				<h2 class="event-info"><?php echo $event->getCityName();?></h2>
				<?php if($this->session->user()->online()): ?>
				<h3 class="event-info"><?php echo stripcslashes($event->address);?></h3>
				<?php else: ?>
				<small><a href="<?php echo Router::url('users/login');?>">Connectez-vous</a> pour voir l'adresse</small>
				<?php endif; ?>

			</div>

			<div class="event-organizer">
				<i class="icon icon-user"></i>					
				<h4 class="event-info">Organisateur :</h4>
				<a class="event-user" href="<?php echo $event->getLinkAuthor();?>"><?php echo $event->author->getLogin();?></a>
				<span class="event-info">(<?php echo $event->author->getAge();?> ans )<span>
			</div>

			<?php if($this->session->user()->online()): ?>
			<div class="event-description">
				<img src="<?php echo Router::webroot('img/icon-quote-left.png');?>" alt="" class="lquote">
				<img src="<?php echo Router::webroot('img/icon-quote-right.png');?>" alt="" class="rquote">
				<span><?php echo $event->description;?></span>
			</div>
			<?php endif; ?>


			<div class="event-participants">
				<i class="icon icon-user"></i>
				<h4 class="event-info">Participants :</h4>
				<div class="event-participants-avatars">
				<?php foreach ($event->participants as $participant):?>										
					<a href="<?php echo $participant->getLink();?>"><img class="event-avatar event-participant-avatar" src="<?php echo Router::webroot($participant->avatar);?>" data-toggle="tooltip" title="<?php echo $participant->getLogin().' ('.$participant->getAge().' ans)';?>"/></a>
				<?php endforeach;?>
				<?php foreach ($event->uncertains as $participant):?>										
					<a href="<?php echo $participant->getLink();?>"><img class="event-avatar event-uncertains-avatar" src="<?php echo Router::webroot($participant->avatar);?>" data-toggle="tooltip" title="<?php echo $participant->getLogin().' ('.$participant->getAge().' ans) (peut être)';?>"/></a>
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
																		'context_id'=>$event->id,
																		'displayRenderButtons'=>true,
																		'enableInfiniteScrolling'=>false
																	)
															)
									);

					?>
				</div>
			</div>

		</div>

		
		<div class="span4">
			<?php if($this->session->user()->online()): ?>
			<div>					        
				<?php echo $gmap->getGoogleMap(); ?>
			</div>
			<?php endif; ?>
		</div>
	
	</div>

</div>

<script type="text/javascript">

	$(document).ready(function(){

		$('.event-avatar').tooltip({placement:'top'});

	});

</script>