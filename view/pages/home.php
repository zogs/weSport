<div class="homepage">
	<h1>Wesport. Faites du sport dans votre region !</h1>

	<div class="row-fluid">
		<div class="flash"><?php echo $this->session->flash() ;?></div>
		
		
		<form id="formSearch" method="GET" action="<?php echo Router::url('home/'.$params['date']);?>" >
			<?php echo $this->Form->input('cityID','hidden',array("value"=>$this->cookieEventSearch->read('cityID'))) ;?>					
			<?php echo $this->Form->input('user_id','hidden',array('value'=>$this->session->user()->getID())) ;?>				

			<div class="cityRounded">
				
				<div class="cityInputs">
					<div class="containerCityName">
						<?php if(!empty($params['cityID'])): ?><a class="resetCity tooltiptop" title="Supprimer la ville" href="?cityName=&cityID=" rel="nofollow">x</a><?php endif; ?>
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
							<a href="?sports%5B%5D=<?php echo $sport->slug;?>"><?php echo $sport->name;?></a>
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

		<div class="calendar">
			<div class="calendar-header">
				<div class="calendar-period">
					<div class="periodChoice">
						<input type="radio" class="periodRadio" id="period1" name="nbdays" value="7" <?php if($params['nbdays']<=7) echo 'checked="checked"';?>>
						<label for="period1">1 semaine</label>
					</div>
					<div class="periodChoice">
						<input type="radio" class="periodRadio" id="period2" name="nbdays" value="14" <?php if($params['nbdays']>7) echo 'checked="checked"';?>>	
						<label for="period2">2 semaines</label>
					</div>
					<div class="periodChoice">
						<input type="radio" class="periodRadio" id="period4" name="nbdays" value="28" <?php if($params['nbdays']>21) echo 'checked="checked"';?>>
						<label for="period4">1 mois</label>
					</div>
				</div>
				<div class="calendar-loader" id="calendar-loader"><span class="text">Chargement ...</span></div>
				<div class="fresque"></div>
				<a style="display:none" class="with-ajax calendar-nav calendar-nav-now fleft" href="<?php echo Router::url('events/calendar/now');?>"><span>Now</span></a>
			</div>

			<table class="calendar-nav" id="calendar-nav">
				<tr>
					<td class="colomn-nav colomn-prev" id="colomn-prev">					
						<a class="calendar-nav-prev calendar-nav-link fleft" id="colomn-prev-arrow" title="Semaine précédante" href="#!<?php echo Router::url('events/calendar/week/prev/');?>" draggable="false"></a>
					</td>

					<td>
						<div class="calendar-content"
							id="calendar-content" 
							data-url-calendar="<?php echo Router::url('events/calendar/');?>"
							data-url-calendar-prev="<?php echo Router::url('events/calendar/week/prev');?>"
							data-url-calendar-next="<?php echo Router::url('events/calendar/week/next');?>"
							data-url-calendar-now="<?php echo Router::url('events/calendar/week/now');?>"
							data-url-calendar-date="<?php echo Router::url('events/calendar/week/date');?>"
							  >
							<?php 
							//appel les evenements de la semaine en cours
							echo $this->request('events','calendar',array('now'));
							?>
						</div>
					</td>
				
					<td class="colomn-nav colomn-next" id="colomn-next">		
						<a class="calendar-nav-next calendar-nav-link fright" id="colomn-next-arrow" title="Semaine suivante" href="#!<?php echo Router::url('events/calendar/week/next/');?>" draggable='false' ></a>
					</td>
				</tr>

			</table>
			<div class="calendar-footer"></div>
		</div>
		</form>

	</div>
</div>

