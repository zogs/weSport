<div class="events-week">
<?php
$cdate='';
foreach ($events as $date => $evts):
?>

	<div class="events-colomn">
		<div class="colomn-date">
			<?php

				if($cdate!=$date) {

					if($date == date("Y-m-d"))
						echo '<strong>'.Date::dayoftheweek('Today').'</strong>';				
					else 
						echo '<strong>'.Date::dayoftheweek(date('D',strtotime($date))).'</strong>';
					echo '<i>'.Date::datefr($date).'</i>';
							
				}

			?>
		</div>

<?php

			foreach ($evts as $event):
				
			?>
			
			<div class="event-bb">				

				<div class="event-time">
					<img class="event-logo" src="<?php echo Router::webroot('img/sport_icons/icon_'.$event->sport.'.png');?>" alt="<?php echo $event->sport;?>">
					<?php echo str_replace(':','h',substr($event->getTime(),0,5)); ?>
				</div>

				<div class="event-title">
					<?php echo $event->title; ?>
				</div>	

				<div class="event-meta">
					<span class="event-city">à <?php echo $event->getCityName(); ?></span>
					<a class="event-link" href="<?php echo Router::url('events/view/'.$event->getID().'/'.$event->getSlug());?>">En savoir +</a>
				</div>		

				<div class="event-user">
					<img class="event-avatar" src="<?php echo Router::webroot($event->getAvatar())?>" alt="">
					<div class="event-users">avec <b><?php echo $event->getLogin();?></b> <?php echo '('.Date::yearsSince($event->getAge()).' ans)';?> 						
					<?php if($event->getNbParticipants()>1) echo 'et '.$event->getNbParticipants().' autres'; ?>
					</div>
				</div>

				<?php if($event->getUserParticipation()): ?>
				<div class="event-userin">
					J'en suis 
				</div>
				<?php endif;?>
				
			</div>


			<?php				
			endforeach;

?>	
	</div>
<?php
$cdate = $date;
endforeach;
?>
</div>