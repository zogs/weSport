<div class="container createEvent">
	<div class="row">
		<?php echo $this->session->flash(); ?>

		<div class="span7">

			<div class="module module-rounded">
								
				<?php if($event->exist()): ?>
				<a href="<?php echo Router::url('events/view/'.$event->getID());?>">Retourner à l'évènement</a>
				<h4>
					<img src="<?php echo $event->getSportLogo();?>"/>
					<?php echo '<a href="'.Router::url('events/view/'.$event->getID()).'">'.$event->getTitle().'</a>';?>
				</h4>
				<?php else: ?>
				<h4>
					Proposer une nouvelle activité !
				</h4>
				<?php endif; ?>

				<form class="form" action="<?php echo Router::url('events/create/'.$event->id);?>" method="POST">

					<?php echo $this->Form->input("id","hidden",array("value"=>$event->id)) ;?>
					<?php echo $this->Form->input('user_id','hidden',array('value'=>$this->session->user()->getID())) ;?>
					<?php echo $this->Form->input('token','hidden',array('value'=>$this->session->token())) ;?>
					<?php echo $this->Form->input('title',"Titre",array('placeholder'=>"Petit match entre amis, ...")) ;?>
					<?php echo $this->Form->select('sport','Sport',$sports_available,array('default'=>$event->sport,'placeholder'=>"Choisir un sport",'style'=>'width:100%;')); ?>
					<?php //$this->request('world','formLocate',array('city','Location',$event,array('helper'=>"Choississez jusqu'a votre ville"))); ?>
					<?php echo $this->Form->input("cityID","hidden",array("value"=>$event->getCityID())) ;?>
					<?php echo $this->Form->input("cityName","Ville",array("type"=>"text",'placeholder'=>'Ville',"required"=>"required","data-autocomplete-url"=>Router::url('world/suggestCity'))) ;?>
					<?php echo $this->Form->input('address','Adresse',array('placeholder'=>'Salle Michel Bon, 36 rue Henri Dunant, ...')) ;?>
					<?php echo $this->Form->input('date',"Date",array("class"=>'datepicker','placeholder'=>'ex : 2013/02/26 ')) ;?>
					<div class="control-group" id="control-time">
						<div class="control-label">Heure</div>
						<div class="controls">
							<?php echo $this->Form->_select('hours',Form::Hours(),array('default'=>$event->getHours(),'style'=>'float:left;width:30%')); ?>
							<?php echo $this->Form->_select('minutes',Form::Minutes(),array('default'=>$event->getMinutes(),'style'=>'float:left;width:30%')); ?>
						</div>
					</div>
					<?php //echo $this->Form->input('time','Heure du rendez-vous',array('type'=>'time','placeholder'=>'ex: 06:00, 13:00, 18:30 , ...','value'=>(isset($event->time)? $event->time : '12:00'))) ;?>
					<?php echo $this->Form->input('nbmin','Nombre minimum',array("type"=>"number",'placeholder'=>"Minimum 2 (vous inclus)","value"=>(isset($event->nbmin)? $event->nbmin : ''))) ;?>					
					<?php echo $this->Form->input('description','Descriptif',array('type'=>'textarea','rows'=>'5','placeholder'=>"Préciser niveau de jeu, matériel à amener, le coût (si location de la salle ou autre), préciser la durée, le fonctionnement de l'activité, mixité ou non et si les «pompom girls» ou «pompom boys» sont accepté(e)s !!! Il en faut pour toutes et tous haha!")) ;?>
					<?php echo $this->Form->input("phone","Téléphone",array("type"=>"tel","placeholder"=>"optionnel")) ;?>
									

				
			</div>

			
		</div>



		<div class="span4">
			<div class="module module-rounded">					
				<?php if($event->exist()): ?>
					<?php echo $this->Form->input("Mettre à jour l'annonce",'submit',array('class'=>'btn btn-primary btn-large')) ;?>					
					<a href="<?php echo Router::url('events/delete/'.$event->getID().'/'.$this->session->token());?>" class="btn btn-link" onclick="return confirm('L\'événement va être supprimé, êtes-vous sûr ?')">Supprimer l'activité </a>
					
					<?php if(!$event->isConfirm()): ?>
					<a href="<?php echo Router::url('events/confirm/'.$event->getID().'/'.$this->session->token());?>" class="btn btn-link" onclick="return confirm('Confirmer l\'activité même si le nombre de participants n\'est pas atteint ?')">Confirmer l'activité</a>
					<?php endif; ?>
					
					<a href="<?php echo Router::url('events/report/'.$event->getID());?>" class="btn btn-link">Reporter à la semaine suivant</a>

				<?php else: ?>
					<?php echo $this->Form->input("Soumettre l'annonce",'submit',array('class'=>'btn btn-primary btn-large')) ;?>
				<?php endif; ?>				

				</form>
			</div>

			<div class="module module-rounded">
				<div class="module-header">
						<h5>Mes activités</h5>
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
					<a href="<?php echo Router::url('events/create');?>" class="btn btn-link">Proposer une nouvelle activité</a>
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