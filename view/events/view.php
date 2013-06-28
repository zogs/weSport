<div class="viewevent">
	<div class="top-banner">
		<div class="void"></div>
		<div class="fresque"></div>
	</div>
	<?php echo $this->session->flash(); ?>

	<div class="container white-sheet">		
		<?php if($event->timingSituation() == 'past'):?>
				<div class="alert alert-danger"><button class="close" data-dismiss="alert">×</button><p>Cette activité est terminée !</p></div>		

				<?php if($event->isUserParticipate($this->session->user()->getID())):?>
					<div class="alert alert-info">
						<form action="<?php echo Router::url('events/review/'.$event->getID());?>" method="POST">
							<button class="close" data-dismiss="alert">×</button>
							<strong>Vous y êtiez ;)</strong> 
							<input type="text" name="review" placeholder="Comment ça s'est passé ?">
							<input type="hidden" name="event_id" value="<?php echo $event->id;?>">
							<input type="hidden" name="user_id" value="<?php echo $this->session->user()->getID();?>">
							<input type="hidden" name="orga_id" value="<?php echo $event->user_id;?>">
							<input type="hidden" name="token" value="<?php echo $this->session->token();?>">
							<input type="hidden" name="lang" value="<?php echo $this->getLang();?>">
							<input type="submit" value="Envoyer">
						</form>
						
					</div>
				<?php endif;?>	

		<?php endif; ?>
		<section>
			<div class="event-header">
				<div class="event-sport-logo"><span class="ws-icon ws-icon-large ws-icon-halo ws-icon-<?php echo $event->sport->slug;?>"></span></div>
				<div class="event-title">
					<h1><?php echo $event->getTitle();?></h1>
					<div class="event-action">
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
							<a class="btn-ws" href="<?php echo Router::url('users/login');?>">Connexion</a>
							<a class="btn-ws" href="<?php echo Router::url('users/register');?>">Inscription</a>
						<?php endif; ?>
					</div>
				</div>
				
				<div class="event-orga">
					<div class="orga-avatar"><img src="<?php echo $event->author->getAvatar();?>"/></div>
					<div class="orga-info">
						<span class="orga-name"><a href="<?php echo $event->getLinkAuthor();?>"><?php echo $event->author->login;?></a></span>
						<span>Organisateur</span>
						<span><?php echo $event->author->getAge();?> ans</span>
					</div>

				</div>
				<div class="clearfix"></div>				
			</div>
		</section>


		<div class="event-status <?php echo ($event->confirmed==1)? 'event-confirmed' : 'event-pending';?>">
			<?php if($event->confirmed==1): ?>
					<span class="label label-success">Confirmé</span> <?php echo count($event->participants);?> participants						
			<?php endif ?>
			<?php if($event->confirmed==0): ?>
					<span class="label">En attente</span> de <?php echo ($event->nbmin-count($event->participants));?> participants						
			<?php endif;?>				
		</div>
		
		<a href="<?php echo Router::url('/date/'.$this->cookieEventSearch->read('date'));?>"><<</a>

		<article>
			<div class="col_large">
				<h2 class="event-info">
					<span class="ws-icon-calendar"></span>
					<?php

						if($event->date == date("Y-m-d"))
							echo day('fr','Today').' ';				
						else 
							echo day('fr',date('D',strtotime($event->date))).' ';
						echo datefr($event->date).'';

					?>
				</h2>

				<h2 class="event-info">
					<span class="ws-icon-alarm"></span>
					<?php echo str_replace(':','h',substr($event->time,0,5));?>
				</h2>

				<h2 class="event-info">
					<span class="ws-icon-location-2"></span>
					<?php echo $event->getCityName();?>
					<?php if($this->session->user()->online()): ?>
					<small><?php echo stripcslashes($event->address);?></small>
					<?php else: ?>
					<small><?php echo substr(stripcslashes($event->address),0,8).'...';?></small>
					<small><a href="<?php echo Router::url('users/login');?>">Connectez-vous</a> pour voir la suite de l'adresse</small>
					<?php endif; ?>
				</h2>

				<h2 class="event-info">
					<span class="ws-icon-<?php echo $event->sport->slug;?>"></span>
					<?php echo $event->sport->name;?>
				</h2>

				<?php if(!empty($event->description)): ?>
				<div class="block event-description">
					<h3>Description de l'activité</h3>
					<div class="block-content">
						<span><?php echo $event->description;?></span>
					</div>
				</div>
				<?php endif; ?>
				
				<div class="event-discussion">		
					<h3>Question & Commentaires</h3>
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
		</article>

		<aside>
			<div class="col_small">
				<div class="block event-participants">
					<h3><?php echo count($event->participants);?> Participants</h3>
					<div class="block-content">
						<?php foreach ($event->participants as $participant):?>										
							<a href="<?php echo $participant->getLink();?>">
								<img class="event-avatar event-participant-avatar tooltiptop" src="<?php echo $participant->getAvatar();?>" data-toggle="tooltip" title="<?php echo $participant->getLogin().' ('.$participant->getAge().' ans)';?>"/>
								<strong><?php echo $participant->getLogin();?></strong>
								<br />
								<small><?php echo $participant->getAge();?> ans</small>
							</a>
						<?php endforeach;?>
						<?php foreach ($event->uncertains as $participant):?>										
							<a href="<?php echo $participant->getLink();?>"><img class="event-avatar event-uncertains-avatar tooltiptop" src="<?php echo $participant->getAvatar();?>" data-toggle="tooltip" title="<?php echo $participant->getLogin().' ('.$participant->getAge().' ans) (peut être)';?>"/></a>
						<?php endforeach;?>
						<div class="clearfix"></div>
					</div>
				</div>

				<div class="block event-map">
					<h3>Carte géographique</h3>
					<div class="block-content">
						<?php echo $gmap->getGoogleMap(); ?>
					</div>
				</div>
				
				<?php if($event->authorReviewed()): ?>
				<div class="block event-review">
					<h3>Derniers avis sur <a class="event-user" href="<?php echo $event->author->getLink();?>"><?php echo $event->author->getLogin();?></a></h3>
					<div class="block-content">
						<ul>
						<?php foreach ($event->reviews as $key => $review):?>					
							<li>
								<img class="event-avatar tooltiptop" data-toggle="tooltip" data-original-title="<?php echo $review->user->getLogin();?> (<?php echo $review->user->getAge();?> ans)" src="<?php echo $review->user->getAvatar();?>">  
								<?php echo $review->review;?>
							</li>

						<?php endforeach; ?>
						</ul>
						<a href="<?php echo Router::url('users/view/'.$review->orga_id);?>">Voir tous les avis</a>
					</div>
				</div>
				<?php endif; ?>
			</div>
		</aside>

	</div>
</div>

<script type="text/javascript">

</script>