<ol class='tourbus-legs' id='wesport-demo'>

  <li data-el='#logoWeSport' data-orientation='bottom' data-width='500' data-arrow='20%' data-margin='20' data-align="left">
    <h2>Bienvenue sur WeSport !</h2>
    <p><strong>L'agenda des activités sportives de ta ville !</strong>
		<br><small><i>Pour ne plus jamais jouer seul au Jokari</i></small>
    </p>
    <a href='javascript:void(0);' class='btn tourbus-next' rel="nofollow">Visite guidée</a>
    <a href="javascript:void(0);" class='btn tourbus-stop' rel="nofollow"><i class="icon icon-remove"></i> Non merci</a>
  </li>

  <li data-el='#cityName' data-orientation='bottom' data-width='450' data-arrow='20%' >
    <p><strong>Tape ici la ville de ton choix et/ou choisis dans la liste déroulante</strong></p>
    <p><small> ( tu peux étendre le rayon d'activité jusqu'à 100km ! )</small></p>
    <a href='javascript:void(0);' class='btn tourbus-next' rel="nofollow">Suivant</a>
  </li>

  <li data-el='#sportCheckboxs' data-orientation='top' data-width='350'>
    <p><strong>Tu peux afficher uniquement les sports que tu souhaites ! </strong></p>
    <p><small>( par défaut, tous les sports sont affichés )</small></p>
    <a href='javascript:void(0);' class='btn tourbus-next' rel="nofollow">Suivant</a>
  </li>

  <li data-el="#calendar-content" data-orientation="top" data-width='400'>
  	<p><strong>Les annonces apparaissent dans le calendrier</strong></p>
  	<p><small>( tu peux naviguer vers les jours suivants avec la flèche de droite, ou, pour les écrans tactiles, en slidant le calendrier !)</small></p>
  	<a href="javascript:void(0);" class="btn tourbus-next" rel="nofollow">Compris !</a>
  </li>

  <li data-el="#contact" data-orientation="top" date-width='300' data-align="center">
  	<h2>Soyez sympa</h2>
  	<p>WeSport est encore en version de test !
  	<br><strong>Donnez nous votre avis, vos idées, vos envies, vos difficultés, ect ...</strong>
  	<br>On se fera un plaisir de vous répondre !
  	</p>
  	<a href="javascript:void(0);" class="btn tourbus-next" rel="nofollow">Une dernière chose</a>
  	<a href="<?php echo Router::url('pages/contact');?>" class="btn" rel="nofollow">Donnez votre avis</a>
  </li>

  <li data-el="#registerMenu" data-orientation="bottom" data-width='400' data-align="right">
  	<h2>Inscrivez-vous :)</h2>
  	<p>C'est facile, rapide, et gratuit !</p>
  	<a href="javascript:void(0);" class='btn tourbus-stop' rel="nofollow"><i class="icon icon-remove"></i> Terminer</a>
  	<a href="<?php echo Router::url('users/register');?>" class="btn" rel="nofollow">S'inscrire</a>
  </li>

</ol>

<script type="text/javascript">

