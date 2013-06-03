<div class="events-week">

<div class="events-prev">
	<?php if($firstday!=date('Y-m-d')): ?>
	<a class="with-ajax calendar-nav calendar-prev fleft" href="<?php echo Router::url('events/calendar/'.date('Y-m-d',strtotime($firstday." - ".$numDaysPerWeek." days")));?>"><span><-</span></a>
	<a href="<?php echo Router::url('pages/home/'.date('Y-m-d',strtotime($firstday." - ".$numDaysPerWeek." days")));?>">PREV</a>
	<?php endif; ?>
</div>
<?php

//Boucle des jours de la semaine

$cdate='';
foreach ($events as $date => $evts):
?>


	<div class="events-colomn">
		<div class="colomn-date">
			<a href="">
			<?php
					if($date==date('Y-m-d')){
						echo "<strong>Aujourd'hui</strong>";	
						echo '<i>'.Date::datefr($date).'</i>';	
					}
					else{						
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
						<span class="events-title"><?php if($event->confirmed==1):?><span class="label label-success"><i class="icon icon-white icon-ok"></i></span><?php endif;?><?php echo $event->title; ?></span>	
						<span class="events-city">à <?php echo $event->getCityName(); ?></span>	
				
						<div class="events-info">							
							
							<span class="events-users">avec <b><?php echo $event->author->getLogin();?></b> <?php echo '('.$event->author->getAge().' ans)';?> 						
							<?php if($event->getNbParticipants()>1) echo 'et '.$event->getNbParticipants().' autres'; ?>
							</span>												
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

<div class="events-next">
	<a href="<?php echo Router::url('pages/home/'.date('Y-m-d',strtotime($firstday." + ".$numDaysPerWeek." days")));?>">NEXT</a>
	<a class="with-ajax calendar-nav calendar-next fright" href="<?php echo Router::url('events/calendar/'.date('Y-m-d',strtotime($firstday." + ".$numDaysPerWeek." days")));?>"><span>-></span></a>
</div>
</div>