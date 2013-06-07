<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr">
<head>
	<meta http-equiv="Content-type" content="text/html; charset=utf-8" />
	<?php $this->loadCSS();?>
	<?php $this->loadJS();?>	
	<title><?php echo isset($title_for_layout)?$title_for_layout : Conf::$website;?></title>
	
</head>
<body data-user_id="<?php echo $this->session->user()->getID(); ?>">


	<div class="navbar navbar-inverse navbar-fixed-top">
	  <div class="navbar-innerOO">
	    <div class="container" id="navbar-bkg">
      		<a class="brand" href="<?php echo Router::url('pages/home');?>">
	      	  	<?php echo Conf::$website; ?>
			</a>
			<ul class="nav">
				<li><a href="<?php echo Router::url('pages/home');?>">Trouver un sport</a></li>				
				<li><a href="<?php echo Router::url('events/create');?>">Proposer un sport</a></li>	
				<li><a href="<?php echo Router::url('blog');?>">Le Blog</a></li>
				
				<?php
				//Admin section button
				if($this->session->user()->getRole()=='admin'):?>
				<li><a href="<?php echo Router::url('admin/posts/index');?>">Admin.</a></li>
				<?php endif;
				
				?>			
			</ul>

			<ul class="nav pull-right">
				<?php if ($this->session->user()->isLog()): ?>
					<li><a href="<?php echo Router::url('users/account');?>">
							<img class="nav-avatar" src="<?php echo Router::webroot($this->session->user()->getAvatar()); ?>" />	
							<span class="nav-login"><?php echo $this->session->user()->getLogin(); ?></span>
					</a></li>
					<li class="dropdown">				
						<a href="#" class="dropdown-toggle" data-toggle="dropdown">
							<b class="caret"></b>
						</a>
						<ul class="dropdown-menu">
							<li><a href="<?php echo Router::url('users/logout'); ?>">Déconnexion</a></li>
							<li class="divider"></li>
							<li><a href="<?php echo Router::url('users/account'); ?>">Mon Compte</a></li>						
						</ul>
					</li>
				<?php else: ?>
					<form class="loginForm" action="<?php echo Router::url('users/login'); ?>" method='post'>
						<input type="login" name="login" required="required" placeholder="Login or email" autofocus="autofocus" value="admin"/>
						<input type="password" name="password" required="required" placeholder="Password" value="fatboy" />
						<input type="hidden" name="token" value="<?php echo $this->session->token();?>" />
						<input type="submit" value="OK" />
					</form>
					<li><a href="<?php echo Router::url('users/login');?>">Login</a></li>	
					<li><a href="<?php echo Router::url('users/register');?>" >Inscription</a></li>
				<?php endif ?>
			</ul>
		</div>
	  </div>
	</div>

	<div class="container-fluid mainContainer">			
		<?php echo $content_for_layout;?>
	</div>


	<div class="footer container-fluid">
		<div class="container">
			<ul>
				<li><a href="<?php echo Router::url('pages/view/conditions');?>">Conditions d'utilisations</li>
				<li><a href="<?php echo Router::url('pages/view/mentions');?>">Mentions Légales</li>
				<li><a href="<?php echo Router::url('pages/view/apropos');?>">A Propos de nous</li>
				<li><a href="<?php echo Router::url('pages/contact');?>">Contact</li>
			</ul>
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