$(document).ready(function(){

	//Appel la semaine courante
	callThisWeek();

	//init Drag calendar
	var _cal = $('#calendar-content');
	var _zone = $('#calendar-nav');
	var _w = 200;
	var _xO;
	var _yO;
	var _mxO;
	var _myO;
	var _x;
	var _y;
	var _lock;


	setInitialListener();
	function setInitialListener(){
		_zone.on('mousedown',startDrag);
		$(window).on('mouseup',stopDrag);
		setCalendarOrigin();
	}
	function setCalendarOrigin(){
		pos = _cal.position();
		_xO = pos.left;
		_yO = pos.top;
	}
	function setMouseOrigin(e){
		_mxO = e.clientX +_xO;
		_myO = e.clientY +_yO;
	}
	function startDrag(e){

		var t = $(e.target);
		if(t.is('span') || t.is('strong') || t.is('i') || t.is('p') || t.is('div') || t.is('a:not(.calendar-nav-link)')) return; //if drag on text element stop script
		if ((e.button == 1 && window.event != null) || e.button == 0) {//if left mouse button
			setMouseOrigin(e);
			$(window).on('mousemove',dragCalendar);
		}
	}
	function stopDrag(e){
		$(window).off('mousemove');
		if(_lock=='left'){ callPreviousWeek(); lockLoad();}
		else if(_lock=='right'){ callNextWeek();  lockLoad();}
		else revert();
	}
	function revert(){
		_cal.animate({left:0}, _w, 'swing');
	}
	function dragCalendar(e){

		var x;
	
		x = _xO + e.clientX - _mxO;

		if(x>0 && isCurrentWeek()==true) return;

		if(x==0 || x==1 ||x==-1 ||x==2 ||x==-2) return;

		if(x>=_w) {
			lockPrev();
			return;
		}
		if(x<=-_w) {
			lockNext();
			return;
		}

		nolock();

		x += 'px';

		_cal.css('left',x);
		_cal.css('z-index',0);
	}
	function nolock(){
		_lock = '';
		$('#pullPrev .pull-off, #pullNext .pull-off').hide();
		$('#pullPrev .pull-in, #pullNext .pull-in').show();

	}
	function lockPrev(){
		_lock = 'left';
		$('#pullPrev .pull-in').hide();
		$('#pullPrev .pull-off').show();
	}
	function lockNext(){
		_lock = 'right';
		$('#pullNext .pull-in').hide();
		$('#pullNext .pull-off').show();
	}
	function lockLoad(){
		_lock='';
		$('#pullPrev .pull-off, #pullNext .pull-off').hide();
		$('#pullPrev .pull-in, #pullNext .pull-in').hide();
		$('#pullPrev .pull-load, #pullNext .pull-load').show();
	}




	//Info bulle des activités
	$('#calendar-content .events-link').livequery(function(){
		$(this).popover({
			html:true,
			trigger:'hover',
			placement:'top',
			container:'body',			
			speed:10,
			delay: { show: 800, hide: 100 }		
		});
	});


	//Sport checkbox slider
	$('#sportCheckboxs').FlowSlider();
	$('#sportCheckboxs').css('overflow','visible');

	//Sport button
	//Submit form on click
	$('.sportCheckbox, .periodRadio').change(function(){
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

		$('#calendar-content').css('left',0);

		$(".events-weeks").last().css({'left':contentPosition+'px'}).addClass('new-week');

		$('.events-weeks').animate({
			left:contentSliding,
			},duration,function(){ ;
				if(!$(this).hasClass('new-week')) $(this).remove();
				$(this).removeClass('new-week');				
				return;
		});
	}

	function setHeightCalendar(){

		var heightCalendar = $("#calendar-content .events-weeks").height();
		$("#calendar-content").css('height',heightCalendar);
	}

	function findNumberDayPerWeek(){

		//Nombre de jour à afficher en fonction de la largeur de l'écran
		var dayPerWeek = {320:1,480:2,768:3,1024:4,1280:5,1440:6};
		
		var screenWidth = $(window).width();
		var nb;
		for(var maxwidth in dayPerWeek){	
			if(screenWidth<=maxwidth) return dayPerWeek[maxwidth];	
		}
		return 0;
	}

	function isCurrentWeek(){
		
		if($('.events-weeks:last').hasClass('current-week')) return true;
		return false;
	}

	function callWeek(url,direction){		

		$('#calendar-loader > .text').show();		
		var screenWidth = $(window).width();
		var form = $('#formSearch').serialize();
		form += '&maxdays='+findNumberDayPerWeek();

		$.ajax({
			type:'GET',
			url: url,
			data : form,
			success: function( data ){
				
				$("#calendar-content").append( data );				
				
				setHeightCalendar();

				slideCalendar(direction,screenWidth);	  				
				
				console.log(isCurrentWeek());
				if(isCurrentWeek()){
					console.log('is current week');
					$('.calendar-nav-prev').hide();
				}
				else{
					$('.calendar-nav-prev').show();
				}

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
		var date = $('.events-weeks').attr('data-first-day');
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

	/* detect mobile swipe event 
	$('#calendar-content').swipe({
		swipeLeft:function(event,direction,distance,duration,fingerCount){
			callNextWeek();
		},
		swipeRight:function(event,direction,distance,duration,fingerCount){
			callPreviousWeek();
		}
	});
*/


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