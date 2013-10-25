<?php

	$nbevents=0;

?>

<table class="events-weeks <?php if($numWeeks>=3) echo 'minified-events';?>" data-first-day="<?php echo $firstday;?>">
	<?php
		foreach ($weeks as $week):
	?>
	<tr class="events-week">
		<?php 
			foreach ($week as $date => $evts):
				$datediff = Date::dateDiff(date('Y-m-d'),$date)%7+1;
		?>
			<td style="width:2%" class="events-day colomn-<?php echo $datediff;?> <?php if(Date::dateStatus($date)=='past') echo 'colomn-past'; ?>" id="colomn-<?php echo $datediff;?>">
				<div class="colomn-date" id="colomn-date-<?php echo $datediff;?>">
					<?php
						if($date==date('Y-m-d')){
							echo "<strong>Aujourd'hui</strong>";	
							echo '<br /><i>'.Date::day_month($date).'</i>';	
						}
						else{						
							echo '<strong>'.Date::dayoftheweek(date('D',strtotime($date))).'</strong>';
							echo '<br /><i>'.Date::day_month($date).'</i>';							
						}					
					?>
				</div>

				<?php				
				foreach ($evts as $event):				
				?>				
				<div class="events <?php if($event->getUserParticipation()) echo 'events-userin' ?> <?php if($event->isConfirm()) echo 'events-confirmed';?>">				
					<a class="events-link" 
						href="<?php echo $event->getUrl();?>"						
						data-content="Ville : <strong><?php echo $event->getCityName();?></strong><br />													
										Organisateur : <strong><?php echo $event->getAuthor();?></strong> (<?php echo $event->author->getAge();?>)<br />
										Participants : <strong><?php echo $event->getNbParticipants();?></strong><br />
										<i><?php echo substr($event->getDescription(),0,100);?></i>
									"
						<?php if(!$event->confirmed):?>title="<span style='color:grey'>En attente de participants</span>"<?php endif;?>
					>
		
						<div class="events-content">							
						
							<div class="events-logo">
								<span class="ws-sport-icon"><span class="ws-icon-<?php echo $event->sport->slug;?> tooltipbottom" data-toggle="tooltip" title="<?php echo $event->sport->name;?>"></span></span>													
							</div>
							<div class="events-descr">
								<span class="events-title <?php echo (strlen($event->title)>60)? 'title-small' : '';?>"><?php echo ucfirst($event->title); ?></span>																																				
							</div>
							<div class="events-meta">
								<div class="events-time"><?php echo str_replace(':','h',substr($event->getTime(),0,5)); ?></div>
								<div class="events-label">
									<?php if($event->confirmed==1):?>
										<span class="ws-icon ws-icon-checkmark-circle ws-icon-confirm tooltipbottom" data-toggle="tooltip" title="L'activité est confirmé!"></span>										
									<?php else:?>
										<span class="ws-icon ws-icon-checkmark-circle tooltipbottom" data-toggle="tooltip" title="L'activité n'est pas encore confirmé."></span>
									<?php endif;?>
									<?php if(isset($event->UserParticipation)): ?><span class="label ws-label ws-label-important ws-label-participe tooltipbottom" data-toggle="tooltip" title="Je participe"><i class="icon icon-white icon-thumbs-up"></i></span><?php endif; ?>
									<?php if($event->author->isAsso()): ?><span class="label ws-label ws-label-grey tooltipbottom" data-toggle="tooltip" title="Association"><i>A</i></span><?php endif;?>
									<?php if($event->author->isPro()): ?><span class="label ws-label ws-label-grey tooltipbottom" data-toogle="tooltip" title="Bizness"><i>P</i></span><?php endif;?>																							
								</div>
								<div class="events-city"><?php echo $event->getCityName();?></div>
							</div>

						
						</div>
					</a>
					<div class="clearfix"></div>
				</div>

				<?php	
					$nbevents++;

				endforeach;

				?>

				<a class="addEvent tooltipbottom" data-toggle="tooltip" title="Ajouter un sport" href="<?php echo Router::url('events/create/?date='.$date);?>"><span class="ws-icon ws-icon-plus-alt"></span></a>
			</td>
		<?php
			$cdate = $date;
			endforeach;
		?>
	</tr>
	<?php
	endforeach;
	?>
</table>
