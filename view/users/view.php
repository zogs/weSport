<div class="userSheet">
	<div class="container">
		<div class="row">
			<img src="<?php echo $user->getAvatar();?>" />
			<h1><?php echo $user->getLogin();?></h1>
			<?php echo $user->getAge();?> ans
		</div>

		<div class="row">
			<div class="span6">
				<h3>Organise</h3>
				<ul>
					<?php foreach ($hasOrganized as $event):?>
						<li>
							<img src="<?php echo $event->getSportLogo();?>"><a href="<?php echo Router::url('events/view/'.$event->id.'/'.$event->slug);?>">
								<?php echo $event->getTitle();?>
								<small>le <?php echo Date::datefr($event->date);?></small>
							</a>
							<?php if(!empty($event->reviews)):?>
							<ul>
								
							<?php foreach ($event->reviews as $review):?>
								<li><i><?php echo $review->review;?></i></li>
							<?php endforeach;?>
							</ul>
							<?php endif;?>
						</li>
					<?php endforeach; ?>
				</ul>
				<p>
					a organisé <?php echo count($hasOrganized); ?> événements
				</p>

				
			</div>
			<div class="span6">
				<h3>participe</h3>		
				<ul>
					<?php foreach ($futurParticipation as $event):?>
						<li><img src="<?php echo $event->getSportLogo();?>"><a href="<?php echo Router::url('events/view/'.$event->id.'/'.$event->slug);?>">
							<?php echo $event->title;?>
							<small>le <?php echo Date::datefr($event->date);?></small>
						</a></li>
					<?php endforeach; ?>
				</ul>
				<p>a participé à <?php echo count($pastParticipation);?> activitées</p>		
				<p>a participé à <?php echo count($weekParticipation);?> activitées cette semaine</p>		
				<p>a participé à <?php echo count($monthParticipation);?> activitées ce mois</p>
				<p>a déjà participé aux sports suivants : <?php foreach ($sportsPracticed as $sport):?><?php echo $sport->sport_slug;?><?php endforeach;?></p>
			</div>
		</div>
	

		<div class="row">
			<div class="span12">
				<h3>Avis</h3>

				<?php foreach ($eventsReviewed as $key => $review): ?>
				<?php if(!empty($review->event)):?>
				<div class="event-review">
					<div class="bulle">
						<img class="event-avatar tooltiptop" src="<?php echo $review->user->getAvatar();?>" alt="" data-toggle="tooltip" data-original-title="<?php echo $review->user->getLogin();?> (<?php echo $review->user->getAge();?> ans)">
						<span><?php echo $review->review; ?></span>				
						<small><a href="<?php echo Router::url('events/view/'.$review->event->id.'/'.$review->event->slug);?>"><?php echo $review->event->title; ?></a></small>						
					</div>
				</div>
				<?php endif; ?>
				<?php endforeach; ?>
				
			</div>
		</div>
		
	</div>
</div>