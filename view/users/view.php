<div class="userSheet">
	<div class="container">
		<div class="row">
			<img src="<?php echo Router::url($user->getAvatar());?>" />
			<h1><?php echo $user->getLogin();?></h1>
			<?php echo $user->getAge();?> ans
		</div>

		<div class="row">
			<div class="span6">
				<h3>Organise</h3>
				<ul>
					<?php foreach ($hasOrganized as $event):?>
						<li>
							<img src="<?php echo $event->getSportLogo();?>"><a href="<?php echo Router::url('events/view/'.$event->id.'/'.$event->slug);?>"><?php echo $event->getTitle();?></a>
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


				
			</div>
			<div class="span6">
				<h3>participe</h3>		
				<ul>
					<?php foreach ($futurParticipation as $event):?>
						<li><img src="<?php echo $event->getSportLogo();?>"><a href="<?php echo Router::url('events/view/'.$event->id.'/'.$event->slug);?>"><?php echo $event->title;?></a></li>
					<?php endforeach; ?>
				</ul>				
			</div>
		</div>

		<div class="row">
			<div class="span12">
				<p>
					a organisé <?php echo count($hasOrganized); ?> événements
				</p>
				<p>
					a participé à <?php echo count($pastParticipation);?> événements
				</p>						
			</div>
		</div>

		<div class="row">
			<div class="span12">
				<h3>Avis</h3>

				<?php foreach ($eventsReviewed as $key => $event): ?>
				<div class="event-review">
					<div class="bulle">
						<span><?php echo $event->review; ?></span>				
						<small><?php echo $event->title; ?></small>						
					</div>
				</div>
				<?php endforeach; ?>
				
			</div>
		</div>
		
	</div>
</div>