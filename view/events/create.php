<div class="container">
	<div class="row createEvent">
		<?php echo $this->session->flash(); ?>

		<div class="span3">
			<div class="module module-rounded">
				<img class="avatar size32" src="<?php echo Router::webroot($this->session->user()->getAvatar());?>" alt="">
				<a class="user" href="<?php echo Router::url('users/thread');?>">
					<?php echo $this->session->user()->getLogin();?>
				</a>
				<p>
					<span><?php echo $this->session->user()->getAge();?> ans</span>
					<br />
					<small><a href="<?php echo Router::url('users/logout');?>">Déconnexion</a></small>
				</p>
			</div>	

			<div class="module module-rounded">
				<div class="module-header">
						<h5>Mes événements</h5>
				</div>

				<div>
					<strong>à venir :</strong>
					<?php 
					if(!empty($user_events_in_futur)){
						foreach ($user_events_in_futur as $e) {
							
							echo '<p>';
							echo '<a href="'.Router::url('events/create/'.$e->getID()).'">';
							echo $e->getTitle();
							echo '</a>';
							echo '</p>';
						} 
					}
					else
						echo "<small><i>Pas d'événement</i></small>";
					?>				
				</div>

				<?php if(!empty($user_events_in_past)): ?>
				<div>
					<strong>événements passés :</strong>
					<?php 

					foreach ($user_events_in_past as $e) {
						
						echo '<p>';
						echo '<a href="'.Router::url('events/create/'.$e->getID()).'">';
						echo $e->getTitle();
						echo '</a>';
						echo '</p>';
					}

					?>
				</div>
				<?php endif; ?>
			</div>
		</div>
		

		<div class="span8">

			<div class="module module-rounded">
				<h5>
				<?php if($event->exist()): ?>
					<?php echo $event->getTitle(); ?>
					<?php echo '<a href="'.Router::url('events/view/'.$event->getID()).'">( voir l\'annonce )</a>';?>
				<?php else: ?>
					Proposer un nouvel événement !
				<?php endif; ?>
				</h5>
			</div>

			<div class="module module-rounded">
				<form class="form" action="<?php echo Router::url('events/create/'.$event->id);?>" method="POST">

					<?php echo $this->Form->input("id","hidden",array("value"=>$event->id)) ;?>
					<?php echo $this->Form->input('user_id','hidden',array('value'=>$this->session->user()->getID())) ;?>
					<?php echo $this->Form->input('token','hidden',array('value'=>$this->session->token())) ;?>
					<?php echo $this->Form->input('title',"Intitulé de l'annonce",array('placeholder'=>"Petit match entre amis, ...")) ;?>
					<?php echo $this->Form->select('sport','Quel sport ?',$sports_available,array('default'=>$event->sport,'placeholder'=>"Choississez un sport")); ?>
					<?php //$this->request('world','formLocate',array('city','Location',$event,array('helper'=>"Choississez jusqu'a votre ville"))); ?>
					<?php echo $this->Form->input("cityID","hidden",array("value"=>$event->city)) ;?>
					<?php echo $this->Form->input("cityName","Ville",array("type"=>"text",'placeholder'=>'Ville',"required"=>"required","data-autocomplete-url"=>Router::url('world/suggestCity'))) ;?>
					<?php echo $this->Form->input('address','Adresse exacte',array('placeholder'=>'Salle Michel Bon, 36 rue Henri Dunant, ...')) ;?>
					<?php echo $this->Form->input('date',"Date de l'événement",array("class"=>'datepicker','placeholder'=>'ex : 2013/02/26 ')) ;?>
					<?php echo $this->Form->input('time','Heure du rendez-vous',array('type'=>'time','placeholder'=>'ex: 10h30, 6pm, ...')) ;?>
					<?php echo $this->Form->input('nbmin','Nombre minimum',array("type"=>"number","value"=>"2",'placeholder'=>"Nombre de participants minimum pour que l'événement ait lieu")) ;?>
					<?php echo $this->Form->input('description','Commentaires',array('type'=>'textarea','rows'=>'5','placeholder'=>"Préciser niveau de jeu, matériel à
																										amener, le coût (si location de la salle ou autre), préciser la durée, le
																										fonctionnement de l'activité, mixité ou non et si les «pompom girls» ou «pompom
																										boys» sont accepté(e)s !!! Il en faut pour toutes et tous haha!")) ;?>
					<?php echo $this->Form->input("phone","Téléphone de contact",array("type"=>"tel","placeholder"=>"optionnel")) ;?>
									

				
			</div>

			<div class="module module-rounded">				
				<?php if($event->exist()): ?>
					<?php echo $this->Form->input("Mettre à jour cette annoce",'submit',array('class'=>'btn btn-primary btn-large')) ;?>
					<?php echo $this->Form->input("Supprimer cette annonce","submit",array("class"=>"btn btn-warning btn-large","name"=>"suppress","onClick"=>"confirm('Are you sure ?')")) ;?>
				<?php else: ?>
					<?php echo $this->Form->input("Soumettre cette annonce",'submit',array('class'=>'btn btn-primary btn-large')) ;?>
				<?php endif; ?>				

				</form>
			</div>
		</div>
	</div>
	
</div>
<script type="text/javascript">
	 $(function() {
		
		$( ".datepicker" ).datepicker({
			format : 'yyyy/mm/dd',
			autoclose : true,
			todayHightlight : true,
			language : 'fr'
		});

	});
</script>