
<div class="container">
	
	<h2>Liste des mailings 
		<a class="btn btn-mini btn-info" href="<?php echo Router::url('admin/mailing/editmailing');?>">Créer un nouveau mailing</a>
		<a class="btn btn-mini btn-info" href="<?php echo Router::url('admin/mailing/listmailing');?>">Gestion des listes d'adresses</a>
	</h2>
	<table class="table table-striped table-condensed table-hover">
		<thead>
			<th>Date d'envoi</th>
			<th>Methode</th>
			<th>Nom du mailing</th>
			<th>Action</th>

		</thead>
		<tbody>
			 <?php foreach ($mailings as $m): ?>	
			 	<tr>
		 			<td>
		 				<?php echo $m->getStatus(); ?>
		 				<?php if($m->getStatus()=='pending'): ?>
		 					<a href="<?php echo Router::url('admin/mailing/launchmailing/'.$m->id);?>">Commencer</a>
		 				<?php endif;?>
		 			</td>
		 			<td>
		 				<?php echo $m->getMethod(); ?>
		 			</td>	 			
		 			<td><a href="<?php echo Router::url('admin/mailing/editmailing/'.$m->id);?>"><?php echo $m->title; ?></a></td>
			 		<td>
			 			<a href="<?php echo Router::url('admin/mailing/editmailing/'.$m->id);?>">Editer</a>			 			
			 			<a href="<?php echo Router::url('admin/mailing/deletemailing/'.$m->id);?>">Supprimer</a>
			 		</td>				
						
			 	</tr>
				 </form>
			 <?php endforeach ?>
		</tbody>
	</table>
	
</div>