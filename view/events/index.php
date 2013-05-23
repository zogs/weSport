<div class="events-week">
<?php
$cdate='';
foreach ($events as $date => $evts):
?>

<?php

	$today_class = '';
	if($date==date('Y-m-d')){
		$today_class = "events-colomn-today events-colomn-today-".$this->getLang();
	}
?>

	<div class="events-colomn <?php echo $today_class;?>">
		<div class="colomn-date">
			<a href="">
			<?php

				if($cdate!=$date) {

					echo '<strong>'.Date::dayoftheweek(date('D',strtotime($date))).'</strong>';
					echo '<i>'.Date::datefr($date).'</i>';
							
				}

			?>
			</a>
		</div>

<?php

			foreach ($evts as $event):
				
			?>
			
			<div class="events-bb <?php if($event->getUserParticipation()) echo 'events-userin' ?>">				
				<a class="events-link" href="<?php echo Router::url('events/view/'.$event->getID().'/'.$event->getSlug());?>">
					<div class="events-sport-logo">
						<img src="<?php echo $event->getSportLogo();?>" alt="<?php echo $event->sport;?>">				
					</div>
	
					<div class="events-time">
						<?php echo str_replace(':','h',substr($event->getTime(),0,5)); ?>
					</div>

					<div class="events-content">
						<span class="events-title"><?php echo $event->title; ?></span>	
						<span class="events-city">à <?php echo $event->getCityName(); ?></span>	
				
						<div class="events-info">							
							
							<span class="events-users">avec <b><?php echo $event->getLogin();?></b> <?php echo '('.$event->getAge().' ans)';?> 						
							<?php if($event->getNbParticipants()>1) echo 'et '.$event->getNbParticipants().' autres'; ?>
							</span>												
						</div>

						<div class="events-user">
							<?php 
							foreach ($event->participants as $p):?>
							<a href="<?php echo $p->getLink();?>"><img class="events-avatar" data-toggle="tooltip" title="<?php echo $p->getLogin();?>" src="<?php echo Router::webroot($p->getAvatar())?>" alt=""></a>
							<?php endforeach; ?>
						</div>						
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