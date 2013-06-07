<div class="page-header">
	<h1>Editer une page</h1>
	<?php
	if(isset($trad)):
	 foreach ($trad as $lang) : ?> 
	<a href="?lang=<?php echo $lang;?>"><i class="flag flag-<?php echo $this->getFlagLang($lang);?>"></i> <?php echo Conf::$languageAvailable[$lang];?></a>
	<?php endforeach;
	endif;
	 ?>
</div>

<form class="form-horizontal" action="<?php echo Router::url('admin/pages/edit/'.$id); ?>" method="post">



<?php echo $this->Form->input('title','Titre du contenu');  ?>
<?php echo $this->Form->input('id','hidden');  ?>
<?php echo $this->Form->input('page_id','hidden') ;?>
<?php echo $this->Form->input('id_i18n','hidden') ;?>
<?php echo $this->Form->input('content','Contenu',array("type"=>"textarea","class"=>"wysiwyg","style"=>"width:100%;","rows"=>5));  ?>
<?php echo $this->Form->select('lang','Language',Conf::$languageAvailable,array('default'=>$this->getLang()));?>
<div class="control-group">
	<label for="" class="control-label"></label>
	<div class="controls">
		<input type="submit" class="btn btn-primary" value="Envoyer" />
	</div>
</div>

<?php echo $this->Form->input('online','Publié',array("type"=>"checkbox")) ;?>
<?php echo $this->Form->input('valid','Traduction valide',array("type"=>"checkbox")) ;?>
<?php echo $this->Form->input('menu','Menu',array("type"=>"checkbox")) ;?>
<?php echo $this->Form->input('type','type de contenu',array("value"=>$this->request->get('type'))) ;?>
<?php echo $this->Form->input('position','Position',array()) ;?>

<?php echo $this->Form->input('token','hidden',array('value'=>$this->session->token())) ;?>

<div class="control-group">
	<label for="" class="control-label"></label>
	<div class="controls">
		<input type="submit" class="btn btn-primary" value="Envoyer" />
	</div>
</div>
</form>

<script type="text/javascript">
	$(document).ready(function(){

		$("select#lang").on('change',function(){

			var url = window.location.href;
			if (url.indexOf('?') > -1){
			   url += '&lang='+$(this).val();
			}else{
			   url += '?lang='+$(this).val();
			}
			window.location.href = url;
		});


	});	
</script>