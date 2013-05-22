<div class="homepage">

	<div class="row-fluid">
		
		<div class="span8 offset2">
	
			<?php echo $this->session->flash() ;?>
			<?php //debug($this->cookieEventSearch->arr());
			
			?>
					
			<form id="formSearch" method="GET" action="#" >
				<?php echo $this->Form->input('cityID','hidden',array("value"=>$this->cookieEventSearch->read('cityID'))) ;?>					
				<?php echo $this->Form->input("date","hidden",array("value"=>date('Y-m-d'))) ;?>
				<?php echo $this->Form->input('user_id','hidden',array('value'=>$this->session->user()->getID())) ;?>				

				<div class="cityRounded">
						
					<div class="cityInputs">
						<div class="containerCityName">
							<input type="text" id="cityName" name="cityName" class="cityName" value="<?php echo ($this->cookieEventSearch->read('cityID'))? $this->cookieEventSearch->read('cityName') : 'Votre Ville?';?>" autocomplete='off' data-autocomplete-url="<?php echo Router::url('world/suggestCity');?>">						
						</div>
						<div class="containerExtend">
							<?php echo $this->Form->_select("extend",array(0=>'0km',10=>'10km',30=>'30km', 50=>'50km',100=>'100km'),array("default"=>$this->cookieEventSearch->read('extend'),"placeholder"=>"Etendre à :")) ;?>								
						</div>								
					</div>
										
					<button class="citySubmit"><img src="<?php echo Router::webroot('img/search-icon.png');?>"></button>
				</div>

				<div class="sportCheckboxs">
					<?php 
						$sportsCols = array_chunk($sports_available, 5);

						foreach ($sportsCols as $sportsCol):?>
							<div class="sportsColumn">								
								<?php echo $this->Form->_checkbox('sports[]','Sport',$sportsCol,array('default'=>$this->cookieEventSearch->read('sports'),'openwrap'=>'<div class="colomnCheckbox">','closewrap'=>'</div>'));?>
							</div>							
					 	<?php endforeach; ?>					
					<?php ?>
				</div>	
			</form>
						
		</div>

		<div class="calendar">
			<div class="calendar-nav">
				<?php 
				$previousWeek = date('Y-m-d', strtotime($params['date'].' -7 days'));
				$nextWeek = date('Y-m-d', strtotime($params['date'].' +7 days'));
				?>
				<a class="with-ajax calendar-nav calendar-prev fleft" href="<?php echo Router::url('events/calendar');?>/-7/"><span><-</span></a>
				<a class="calendar-nav calendar-prev fleft" href="<?php echo Router::url('pages/home/'.$previousWeek);?>">Previous week</a>
				<a class="with-ajax calendar-nav calendar-next fright" href="<?php echo Router::url('events/calendar');?>/+7/"><span>-></span></a>
				<a class="calendar-nav calendar-next fright" href="<?php echo Router::url('pages/home/'.$nextWeek);?>">Next Week</a>
			</div>
			<div class="calendar-content">
					<?php $this->request('events','calendar',array($params)); ?>							
			</div>

			

		</div>
	</div>
</div>


<script type="text/javascript">

$(document).ready(function(){

	$('.colomn-date a').click(function(e){
		
		e.preventDefault();

		var colomn = $(this).parent().parent();			
		var others = $('.events-colomn');
		var ms = 200;

		if(colomn.hasClass('colomn-open')==true) { 


			colomn.animate({width:'14%'},{duration:ms,queue:false,ease:'ease-out'});
			others.find('.events-bb').animate({width:'100%',margin:'0%'},{duration:ms,queue:false,ease:'ease-out'});
			others.animate({width:'14%'},{duration:ms,queue:false,ease:'ease-out'});	
			colomn.removeClass('colomn-open');		
			
		}
		else {

			others.removeClass('colomn-open');			
			$('.events-colomn').animate({width:'10%'},{duration:ms,queue:false,ease:'ease-out'});
			colomn.animate({width:'40%'},{duration:ms,queue:false,ease:'ease-out'});
			others.find('.events-bb').animate({width:'100%'},{duration:ms,queue:false,ease:'ease-out'});
			colomn.find('.events-bb').animate({width:'30%',margin:'1%'},{duration:ms,queue:false,ease:'ease-out'});
			colomn.addClass('colomn-open');
			
		}
		
	});


	$('.events-avatar').tooltip({placement:'bottom'});


	


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


</script>