<div class="homepage">
	<div class="row-fluid">
		<div class="flash"><?php echo $this->session->flash() ;?></div>
		
		<form id="formSearch" method="GET" action="<?php echo Router::url('home/'.$params['date']);?>" >
			<?php echo $this->Form->input('cityID','hidden',array("value"=>$this->cookieEventSearch->read('cityID'))) ;?>					
			<?php echo $this->Form->input('user_id','hidden',array('value'=>$this->session->user()->getID())) ;?>				

			<div class="cityRounded">
				
				<div class="cityInputs">
					<div class="containerCityName">
						<?php if(!empty($params['cityID'])): ?><a class="resetCity tooltiptop" title="Supprimer la ville" href="?cityName=&cityID=">x</a><?php endif; ?>
						<input type="text" id="cityName" name="cityName" class="cityName" value="<?php echo (!empty($params['cityName']))? $params['cityName'] : 'Votre ville ?';?>" autocomplete='off' data-autocomplete-url="<?php echo Router::url('world/suggestCity');?>">						
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
					<?php else: ?>
					<i>Taper une ville et sélectionner dans la liste déroulante</i>
					<?php endif; ?>
				</div>

				<div class="clearfix"></div>
			</div>

			<?php 
			$sports_selected = $this->cookieEventSearch->read('sports');									
			?>
			<div class="sportCheckboxs <?php if(empty($sports_selected)) echo 'allSportDisplayed';?>" id="sportCheckboxs">
				<?php 																	
					foreach ($sports_available as $sport):?>						
						<div class="sportChoice">
							<input class="sportCheckbox" type="checkbox" name="sports[]" value="<?php echo $sport->slug;?>" id="label-<?php echo $sport->slug;?>" <?php if(!empty($sports_selected)&&in_array($sport->slug,$sports_selected)) echo "checked='checked'";?> >
							<label for="label-<?php echo $sport->slug;?>" class="tooltiptop" data-toggle="tooltip" title="<?php echo ucfirst($sport->name);?>">
								<span class="ws-icon ws-icon-<?php echo $sport->slug;?>"><strong><?php echo $sport->name;?></strong></span>
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
			<div class="calendar-header">
				<div class="calendar-loader" id="calendar-loader"><span class="text">Chargement ...</span></div>
				<div class="fresque"></div>
				<a style="display:none" class="with-ajax calendar-nav calendar-nav-now fleft" href="<?php echo Router::url('events/calendar/now');?>"><span>Now</span></a>
			</div>

			<table class="calendar-nav">
				<tr>
					<td class="colomn-nav colomn-prev" id="colomn-prev">					
						<a class="calendar-nav-prev fleft" href="<?php echo Router::url('events/calendar/prev/');?>">						
							<img alt="Previous week" id="colomn-prev-arrow" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAFwAAAJzCAYAAACCinSSAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAAyJpVFh0WE1MOmNvbS5hZG9iZS54bXAAAAAAADw/eHBhY2tldCBiZWdpbj0i77u/IiBpZD0iVzVNME1wQ2VoaUh6cmVTek5UY3prYzlkIj8+IDx4OnhtcG1ldGEgeG1sbnM6eD0iYWRvYmU6bnM6bWV0YS8iIHg6eG1wdGs9IkFkb2JlIFhNUCBDb3JlIDUuMy1jMDExIDY2LjE0NTY2MSwgMjAxMi8wMi8wNi0xNDo1NjoyNyAgICAgICAgIj4gPHJkZjpSREYgeG1sbnM6cmRmPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5LzAyLzIyLXJkZi1zeW50YXgtbnMjIj4gPHJkZjpEZXNjcmlwdGlvbiByZGY6YWJvdXQ9IiIgeG1sbnM6eG1wPSJodHRwOi8vbnMuYWRvYmUuY29tL3hhcC8xLjAvIiB4bWxuczp4bXBNTT0iaHR0cDovL25zLmFkb2JlLmNvbS94YXAvMS4wL21tLyIgeG1sbnM6c3RSZWY9Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC9zVHlwZS9SZXNvdXJjZVJlZiMiIHhtcDpDcmVhdG9yVG9vbD0iQWRvYmUgUGhvdG9zaG9wIENTNiAoV2luZG93cykiIHhtcE1NOkluc3RhbmNlSUQ9InhtcC5paWQ6MkQ1RUQ1MTdEREJCMTFFMkJDM0ZGOTJBODM4Q0NDQjgiIHhtcE1NOkRvY3VtZW50SUQ9InhtcC5kaWQ6MkQ1RUQ1MThEREJCMTFFMkJDM0ZGOTJBODM4Q0NDQjgiPiA8eG1wTU06RGVyaXZlZEZyb20gc3RSZWY6aW5zdGFuY2VJRD0ieG1wLmlpZDoyRDVFRDUxNUREQkIxMUUyQkMzRkY5MkE4MzhDQ0NCOCIgc3RSZWY6ZG9jdW1lbnRJRD0ieG1wLmRpZDoyRDVFRDUxNkREQkIxMUUyQkMzRkY5MkE4MzhDQ0NCOCIvPiA8L3JkZjpEZXNjcmlwdGlvbj4gPC9yZGY6UkRGPiA8L3g6eG1wbWV0YT4gPD94cGFja2V0IGVuZD0iciI/Pu0haqgAAAy2SURBVHja7N1taxzn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" />				
						</a>
					</td>

					<td>
						<div class="calendar-content"
							id="calendar-content" 
							data-url-calendar="<?php echo Router::url('events/calendar/');?>"
							data-url-calendar-prev="<?php echo Router::url('events/calendar/prev');?>"
							data-url-calendar-next="<?php echo Router::url('events/calendar/next');?>"
							data-url-calendar-now="<?php echo Router::url('events/calendar/now');?>"
							data-url-calendar-date="<?php echo Router::url('events/calendar/date');?>"
							  >
						</div>
					</td>
				
					<td class="colomn-nav colomn-next">		
						<a class="calendar-nav-next fright" href="<?php echo Router::url('events/calendar/next/');?>">						
							<img alt="Next week" id="colomn-next-arrow" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAFwAAAJzCAYAAACCinSSAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAAyJpVFh0WE1MOmNvbS5hZG9iZS54bXAAAAAAADw/eHBhY2tldCBiZWdpbj0i77u/IiBpZD0iVzVNME1wQ2VoaUh6cmVTek5UY3prYzlkIj8+IDx4OnhtcG1ldGEgeG1sbnM6eD0iYWRvYmU6bnM6bWV0YS8iIHg6eG1wdGs9IkFkb2JlIFhNUCBDb3JlIDUuMy1jMDExIDY2LjE0NTY2MSwgMjAxMi8wMi8wNi0xNDo1NjoyNyAgICAgICAgIj4gPHJkZjpSREYgeG1sbnM6cmRmPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5LzAyLzIyLXJkZi1zeW50YXgtbnMjIj4gPHJkZjpEZXNjcmlwdGlvbiByZGY6YWJvdXQ9IiIgeG1sbnM6eG1wPSJodHRwOi8vbnMuYWRvYmUuY29tL3hhcC8xLjAvIiB4bWxuczp4bXBNTT0iaHR0cDovL25zLmFkb2JlLmNvbS94YXAvMS4wL21tLyIgeG1sbnM6c3RSZWY9Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC9zVHlwZS9SZXNvdXJjZVJlZiMiIHhtcDpDcmVhdG9yVG9vbD0iQWRvYmUgUGhvdG9zaG9wIENTNiAoV2luZG93cykiIHhtcE1NOkluc3RhbmNlSUQ9InhtcC5paWQ6RUFBQzA4QTNEREJBMTFFMkFFNkJGNTZCQkVDQjhFQTkiIHhtcE1NOkRvY3VtZW50SUQ9InhtcC5kaWQ6RUFBQzA4QTREREJBMTFFMkFFNkJGNTZCQkVDQjhFQTkiPiA8eG1wTU06RGVyaXZlZEZyb20gc3RSZWY6aW5zdGFuY2VJRD0ieG1wLmlpZDpFQUFDMDhBMUREQkExMUUyQUU2QkY1NkJCRUNCOEVBOSIgc3RSZWY6ZG9jdW1lbnRJRD0ieG1wLmRpZDpFQUFDMDhBMkREQkExMUUyQUU2QkY1NkJCRUNCOEVBOSIvPiA8L3JkZjpEZXNjcmlwdGlvbj4gPC9yZGY6UkRGPiA8L3g6eG1wbWV0YT4gPD94cGFja2V0IGVuZD0iciI/PkOofGkAAAyLSURBVHja7N3/i1xX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" />
						</a>
					</td>
				</tr>

			</table>
			<div class="calendar-footer"></div>
		</div>
	</div>
