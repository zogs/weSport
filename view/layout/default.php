<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr">
<?php 

//Si cette page est un objet OpenGraph on recupere les balises metas qui vont bien

if(isset($this->OpenGraphObject)) $openGraph = $this->request('events',$this->OpenGraphObject['method'],array($event));

?>
<head <?php if(isset($openGraph['head'])) echo $openGraph['head'];?>>
	<?php if(isset($openGraph['metas'])) echo $openGraph['metas']; ?>
	<meta http-equiv="Content-type" content="text/html; charset=utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta http-equip="cache-control" content="no-cache">
	<link rel="icon" type="image/png" href="<?php echo Router::webroot('img/LOGO.gif');?>">
	<link rel="shortcut icon" type="image/x-icon" href="<?php echo Router::webroot('img/wesport.ico');?>">
	<?php $this->loadCSS();?>
	<?php $this->loadJS();?>	
	<title><?php echo isset($title_for_layout)?$title_for_layout : Conf::$website;?></title>
	
</head>
<body data-user_id="<?php echo $this->session->user()->getID(); ?>">


	<div class="navbar navbar-fixed-top">
	      		<a class="weSport" id="logoWeSport" href="<?php echo Router::url('home');?>">
	      			<img src="<?php echo Router::webroot('img/LOGO.gif');?>" alt="">
		      	  	<i>we</i>Sport
			</a>

			<?php 
							
			$menu = $this->request('pages','getMenu',array('top'));				

			?>
			<ul class="nav mobileMenu">								
				<li class="dropdown">
					<a href="#" class="dropdown-toggle" data-toggle="dropdown">Menu<b class="caret"></b></a>
					<ul class="dropdown-menu">
						<?php foreach($menu as $page): ?>
							<li><a href="<?php echo Router::url($page->slug);?>" ><?php echo $page->title;?></a></li>
							<li class="divider"></li>
						<?php endforeach; ?>
						<?php if($this->session->user()->isLog()):?>
							<li><a href="<?php echo Router::url('users/account');?>">Mon Compte</a></li>
							<li class="divider"></li>
							<li><a href="<?php echo Router::url('users/logout'); ?>">Déconnexion</a></li>
						<?php else: ?>
							<li><a href="<?php echo Router::url('users/login');?>">Connexion</a></li>
							<li class="divider"></li>	
							<li><a href="<?php echo Router::url('users/register');?>" >Inscription</a></li>
						<?php endif; ?>						
					</ul>
				</li>
			</ul>

			<ul class="nav desktopMenu">
				<?php 
					reset($menu);
					foreach ($menu as $page):
				 ?>
				 <li>
				 	<a href="<?php echo Router::url($page->slug);?>" class="<?php echo ($page->isCurrentPage($this))? 'currentPage':'';?>"><?php echo $page->title;?></a>
				 </li>
				<?php endforeach;

				//Admin section button
				if($this->session->user()->getRole()=='admin'):?>
				<li>
					<a href="<?php echo Router::url('admin/pages/index');?>">Admin.</a>
				</li>
				<?php endif;
				
				?>			
			</ul>

			<ul class="nav pull-right desktopMenu">
				<?php if ($this->session->user()->isLog()): ?>
					<li><a href="<?php echo Router::url('users/account');?>">
							<img class="nav-avatar" src="<?php echo $this->session->user()->getAvatar(); ?>" />	
							<span class="nav-login"><?php echo $this->session->user()->getLogin(); ?></span>
					</a></li>
					<li class="dropdown">				
						<a href="#" class="dropdown-toggle" data-toggle="dropdown">
							<b class="caret"></b>
						</a>
						<ul class="dropdown-menu">
							<li><a href="<?php echo Router::url('users/account'); ?>">Mon Compte</a></li>						
							<li class="divider"></li>
							<li><a href="<?php echo Router::url('users/logout'); ?>">Déconnexion</a></li>
						</ul>
					</li>
				<?php else: ?>
					<form class="loginForm" action="<?php echo Router::url('users/login'); ?>" method='post'>
						<input type="login" name="login" required="required" placeholder="Login or email" autofocus="autofocus" value="admin"/>
						<input type="password" name="password" required="required" placeholder="Password" value="fatboy" />
						<input type="hidden" name="token" value="<?php echo $this->session->token();?>" />
						<input type="submit" value="OK" />
					</form>
					<li><a href="<?php echo Router::url('users/login');?>">Connexion</a></li>	
					<li><a href="<?php echo Router::url('users/register');?>" >Inscription</a></li>
				<?php endif ?>
			</ul>	
	</div>

	<div class="container-fluid mainContainer">	
		<?php echo $content_for_layout;?>
	</div>


	<div class="footer container-fluid">
		<div class="container">
			<ul>
				<?php 
				$menu = $this->request('pages','getMenu',array('bottom'));
				foreach ($menu as $page):
				?>
					<li><a href="<?php echo Router::url($page->slug);?>"><?php echo $page->title;?></a></li>
				<?php endforeach;?>
				 			
			</ul>
			<div class="copyright">2013 © weSport</div>
		</div>
	</div>

	<div class="modal fade" id="myModal"></div>


</body>



 <script type="text/javascript">

 	/*===========================================================
 		Set security token
 	============================================================*/
 	var CSRF_TOKEN = '<?php echo $this->session->token(); ?>';

  	/*===========================================================
 		Language of the page
 	============================================================*/
 	var Lang = '<?php echo $this->getLang(); ?>';	

 	
 	/*===========================================================
 		GOOGLE ANALYTICS
 	============================================================*/
	  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
	  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
	  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
	  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');
	  ga('create', 'UA-42468932-1', 'zogs.org');
	  ga('send', 'pageview');




</script>





</html>