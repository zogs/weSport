<div class="viewuser">
	<div class="top-banner">
		<div class="void"></div>		
		<div class="flash">
			<?php echo $this->session->flash() ;?>			
		</div>
	</div>

	<div class="container">
		<div class="fresque fresque-mini"></div>
		<div class="calendar-return"><a class="tooltiptop" data-toggle="tooltip" title="Retour au calendrier" rel="nofollow" href="<?php echo Router::url('calendar/date/'.$this->cookieEventSearch->read('date'));?>"> </a></div>	
		<div class="white-sheet">
			<section>
				<div class="content-header">
					<div class="header-left">
						<div class="user-login">
							<img src="<?php echo $user->getAvatar();?>" alt="">
							<h1><?php echo $user->getLogin();?></h1>
							<span class="user-descr">
								<?php if(!empty($user->descr)): ?>
									<?php echo $user->descr;?>
								<?php else: ?>
									- pas encore de description -
								<?php endif;?>
							</span>
						</div>						
					</div>
					<div class="header-right user-info">

							<span><strong><?php echo $user->getAge();?> ans, inscrit depuis <?php echo date('d/m/Y',strtotime($user->date_signin));?></strong></span>
							<span><?php echo count($pastParticipation);?> participation | <?php echo count($hasOrganized);?> organisation</span>
							<span><i><?php 
							$loc = (array) $user->location;
							$loc = array_reverse($loc);
							foreach ($loc as $state) {
								echo $state.', ';
							}							
							?>
							</i></span>
					</div>
				</div>
				<div class="event-action-bar <?php if(count($monthParticipation)==0) echo 'event-pending'; else echo 'event-confirmed';?>">
					<div class="count_activity">
						<span class="label <?php if(count($monthParticipation)!=0) echo 'label-success';?>"><?php echo count($monthParticipation);?></span> sports ce mois-ci						
					</div>
					<div class="which_activity">
						<?php if(!empty($sportsPracticed)): ?>
							Sports pratiqués : <?php foreach ($sportsPracticed as $sport):?><span class="ws-icon ws-icon-small ws-icon-<?php echo $sport->sport_slug;?>"></span><?php endforeach;?>
						<?php endif;?>
					</div>
				</div>
			</section>

			<section>
				<div class="col_large">

					<div class="block block-yellow event-reviews">
						<h3>L'Avis des autres</h3>
						<div class="block-content">
							<ul>
								<?php if(!empty($eventsReviewed)): ?>
									<?php foreach ($eventsReviewed as $key => $review): ?>
									<?php if(!empty($review->event)):?>
									<li>
											<a href="<?php echo Router::url('users/view/'.$review->user->getID().'/'.$review->user->getLogin());?>" rel="me nofollow">
												<img class="event-avatar tooltiptop" src="<?php echo $review->user->getAvatar();?>" alt="" data-toggle="tooltip" data-original-title="<?php echo $review->user->getLogin();?> (<?php echo $review->user->getAge();?> ans)">
											</a>
											<span><?php echo $review->review; ?></span>				
											<small><a href="<?php echo Router::url('events/view/'.$review->event->id.'/'.$review->event->slug);?>"><?php echo $review->event->title; ?></a></small>						
									</li>
									<?php endif; ?>
									<?php endforeach; ?>
								<?php else: ?>
									<li><small class="noevents">Personne n'a encore laisser d'avis</small></li>
								<?php endif;?>
							</ul>
						</div>
					</div>

					<div class="event-discussion">		
						<h3>Discussion</h3>
						<div class="event-comments">
							<?php 

							//Call to comment system
							$this->request('comments','show',array(
																		array('context'=>'user',
																				'context_id'=>$user->getID(),
																				'displayRenderButtons'=>false,
																				'enableInfiniteScrolling'=>false,
																				'levelFormReplyToDisplay'=>0,
																				'enablePreview'=>true,
																				'enableInfiniteScrolling'=>true,
																			)
																	)
											);

							?>
						</div>
					</div>
				</div>

				<div class="col_small">

					<div class="block block-orange">
						<h3>Organise prochainement</h3>
						<div class="block-content">
							<ul>
							<?php if(!empty($hasOrganized)): ?>
							<?php foreach ($hasOrganized as $event): ?>
								<li>
									<a href="<?php echo $event->getUrl();?>">
										<span class="ws-icon ws-icon-small ws-icon-<?php echo $event->sport->slug;?>"></span>
										<?php echo $event->getTitle();?>
									</a>
								</li>
							<?php endforeach; ?>
							<?php else: ?>
								<li>
									<small class="noevents">Pas d'activité de prévu...</small>
								</li>
							<?php endif; ?>
							</ul>							
						</div>
					</div>

					<div class="block block-green">
						<h3>Participe prochainement</h3>
						<div class="block-content">
							<ul>
								<?php if(!empty($futurParticipation)): ?>
								<?php foreach ($futurParticipation as $event): ?>
								<li>
									<a href="<?php echo $event->getUrl();?>">
										<span class="ws-icon ws-icon-small ws-icon-<?php echo $event->sport->slug;?>"></span>
										<?php echo $event->getTitle();?>
									</a>
								</li>
								<?php endforeach; ?>
								<?php else:?>
								<li>
									<small class="noevents">Pas d'activité de prévu...</small>
								</li>
								<?php endif;?>
							</ul>							
						</div>
					</div>
					
				</div>
			</section>
		</div>
		<div class="fresque"></div>
	</div>
</div>