</div>

<ol class='tourbus-legs' id='wesport-demo'>

  <li data-el='#logoWeSport' data-orientation='bottom' data-width='500' data-arrow='20%' data-margin='20' data-align="left">
    <h2>Bienvenue sur WeSport !</h2>
    <p><strong>L'agenda des activités sportives de ta ville !</strong>
		<br><small><i>Pour ne plus jamais jouer seul au Jokari</i></small>
    </p>
    <a href='javascript:void(0);' class='btn tourbus-next'>Visite guidée</a>
    <a href="javascript:void(0);" class='btn tourbus-stop'><i class="icon icon-remove"></i> Non merci</a>
  </li>

  <li data-el='#cityName' data-orientation='bottom' data-width='450' data-arrow='20%' >
    <p><strong>Tapes ici la ville de ton choix et/ou choisis dans la liste déroulante</strong></p>
    <p><small> ( tu peux étendre le rayon d'activité jusqu'à 100km ! )</small></p>
    <a href='javascript:void(0);' class='btn tourbus-next'>Suivant</a>
  </li>

  <li data-el='#sportCheckboxs' data-orientation='top' data-width='350'>
    <p><strong>Tu peux afficher uniquement les sports que tu souhaites ! </strong></p>
    <p><small>( par défaut, tous les sports sont affichés )</small></p>
    <a href='javascript:void(0);' class='btn tourbus-next'>Suivant</a>
  </li>

  <li data-el="#calendar-content" data-orientation="top" data-width='400'>
  	<p><strong>Les annonces apparaissent dans le calendrier</strong></p>
  	<p><small>( tu peux naviguer vers les jours suivants avec la flèche de droite, ou, pour les écrans tactiles, en slidant le calendrier !)</small></p>
  	<a href="javascript:void(0);" class="btn tourbus-next">Compris !</a>
  </li>

  <li data-el="#contact" data-orientation="top" date-width='300' data-align="center">
  	<h2>Soyez sympa</h2>
  	<p>WeSport est encore en version de test !
  	<br><strong>Donnez nous votre avis, vos idées, vos envies, vos difficultés, ect ...</strong>
  	<br>On se fera un plaisir de vous répondre !
  	</p>
  	<a href="javascript:void(0);" class="btn tourbus-next">Une dernière chose</a>
  	<a href="<?php echo Router::url('pages/contact');?>" class="btn">Donnez votre avis</a>
  </li>

  <li data-el="#registerMenu" data-orientation="bottom" data-width='400' data-align="right">
  	<h2>Inscrivez-vous :)</h2>
  	<p>C'est facile, rapide, et gratuit !</p>
  	<a href="javascript:void(0);" class='btn tourbus-stop'><i class="icon icon-remove"></i> Terminer</a>
  	<a href="<?php echo Router::url('users/register');?>" class="btn">S'inscrire</a>
  </li>

