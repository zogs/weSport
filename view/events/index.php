<table class="events-week" data-first-day="<?php echo $firstday;?>">
	<tr>

		<?php 
			foreach ($events as $date => $evts):
		?>
			<td style="width:2%" class="colomn-<?php echo Date::dateDiff(date('Y-m-d'),$date)%7+1;?> <?php if(Date::dateStatus($date)=='past') echo 'colomn-past'; ?>">

				<div class="colomn-date">
					
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

				foreach ($evts as $event):
				
				?>
				
				<div class="events <?php if($event->getUserParticipation()) echo 'events-userin' ?>">				
					<a class="events-link" href="<?php echo Router::url('events/view/'.$event->getID().'/'.$event->getSlug());?>">
		
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
										
					endforeach;

				?>
			</td>
		<?php
			$cdate = $date;
			endforeach;
		?>
	</tr>
</table>

