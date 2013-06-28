<div class="homepage">
	<div class="row-fluid">

	
		<?php echo $this->session->flash() ;?>
		<?php //debug($this->cookieEventSearch->arr());
		
		?>	
		<form id="formSearch" method="GET" action="<?php echo Router::url('date/'.$params['date']);?>" >
			<?php echo $this->Form->input('cityID','hidden',array("value"=>$this->cookieEventSearch->read('cityID'))) ;?>					
			<?php echo $this->Form->input('user_id','hidden',array('value'=>$this->session->user()->getID())) ;?>				

			<div class="cityRounded">
				
				<div class="cityInputs">
					<div class="containerCityName">
						<input type="text" id="cityName" name="cityName" class="cityName" value="<?php echo ($this->cookieEventSearch->read('cityID'))? $this->cookieEventSearch->read('cityName') : 'Votre ville ?';?>" autocomplete='off' data-autocomplete-url="<?php echo Router::url('world/suggestCity');?>">						
					</div>
					<div class="containerExtend">
						<?php echo $this->Form->_select("extend",array(0=>'0km',10=>'10km',30=>'30km', 50=>'50km',100=>'100km'),array("default"=>$this->cookieEventSearch->read('extend'),"placeholder"=>"Etendre à :")) ;?>								
					</div>								
				</div>

				<button class="citySubmit"><span class="ws-icon-loupe"></span></button>
				
				<div class="cityAriane">
					
					<?php if($location = $this->cookieEventSearch->read('location')): ?>
						<?php if(!empty($location['CC1'])) echo $location['CC1'].', ';?>
						<?php if(!empty($location['ADM1'])) echo $location['ADM1'].', ';?>
						<?php if(!empty($location['ADM2'])) echo $location['ADM2'].', ';?>
						<?php if(!empty($location['ADM3'])) echo $location['ADM3'].', ';?>
						<?php if(!empty($location['ADM4'])) echo $location['ADM4'].', ';?>
						<?php if($this->cookieEventSearch->read('cityName')) echo $this->cookieEventSearch->read('cityName'); ?>
					<?php endif; ?>
				</div>

				<div class="clearfix"></div>
			</div>

			<div class="sportCheckboxs">
				<?php 
					
					$sports_selected = $this->cookieEventSearch->read('sports');									
					foreach ($sports_available as $sport):
				
				?>
						<div class="sportChoice">
							<input type="checkbox" name="sports[]" value="<?php echo $sport->slug;?>" id="label-<?php echo $sport->slug;?>" <?php if(!empty($sports_selected)&&in_array($sport->slug,$sports_selected)) echo "checked='checked'";?> >
							<label for="label-<?php echo $sport->slug;?>"><span class="ws-icon-<?php echo $sport->slug;?>"></span></label>
						</div>
						
						
													
				 	<?php endforeach; ?>					
				<?php ?>
				<div class="clearfix"></div>
			</div>	
		</form>

		<div class="calendar">
			<div class="calendar-header"></div>
			<div class="calendar-content">
					<?php $this->request('events','calendar',array($params)); ?>							
			</div>
			<div class="calendar-footer"></div>
		</div>
	</div>
</div>


<script type="text/javascript">

$(document).ready(function(){

	// $('.colomn-date a').click(function(e){
		
	// 	e.preventDefault();

	// 	var colomn = $(this).parent().parent();			
	// 	var others = $('.events-colomn');
	// 	var ms = 200;

	// 	if(colomn.hasClass('colomn-open')==true) { 


	// 		colomn.animate({width:'14%'},{duration:ms,queue:false,ease:'ease-out'});
	// 		others.find('.events-bb').animate({width:'100%',margin:'0%'},{duration:ms,queue:false,ease:'ease-out'});
	// 		others.animate({width:'14%'},{duration:ms,queue:false,ease:'ease-out'});	
	// 		colomn.removeClass('colomn-open');		
			
	// 	}
	// 	else {

	// 		others.removeClass('colomn-open');			
	// 		$('.events-colomn').animate({width:'10%'},{duration:ms,queue:false,ease:'ease-out'});
	// 		colomn.animate({width:'40%'},{duration:ms,queue:false,ease:'ease-out'});
	// 		others.find('.events-bb').animate({width:'100%'},{duration:ms,queue:false,ease:'ease-out'});
	// 		colomn.find('.events-bb').animate({width:'30%',margin:'1%'},{duration:ms,queue:false,ease:'ease-out'});
	// 		colomn.addClass('colomn-open');
			
	// 	}
		
	// });


	$('.events-avatar').tooltip({placement:'bottom'});

	$('.events-bb').livequery(function(){

		$(this).click(function(){ location.href=$(this).find('a.events-link').attr('href'); });
	});


	$('a.calendar-nav.with-ajax').livequery(function(){

		$("a.calendar-nav.with-ajax").bind('click',function(e){
			
	  		var url = $(this).attr('href');
	  		var form = $("#formSearch");
	  		var datas = form.serialize();
	  		var direction;
	  		if($(this).hasClass("calendar-next")) direction = 'next';
	  		if($(this).hasClass("calendar-prev")) direction = 'prev';

	  		var screenWidth = $(window).width();
	  		
	  		$.ajax({
	  				type:'GET',
	  				url: url,
	  				data : datas,
	  				success: function( data ){

	  					$(".calendar-content").append( data );

	  					if(direction == 'next') {
	  						contentPosition = screenWidth;
	  						contentSliding = '-='+screenWidth;
	  					}
	  					if(direction == 'prev') {
	  						contentPosition = -screenWidth;
	  						contentSliding = '+='+screenWidth;
	  					}

	  					$(".events-week").last().css({'left':contentPosition+'px'});
	  					$(".events-week").last().addClass('new-week');

	  					$('.events-week').animate({
	  						left:contentSliding,
	  						},500,function(){ ;
	  							if(!$(this).hasClass('new-week')) $(this).remove();
	  							$(this).removeClass('new-week');
	  					});
	  					
	  				},
	  				dataType:'html'
	  		});


	  		return false;
	  	});

	});


});


</script>