<div class="userSheet">
	<div class="container">
		<div class="row">
			<img src="<?php echo Router::url($user->getAvatar());?>" />
			<h1><?php echo $user->getLogin();?></h1>
			<?php echo $user->getAge();?> ans
		</div>

		<div class="row">
			<div class="span6">
				<h4>participe bientôt à :</h4>		
				<ul>
					<?php foreach ($futurParticipation as $event):?>
						<li><img src="<?php echo $event->getSportLogo();?>"><a href="<?php echo Router::url('events/view/'.$event->id.'/'.$event->slug);?>"><?php echo $event->title;?></a></li>
					<?php endforeach; ?>
				</ul>

				<h4>a participé à :</h4>		
				<ul>
					<?php foreach ($pastParticipation as $event):?>
						<li><img src="<?php echo $event->getSportLogo();?>"><a href="<?php echo Router::url('events/view/'.$event->id.'/'.$event->slug);?>"><?php echo $event->getTitle();?></a></li>
					<?php endforeach; ?>
				</ul>
			</div>

			<div class="span6">
				<h4>a organisé :</h4>
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
		</div>
		
	</div>
</div>