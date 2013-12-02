<div class="homepage">
	<h1>Wesport ! Faire du sport dans votre ville !</h1>

	<div class="row-fluid">
		<?php echo $this->session->flash() ;?>
		
		
		<form id="formSearch" method="GET" action="<?php echo Router::url('home/'.$params['date']);?>" >
			<?php echo $this->Form->input('cityID','hidden',array("value"=>$this->cookieEventSearch->read('cityID'))) ;?>	
			<?php echo $this->Form->input('extend',"hidden",array("value"=>$this->cookieEventSearch->read('extend'))) ;?>				
			<?php echo $this->Form->input('user_id','hidden',array('value'=>$this->session->user()->getID())) ;?>							

			<?php 
			$sports_selected = $this->cookieEventSearch->read('sports');

			$sport_list = array();
				foreach ($sports_available as $key => $sport) {
				 	$sports_list[$sport->slug] = $sport->name;
				 }									
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
			<?php reset($sports_available);?>
			</div>	

			
			<div class="sportSelect" id="control-sport">
			<a class="resetSports tooltipbottom" href="?sports=&sport=" title="Supprimer les sports"><span class="ws-icon ws-icon-close"></span></a>
				<?php echo $this->Form->_select('sport',$sports_list,array('default'=>$sports_selected,'placeholder'=>"Chercher un sport...")); ?>
			</div>


			<div class="calendar" id="calendar">
				<div class="calendar-header">
					<div class="week-nav previousWeek">
						<a class="calendar-nav-prev calendar-nav-link" id="colomn-prev-arrow" title="Semaine précédante" href="#!<?php echo Router::url('events/calendar/week/prev/');?>" draggable="false">AVANT</a>
					</div>

					<div class="week-nav nextWeek">
						<a class="calendar-nav-next calendar-nav-link" id="colomn-next-arrow" title="Semaine suivante" href="#!<?php echo Router::url('events/calendar/week/next/');?>" draggable='false' >APRES</a>
					</div>

					<div class="calendar-arianne">
						<span class="ws-icon ws-icon-location" style="color:white"></span>
						<?php if($location = $this->cookieEventSearch->read('location')): ?>
							<?php if($this->cookieEventSearch->read('cityName')) echo '<strong>'.$this->cookieEventSearch->read('cityName').'</strong>'; ?>
							<?php if($this->cookieEventSearch->read('extend')) echo '<strong>(+'.$this->cookieEventSearch->read('extend').'km)</strong>'; ?>
							<?php if(!empty($location['ADM4'])) echo '<i>'.$location['ADM4'].'</i>';
									elseif(!empty($location['ADM3'])) echo '<i>'.$location['ADM3'].'</i>';
									elseif(!empty($location['ADM2'])) echo '<i>'.$location['ADM2'].'</i>';
									elseif(!empty($location['ADM1'])) echo '<i>'.$location['ADM1'].'</i>';
									elseif(!empty($location['CC1'])) echo'<i>'. $location['CC1'].'</i>';?>
						<?php else: ?>
						<i>Toute la France</i>
						<?php endif; ?>
					</div>
					<div class="calendar-period">
						<strong>Affichage:</strong>
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
					<div class="calendar-loader" id="calendar-loader"><img id="loadingweeks" src="<?php echo Router::webroot('img/ajax-loader-bar.gif');?>" title="Chargement..." /></div>
					<div class="fresque"></div>
					<a style="display:none" class="with-ajax calendar-nav calendar-nav-now fleft" href="<?php echo Router::url('events/calendar/now');?>"><span>Now</span></a>
				</div>

				<table class="calendar-nav" >
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

								$this->request->setGet($params);
								//appel les evenements de la semaine en cours
								echo $this->request('events','calendar',array(array('week','now')));
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


