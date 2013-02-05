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
					<img src="" alt="">
					<?php echo str_replace(':','h',substr($event->time,0,5)); ?>
				</div>

				<div class="event-title">
					<?php echo $event->title; ?>
				</div>	

				<div class="event-meta">
					<span class="event-city">à <?php echo $event->city; ?></span>
					<a class="event-link" href="<?php echo Router::url('events/view/'.$event->id.'/'.$event->slug);?>">En savoir +</a>
				</div>		

				<div class="event-user">
					<img class="event-avatar" src="<?php echo Router::webroot($event->avatar)?>" alt="">
					<div class="event-users">avec <b><?php echo $event->login;?></b> <?php echo '('.Date::yearsSince($event->age).' ans)';?> 
						<?php if(count($event->participants)>0):?>+ <?php echo count($event->participants);?> autres<?php endif;?>
					</div>
				</div>

				<?php if(isset($event->UserParticipation)): ?>
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