<?php 
								
	$menu = $this->request('pages','getMenu',array('top'));				
	$bottommenu = $this->request('pages','getMenu',array('bottom'));
				
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr">
<?php 

//Si cette page est un objet OpenGraph on recupere les balises metas qui vont bien

if(isset($this->OpenGraphObject)) $openGraph = $this->OpenGraphObject;;

?>
<head <?php if(isset($openGraph['head'])) echo $openGraph['head'];?>>
	
	<title><?php echo isset($title_for_layout)?$title_for_layout : Conf::$website;?></title>
	<meta charset='utf-8'> 
	<meta http-equiv="X-UA-Compatible" content="IE=edge" />
	<meta name="description"content="<?php echo (!empty($description_for_layout))? $description_for_layout : '';?>" />
	<meta name="keywords" content="<?php echo (!empty($keywords_for_layout))? $keywords_for_layout : '';?>" />
	<meta name="google-site-verification" content="SeofOTfUyHE48_yX-s-Q2BUdqWuTiXZiX9fSkdGgZ9w" />
	<meta name="robots" content="index,follow" />

	<?php 
	//Open Graph special meta tags
	if(isset($openGraph['metas'])) echo $openGraph['metas']; 

	?>

	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta http-equip="cache-control" content="no-cache">
	<link rel="icon" type="image/png" href="<?php echo Router::webroot('img/LOGO.gif');?>">
	<link rel="shortcut icon" type="image/x-icon" href="<?php echo Router::webroot('img/wesport.ico');?>">
	<?php $this->loadCSS();?>
	<?php $this->loadJS();?>	
	
</head>
<body data-user_id="<?php echo $this->session->user()->getID(); ?>" data-display-demo="<?php echo (!empty($display_demo))? 1 : 0;?>">
	
	<div>

		<div class="navbar navbar-fixed-top">


				<div class="mobileMenus" id="mobileMenus">

					<div class="mobileHead">
						<a href="#mobMenuLeft"><span class="ws-icon-menu"></span></a>
						<a class="weSport" href="<?php echo Router::url('');?>">
			      			<img src="<?php echo Router::webroot('img/LOGO.gif');?>" alt="">
				      	  	<i>we</i><strong>Sport</strong>
						</a>						
						<a href="#mobMenuRight"><span class="ws-icon-home"></span></a>
													
					</div>
					<div id="mobMenuLeft">								
						<ul>
						<?php 
							$mobmenu = array_merge($menu,$bottommenu);
							foreach($mobmenu as $page):
							 ?>
							<li class="<?php echo ($page->isCurrentPage($this))? 'selected' : '';?>"><a href="<?php echo Router::url($page->slug);?>" ><span class="mm-icon ws-icon-<?php echo $page->icon;?>"></span> <?php echo $page->title;?></a></li>

						<?php endforeach; ?>																
						</ul>
					</div>	
					<div id="mobMenuRight">								
						<ul>						
						<?php if($this->session->user()->isLog()):?>	
							<li><a href="<?php echo Router::url('users/account');?>"><span class="mm-icon ws-icon-cog"></span> Mon Compte</a>
								<ul>
									<li><a href="<?php echo Router::url('users/account/account');?>">Compte</a></li>
									<li><a href="<?php echo Router::url('users/account/profil');?>">Profil</a></li>
									<li><a href="<?php echo Router::url('users/account/avatar');?>">Avatar</a></li>
									<li><a href="<?php echo Router::url('users/account/password');?>">Mot de passe</a></li>
									<li><a href="<?php echo Router::url('users/account/mailing');?>">Mailing</a></li>
								</ul>
							</li>							
							<li><a href="<?php echo Router::url('users/logout'); ?>"><span class="mm-icon ws-icon-exit"></span> Déconnexion</a></li>
						<?php else: ?>
							<li><a href="<?php echo Router::url('users/login');?>"><span class="mm-icon ws-icon-enter"></span> Connexion</a></li>
								
							<li><a href="<?php echo Router::url('users/register');?>"><span class="mm-icon ws-icon-quill"></span> Inscription</a></li>
						<?php endif; ?>						
							
						</ul>
					</div>					
				</div>

				<div class="desktopMenus">

					<ul class="nav">
						<li>
							<a class="weSport" href="<?php echo Router::url('');?>" title="Du Sport ! Vite !">
				      			<img src="<?php echo Router::webroot('img/LOGO.gif');?>" alt="">
					      	  	<i>we</i><strong>Sport</strong>
							</a>
						</li>
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
							<a href="<?php echo Router::url('admin/pages/home');?>"><span class="ws-icon-pacman"></span></a>
						</li>
						<?php endif;
						
						?>			
					</ul>

					<ul class="nav pull-right" id="registerMenu">
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

				<div id="userCounter">
					<span><b class="circle circle-grey"></b><?php echo $this->request('users','getCountTotalUsers',array());?> inscrits</span>
					<span><b class="circle circle-green"></b><?php echo $this->session->getOnlineUsers();?></span>
					<?php 
					if($id = $this->cookieEventSearch->read('cityID')):?>
						<br><span><b class="circle circle-blue"></b>
						<?php echo $this->request('users','getCountTotalUsersByCity',array($id)); ?>
						à
						<?php echo $this->cookieEventSearch->read('cityName'); ?>
					<?php endif; ?>													
				</div>	
		</div>

		<div class="container-fluid mainContainer">	
			<?php echo $content_for_layout;?>
		</div>


		<div class="footer container-fluid">
			<div class="container">
				<ul>
					<?php 					
					reset($bottommenu);
					foreach ($bottommenu as $page):
					?>
						<li><a href="<?php echo Router::url($page->slug);?>" id="<?php echo $page->slug;?>"><?php echo $page->title;?></a></li>
					<?php endforeach;?>
					 			
				</ul>

				<ul class='footer-cities'>
					<?php
					foreach (Conf::$villes as $sub => $ville): 
					?>	
						<li><a href="http://<?php echo $sub;?>.we-sport.fr"><?php echo $ville['name'];?></a></li>
					<?php
					endforeach;
					?>
				</ul>
				<div class="copyright">2013 © weSport</div>
			</div>
		</div>

		<div class="modal fade" id="myModal"></div>
		
		<!-- facebook code for like buttons -->
		<div id="fb-root"></div>
		<script>(function(d, s, id) {
		  var js, fjs = d.getElementsByTagName(s)[0];
		  if (d.getElementById(id)) return;
		  js = d.createElement(s); js.id = id;
		  js.src = "//connect.facebook.net/fr_FR/all.js#xfbml=1&appId=153720748148187";
		  fjs.parentNode.insertBefore(js, fjs);
		}(document, 'script', 'facebook-jssdk'));</script>

	</div>
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