<div class="createEvent">
	<div class="top-banner">
		<div class="void"></div>
		<div class="flash">
			<?php echo $this->session->flash(); ?>			
		</div>	
	</div>

	<div class="container">
		<div class="calendar-return"><a class="tooltiptop" data-toggle="tooltip" title="Retour au calendrier" href="<?php echo Router::url('calendar/date/'.$this->cookieEventSearch->read('date'));?>"></a></div>	
		<div class="fresque fresque-mini"></div>
		<div class="white-sheet">			
			<div class="head-sheet">							
				<?php if($event->exist()): ?>
				
				<h1 class="title-sheet">					
					<span class="ws-icon ws-icon-large ws-icon-halo ws-icon-<?php echo $event->sport->slug;?>"></span>
					<?php echo '<a href="'.Router::url('events/view/'.$event->getID()).'">'.$event->getTitle().'</a>';?>
				</h1>
				<?php else: ?>
				<h1 class="title-sheet">
					Proposer une nouvelle activité !
				</h1>
				<?php endif; ?>
			</div>

			<div class="col_large">				
				<form class="form form-ws form-create" action="<?php echo Router::url('events/create/'.$event->id);?>" method="POST">

					<?php echo $this->Form->input("id","hidden",array("value"=>$event->id)) ;?>
					<?php echo $this->Form->input('user_id','hidden',array('value'=>$this->session->user()->getID())) ;?>
					<?php echo $this->Form->input('token','hidden',array('value'=>$this->session->token())) ;?>
					<?php echo $this->Form->input('title',"Titre",array('placeholder'=>"Petit match entre amis, ...")) ;?>
					<?php echo $this->Form->select('sport','Sport',$sports_available,array('default'=>$event->getSportSlug(),'placeholder'=>"Choisir un sport",'style'=>'width:100%;')); ?>
					<?php //$this->request('world','formLocate',array('city','Location',$event,array('helper'=>"Choississez jusqu'a votre ville"))); ?>
					<?php echo $this->Form->input("cityID","hidden",array("value"=>$event->getCityID())) ;?>
					<?php echo $this->Form->input("cityName","Ville",array("type"=>"text",'placeholder'=>'Ville',"required"=>"required","style"=>"width:100%;","data-autocomplete-url"=>Router::url('world/suggestCity'))) ;?>
					<?php echo $this->Form->input('address','Adresse',array('placeholder'=>'Salle Michel Bon, 36 rue Henri Dunant, ...')) ;?>
					<?php echo $this->Form->input('date',"Date",array("class"=>'datepicker','placeholder'=>'ex : 2013/02/26 ','value'=>(($this->request->get('date'))? $this->request->get('date'):''))) ;?>
					<div class="control-group " id="control-time">
						<label class="control-label">Heure</label>
						<div class="controls">
							<?php echo $this->Form->_select('hours',Form::Hours(),array('default'=>$event->getHours(),'style'=>'width:30%','class'=>'inline')); ?>
							<?php echo $this->Form->_select('minutes',Form::Minutes(),array('default'=>$event->getMinutes(),'style'=>'width:30%','class'=>'inline')); ?>
						</div>
					</div>
					<?php //echo $this->Form->input('time','Heure du rendez-vous',array('type'=>'time','placeholder'=>'ex: 06:00, 13:00, 18:30 , ...','value'=>(isset($event->time)? $event->time : '12:00'))) ;?>
					<?php echo $this->Form->input('nbmin','Nombre minimum',array("type"=>"number",'placeholder'=>"Minimum 2 (vous inclus)","value"=>(isset($event->nbmin)? $event->nbmin : ''))) ;?>					
					<?php echo $this->Form->input('description','Descriptif',array('type'=>'textarea','rows'=>'5','placeholder'=>"Préciser niveau de jeu, matériel à amener, le coût (si location de la salle ou autre), préciser la durée, le fonctionnement de l'activité, mixité ou non et si les «pompom girls» ou «pompom boys» sont accepté(e)s !!! Il en faut pour toutes et tous haha!")) ;?>
					<?php echo $this->Form->input("phone","Téléphone",array("type"=>"tel","placeholder"=>"optionnel")) ;?>

				
			</div>



			<div class="col_small">

				<div class="block form-submit">
					<?php if($event->exist()): ?>
						<?php echo $this->Form->input("Mettre à jour l'annonce",'submit',array('class'=>'btn-ws')) ;?>					
						<ul>
							<li><a href="<?php echo Router::url('events/delete/'.$event->getID().'/'.$this->session->token());?>" class="btn btn-link" onclick="return confirm('L\'événement va être supprimé, êtes-vous sûr ?')">Supprimer l'activité </a></li>
							<?php if(!$event->isConfirm()): ?>
							<li><a href="<?php echo Router::url('events/confirm/'.$event->getID().'/'.$this->session->token());?>" class="btn btn-link" onclick="return confirm('Confirmer l\'activité même si le nombre de participants n\'est pas atteint ?')">Confirmer l'activité</a></li>
							<?php endif; ?>
							<li><a href="<?php echo Router::url('events/report/'.$event->getID());?>" class="btn btn-link">Reporter à la semaine suivant</a></li>
						</ul>
					<?php else: ?>
						<?php echo $this->Form->input("Soumettre l'annonce",'submit',array('class'=>'btn-ws')) ;?>
					<?php endif; ?>				

					</form>					
				</div>					

				<?php if(!empty($user_events_in_futur)):?>
				<div class="block block-orange event-to-come">
					<h3>Mes activités à venir</h3>
					<div class="block-content">
						<ul>
							<?php foreach ($user_events_in_futur as $e):?>							
								<li>
									
									<a href="<?php echo Router::url('events/create/'.$e->getID());?>">
										<span class="ws-icon ws-icon-small ws-icon ws-icon-<?php echo $e->sport->slug;?>"></span>
										<?php echo $e->getTitle();?>
									</a>
								</li>
							<?php endforeach; ?>
						</ul>
					</div>
				</div>
				<?php endif; ?>		

				<?php if(!empty($user_events_in_past)): ?>
				<div class="block block-green event-finished">
					<h3>Activités terminés</h3>
					<div class="block-content">
						<ul>
						<?php foreach ($user_events_in_past as $e): ?>					
							<li>
								
								<a href="<?php echo Router::url('events/create/'.$e->getID());?>">
									<span class="ws-icon ws-icon-small ws-icon-<?php echo $e->sport->slug;?>"></span>
									<?php echo $e->getTitle();?>
								</a>
							</li>
						<?php endforeach;?>
						</ul>
						<a href="<?php echo Router::url('events/create');?>" class="btn btn-link">Proposer une nouvelle activité</a>	
					</div>
				</div>
				<?php endif; ?>		
				
			</div>
		<div class="clearfix"></div>
		<div class="fresque"></div>
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