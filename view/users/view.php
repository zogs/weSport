<div class="viewuser">
	<div class="top-banner">
		<div class="void"></div>		
		<div class="flash">
			<?php echo $this->session->flash() ;?>			
		</div>
	</div>

	<div class="container">
		<div class="fresque fresque-mini"></div>
		<div class="calendar-return"><a class="tooltiptop" data-toggle="tooltip" title="Retour au calendrier" href="<?php echo Router::url('calendar/date/'.$this->cookieEventSearch->read('date'));?>"> </a></div>	
		<div class="white-sheet">
			<section>
				<div class="user-header">
					<div class="user-avatar">
						<img src="<?php echo $user->getAvatar();?>" alt="">
					</div>
					<div class="user-login">
						<h1><?php echo $user->getLogin();?></h1>
						<span><?php 
						foreach ($user->location as $state) {
							echo $state.', ';
						}
						if(!empty($user->city)) echo $user->city;
						?>
						</span>
						<br />
						<span><?php echo $user->getAge();?> ans</span>
					</div>
				</div>
				<div class="user-status event-confirmed">
					<div class="count_activity">
						<span class="label label-success"><?php echo count($monthParticipation);?></span> sports ce mois-ci						
					</div>
					<div class="which_activity">
						Sports pratiqués : <?php foreach ($sportsPracticed as $sport):?><span class="ws-icon ws-icon-small ws-icon-<?php echo $sport->sport_slug;?>"></span><?php endforeach;?>
					</div>
				</div>
			</section>

			<section>
				<div class="col_large">
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
									<strong style="text-transform:capitalize"><?php echo $user->getLogin();?></strong> n'organise pas d'événement en ce moment.
								</li>
							<?php endif; ?>
							</ul>
							<span class="bottom_link"><strong style="text-transform:capitalize"><?php echo $user->getLogin();?></strong> a organisé <?php echo count($hasOrganized);?> activités depuis son inscription</span>
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
																				'enableInfiniteScrolling'=>false
																			)
																	)
											);

							?>
						</div>
					</div>
				</div>

				<div class="col_small">
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
								<?php endif;?>
							</ul>
							<span class="bottom_link"><strong style="text-transform:capitalize"><?php echo $user->getLogin();?></strong> a participé à <?php echo count($pastParticipation);?> activités depuis son inscription</span>
						</div>
					</div>

					<div class="block block-yellow event-reviews">
						<h3>Avis des autres</h3>
						<div class="block-content">
							<ul>
								<?php foreach ($eventsReviewed as $key => $review): ?>
								<?php if(!empty($review->event)):?>
								<li>
										<a href="<?php echo Router::url('users/view/'.$review->user->getID().'/'.$review->user->getLogin());?>">
											<img class="event-avatar tooltiptop" src="<?php echo $review->user->getAvatar();?>" alt="" data-toggle="tooltip" data-original-title="<?php echo $review->user->getLogin();?> (<?php echo $review->user->getAge();?> ans)">
										</a>
										<span><?php echo $review->review; ?></span>				
										<small><a href="<?php echo Router::url('events/view/'.$review->event->id.'/'.$review->event->slug);?>"><?php echo $review->event->title; ?></a></small>						
								</li>
								<?php endif; ?>
								<?php endforeach; ?>
							</ul>
						</div>
					</div>
				</div>
			</section>
		</div>
		<div class="fresque"></div>
	</div>
</div>
