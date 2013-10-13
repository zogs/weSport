<?php debug($this->vars);
	
?>
<?php 

/*
	Display categories
*/

if($display=='category'):?>
<table class="table table-row table-stripped">
<?php foreach ($categories as $key => $cat):?>
	<tr>
		<td><?php echo $cat->name;?></td>
	</tr>
<?php endforeach;?>
</table>
<?php endif;?>




<?php 

/* 
	Display Topics
*/

if($display=='topic'):?>
<table class="table table-row table-stripped">
<?php foreach ($topics as $key => $topic):?>
	<tr>
		<td><?php echo $topic->subject;?></td>
		<td><?php echo $topic->user_id;?></td>
		<td><?php echo $topic->date;?></td>
		<td><?php echo $topic->nbreplies;?></td>
	</tr>
<?php endforeach;?>
</table>
<?php endif;?>





<?php
/* 
	Display Replies
*/

if($display=='reply'):?>
<table class="table table-row table-stripped">
<?php foreach ($replies as $key => $reply):?>
	<tr>
		<td><?php echo $reply->content;?></td>
		<td><?php echo $reply->user_id;?></td>
		<td><?php echo $reply->date;?></td>
	</tr>
<?php endforeach;?>
</table>
<?php endif;?>