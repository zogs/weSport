<table class="events-week" data-first-day="<?php echo $firstday;?>">
	<tr>

		<?php 
			foreach ($events as $date => $evts):
				$datediff = Date::dateDiff(date('Y-m-d'),$date)%7+1;
		?>
			<td style="width:2%" class="colomn-<?php echo $datediff;?> <?php if(Date::dateStatus($date)=='past') echo 'colomn-past'; ?>" id="colomn-<?php echo $datediff;?>">

				<div class="colomn-date" id="colomn-date-<?php echo $datediff;?>">
					
					<a href="">
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
					</a>
				</div>

				<?php
					


				foreach ($evts as $event){
				
				?>
				
				<div class="events <?php if($event->getUserParticipation()) echo 'events-userin' ?>">				
					<a class="events-link" href="<?php echo $event->getUrl();?>">
		
						<div class="events-time">
							<?php echo str_replace(':','h',substr($event->getTime(),0,5)); ?>
							<?php if($event->confirmed==1):?><span class="label label-success label-confirmed tooltiptop" data-toggle="tooltip" title="L'activité est confirmé!">Confirmé</span><?php endif;?>
							<?php if(isset($event->UserParticipation)): ?><span class="label label-important label-participe tooltiptop" data-toggle="tooltip" title="Je participe"><i class="icon icon-white icon-thumbs-up"></i></span><?php endif; ?>
						</div>

						<div class="events-content">							
							<span class="events-title">
								<div class="ws-sport-icon tooltiptop" data-toggle="tooltip" title="<?php echo $event->sport->name;?>"><span class="ws-icon-<?php echo $event->sport->slug;?>"></span></div>
								<?php echo $event->title; ?>
							</span>																												
						</div>
					</a>
					<div class="clearfix"></div>
				</div>

				<?php	
								
					}

				?>

				<a class="addEvent tooltipbottom" data-toggle="tooltip" title="Ajouter votre sport" href="<?php echo Router::url('events/create/?date='.$date);?>"><span class="ws-icon ws-icon-plus-alt"></span></a>
			</td>
		<?php
			$cdate = $date;
			endforeach;
		?>
	</tr>
</table>

