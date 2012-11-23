<div class="homepage">

	<?php echo $this->session->flash() ;?>

	<div class="formular">
			
		<form class="form" method="POST" action="#" >
			<?php echo $this->Form->input('user_id','hidden',array('value'=>$this->session->user('user_id'))) ;?>
			<?php echo $this->Form->input('token','hidden',array('value'=>$this->session->token() )) ;?>
			<?php echo $this->Form->input('city','hidden',array()) ;?>
			<?php echo $this->Form->input('cityName','Ville',array("value"=>$this->CookieSearch->read('cityName'),'placeholder'=>"Vieux Boucau","data-autocomplete-url"=>Router::url('world/suggestCity'))) ;?>
			<?php $this->request('world','formLocate',array('CC1','Location',$this->CookieSearch->arr(),array()));?>
			<?php echo $this->Form->checkbox('sports[]','Sport',conf::$sportsAvailable,array('default'=>$this->CookieSearch->read('sports')));?>

			<?php echo $this->Form->input('Chercher','submit',array('class'=>'btn btn-large')) ;?>
		</form>

	</div>

	<div class="evts_results">
		
		<?php $this->request('events','index',array($params)); ?>

	</div>
</div>
<script type="text/javascript">

jQuery(function(){

	var inputcity = $("#inputcityName");
	var hiddencity = $("#city");
	var url = inputcity.attr('data-autocomplete-url');

  	inputcity.autocomplete({
  			serviceUrl:url,
  			minChars:3,
  			onSelect:function(value,data){ 
  				
  				hiddencity.val(data)},
  		});
}); 


</script>