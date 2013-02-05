<div class="homepage">

	<?php echo $this->session->flash() ;?>
	<?php //debug($this->cookieEventSearch->arr()); ?>
	<div class="formular">

			
		<form class="homeForm" id="formSearch" method="POST" action="#" >
			<?php echo $this->Form->input('user_id','hidden',array('value'=>$this->session->user('user_id'))) ;?>
			<?php echo $this->Form->input('token','hidden',array('value'=>$this->session->token() )) ;?>
			<?php echo $this->Form->input("date","hidden",array("value"=>date('Y-m-d'))) ;?>
			<?php echo $this->Form->input('cityID','hidden',array("value"=>$this->cookieEventSearch->read('cityID'))) ;?>
			<?php echo $this->Form->input('cityName','Ville',array("value"=>$this->cookieEventSearch->read('cityName'),'placeholder'=>"Dijon","data-autocomplete-url"=>Router::url('world/suggestCity'))) ;?>
			<?php echo $this->Form->select("extend","Etendre de",array(10=>'10km',30=>'30km', 50=>'50km',100=>'100km'),array("default"=>$this->cookieEventSearch->read('extend'))) ;?>
			<?php echo $this->Form->input('Chercher','submit',array('class'=>'btn btn-large')) ;?>
			<?php //$this->request('world','formLocate',array('CC1','Localisation',$this->cookieEventSearch->arr(),array()));?>
			<div class="sportsButtons">
			<?php echo $this->Form->_checkbox('sports[]','Sport',conf::$sportsAvailable,array('default'=>$this->cookieEventSearch->read('sports'),'openwrap'=>'<div class="sportButton">','closewrap'=>'</div>'));?>
			</div>
			
		</form>

	</div>




	<div class="events-table">
		<div class="events-top">
			<a class="events-nav events-next fright" href="<?php echo Router::url('events/index');?>/+6/">Semaine suivante</a>
			<a class="events-nav events-prev fleft" href="<?php echo Router::url('events/index');?>/-6/">Semaine précédante</a>
		</div>
		
		<div class="events-content">
			<?php $this->request('events','index',array($params)); ?>
		</div>

	</div>
</div>
<script type="text/javascript">

jQuery(function(){

	



  	$(".events-nav").click(function(){

  		var url = $(this).attr('href');
  		var form = $("#formSearch");
  		var datas = form.serialize();

  		$.ajax({
  				type:'GET',
  				url: url,
  				data : datas,
  				success: function( data ){

  					
  					$(".events-content").empty().append( data );
  					
  				},
  				dataType:'html'
  		});


  		return false;
  	});
}); 


</script>