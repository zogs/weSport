<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr">
<?php 

//Si cette page est un objet OpenGraph on recupere les balises metas qui vont bien

if(isset($this->OpenGraphObject)) $openGraph = $this->request('events',$this->OpenGraphObject['method'],array($event));

?>
<head <?php if(isset($openGraph['head'])) echo $openGraph['head'];?>>
	<?php if(isset($openGraph['metas'])) echo $openGraph['metas']; ?>
	<meta http-equiv="Content-type" content="text/html; charset=utf-8" />
	<link rel="icon" type="image/png" href="<?php echo Router::webroot('img/LOGO.gif');?>">
	<link rel="shortcut icon" type="image/x-icon" href="<?php echo Router::webroot('img/wesport.ico');?>">
	<?php $this->loadCSS();?>
	<?php $this->loadJS();?>	
	<title><?php echo isset($title_for_layout)?$title_for_layout : Conf::$website;?></title>
	
</head>
<body data-user_id="<?php echo $this->session->user()->getID(); ?>">


	<div class="navbar navbar-fixed-top">
      		<a class="weSport" href="<?php echo Router::url('/');?>">
      			<img src="<?php echo Router::webroot('img/LOGO.gif');?>" alt="">
	      	  	<i>we</i>Sport
			</a>
			<ul class="nav">
				<?php 
								
				$menu = $this->request('pages','getMenu',array('top'));
				foreach ($menu as $page):

				?>
				 <li><a href="<?php echo Router::url($page->slug);?>" class="<?php echo ($page->isCurrentPage())? 'currentPage':'';?>"><?php echo $page->title;?></a></li>

				<?php endforeach;?>				
				
				<?php
				//Admin section button
				if($this->session->user()->getRole()=='admin'):?>
				<li><a href="<?php echo Router::url('admin/pages/index');?>">Admin.</a></li>
				<?php endif;
				
				?>			
			</ul>

			<ul class="nav pull-right">
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
 		GOOGLE FONTS
 	============================================================*/
       WebFontConfig = {
         google: { families: [ 'Lobster'] },      
         fontinactive: function(fontFamily, fontDescription) { /*alert('Font '+fontFamily+' is currently not available'); */}
       };

       (function() {
         var wf = document.createElement('script');
         wf.src = ('https:' == document.location.protocol ? 'https' : 'http') +
             '://ajax.googleapis.com/ajax/libs/webfont/1/webfont.js';
         wf.type = 'text/javascript';
         wf.async = 'true';
         var s = document.getElementsByTagName('script')[0];
         s.parentNode.insertBefore(wf, s);
       })();
</script>





</html>