<!-- DEMO TOUR :: http://ryanfunduk.com/jquery-tourbus/ -->
<div class="tour-overlay"></div>
<ol class='tourbus-legs' id='wesport-demo'>
  <li data-el='#weSportBrand' data-orientation='bottom' data-width='500' data-arrow='20%' data-margin='20' data-align="left">
    <h2>Bienvenue sur <img src="<?php echo Router::webroot('img/BRAND.png');?>"></h2>
    <p>
    	<strong>We-Sport c'est l'agenda des activités sportives de ta ville&nbsp;!</strong>
		<br><small><i>Pour ne plus jamais jouer seul au Jokari</i></small>
    </p>
    <a href='javascript:void(0);' class='btn btn-info tourbus-next fright' rel="nofollow"><i class="icon icon-play icon-white"></i> Comment ça marche?</a>
    <a href="javascript:void(0);" class='btn tourbus-stop fright btn-link' rel="nofollow"><i class="icon icon-remove"></i> Fermer</a>
  </li>

  <li data-el='#menu-searchbar' data-orientation='bottom' data-width='450' data-arrow='20%' data-highlight="true">
    <h2><span class="stepNumber">1</span> Précise ta ville !</h2>
    <p class='tabbed'>
	    <strong>Tape ici le nom de ta ville et choisi dans la liste déroulante&nbsp;!</strong> 
	    <small>Tu peux aussi étendre la recherche jusqu'à un rayon de 100km&nbsp;!</small>
	</p>
    <a href='javascript:void(0);' class='btn tourbus-next fright btn-info' rel="nofollow">Suivant <span class="ws-icon ws-icon-arrow-right"></span></a>
    <a href="javascript:void(0);" class='btn tourbus-prev fright btn-link' rel="nofollow"><i class="icon icon-arrow-left"></i> Précedant</a>
  </li>

  <li data-el='#sportCheckboxs' data-orientation='top' data-left='17%' data-width='600' data-highlight="true">
    <h2><span class="stepNumber">2</span> Choisi tes sports préférés !</h2>
    <p class='tabbed'>
    	<strong>Beaucoup de sports et d'activités de nature sont possibles&nbsp;!</strong> 
    	<small>Par défaut tous les sports sont affichés !</small>
    </p>
   <a href='javascript:void(0);' class='btn tourbus-next fright btn-info' rel="nofollow">Suivant <span class="ws-icon ws-icon-arrow-right "></span></a>
    <a href="javascript:void(0);" class='btn tourbus-prev fright btn-link' rel="nofollow"><i class="icon icon-arrow-left"></i> Précedant</a>
  </li>

  <li data-el="#calendar-content" data-orientation="top" data-left='25%' data-width='550' data-align='left' data-highlight="true">
  	<h2><span class="stepNumber">3</span> Trouve une annonce qui te plaît&nbsp;!</h2>
  	<p class='tabbed'>
  		<strong>Regarde les annonces et fais défiler le calendrier !</strong>
  		<br><small>Tu peux slider le calendrier vers la gauche ou vers la droite...</small>
  	</p>
  
  	<a href='javascript:void(0);' class='btn tourbus-next fright btn-info' rel="nofollow">Suivant <span class="ws-icon ws-icon-arrow-right "></span></a>
    <a href="javascript:void(0);" class='btn tourbus-prev fright btn-link' rel="nofollow"><i class="icon icon-arrow-left"></i> Précedant</a>
  </li>

   <li data-el="#menu-createevent" data-orientation="bottom" data-width='400' data-align='center' data-highlight="true">
  	<h2><span class="stepNumber">4</span> Pas trouvé d'activité ?</h2>
  	<p class='tabbed'><strong>Pas grave, tu peux poster la tienne en 2min&nbsp;!</strong></p>

  	<a href='javascript:void(0);' class='btn tourbus-next fright btn-info' rel="nofollow">Suivant <span class="ws-icon ws-icon-arrow-right "></span></a>
    <a href="javascript:void(0);" class='btn tourbus-prev fright btn-link' rel="nofollow"><i class="icon icon-arrow-left"></i> Précedant</a>
  </li>

  <li data-el="#registerMenu" data-orientation="bottom" data-width='500' data-align="right" data-arrow='80%'>
  	<h2>Voila <span class="ws-icon-happy"></span> !</h2>
  	<p><strong>Inscrivez-vous !</strong><br> C'est simple, rapide, et gratuit&nbsp;!</p>
  	<a href="<?php echo Router::url('users/register');?>" class="btn btn-info" rel="nofollow"><span class="ws-icon ws-icon-checkmark"></span> S'inscrire maintenant</a>
  	<a href="javascript:void(0);" class="btn tourbus-next btn-link" rel="nofollow"><i class="icon icon-hand-right"></i> une dernière chose...</a>
  </li>

  <li data-el="#contact" data-orientation="top" date-width='400' data-align="center" data-margin='15'>
  	<h2>Nous avons besoin de gens !</h2>  	
  	<p><strong>Soyez sympa, donnez-nous votre avis, vos idées, ...</strong></p>
  	<p>We-Sport est encore jeune et nous avons besoin de testeurs ! <small>( et bientôt de modérateur, d'ambassadeur, ... )</small></p>

  	<a href="<?php echo Router::url('pages/contact');?>" class="btn btn-success" rel="nofollow"><span class="ws-icon ws-icon-happy"></span> Contactez-nous !</a>
	<a href="<?php echo Router::url('users/register');?>" class="btn btn-link" rel="nofollow"><i class="icon icon-ok"></i> S'inscrire ! </a>
	<a href="javascript:void(0);" class='btn tourbus-stop btn-link' rel="nofollow"><i class="icon icon-remove"></i> Non merci</a>

  </li>
</ol>

<script type="text/javascript">

$(document).ready(function(){

	//init var 
	var _cal = $('#calendar-content');
	var _zone = $('#calendar');
	var _aPrev = $('#pullPrev');
	var _aNext = $('#pullNext');
	var _drag = false;
	var _cWeek = true;
	var _newWeek, _oldWeek;
	var _wDrag = 150;
	var _xO;
	var _yO;
	var _mxO;
	var _myO;
	var _x;
	var _y;
	var _lock;


	//Appel la semaine courante
	callThisWeek();


	//set drag listeners
	setInitialListener();
	function setInitialListener(){
		_zone.on('mousedown touchstart',startDrag);
		$(window).on('mouseup touchend',stopDrag);
		setCalendarOrigin();
	}
	function setCalendarOrigin(){
		pos = _cal.position();
		_xO = pos.left;
	}
	function setMouseOrigin(e){
		_mxO = getClientX(e) +_xO;
	}

	function getClientX(e){
		if(e.clientX) return e.clientX;
		if(e.originalEvent.changedTouches[0].clientX) return e.originalEvent.changedTouches[0].clientX;
	}

	function startDrag(e){			
		
		if(e.which == 2 ||e.which == 3) return; //if middle click or right click , stop script

		setMouseOrigin(e);

		//prevent android bug where touchmove fire only once
		if( navigator.userAgent.match(/Android/i) ) {
		    //e.preventDefault();
		}

		$(window).on('mousemove touchmove',dragCalendar);
		
	}
	function stopDrag(e){

		$(window).off('mousemove touchmove');
		if(_lock=='left'){ 
			callPreviousWeek(); 
			lockLoad();
			_drag=true;
		}
		else if(_lock=='right'){ 
			callNextWeek();  
			lockLoad();
			_drag=true;
		}
		else {
			revert();
			_drag = false;
		}
	}

	function revert(){
		_cal.animate({left:0}, _wDrag, 'swing');
	}


	function dragCalendar(e){
		//distance between mouse coord and initial coord
		var x = _xO + getClientX(e) - _mxO;
		//if it is the first week and drag to previous return false
		if(isCurrentWeek()==true && x>0 ) return;
		//if the drag distance if inferior to 10 px , return false
		if(Math.sqrt(Math.pow(x,2))<10) return;
		//if the drag distance is superior to the trigger width
		if(x>=_wDrag) {
			_cal.css('left',_wDrag);
			lockPrev(); //set lock to previous
			return;
		}
		if(x<=-_wDrag) {
			_cal.css('left',-_wDrag);
			lockNext(); //set lock to next week
			return;
		}
		//set no lock
		nolock();

		x += 'px';

		_cal.css('left',x);
		_cal.css('z-index',0);
	}
	function nolock(){
		_lock = '';		
		_cal.find('#pullPrev,#pullNext').removeClass('locked').removeClass('loading');
	}
	function lockPrev(){
		_lock = 'left';
		_cal.find('#pullPrev').addClass('locked');
	}
	function lockNext(){
		_lock = 'right';
		_cal.find('#pullNext').addClass('locked');		
	}
	function lockLoad(){
		_lock='';
		_cal.find('#pullPrev,#pullNext').removeClass('locked').addClass('loading');
	}

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

		_cal.css('left',0);

		_newWeek.css({'left':contentPosition+'px'});

		_cal.find('.events-weeks').animate({
			left:contentSliding,
			},duration,function(){ 
				_oldWeek.remove();							
				return;
		});
	}

	function setHeightCalendar(){

		var minHeight = parseInt(_newWeek.css('minHeight'));
		var heightCalendar = _newWeek.height();		
		if(heightCalendar > minHeight){
			_cal.css('height',heightCalendar);
			_newWeek.css('height',heightCalendar);
		} else {
			_cal.css('height',minHeight);
			_newWeek.css('height',minHeight);
		}
		
	}


	function isCurrentWeek(){
		
		if(_cWeek==true) return true;
		return false;
	}

	function setCurrentWeek(){
		
		if(_newWeek.hasClass('current-week')) _cWeek = true;
		else _cWeek = false;
		return _cWeek;
	}

	function callWeek(url,direction){		

		$('#calendar-loader #loadingweeks').show();		
		var screenWidth = $(window).width();
		var form = $('#formSearch').serialize();
		form += '&maxdays='+findNumberDayPerWeek();

		$.ajax({
			type:'GET',
			url: url,
			data : form,
			success: function( data ){
				
				_cal.append( data );	

				_oldWeek = _cal.find(".events-weeks:first");
				_newWeek = _cal.find(".events-weeks:last");										

				slideCalendar(direction,screenWidth);	  				
				
				setCurrentWeek();

				if(isCurrentWeek()){					
					$('a.calendar-nav-prev').hide();
				}
				else{
					$('a.calendar-nav-prev').show();
				}

				setHeightCalendar();
			
				$('#calendar-loader #loadingweeks').hide();


			},
			dataType:'html'
		});		

		return false;
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

	function callNextWeek(){
		var url = _cal.attr('data-url-calendar-next');
		var direction = 'right';
		callWeek(url,direction);
	}

	function callPreviousWeek(){
		var url = _cal.attr('data-url-calendar-prev');
		var direction = 'left';
		callWeek(url,direction);
	}

	function callThisWeek(direction){
		var url = _cal.attr('data-url-calendar-now');
		callWeek(url,direction);
	}

	function callCurrentWeek(direction){
		var url = _cal.attr('data-url-calendar-date');
		var date = $('.events-weeks').attr('data-first-day');
		url = url+'/'+date;
		callWeek(url,direction);
	}

	$('a.calendar-nav-prev').livequery(function(){
		$(this).click(function(e){
			callPreviousWeek();
			e.preventDefault();
			e.stopPropagation();
			return false;
		});
	});

	$('a.calendar-nav-next').livequery(function(){
		$(this).click(function(e){
			callNextWeek();
			e.preventDefault();
			e.stopPropagation();
			return false;
		});
	});

	$('a.calendar-nav-now').livequery(function(){
		$(this).click(function(e){
			e.preventDefault();
			e.stopPropagation();
			callThisWeek('prev');
			return false;
		});
	});


	

	//Info bulle des activités
	_cal.find('.events-link').livequery(function(){
		$(this).popover({
			html:true,
			trigger:'hover',
			placement:'top',
			container:'body',			
			speed:10,
			delay: { show: 1500, hide: 100 }		
		});
		$(this).on('click',function(e){
			if(_drag==true) e.preventDefault();
		});

	});


	//Sport checkbox slider
	$('#sportCheckboxs').FlowSlider({
		animation:'None',
		detectCssTransition:'true',
		detectTouchDevice:'true'
	});
	$('#sportCheckboxs').css('overflow','visible');

	//Sport button
	//Submit form on click
	$('input[type=checkbox].sportCheckbox, input[type=radio].periodRadio, select#sport').change(function(e){
		//call same week
		callCurrentWeek();

		if($(e.target).is("select#sport")){
			var s = $(e.target).val();
			$("#label-"+s).attr('checked',true);
			$('#sportCheckboxs').removeClass('allSportDisplayed');		
		}

		if($('.sportCheckbox:checked').length!=0)
			$('#sportCheckboxs').removeClass('allSportDisplayed');		
		else 
			$('#sportCheckboxs').addClass('allSportDisplayed');				
	});




	//Demo Tourbus
	if(
		$('#wesport-demo').length!=0 // if a demo tour is present on the DOM
		&& $('body').attr('data-user_id')==0 // and if no user is log
		&& $('body').attr('data-display-demo')==1 //and if the cookie settings are ok
		&& $(window).width()>=768) // and if the screen is large enougth
	{
		//Init the demo tour
		var demo = $('#wesport-demo').tourbus({
			leg:{scrollto:0},
			onLegStart: function( leg, bus ) {
			    if( leg.rawData.highlight ) {
			      leg.$target.addClass('tour-highlight');
			      $('.tour-overlay').show();
			    }
			},
			  onLegEnd: function( leg, bus ) {
			    if( leg.rawData.highlight ) {
			      leg.$target.removeClass('tour-highlight');
			      $('.tour-overlay').hide();
			    }
			}
		});
		//Start the demo tour
		demo.trigger('depart.tourbus');
		
	}




});


</script>
