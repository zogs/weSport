<div class="container">

	<?php echo $this->session->flash(); ?>

	<div class="row">
		<div class="span8 offset2">
			<h1>Contact</h1>
			<span>Si tu es blonde a forte poitrine laisse nous un pti message !</span>
			<?php 
			//debug($user);
			 ?>
			<form class="form" action="<?php echo Router::url('pages/contact/#');?>" method="POST">
				<?php echo $this->Form->input("name","Votre nom",array("type"=>"text","value"=>$user->login)) ;?>
				<?php echo $this->Form->input("email","Votre mail",array("type"=>"text","value"=>$user->email)) ;?>
				<?php echo $this->Form->input("token","hidden",array("value"=>$this->session->token())) ;?>
				<?php echo $this->Form->input("time","hidden",array("value"=>time())) ;?>
				<?php echo $this->Form->input("login","hidden",array("value"=>"")) ;?>
				<?php echo $this->Form->input("title","Titre du message",array("placeholder"=>"Titre du message")) ;?>
				<?php echo $this->Form->input("message","Votre message",array("type"=>"textarea","placeholder"=>"Votre message","style"=>"height:100px")) ;?>

				<input type="submit" value="Envoyer" class="btn btn-primary">
			</form>
		</div>
	</div>
	
</div>