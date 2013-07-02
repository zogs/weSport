<div class="homepage">
	<div class="row-fluid">
		<div class="flash"><?php echo $this->session->flash() ;?></div>
		
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
					<span class="ws-icon ws-icon-location" style="color:white"></span>
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
					foreach ($sports_available as $sport):?>
						<div class="sportChoice">
							<input type="checkbox" name="sports[]" value="<?php echo $sport->slug;?>" id="label-<?php echo $sport->slug;?>" <?php if(!empty($sports_selected)&&in_array($sport->slug,$sports_selected)) echo "checked='checked'";?> >
							<label for="label-<?php echo $sport->slug;?>" class="tooltiptop" data-toggle="tooltip" title="<?php echo $sport->name;?>">
								<span class="ws-icon-small ws-icon-<?php echo $sport->slug;?>"></span>
							</label>
						</div>						
				 	<?php endforeach; ?>					
				<?php ?>				
			</div>	
			<?php 
				reset($sports_available);
				$sport_list = array();
				foreach ($sports_available as $key => $sport) {
				 	$sports_list[$sport->slug] = $sport->name;
				 } ?>
			<div class="sportSelect">
				<?php echo $this->Form->select('sport','Seulement le sport suivant',$sports_list,array('default'=>$sports_selected,'placeholder'=>"Choisir un sport",'style'=>'width:100%;')); ?>
			</div>
		</form>

		<div class="calendar">
			<div class="calendar-header"></div>
			<div class="calendar-content" id="calendar-content">
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


	function slideCalendar(direction,width){

		if(direction == 'next') {
			contentPosition = width;
			contentSliding = '-='+width;
		}
		if(direction == 'prev') {
			contentPosition = -width;
			contentSliding = '+='+width;
		}

		$(".events-week").last().css({'left':contentPosition+'px'});
		$(".events-week").last().addClass('new-week');

		$('.events-week').animate({
			left:contentSliding,
			},500,function(){ ;
				if(!$(this).hasClass('new-week')) $(this).remove();
				$(this).removeClass('new-week');				
				return;
		});
	}

	function setHeightCalendar(){

		var heightCalendar = $(".events-week").last().height();
		$("#calendar-content").css('height',heightCalendar);
	}

	function callWeek(url,form,direction){

		var screenWidth = $(window).width();

		$.ajax({
			type:'GET',
			url: url,
			data : form,
			success: function( data ){
				
				$("#calendar-content").append( data );

				setHeightCalendar();

				slideCalendar(direction,screenWidth);	  				
				
			},
			dataType:'html'
		});
		return false;
	}
	function callNextWeek(url,form){
		callWeek(url,form,'next');
	}

	function callPreviousWeek(url,form){
		callWeek(url,form,'prev');
	}

	function callCurrentWeek(){
		callWeek($('.calendar-nav-now').attr('href'),$('#formSearch').serialize(),'prev');
	}

	$('.calendar-nav-prev').livequery(function(){
		$(this).click(function(){
			callPrevWeek($(this).attr('href'),$('#formSearch').serialize());
			return false;
		});
	});

	$('.calendar-nav-next').livequery(function(){
		$(this).click(function(){
			callNextWeek($(this).attr('href'),$('#formSearch').serialize());
			return false;
		});
	});

	$('.calendar-nav-now').livequery(function(){
		$(this).click(function(){
			callCurrentWeek();
			return false;
		});
	});


});


</script>