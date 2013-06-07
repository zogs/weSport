<div class="page-header">
	<h1>Pages du site</h1>
	<?php foreach ($traductions as $lang => $number): ?>
	<a href="?lang=<?php echo $lang;?>"><i class="flag flag-<?php echo $this->getFlagLang($lang);?>"></i><?php echo Conf::$languageAvailable[$lang];?> (<?php echo $number;?>)</a>
	<?php endforeach; ?>
	

</div>


<table class="table table-striped">
	<thead>
		<th>Titre</th>
		<th>En ligne ?</th>
		<th>Menu</th>
		<th>Lang</th>		
		<th>Action</th>
		<th>Date</th>
		<th>ID</th>
	</thead>
	<tbody>

		 <?php foreach ($pages as $k => $v): ?>
		 	<?php if(isset($v->lang)):?>
				<form class="form " action="<?php echo Router::url('admin/pages/index');?>" method="POST">
			 	<tr>
		 			<td>
		 				<a href="<?php echo Router::url('admin/pages/edit/'.$v->id.'?lang='.$lang); ?>" >
		 					<?php echo $v->getTitle(); ?>
						</a>
	 				</td>
			 		<td>
			 			<span class="label<?php echo ($v->isOnline()==1)? ' label-success' : ''; ?>"><?php echo ($v->isOnline()==1)? 'En ligne' : 'Hors ligne'; ?></span>
			 			<?php echo $this->Form->_input('online','',array('type'=>'checkbox','value'=>$v->isOnline())) ;?>
			 		</td>

			 		<td>
			 			<span class="label<?php echo ($v->isOnMenu()==1)? ' label-success' : ''; ?>">Menu</span>
			 			<?php echo $this->Form->_input('menu','',array('type'=>'checkbox','value'=>$v->isOnMenu())) ;?>
			 		</td>

			 		<td>
			 			<i class="flag flag-<?php echo $this->getFlagLang($v->lang);?>"></i>
			 			<?php if(!$v->isTraductionValid()): ?><span class="label">Pending</span><?php endif; ?>
			 		</td>
			 		
			 		<td>
						<input type="Submit"  class="submitAsLink" value="Sauver" />

			 			<a href="<?php echo Router::url('admin/pages/edit/'.$v->id.'?lang='.$lang); ?>" >Editer</a>

			 			<a onclick="return confirm('Voulez-vous vraiment supprimer cet élément ?');" href="<?php echo Router::url('admin/pages/delete/'.$v->id); ?>" >Supprimer</a>


			 		</td>
			 		<td><?php echo $v->date;?></td>
			 		<td><?php echo $v->id ?></td>
			 	</tr>
			 	<?php echo $this->Form->input('id','hidden',array('value'=>$v->id)) ;?>
			 	<?php echo $this->Form->input('token','hidden',array('value'=>$this->session->token())) ;?>
				 </form>
			<?php endif; ?>
		 <?php endforeach ?>
	</tbody>
</table>

	


</form>
<a href="<?php echo Router::url('admin/pages/edit?type=page'); ?>" class="btn btn-primary"> Ajouter une page</a>