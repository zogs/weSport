<div class="viewevent">
	<div class="top-banner">
		<div class="void"></div>
		<div class="flash">
			<?php echo $this->session->flash(); ?>			
		</div>
		
	</div>

	<div class="container">
		<div class="calendar-return"><a class="tooltiptop" data-toggle="tooltip" title="Retour au calendrier" href="<?php echo Router::url('calendar/date/'.$this->cookieEventSearch->read('date'));?>"></a></div>	
		<div class="fresque fresque-mini"></div>
		<div class="white-sheet">
			
			<section>
				<div class="event-header">

					<h1 class="event-title">
						<span class="ws-icon ws-icon-large ws-icon-halo ws-icon-<?php echo $event->sport->slug;?> event-sport-logo"></span>
						<?php echo $event->getTitle();?>
					</h1>

					
					<div class="event-orga">
						<div class="orga-avatar"><img src="<?php echo $event->author->getAvatar();?>"/></div>
						<div class="orga-info">
							<span><small>Organisateur</small></span>
							<span class="orga-name"><a href="<?php echo $event->getLinkAuthor();?>"><?php echo $event->author->login;?></a></span>
							<span class="orga-age"><?php echo $event->author->getAge();?> ans</span>
						</div>

					</div>				
				</div>
			</section>

			<div class="event-action-bar <?php echo ($event->confirmed==1)? 'event-confirmed' : 'event-pending';?> <?php echo ($event->timing=='past')? 'event-finished':'';?>">
				<div class="event-action">
					<?php if($event->timing=='tocome'): ?>
						<?php if($this->session->user()->online()): ?>
							<?php if(!$event->isAdmin($this->session->user()->getID())): ?>
								<?php if(isset($event->UserParticipation)): ?>
									<a class="btn-ws" > 
										<i class="icon icon-ok-sign icon-white"></i>
										<?php if($event->UserParticipation->proba==1): ?> Vous participez !<?php endif;?>
										<?php if($event->UserParticipation->proba==0): ?> Peut être !<?php endif;?>
									</a>
									<a class="btn btn-link" href="<?php echo Router::url('events/removeParticipant?event_id='.$event->id.'&user_id='.$this->session->user()->getID());?>"><i class="icon-remove"></i> Annuler</a>
								<?php else: ?>
									<a class="btn-ws" href="<?php echo Router::url('events/addParticipant?event_id='.$event->id.'&user_id='.$this->session->user()->getID().'&proba=1');?>">											
										Je veux participer !
									</a>
									<a style="display:none" class="btn btn-info" href="<?php echo Router::url('events/addParticipant?event_id='.$event->id.'&user_id='.$this->session->user()->getID().'&proba=0');?>">											
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
					<?php endif; ?>

					</div>
				
				<div class="event-status">
				<?php if($event->timing=='tocome'): ?>
					<?php if($event->confirmed==1): ?>
							<span class="label label-success">Confirmé</span> <span class="nb-participant"> <?php echo count($event->participants);?> participants</span>
					<?php endif ?>
					<?php if($event->confirmed==0): ?>
							<span class="label">En attente</span> <span class="nb-participant">de <?php echo ($event->nbmin-count($event->participants));?> participants</span>						
					<?php endif;?>	
				<?php elseif($event->timing=='past'): ?>			
							<span class="label">Terminé</span> <span class="nb-participant"><abbr title="<?php echo $event->date;?>" class="date timeago"><?php echo $event->getDatetime();?></abbr></span>
				<?php endif; ?>
				</div>
			</div>

			<?php 
			/*===========
			==== User review 
			=============*/
			if($this->session->user()->online() && $event->timing=='past' && isset($event->UserParticipation)): ?>
			<div class="block event-userreview">
				<div class="block-content">
					<form action="<?php echo Router::url('events/review/'.$event->getID());?>" method="POST">
						<label for="review">Vous y étiez ?</label>
						<input type="text" name="review" placeholder="Dites nous comment ça s'est passé ?">
						<input type="hidden" name="event_id" value="<?php echo $event->id;?>">
						<input type="hidden" name="user_id" value="<?php echo $this->session->user()->getID();?>">
						<input type="hidden" name="orga_id" value="<?php echo $event->user_id;?>">
						<input type="hidden" name="token" value="<?php echo $this->session->token();?>">
						<input type="hidden" name="lang" value="<?php echo $this->getLang();?>">
						<input type="submit" class="btn-ws btn-ws-small" value="Envoyer">
					</form>					
				</div>							
			</div>
			<?php endif; ?>


			<article>
				<div class="col_large">
					<h2 class="event-info">
						<span class="ws-icon-calendar" title="Date"></span>
						<?php

							if($event->date == date("Y-m-d"))
								echo day('fr','Today').' ';				
							else 
								echo day('fr',date('D',strtotime($event->date))).' ';
							echo datefr($event->date).'';

						?>
					</h2>

					<h2 class="event-info">
						<span class="ws-icon-alarm" title="Heure"></span>
						<?php echo str_replace(':','h',substr($event->time,0,5));?>
					</h2>

					<h2 class="event-info">
						<span class="ws-icon-location-2" title="Ville"></span>
						<?php echo $event->getCityName();?>	
						<small><?php echo $event->lastRegion();?></small>			
					</h2>

					<h2 class="event-info">
						<span class="ws-icon-map-2" title="Adresse"></span>						
						<?php if($this->session->user()->online()): ?>
						<?php echo ucfirst($event->address);?>
						<?php else: ?>
						<?php echo substr(ucfirst($event->address),0,8).'...';?>
						<small><a href="<?php echo Router::url('users/login');?>">Connectez-vous</a> pour voir la suite de l'adresse</small>
						<?php endif; ?>
					</h2>

					<h2 class="event-info">
						<span class="ws-icon-<?php echo $event->sport->slug;?>" title="Sport"></span>
						<?php echo ucfirst($event->getSportName());?>
					</h2>

					<?php if(!empty($event->description)): ?>
					<div class="event-description">
						<h3>Description de l'activité</h3>
						<div class="block-content">
							<span><?php echo $event->getDescription();?></span>
						</div>
					</div>
					<?php endif; ?>
					
					<?php if($this->session->user()->online() && $event->timing=='tocome'): ?>
					<div class="block block-action">
							<?php if(!$event->isAdmin($this->session->user()->getID())): ?>
								<?php if(isset($event->UserParticipation)): ?>
									<a class="btn-ws btn-ws-success" > 
										<i class="icon icon-ok-sign icon-white"></i>
										<?php if($event->UserParticipation->proba==1): ?> Vous participez !<?php endif;?>
										<?php if($event->UserParticipation->proba==0): ?> Peut être !<?php endif;?>
									</a>
									<a class="btn btn-link" href="<?php echo Router::url('events/removeParticipant?event_id='.$event->id.'&user_id='.$this->session->user()->getID());?>"><i class="icon-remove"></i> Annuler</a>
								<?php else: ?>
									<a class="btn-ws" href="<?php echo Router::url('events/addParticipant?event_id='.$event->id.'&user_id='.$this->session->user()->getID().'&proba=1');?>">
										<i class="icon-white icon-ok"></i>
										Je veux en être!
									</a>
									<a style="display:none" class="btn btn-info" href="<?php echo Router::url('events/addParticipant?event_id='.$event->id.'&user_id='.$this->session->user()->getID().'&proba=0');?>">
										<i class="icon-white icon-asterisk"></i>
										Peut-être
									</a>
								<?php endif; ?>
							
							<?php else: ?>	
							<a class="btn btn-small" href="<?php echo Router::url('events/create/'.$event->id);?>">Modifier mon annonce</a>					
							<?php endif;?>												
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
																				'enableInfiniteScrolling'=>false,
																				'levelFormReplyToDisplay'=>0
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
					<div class="block block-red event-participants">
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
						</div>
					</div>

					<div class="block block-green event-map">
						<h3>Carte géographique</h3>
						<div class="block-content">
							<?php echo $gmap->getGoogleMap(); ?>
						</div>
					</div>
					
					<div class="block">
						<script type="text/javascript"><!--
							google_ad_client = "ca-pub-5083969946680628";
							/* WeSport big rectangle */
							google_ad_slot = "1932113308";
							google_ad_width = 336;
							google_ad_height = 280;
							//-->
							</script>
							<script type="text/javascript"
							src="http://pagead2.googlesyndication.com/pagead/show_ads.js">
						</script>
					</div>
				
					<?php if($this->displayEventWeather($event)): ?>
					<div class="block block-yellow event-weather" id="event-weather" data-url="<?php echo Router::url('/events/getEventWeather/'.$event->getID().'/'.$this->session->token());?>">
						<h3>Prévision météo</h3>
						<div class="block-content">
							<div id="weather-content"><small>En attende des données météo...</small></div>							
							<div class="mention"><small>Powered by <a href="http://worldweatheronline.com" target="_blank" title="Worldweatheronline.com">Worldweatheronline.com</strong></small></div>
						</div>
					</div>
					<?php endif; ?>
					
					<?php if($event->authorReviewed()): ?>
					<div class="block block-orange event-reviews">
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
		<div class="fresque"></div>
	</div>
</div>

<script type="text/javascript">

	$(document).ready(function(){

		//Retrieve weather data
		if($('#event-weather').length!=0){

			var obj = $('#event-weather');
			var url = obj.attr('data-url');

			$.ajax({
				type:'GET',
				url: url,
				success: function(html){
					$('#weather-content').empty().html(html);
				}
			});
		}

	});

</script>