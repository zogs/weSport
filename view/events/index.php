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
			
			<div class="events-bb <?php if($event->getUserParticipation()) echo 'events-userin' ?>">				
				<a class="events-link" href="<?php echo Router::url('events/view/'.$event->getID().'/'.$event->getSlug());?>">
					<div class="events-title">	
						<img class="events-logo" src="<?php echo Router::webroot('img/sport_icons/icon_'.$event->sport.'.png');?>" alt="<?php echo $event->sport;?>">				
						<?php echo $event->title; ?>
					</div>	

					<div class="events-info">
						<span class="events-time"><?php echo str_replace(':','h',substr($event->getTime(),0,5)); ?></span>
						<span class="events-city">à <?php echo $event->getCityName(); ?></span>	
						<span class="events-users">avec <b><?php echo $event->getLogin();?></b> <?php echo '('.$event->getAge().' ans)';?> 						
						<?php if($event->getNbParticipants()>1) echo 'et '.$event->getNbParticipants().' autres'; ?>
						</span>												
					</div>

					<div class="events-user">
						<?php 
						foreach ($event->participants as $p):?>
						<img class="events-avatar" data-toggle="tooltip" title="<?php echo $p->getLogin();?>" src="<?php echo Router::webroot($p->getAvatar())?>" alt="">
						<?php endforeach; ?>
					</div>
				</a>
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