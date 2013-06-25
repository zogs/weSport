<div style="width:100%;overflow:hidden;">
	<table style="width:100%;float:left;background:#DDD">
		<tr style="text-align:center;">
			<th style="width:1%;background:#473298"></th>
			<th style="width:2%">Lundi</th>
			<th style="width:2%">Mardi</th>
			<th style="width:2%">Mercredi</th>
			<th style="width:2%">Jeudi</th>
			<th style="width:2%">Vendredi</th>
			<th style="width:2%">Samedi</th>
			<th style="width:2%">Dimanche</th>
			<th style="width:1%;background:#473298"></th>
		</tr>
		<tr class="events-of-the-day">
			<td style="background:#473298"></td>
			<td>
				<div style="float:left;width:100%;padding:10px;text-align:center;margin:5px;background:#327632">LOLILOL</div>
				<div style="float:left;width:100%;padding:10px;text-align:center;margin:5px;background:#327632">LOLILOL</div>
				<div style="float:left;width:100%;padding:10px;text-align:center;margin:5px;background:#327632">LOLILOL</div>
				<div style="float:left;width:100%;padding:10px;text-align:center;margin:5px;background:#327632">LOLILOL</div>
			</td>
			<td><div style="float:left;width:100%;padding:10px;text-align:center;margin:5px;background:#987234">LOLILOL</div>
				<div style="float:left;width:100%;padding:10px;text-align:center;margin:5px;background:#987234">LOLILOL</div>
				<div style="float:left;width:100%;padding:10px;text-align:center;margin:5px;background:#987234">LOLILOL</div>
				<div style="float:left;width:100%;padding:10px;text-align:center;margin:5px;background:#987234">LOLILOL</div>
			</td>
			<td><div style="float:left;width:100%;padding:10px;text-align:center;margin:5px;background:#238764">LOLILOL</div>
				<div style="float:left;width:100%;padding:10px;text-align:center;margin:5px;background:#238764">LOLILOL</div>
				<div style="float:left;width:100%;padding:10px;text-align:center;margin:5px;background:#238764">LOLILOL</div>
				<div style="float:left;width:100%;padding:10px;text-align:center;margin:5px;background:#238764">LOLILOL</div>
			</td>
			<td><div style="float:left;width:100%;padding:10px;text-align:center;margin:5px;background:#238758">LOLILOL</div>
				<div style="float:left;width:100%;padding:10px;text-align:center;margin:5px;background:#238758">LOLILOL</div>
				<div style="float:left;width:100%;padding:10px;text-align:center;margin:5px;background:#238758">LOLILOL</div>
				<div style="float:left;width:100%;padding:10px;text-align:center;margin:5px;background:#238758">LOLILOL</div>
			</td>
			<td><div style="float:left;width:100%;padding:10px;text-align:center;margin:5px;background:#983432">LOLILOL</div>
				<div style="float:left;width:100%;padding:10px;text-align:center;margin:5px;background:#983432">LOLILOL</div>
				<div style="float:left;width:100%;padding:10px;text-align:center;margin:5px;background:#983432">LOLILOL</div>
				<div style="float:left;width:100%;padding:10px;text-align:center;margin:5px;background:#983432">LOLILOL</div>
			</td>
			<td><div style="float:left;width:100%;padding:10px;text-align:center;margin:5px;background:#732842">LOLILOL</div>
				<div style="float:left;width:100%;padding:10px;text-align:center;margin:5px;background:#732842">LOLILOL</div>
				<div style="float:left;width:100%;padding:10px;text-align:center;margin:5px;background:#732842">LOLILOL</div>
				<div style="float:left;width:100%;padding:10px;text-align:center;margin:5px;background:#732842">LOLILOL</div>
			</td>
			<td><div style="float:left;width:100%;padding:10px;text-align:center;margin:5px;background:#532023">LOLILOL</div>
				<div style="float:left;width:100%;padding:10px;text-align:center;margin:5px;background:#532023">LOLILOL</div>
				<div style="float:left;width:100%;padding:10px;text-align:center;margin:5px;background:#532023">LOLILOL</div>
				<div style="float:left;width:100%;padding:10px;text-align:center;margin:5px;background:#532023">LOLILOL</div>
			</td>
			<td style="background:#473298"></td>
		</tr>
	</table>
</div>


<div class="events-week" style="display:none">

<div class="events-prev">
	<?php if($firstday!=date('Y-m-d')): ?>
	<a class="with-ajax calendar-nav calendar-prev fleft" href="<?php echo Router::url('events/calendar/'.date('Y-m-d',strtotime($firstday." - ".$numDaysPerWeek." days")));?>"><span><-</span></a>
	<a href="<?php echo Router::url('date/'.date('Y-m-d',strtotime($firstday." - ".$numDaysPerWeek." days")));?>">PREV</a>
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
						<img src="<?php echo $event->getSportLogo();?>" alt="<?php echo $event->getSportSlug();?>">				
					</div>
	
					<div class="events-time">
						<?php echo str_replace(':','h',substr($event->getTime(),0,5)); ?>
					</div>

					<div class="events-content">
						<span class="events-title">
							<?php if($event->confirmed==1):?><span class="label label-success"><i class="icon icon-white icon-ok"></i></span><?php endif;?>
							<?php echo $event->title; ?>
							<?php if(isset($event->UserParticipation)): ?><span class="label label-important"><i class="icon icon-white icon-thumbs-up"></i></span><?php endif; ?>
						</span>																	
											
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
	<a href="<?php echo Router::url('date/'.date('Y-m-d',strtotime($firstday." + ".$numDaysPerWeek." days")));?>">NEXT</a>
	<a class="with-ajax calendar-nav calendar-next fright" href="<?php echo Router::url('events/calendar/'.date('Y-m-d',strtotime($firstday." + ".$numDaysPerWeek." days")));?>"><span>-></span></a>
</div>
</div>