</ol>

<script type="text/javascript">

var current_date = '<?php echo date("Y-m-d");?>';

$(document).ready(function(){


	$('#calendar-content .events-link').livequery(function(){

		$(this).popover({
			html:true,
			trigger:'hover',
			placement:'top',
			container:'body',
			delay: { show: 1500, hide: 100 }		
		});
	});




	//Sport checkbox slider
	$('#sportCheckboxs').FlowSlider();
	$('#sportCheckboxs').css('overflow','visible');

	//Sport button
	//Submit form on click
	$('.sportCheckbox').change(function(){
		//call same week
		callCurrentWeek();

		if($('.sportCheckbox:checked').length!=0)
			$('#sportCheckboxs').removeClass('allSportDisplayed');		
		else 
			$('#sportCheckboxs').addClass('allSportDisplayed');				
	});

	// On mouse over change widht of the items
	
	$(".www_FlowSlider_com-item").each(function(_, item) {
		var $item = $(item).children('.ws-icon-small');
		$item.mouseenter(function() {
		$item.stop().animate({'margin': 300}, 150);
		});
		$item.mouseleave(function() {
		$item.stop().animate({'margin': 150}, 150);
		});
	}); 

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

	var dayPerWeek = {320:1,480:2,768:3,1024:4,1280:5,1440:6};


	callThisWeek();

	function slideCalendar(direction,width){

		duration = 500;

		if(direction == 'right') {
			contentPosition = width;
			contentSliding = '-='+width;
		}
		if(direction == 'left') {
			contentPosition = -width;
			contentSliding = '+='+width;
		}
		if(typeof direction == 'undefined'){
			contentPosition = -width;
			contentSliding = '+='+width;
			duration = 0;
		}

		$(".events-week").last().css({'left':contentPosition+'px'}).addClass('new-week');

		$('.events-week').animate({
			left:contentSliding,
			},duration,function(){ ;
				if(!$(this).hasClass('new-week')) $(this).remove();
				$(this).removeClass('new-week');				
				return;
		});
	}

	function setHeightCalendar(){

		var heightCalendar = $("#calendar-content .events-week").last().height();
		$("#calendar-content").css('height',heightCalendar).css('min-height','1000px');
	}

	function findNumberDayPerWeek(){

		var screenWidth = $(window).width();
		var nb;
		for(var maxwidth in dayPerWeek){			
			if(screenWidth<=maxwidth) return dayPerWeek[maxwidth];	
		}
		return 7;
	}

	function checkCurrentWeek(){
		
		if($('.events-week').last().attr('data-first-day')==current_date) $('.calendar-nav-prev').hide();
		else $('.calendar-nav-prev').show();
	}

	function callWeek(url,direction){

		$('#calendar-loader > .text').show();		
		var screenWidth = $(window).width();
		var form = $('#formSearch').serialize();
		form += '&dayperweek='+findNumberDayPerWeek();

		$.ajax({
			type:'GET',
			url: url,
			data : form,
			success: function( data ){
				
				$("#calendar-content").append( data );				
				
				setHeightCalendar();

				slideCalendar(direction,screenWidth);	  				
				
				checkCurrentWeek();

				$('#calendar-loader > .text').hide();


			},
			dataType:'html'
		});		

		return false;
	}
	function callNextWeek(){
		var url = $('#calendar-content').attr('data-url-calendar-next');
		var direction = 'right';
		callWeek(url,direction);
	}

	function callPreviousWeek(){
		var url = $('#calendar-content').attr('data-url-calendar-prev');
		var direction = 'left';
		callWeek(url,direction);
	}

	function callThisWeek(direction){
		var url = $('#calendar-content').attr('data-url-calendar-now');
		callWeek(url,direction);
	}

	function callCurrentWeek(direction){
		var url = $('#calendar-content').attr('data-url-calendar-date');
		var date = $('.events-week').attr('data-first-day');
		url = url+'/'+date;
		callWeek(url,direction);
	}

	$('.calendar-nav-prev').livequery(function(){
		$(this).click(function(){
			callPreviousWeek();
			return false;
		});
	});

	$('.calendar-nav-next').livequery(function(){
		$(this).click(function(){
			callNextWeek();
			return false;
		});
	});

	$('.calendar-nav-now').livequery(function(){
		$(this).click(function(){
			callThisWeek('prev');
			return false;
		});
	});

	/* detect mobile swipe event */
	$('#calendar-content').swipe({
		swipeLeft:function(event,direction,distance,duration,fingerCount){
			callNextWeek();
		},
		swipeRight:function(event,direction,distance,duration,fingerCount){
			callPreviousWeek();
		}
	});



	//Demo Tourbus
	if(
		$('#wesport-demo').length!=0 // if a demo tour is present on the DOM
		&& $('body').attr('data-user_id')==0 // and if no user is log
		&& $('body').attr('data-display-demo')==1 //and if the cookie settings are ok
		&& $(window).width()>=768) // and if the screen is large enougth
	{
		//Init the demo tour
		var demo = $('#wesport-demo').tourbus({leg:{scrollto:0}});
		//Start the demo tour
		demo.trigger('depart.tourbus');
		
	}




});


</script>