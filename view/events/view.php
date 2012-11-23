<div class="view_event">
	
	<div class="event">	
		<div class="meta">		
			<div class="sport">
				<img src="" alt="">
				<br />
				<?php //echo $event->sport;?>
			</div>
			<div class="orga">
				<strong>avec</strong><br />		
				<img src="<?php echo Router::webroot($event->avatar);?>" alt="">
				
				<br/>
				<a href=""><?php echo $event->login;?></a><br />
				<small>(<?php echo ageFromBY($event->age);?> ans )</small>
			</div>
		</div>

	   <div class="time"><?php echo $event->time;?></div>

		<div class="content">		
			<div class="title"><a href="<?php echo Router::url('events/view/'.$event->id.'/'.$event->slug);?>"><?php echo $event->title;?></a></div>
			<div class="location">
				<span class="city"><small>Ville:</small><?php echo $event->city;?></span>
				<span class="address"><small>Adresse:</smalL><?php echo stripcslashes($event->address);?></span>
				<span class="date"><small> Le :</small><?php echo datefr($event->date);?></span>
			</div>
			<div class="description">
				<span class="description-prefix">Commentaires :</span>
				<span class="description-content"><?php echo $event->description;?></span>
			</div>
		</div>
		
		<div class="actions">
			<?php if($event->user_id == $this->session->user('user_id')):?>
			<a class="btn btn-inverse" href="<?php echo Router::url('events/create/'.$event->id);?>">Administer</a>
			<?php endif; ?>

			

		</div>
	</div>


</div>
