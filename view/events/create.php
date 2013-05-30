<div class="container createEvent">
	<div class="row">
		<?php echo $this->session->flash(); ?>

		<div class="span7">

			<div class="module module-rounded">
								
				<?php if($event->exist()): ?>
				<a href="<?php echo Router::url('events/view/'.$event->getID());?>">Retourner à l'événement</a>
				<h4>
					<img src="<?php echo $event->getSportLogo();?>"/>
					<?php echo '<a href="'.Router::url('events/view/'.$event->getID()).'">'.$event->getTitle().'</a>';?>
				</h4>
				<?php else: ?>
				<h4>
					Proposer un nouvel événement !
				</h4>
				<?php endif; ?>

				<form class="form" action="<?php echo Router::url('events/create/'.$event->id);?>" method="POST">

					<?php echo $this->Form->input("id","hidden",array("value"=>$event->id)) ;?>
					<?php echo $this->Form->input('user_id','hidden',array('value'=>$this->session->user()->getID())) ;?>
					<?php echo $this->Form->input('token','hidden',array('value'=>$this->session->token())) ;?>
					<?php echo $this->Form->input('title',"Intitulé de l'annonce",array('placeholder'=>"Petit match entre amis, ...")) ;?>
					<?php echo $this->Form->select('sport','Quel sport ?',$sports_available,array('default'=>$event->sport,'placeholder'=>"Choississez un sport")); ?>
					<?php //$this->request('world','formLocate',array('city','Location',$event,array('helper'=>"Choississez jusqu'a votre ville"))); ?>
					<?php echo $this->Form->input("cityID","hidden",array("value"=>$event->getCityID())) ;?>
					<?php echo $this->Form->input("cityName","Ville",array("type"=>"text",'placeholder'=>'Ville',"required"=>"required","data-autocomplete-url"=>Router::url('world/suggestCity'))) ;?>
					<?php echo $this->Form->input('address','Adresse exacte',array('placeholder'=>'Salle Michel Bon, 36 rue Henri Dunant, ...')) ;?>
					<?php echo $this->Form->input('date',"Date de l'événement",array("class"=>'datepicker','placeholder'=>'ex : 2013/02/26 ')) ;?>
					<?php echo $this->Form->input('time','Heure du rendez-vous',array('type'=>'time','placeholder'=>'ex: 10h30, 6pm, ...','value'=>'12:00')) ;?>
					<?php echo $this->Form->input('nbmin','Nombre minimum',array("type"=>"number",'placeholder'=>"Nombre de participants minimum pour que l'événement ait lieu","value"=>2)) ;?>
					<?php echo $this->Form->input('description','Commentaires',array('type'=>'textarea','rows'=>'5','placeholder'=>"Préciser niveau de jeu, matériel à amener, le coût (si location de la salle ou autre), préciser la durée, le fonctionnement de l'activité, mixité ou non et si les «pompom girls» ou «pompom boys» sont accepté(e)s !!! Il en faut pour toutes et tous haha!")) ;?>
					<?php echo $this->Form->input("phone","Téléphone de contact",array("type"=>"tel","placeholder"=>"optionnel")) ;?>
									

				
			</div>

			
		</div>



		<div class="span4">
			<div class="module module-rounded">					
				<?php if($event->exist()): ?>
					<?php echo $this->Form->input("Mettre à jour l'annonce",'submit',array('class'=>'btn btn-primary btn-large')) ;?>					
					<a href="<?php echo Router::url('events/delete/'.$event->getID().'/'.$this->session->token());?>" class="btn btn-link" onclick="return confirm('L\'événement va être supprimé, êtes-vous sûr ?')">Supprimer l'événement </a>
					<a href="<?php echo Router::url('events/report/'.$event->getID());?>" class="btn btn-link">Reporter à la semaine suivant</a>

				<?php else: ?>
					<?php echo $this->Form->input("Soumettre l'annonce",'submit',array('class'=>'btn btn-primary btn-large')) ;?>
				<?php endif; ?>				

				</form>
			</div>

			<div class="module module-rounded">
				<div class="module-header">
						<h5>Mes événements</h5>
				</div>

				<?php if(!empty($user_events_in_futur)):?>
				<div>
					<strong>à venir</strong>
					<ul>
						<?php foreach ($user_events_in_futur as $e):?>							
							<li>
								<a href="<?php echo Router::url('events/create/'.$e->getID());?>">
								<img src="<?php echo $e->getSportLogo();?>" />
								<?php echo $e->getTitle();?>
							</li>
						<?php endforeach; ?>
					</ul>

				</div>
				<?php endif; ?>				

				<?php if(!empty($user_events_in_past)): ?>
				<div>
					<strong>passés</strong>
					<ul>
					<?php foreach ($user_events_in_past as $e): ?>
						<li>
							<a href="<?php echo Router::url('events/create/'.$e->getID());?>">
							<img src="<?php echo $e->getSportLogo();?>" />
							<?php echo $e->getTitle();?>
						</li>
					<?php endforeach;?>
					</ul>					
				</div>
				<?php endif; ?>

				<p>
					<a href="<?php echo Router::url('events/create');?>" class="btn btn-link">Créer un nouvel événement</a>
				</p>
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