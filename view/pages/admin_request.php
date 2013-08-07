<?php echo $this->session->flash(); ?>

<div class="page-header">
	<h1>Request</h1>		
</div>


<form action="#" class="" method='POST'>
	<?php foreach ($all_tables as $key => $value) {
		$all_tables[$value] = $value;
		unset($all_tables[$key]);
	} ?>
	<?php echo $this->Form->select('table','Table',$all_tables,array('default'=>$table)); ?>
	<?php echo $this->Form->input('table_choice','',array('type'=>'submit','class'=>'btn','value'=>'OK')); ?>
	<?php echo $this->Form->input('token','hidden',array('value'=>$this->session->token())) ?>
</form>


<?php if(isset($all_fields)): ?>

	<form action="#" method="POST">
		<?php foreach ($all_fields as $key => $value) {
		$all_fields[$value] = $value;
		unset($all_fields[$key]);
		} ?>
		<?php echo $this->Form->select('field','Champ',$all_fields,array('default'=>$field)); ?>
		<?php echo $this->Form->input('value','Valeur',array('')); ?>
		<?php echo $this->Form->input('query','',array('type'=>'submit','class'=>'btn','value'=>'OK')); ?>
		<?php echo $this->Form->input('table','hidden',array('value'=>$table)); ?>
		<?php echo $this->Form->input('primaryKey','hidden',array('value'=>$primaryKey)); ?>
		<?php echo $this->Form->input('token','hidden',array('value'=>$this->session->token())) ?>

	</form>
<?php endif; ?>

<?php if(isset($results)): ?>
	<?php if(!empty($results)): ?>

		<h3><?php echo count($results);?> results :</h3>

		<form action="#" class="" method="POST">
		<?php foreach ($results as $result): ?>
						
			<?php foreach ($result as $key => $val): ?>
				
				<?php $type = (strlen($val)>10)? 'textarea' : null; ?>
				<?php echo $this->Form->input($key.'[]',$key,array('type'=>$type,'value'=>$val)); ?>
				
			<?php endforeach; ?>

			<?php echo $this->Form->input('token','hidden',array('value'=>$this->session->token())) ?>
			<?php echo $this->Form->input('table','hidden',array('value'=>$table)) ?>
			<?php echo $this->Form->input('primaryKey','hidden',array('value'=>$primaryKey)); ?>
			<?php echo $this->Form->input('nbresults','hidden',array('value'=>count($results))); ?>
			<?php echo $this->Form->input('update','',array('type'=>'submit','class'=>'btn btn-large','value'=>'Enregistrer')) ?>

		<?php endforeach; ?>
		</form>
	<?php else: ?>
		No results...
	<?php endif; ?>
<?php endif; ?>