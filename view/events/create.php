<div class="createEvent">
	<?php echo $this->session->flash(); ?>
	<div class="mini-profile module">
		<img class="avatar size32" src="<?php echo Router::webroot($this->session->user()->getAvatar());?>" alt="">
		<a class="user" href="<?php echo Router::url('users/thread');?>">
			<?php echo $this->session->user()->getLogin();?>
		</a>
	</div>

	<div class="eventForm">
		<form class="form module" action="<?php echo Router::url('events/create/'.$event->id);?>" method="POST">

		<?php echo $this->Form->input("id","hidden",array("value"=>$event->id)) ;?>
		<?php echo $this->Form->input('user_id','hidden',array('value'=>$this->session->user()->getID())) ;?>
		<?php echo $this->Form->input('token','hidden',array('value'=>$this->session->token())) ;?>

		<?php echo $this->Form->input('title',"Intitulé de l'annonce",array('helper'=>"Petit match entre amis, ...")) ;?>
		<?php echo $this->Form->select('sport','Quel sport ?',conf::$sportsAvailable,array('default'=>$event->sport,'helper'=>"Choississez un sport")); ?>
		<?php //$this->request('world','formLocate',array('city','Location',$event,array('helper'=>"Choississez jusqu'a votre ville"))); ?>
		<?php echo $this->Form->input("cityID","hidden",array("value"=>$event->city)) ;?>
		<?php echo $this->Form->input("cityName","Ville",array("type"=>"text","required"=>"required","data-autocomplete-url"=>Router::url('world/suggestCity'))) ;?>
		<?php echo $this->Form->input('address','Adresse exacte',array('helper'=>'Salle Michel Bon, 36 rue Henri Dunant, ...')) ;?>
		<?php echo $this->Form->input('date',"Date de l'événement",array("class"=>'datepicker','helper'=>'ex : 2013/02/26 ')) ;?>
		<?php echo $this->Form->input('time','Heure du rendez-vous',array('type'=>'time','helper'=>'ex: 10h30, 6pm, ...')) ;?>
		<?php echo $this->Form->input('nbmin','Nombre minimum',array("type"=>"number","value"=>"2",'helper'=>"Nombre de participants minimum pour que l'événement ait lieu")) ;?>
		<?php echo $this->Form->input('description','Commentaires',array('type'=>'textarea','helper'=>"Préciser niveau de jeu, matériel à
amener, le coût (si location de la salle ou autre), préciser la durée, le
fonctionnement de l'activité, mixité ou non et si les \"pompom girls\" ou \"pompom
boys\" sont accepté(e)s !!! Il en faut pour toutes et tous haha!")) ;?>
		<?php echo $this->Form->input("phone","Téléphone de contact",array("type"=>"tel","helper"=>"optionnel")) ;?>
		<?php echo $this->Form->input("Soumettre l'annonce",'submit',array('class'=>'btn btn-primary btn-large')) ;?>



		</form>
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